
window.mm = window.moment = require('moment/moment.js');
// require('moment-timezone/builds/moment-timezone-with-data.js');

window.moment.load_locale = function() {

    var locales = [];
    var locales2 = [];

    if (window.app_locale && (window.app_locale !== 'en')) {
        locales.push(window.app_locale);
    }



    let normalize_locales = function(locs) {
        return locs.map(function(loc) { return loc.toLowerCase().replace('_', '-'); }).filter(function(loc) { return !_.isEmpty(loc); });
    };
    locales = normalize_locales(locales);

    var nav_locales = (navigator.languages ? navigator.languages : [(navigator.language || navigator.userLanguage)]);
    if (nav_locales && Array.isArray(nav_locales) && (nav_locales.length > 0)) {
        nav_locales = normalize_locales(nav_locales);

        locales2 = [];
        locales.forEach(function(locale) {
            let lang = locale.split('-').shift();
            nav_locales.forEach(function(nav_locale) {
                if (true
                    && (nav_locale.length > lang.length)
                    && (nav_locale.substring(0,(lang.length+1)) === (lang+'-'))
                ) {
                    locales2.push(nav_locale);
                }
            });
            locales2.push(locale);
        });
        locales = locales2;

        locales = locales.concat(nav_locales);
    }
    locales.push('en');

    // remove duplicates
    locales = locales.filter(function(loc,ind) { return (locales.indexOf(loc) === ind); });



    locales2 = [];
    var i = 0, j, curr_loc, next_loc, curr_loc_splitted, next_loc_splitted, wrk_loc;
    while (i < locales.length) {

        curr_loc = locales[i];
        curr_loc = curr_loc.toLowerCase().replace('_', '-');
        curr_loc_splitted = curr_loc.split('-');

        next_loc = (locales[i+1] ? locales[i+1].toLowerCase().replace('_', '-') : locales[i+1]);
        next_loc_splitted = next_loc ? next_loc.split('-') : null;

        j = curr_loc_splitted.length;
        while (j > 0) {


            wrk_loc = curr_loc_splitted.slice(0, j).join('-');

            if (true
                && next_loc
                && (next_loc_splitted.length >= j)
                && (wrk_loc === next_loc_splitted.slice(0,j).join('-'))
            ) {
                // skip
            }
            else {
                locales2.push(wrk_loc);
            }

            j--;
        }
        i++;
    }
    locales = locales2;

    console.log("to-moment-locales: "+locales);



    let aux_then_func = function(to_set_loc) {
        let new_loc = window.moment.locale(to_set_loc);
        if (new_loc === to_set_loc) return jQuery.Deferred().resolve();
        else return jQuery.Deferred().reject();
    };

    if (_.isEmpty(locales)) {
        return aux_then_func('en').promise();
    }



    var dfd = jQuery.Deferred().reject();
    jQuery.each(locales, function(ind, locale) {
        if (dfd.state() === 'resolved') return false; // break;



        if (locale === 'en') {
            dfd = dfd.then(null,function() { return aux_then_func(locale); });
            return false; // break;
        }

        dfd = dfd.then(null,function() {
            // return jQuery.getScript('/llmix/mm-locales/' + locale + '.js').then(aux_then_func);
            return jQuery.ajax({
                url: '/llmix/mm-locales/' + locale + '.js',
                // Make this explicit, since user can override this through ajaxSetup (#11264)
                type: "GET",
                dataType: "text",
                cache: true,
                async: true,
                global: false,
                // Only evaluate the response if it is successful (gh-4126)
                success: function( text ) { jQuery.globalEval( text ); }
            }).then(function() { return aux_then_func(locale); });

        });

        return true;
    });

    dfd = dfd.then(null,function() {
        return jQuery.getScript('/llmix/mm-locales/all.min.js').then(function() {
            window.moment.locale(locales);
            return jQuery.Deferred().resolve();
        });
    });

    return dfd.promise();
};

