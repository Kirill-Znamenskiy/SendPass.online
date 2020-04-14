<?php

namespace App\Http\Controllers;

use App\Models\Secret;
use Carbon\CarbonImmutable;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use kz\Illuminate\Support\Cast;
use kz\Illuminate\Support\RegExp;

class MainController extends BaseController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
    }


    public function create(Request $request) {

        $maxShowCount2Label = static::getMaxShowCount2Label();
        $lifetimeValue2Label = static::getLifetimeValue2Label();


        if ($request->isMethod('POST')) {
            $postData = $request->post();
            $validationRules = [
                'sectext' => ['required_without:secpass','nullable','string','max:11111'],
                'secpass' => ['required_without:sectext','nullable','string','max:255'],
                'max_show_count' => ['required','integer',Rule::in(array_keys($maxShowCount2Label))],
                'is_hide_show_count' => ['bool'],
                'lifetime' => ['required','string',Rule::in(array_keys($lifetimeValue2Label))],
                'is_hide_lifetime' => ['bool'],
            ];
            $validator = validator($postData,$validationRules);
            $validator->validate();

            $newSecret = new Secret();
            $newSecret->secpass = Arr::get($postData,'secpass');
            $newSecret->sectext = Arr::get($postData,'sectext');

            $newSecret->is_allow_show_created = TRUE;

            $newSecret->crr_show_count = 0;
            $newSecret->max_show_count = Cast::toInt($postData['max_show_count']);
            $newSecret->is_hide_show_count = Cast::toBool([$postData,'is_hide_show_count']);

            $lifetime = $postData['lifetime'];
            $expiredAt = CarbonImmutable::now();
            $lifetimeMatches = RegExp::extract_preg_matches($postData['lifetime'],'/^(\d\d)(m|h|d)$/');
            $lifetimeUnitValue = Cast::toInt($lifetimeMatches[1]);
            $lifetimeUnitKey = (string)$lifetimeMatches[2];
            if (false) throw new \LogicException();
            elseif ($lifetimeUnitKey === 'm') $expiredAt = $expiredAt->addMinutes($lifetimeUnitValue);
            elseif ($lifetimeUnitKey === 'h') $expiredAt = $expiredAt->addHours($lifetimeUnitValue);
            elseif ($lifetimeUnitKey === 'd') $expiredAt = $expiredAt->addDays($lifetimeUnitValue);
            else throw new \LogicException();
            $newSecret->expired_at = $expiredAt;
            $newSecret->is_hide_lifetime = Cast::toBool([$postData,'is_hide_lifetime']);

            $newSecret->uuid = Str::uuid();

            throw_unless($newSecret->save(), \RuntimeException::class);


            $request->session()->flash('succCreatedSecretId',$newSecret->id);
            $request->session()->flash('succCreatedSecretLifetime',$lifetime);

            return redirect()->action([static::class,'created'], ['secuuid' => $newSecret->uuid]);
        }

        return view('create', compact(['maxShowCount2Label','lifetimeValue2Label']));
    }

    static public function getMaxShowCount2Label() {
        return [
            1 => '1 раз',
            2 => '2 раза',
            3 => '3 раза',
            4 => '4 раза',
            5 => '5 раз',
            6 => '6 раз',
            7 => '7 раз',
            8 => '8 раз',
            9 => '9 раз',
        ];
    }

    static public function getLifetimeValue2Label() {
        return [
            '05m' => '5 минут',
            '15m' => '15 минут',
            '30m' => '30 минут',
            '01h' => '1 час',
            '03h' => '3 часа',
            '11h' => '11 часов',
            '01d' => '1 день',
            '02d' => '2 дня',
            '03d' => '3 дня',
            '07d' => '7 дней',
            '30d' => '30 дней',
        ];
    }

    public function created(Request $request, $secuuid) {

        /** @var Secret $secret */
        $secret = Secret::where('uuid',$secuuid)->firstOrFail();

        $isKeep = (App::isLocal() AND config('app.debug'));

        $succCreatedSecretId = $request->session()->get('succCreatedSecretId');
        $succCreatedSecretLifetime = $request->session()->get('succCreatedSecretLifetime');
        if ($isKeep) {
            $request->session()->keep(['succCreatedSecretId','succCreatedSecretLifetime']);
        }
        else {
            $request->session()->forget(['succCreatedSecretId','succCreatedSecretLifetime']);
        }

        if (empty($succCreatedSecretId)) {
            $succCreatedSecret = null;
            $succCreatedSecretLifetime = null;
        }
        else {
            if ($secret->id !== $succCreatedSecretId) {
                throw new AuthorizationException();
            }
            if (empty($secret->is_allow_show_created)) {
                throw new AuthorizationException();
            }

            if (!$isKeep) $secret->is_allow_show_created = false;
            throw_unless($secret->save(), \RuntimeException::class);

            $succCreatedSecret = $secret;
        }


        $crr_show_count = $secret->crr_show_count;
        $created_at = $secret->created_at;
        $updated_at = $secret->updated_at;
        $expired_at = $secret->expired_at;


        $maxShowCount2Label = static::getMaxShowCount2Label();
        $lifetimeValue2Label = static::getLifetimeValue2Label();

        return view('created', [
            'secret' => $secret,
            'showSecretUrl' => static::getShowSecretUrl($secret),
            'succCreatedSecret' => $succCreatedSecret,
            'succCreatedSecretLifetime' => $succCreatedSecretLifetime,
            'maxShowCount2Label' => $maxShowCount2Label,
            'lifetimeValue2Label' => $lifetimeValue2Label,
        ]);
    }

    static public function getShowSecretUrl(Secret $secret) {
        if (empty($secret->uuid)) throw new \RuntimeException();
        return action([static::class,'show'],['secuuid' => $secret->uuid]);
    }

    public function show(Request $request, $secuuid) {



        $view_params = [];
        $view_params['secuuid'] = $secuuid;
        if ($request->isMethod('POST')) {

            /** @var Secret $secret */
            $secret = Secret::where('uuid',$secuuid)->firstOrFail();
            $secret->crr_show_count += 1;

            $validationData = [
                'crr_show_count' => $secret->crr_show_count,
                'max_show_count' => $secret->max_show_count,
                'expired_at' => $secret->expired_at,
            ];
            $validationRules = [
                'max_show_count' => ['required','integer'],
                'crr_show_count' => ['required','integer','lte:max_show_count'],
                'expired_at' => ['required','after:now'],
            ];
            $validator = validator($validationData,$validationRules);
            //if ($validator->fails()) {
            //    return redirect('post/create')
            //        ->withErrors($validator)
            //        ->withInput();
            //}

            $validator->validate();

            if (!($secret->crr_show_count <= $secret->max_show_count)) throw new \RuntimeException('!!!');
            if (!($secret->expired_at > CarbonImmutable::now())) throw new \RuntimeException('!!!');

            throw_unless($secret->save(), \RuntimeException::class);

            $view_params['secret'] = $secret;
            unset($view_params['secuuid']);
        }

        $view_params['maxShowCount2Label'] = static::getMaxShowCount2Label();
        $view_params['lifetimeValue2Label'] = static::getLifetimeValue2Label();

        return view('show', $view_params);
    }


    public function faq(Request $request) {
        return view('faq');
    }
}
