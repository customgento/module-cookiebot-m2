define(['mage/utils/wrapper'], function (wrapper) {
    'use strict';

    return function (gaFunction) {
        return wrapper.wrap(gaFunction, function (parentMethod, config) {
            window.addEventListener('CookiebotOnAccept', function () {
                if (Cookiebot.consent.statistics) {
                    parentMethod(config);
                }
            });
            if (typeof Cookiebot === 'undefined') {
                return;
            }
            if (Cookiebot.consent.statistics) {
                parentMethod(config);
            }
        })
    }
});
