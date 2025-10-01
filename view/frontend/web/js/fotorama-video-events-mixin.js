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

                const videoElement = event.target.querySelector('.product-video');
                
                // Use the video blocker widget
                createVideoBlocker(videoElement);
            },

            _initialize: function () {
                this._super();
                addEventListener('CookiebotOnAccept', () => {
                    if (Cookiebot?.consent?.marketing) {
                        const cookiebotOutput = document?.querySelector('.cookieconsent-optout-marketing');
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
