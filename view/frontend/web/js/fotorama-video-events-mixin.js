define([
    'jquery',
    'CustomGento_Cookiebot/js/video-blocker-widget'
], function ($, createVideoBlocker) {
    'use strict';

    return function (widget) {
        $.widget('mage.AddFotoramaVideoEvents', widget, {
            _clickHandler: function (e) {
                const blockVideoConsentConfig = window.cookiebotConfig && window.cookiebotConfig.blockVideosUntilConsent;

                if (Cookiebot?.consent?.marketing || !blockVideoConsentConfig) {
                    this._super(e);
                    return;
                }

                createVideoBlocker(e.target.querySelector('.product-video'));
            },

            _initialize: function () {
                this._super();
                addEventListener('CookiebotOnAccept', () => {
                    if (Cookiebot?.consent?.marketing) {
                        const cookiebotOutput = document?.querySelector('.cookieconsent-optout-marketing');

                        if (!cookiebotOutput) {
                            return;
                        }

                        const videoElement = cookiebotOutput.closest('.video-unplayed[aria-hidden="false"]');

                        const event = new PointerEvent('click', {
                            bubbles: true,
                            cancelable: true,
                            view: window
                        });
                        videoElement?.dispatchEvent(event);
                    }
                });
            }
        });

        return $.mage.AddFotoramaVideoEvents;
    };
});
