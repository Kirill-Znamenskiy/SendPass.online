
window._ = window.lodash = require('lodash');

/** Load popper.js, jQuery and bootstrap.js*/
require('./bootstrap.js');

// require('./fontawesome.js');

// require('./vue.js');

// require('./luxon.js');

require('./moment.js');


/**
 * We'll load jQuery and the Bootstrap jQuery plugin which provides support
 * for JavaScript based Bootstrap features such as modals and tabs. This
 * code may be modified to fit the specific needs of your application.
 */
//
// try {
//     window.Popper = require('popper.js').default;
//     window.$ = window.jQuery = require('jquery');
//
//     require('bootstrap');
// } catch (e) {}
//
//




/*

/!**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 *!/

window.axios = require('axios');

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

*/








/*

/!**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 *!/

import Echo from 'laravel-echo';

window.Pusher = require('pusher-js');

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: process.env.MIX_PUSHER_APP_KEY,
    cluster: process.env.MIX_PUSHER_APP_CLUSTER,
    encrypted: true
});

*/


jQuery(document).ready(function() {
    jQuery('body').removeClass('loading').addClass('loaded');
    jQuery('body>div.loading').addClass('d-none');
    jQuery('body>main').addClass('d-block');
    jQuery('body>div.js-loaded').show();


    var execopy_tmid;
    jQuery('div#succopied').hide().removeClass('d-none');
    jQuery('button#execopy').click(function(){
        jQuery('input#securl').select();
        document.execCommand('copy');
        // var jexecopy = jQuery('button#execopy');
        var jsuccopied = jQuery('div#succopied');

        // jexecopy.attr('disabled', true);
        jsuccopied.fadeIn();
        if (!!execopy_tmid) clearTimeout(execopy_tmid);
        execopy_tmid = setTimeout(function() {
            jsuccopied.fadeOut();
            // jexecopy.removeAttr('disabled');
        }, 3333);
    });

    jQuery('button#shdpass').click(function(event){
        // event.preventDefault();
        var jthis = jQuery(this);
        var jinpt = jthis.parent().parent().children('input');

        if (jinpt.attr('type') === 'password') {
            jinpt.attr('type','text');
            jthis.children('svg#i-eye-slash').removeClass('d-none').show();
            jthis.children('svg#i-eye-open').hide();
        }
        else if (jinpt.attr('type') === 'text') {
            jinpt.attr('type','password');
            jthis.children('svg#i-eye-open').removeClass('d-none').show();
            jthis.children('svg#i-eye-slash').hide();
        }
    });


    if (navigator.share) {
        jQuery('button#share-sec-link').show().removeClass('d-none').click(function() {
            var jthis = jQuery(this);
            navigator.share({
                title: jthis.data('share-title'),
                text: jthis.data('share-text'),
                url: jQuery('input#securl').val()
            });
        });
    }


    window.moment.load_locale().done(function() {

        jQuery('.mmnt-date-time').each(function(index) {
            let jthis = jQuery(this);
            let isoDateTime = jthis.text();
            jthis.attr('title',isoDateTime);
            let mmntDateTime = moment(isoDateTime);
            // let lxnDateTime = luxon.DateTime.fromISO(isoDateTime).setLocale(navigatorLang);
            // window.mmntDateTime = mmntDateTime;

            // jthis.text(mmntDateTime.format('LL LT, dddd, [UTC]Z [('+window.mmLangs1.toString()+')('+window.mmLangs2+')('+mmntDateTime.locale()+')]'));
            jthis.text(mmntDateTime.format('LL LT, dddd, [UTC]Z'));

            // jthis.parent().append(jthis.clone().text('lxn_date_time.toISO() = "'+lxn_date_time.toISO()+'"'));
            // jthis.parent().append(jthis.clone().text('lxn_date_time.toString() = "'+lxn_date_time.toString()+'"'));
            // jthis.parent().append(jthis.clone().text('lxn_date_time.toLocaleString() = "'+lxn_date_time.toLocaleString()+'"'));
            // jthis.parent().append(jthis.clone().text(lxn_date_time.toLocaleString(luxon.DateTime.DATETIME_FULL)));
            // jthis.parent().append(jthis.clone().text(lxn_date_time.toLocaleString(luxon.DateTime.DATETIME_FULL_WITH_SECONDS)));
            // jthis.parent().append(jthis.clone().text(lxn_date_time.toLocaleString(luxon.DateTime.DATETIME_HUGE)));
        });

        jQuery('.mmnt-to-date-time').each(function(index) {
            let jthis = jQuery(this);
            let isoToDateTime = jthis.text();
            jthis.attr('title',isoToDateTime);

            jthis.text(moment().to(isoToDateTime));
        });
    });





});


// for score Google PageSpeed Insights 100%
jQuery(window).on('load', function() {

    setTimeout(() => {

        // Здесь все эти тормознутые трекеры, чаты и прочая ересь,
        // без которой жить не может отдел маркетинга, и которые
        // дико бесят разработчиков, когда тот же маркетинг приходит
        // с вопросом "почему сайт медленно грузится, нам гугл сказал"

        //<!-- Google Tag Manager -->
        (function(w,d,s,l,i){
            w[l]=w[l]||[];
            w[l].push({'gtm.start':new Date().getTime(),event:'gtm.js'});
            var f=d.getElementsByTagName(s)[0],j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';
            j.async=true;
            j.src='https://www.googletagmanager.com/gtm.js?id='+i+dl;
            f.parentNode.insertBefore(j,f);
        })(window,document,'script','dataLayer','GTM-NJNF7MH');
        //<!-- End Google Tag Manager -->

    }, 1111)


});



