<?php

namespace App\Http\Middleware;

use Closure;
use kz\func\Func;
use League\Uri\Uri;

class LocaleMiddleware {

    protected $_supported_locale2flag_svg_file_name;

    /**
     * Create a new middleware instance.
     *
     * @return void
     */
    public function __construct() {
        $supported_locale2flag_svg_file_name = config('locale.supported_locale2flag_svg_file_name');
        throw_unless(is_array($supported_locale2flag_svg_file_name),\RuntimeException::class);

        $supported_locales = array_keys($supported_locale2flag_svg_file_name);
        $supported_locales_normalized = Func::map_first_value($supported_locales,[static::class,'normalize_locale']);
        throw_if(($supported_locales !== $supported_locales_normalized),\RuntimeException::class);

        $this->_supported_locale2flag_svg_file_name = $supported_locale2flag_svg_file_name;
    }

    public function GETsupported_locales() {
        return $this->_supported_locale2flag_svg_file_name;
    }

    static public function normalize_locale($locale) {
        return strtolower(str_replace('_','-',$locale));
    }

    static public function extract_lang($locale) {
        $aux = $locale;
        $aux = static::normalize_locale($aux);
        $aux = explode('-',$aux);
        $aux = reset($aux);
        return $aux;
    }


    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     * @throws \RuntimeException
     */
    public function handle(\Illuminate\Http\Request $request, Closure $next) {

        $supported_locale2flag_svg_file_name = $this->_supported_locale2flag_svg_file_name;

        $app_locale = config('app.locale');
        throw_unless(($app_locale === static::normalize_locale($app_locale)),\RuntimeException::class);
        throw_unless(isset($supported_locale2flag_svg_file_name[$app_locale]),\RuntimeException::class);

        $app_fallback_locale = config('app.fallback_locale');
        throw_unless(($app_locale === static::normalize_locale($app_fallback_locale)),\RuntimeException::class);
        throw_unless(isset($supported_locale2flag_svg_file_name[$app_fallback_locale]),\RuntimeException::class);




        $app_url = config('app.url');
        $app_domain = parse_url($app_url, PHP_URL_HOST);
        $app_domain_exploded = explode('.',$app_domain);
        $app_domain_suffix_exploded = $app_domain_exploded;
        $app_domain_suffix = implode('.',$app_domain_suffix_exploded);

        $ind = count($app_domain_suffix_exploded);
        throw_if(($ind < 1), \RuntimeException::class);



        $req_uri = Uri::createFromString($request->getUri());
        $req_domain = $req_uri->getHost();
        $req_domain_exploded = explode('.',$req_domain);
        $req_domain_exploded_reversed = array_reverse($req_domain_exploded);
        $req_domain_suffix_exploded = array_reverse(array_slice($req_domain_exploded_reversed,0,$ind));
        $req_domain_suffix = implode('.',$req_domain_suffix_exploded);


        $supported_locale2kit = [];
        foreach ($supported_locale2flag_svg_file_name AS $supported_locale => $supported_locale_flag_svg_file_name) {
            $aux_domain = (($supported_locale === $app_fallback_locale) ? '' : $supported_locale.'.').$app_domain_suffix;
            $supported_locale2kit[$supported_locale] = [
                'url' => $req_uri->withHost($aux_domain)->__toString(),
                'flag_svg_file_name' => $supported_locale_flag_svg_file_name,
            ];
        }


        if ($app_domain_suffix === $req_domain_suffix) {

            $locale_subdomain = (empty($req_domain_exploded_reversed[$ind]) ? '' : $req_domain_exploded_reversed[$ind]);
            throw_unless(is_string($locale_subdomain),\RuntimeException::class);

            $wrk_locale = static::normalize_locale($locale_subdomain);
            $wrk_lang = static::extract_lang($locale_subdomain);

            if (empty($wrk_locale)) {
                $new_app_locale = $app_fallback_locale;
            }
            elseif (isset($supported_locale2flag_svg_file_name[$wrk_locale])) {
                $new_app_locale = $wrk_locale;
            }
            elseif (isset($supported_locale2flag_svg_file_name[$wrk_lang])) {
                $new_app_locale = $wrk_lang;
            }
            else {
                $new_app_locale = $app_fallback_locale;
            }


            $new_req_domain = (($new_app_locale === $app_fallback_locale) ? '' : $new_app_locale.'.').$req_domain_suffix;
            if ($new_req_domain !== $req_domain) {
                return redirect($req_uri->withHost($new_req_domain)->__toString(),302);
            }
            canonicalizer()->set_host($new_req_domain);

            if ($new_app_locale !== $app_locale) {
                app()->setLocale($new_app_locale);
                $app_locale = $new_app_locale;

            }
        }

        /** This variable is available globally on all your views, and sub-views */
        view()->share([
            'supported_locale2kit' => $supported_locale2kit,
            'app_locale' => $app_locale,
            'app_locale_kit' => $supported_locale2kit[$app_locale],
        ]);

        return $next($request);
    }


    /**
     * Return all the accepted languages from the Accept-Language header
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return array Matches from the header field Accept-Languages
     */
    protected function parse_accept_language_header(\Illuminate\Http\Request $request) {

        $accept_languages = $request->header('Accept-Language');
        if (empty($accept_languages)) return [];

        $accept_languages = explode(';',$accept_languages);
        $accept_languages = $accept_languages[0];
        $accept_languages = explode(',', $accept_languages);
        return $accept_languages;
    }
}
