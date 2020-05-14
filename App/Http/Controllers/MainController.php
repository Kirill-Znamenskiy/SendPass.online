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
use kz\func\Func;
use kz\Illuminate\Routing\Canonicalizer;
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

        $max_show_count2label = static::get_max_show_count2label();
        $lifetime_value2label = static::get_lifetime_value2label();


        if ($request->isMethod('POST')) {
            canonicalizer()->set_path('/create/');
            $post_data = $request->post();
            $validation_rules = [
                'sectext' => ['required_without:secpass','nullable','string','max:11111'],
                'secpass' => ['required_without:sectext','nullable','string','max:255'],
                'max_show_count' => ['required','integer',Rule::in(array_keys($max_show_count2label))],
                'is_hide_show_count' => ['bool'],
                'lifetime' => ['required','string',Rule::in(array_keys($lifetime_value2label))],
                'is_hide_lifetime' => ['bool'],
            ];
            $validator = validator($post_data,$validation_rules);
            $validator->validate();

            $new_secret = new Secret();
            $new_secret->secpass = Arr::get($post_data,'secpass');
            $new_secret->sectext = Arr::get($post_data,'sectext');

            $new_secret->is_allow_show_created = TRUE;

            $new_secret->crr_show_count = 0;
            $new_secret->max_show_count = Cast::toInt($post_data['max_show_count']);
            $new_secret->is_hide_show_count = Cast::toBool([$post_data,'is_hide_show_count']);

            $lifetime = $post_data['lifetime'];
            $expired_at = CarbonImmutable::now();
            $lifetime_matches = RegExp::extract_preg_matches($post_data['lifetime'],'/^(\d\d)(m|h|d)$/');
            $lifetime_unit_value = Cast::toInt($lifetime_matches[1]);
            $lifetime_unit_key = (string)$lifetime_matches[2];
            if (false) throw new \LogicException();
            elseif ($lifetime_unit_key === 'm') $expired_at = $expired_at->addMinutes($lifetime_unit_value);
            elseif ($lifetime_unit_key === 'h') $expired_at = $expired_at->addHours($lifetime_unit_value);
            elseif ($lifetime_unit_key === 'd') $expired_at = $expired_at->addDays($lifetime_unit_value);
            else throw new \LogicException();
            $new_secret->expired_at = $expired_at;
            $new_secret->is_hide_lifetime = Cast::toBool([$post_data,'is_hide_lifetime']);

            $new_secret->uuid = Str::uuid();

            throw_unless($new_secret->save(), \RuntimeException::class);


            $request->session()->flash('succ_created_secret_id',$new_secret->id);
            $request->session()->flash('succ_created_secret_lifetime',$lifetime);

            return redirect()->action([static::class,'created'], ['secuuid' => $new_secret->uuid]);
        }
        else {
            canonicalizer()->set_path('/');
        }

        return view('create', compact(['max_show_count2label','lifetime_value2label']));
    }

    static public function get_max_show_count2label() {
        $rng = range(1,9);
        $ret = [];
        foreach (range(1,9) AS $count) {
            $ret[$count] = trans_choice('t.time',$count);
        }
        return $ret;
    }

    static public function get_lifetime_value2label() {
        return [
            '05m' => trans_choice('t.minute',5),
            '15m' => trans_choice('t.minute',15),
            '30m' => trans_choice('t.minute',30),
            '01h' => trans_choice('t.hour',1),
            '03h' => trans_choice('t.hour',3),
            '11h' => trans_choice('t.hour',11),
            '01d' => trans_choice('t.day',1),
            '02d' => trans_choice('t.day',2),
            '03d' => trans_choice('t.day',3),
            '07d' => trans_choice('t.day',7),
            '30d' => trans_choice('t.day',30),
        ];
    }

    public function created(Request $request, $secuuid) {
        canonicalizer()->set_path('/created/'.$secuuid.'/');

        /** @var Secret $secret */
        $secret = Secret::where('uuid',$secuuid)->firstOrFail();

        $is_keep = (App::isLocal() AND config('app.debug'));

        $succ_created_secret_id = $request->session()->get('succ_created_secret_id');
        $succ_created_secret_lifetime = $request->session()->get('succ_created_secret_lifetime');
        if ($is_keep) {
            $request->session()->keep(['succ_created_secret_id','succ_created_secret_lifetime']);
        }
        else {
            $request->session()->forget(['succ_created_secret_id','succ_created_secret_lifetime']);
        }

        if (empty($succ_created_secret_id)) {
            $succ_created_secret = null;
            $succ_created_secret_lifetime = null;
        }
        else {
            if ($secret->id !== $succ_created_secret_id) {
                throw new AuthorizationException();
            }
            if (empty($secret->is_allow_show_created)) {
                throw new AuthorizationException();
            }

            if (!$is_keep) $secret->is_allow_show_created = false;
            throw_unless($secret->save(), \RuntimeException::class);

            $succ_created_secret = $secret;
        }


        //$crr_show_count = $secret->crr_show_count;
        //$created_at = $secret->created_at;
        //$updated_at = $secret->updated_at;
        //$expired_at = $secret->expired_at;


        $max_show_count2label = static::get_max_show_count2label();
        $lifetime_value2label = static::get_lifetime_value2label();

        return view('created', [
            'secret' => $secret,
            'show_secret_url' => static::get_show_secret_url($secret),
            'succ_created_secret' => $succ_created_secret,
            'succ_created_secret_lifetime' => $succ_created_secret_lifetime,
            'max_show_count2label' => $max_show_count2label,
            'lifetime_value2label' => $lifetime_value2label,
        ]);
    }

    static public function get_show_secret_url(Secret $secret) {
        if (empty($secret->uuid)) throw new \RuntimeException();
        return action([static::class,'show'],['secuuid' => $secret->uuid]);
    }

    public function show(Request $request, $secuuid) {
        canonicalizer()->set_path('/show/'.$secuuid.'/');


        $view_params = [];
        $view_params['secuuid'] = $secuuid;
        if ($request->isMethod('POST')) {

            /** @var Secret $secret */
            $secret = Secret::where('uuid',$secuuid)->firstOrFail();
            $secret->crr_show_count += 1;

            $validation_data = [
                'crr_show_count' => $secret->crr_show_count,
                'max_show_count' => $secret->max_show_count,
                'expired_at' => $secret->expired_at,
            ];
            $validation_rules = [
                'max_show_count' => ['required','integer'],
                'crr_show_count' => ['required','integer','lte:max_show_count'],
                'expired_at' => ['required','after:now'],
            ];
            $validator = validator($validation_data,$validation_rules);
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

        $view_params['max_show_count2label'] = static::get_max_show_count2label();
        $view_params['lifetime_value2label'] = static::get_lifetime_value2label();

        return view('show', $view_params);
    }


    public function faq(Request $request) {
        canonicalizer()->set_path('/faq/');
        return view('faq');
    }
}
