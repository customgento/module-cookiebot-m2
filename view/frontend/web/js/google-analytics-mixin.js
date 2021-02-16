define(['mage/utils/wrapper'], function (wrapper) {
    'use strict';

    return function (gaFunction) {
        return wrapper.wrap(gaFunction, function (parentMethod, config) {
            if (typeof Cookiebot === 'undefined') {
                return;
            }
            if (!Cookiebot.hasResponse) {
                window.addEventListener('CookiebotOnAccept', function () {
                    if (Cookiebot.consent.statistics) {
                        parentMethod(config);
                    }
                });
            } else if (Cookiebot.consent.statistics) {
                parentMethod(config);
            }
        })
    }
});
