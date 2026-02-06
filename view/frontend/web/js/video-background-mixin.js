define([
    'jquery',
    'CustomGento_Cookiebot/js/video-blocker-widget',
    'CustomGento_Cookiebot/js/video-platform-validator'
], function ($, createVideoBlocker, isSupportedVideoPlatform) {
    'use strict';

    return function (originalWidget) {
        function getValidDimension(value, fallback) {
            if (!value || parseFloat(value) === 0) {
                return fallback;
            }
            return value;
        }

        return function (config, element) {
            const videoElementContainer = $(element);
            const videoElement = videoElementContainer[0];
            const videoElementStyles = window.getComputedStyle(element);
            const height = getValidDimension(videoElementStyles.minHeight, '300px');
            const width = getValidDimension(videoElementStyles.width, '400px');

            if (videoElementContainer.data('background-type') !== 'video') {
                originalWidget(config, element);
                return;
            }

            const blockVideoConsentConfig = window.cookiebotConfig && window.cookiebotConfig.blockVideosUntilConsent;
            const videoSrc = videoElement.getAttribute('data-video-src');
            const cookieblockSrc = videoElement.getAttribute('data-cookieblock-src');
            const src = videoSrc || cookieblockSrc;
            let previousStatus = '';
            let blockerElement = null;

            if (!blockVideoConsentConfig || !isSupportedVideoPlatform(src)) {
                originalWidget(config, element);
                return;
            }

            addEventListener('CookiebotOnLoad', videoBackgroundBlocker);

            function videoBackgroundBlocker() {
                if (previousStatus === 'blocked' && (!window.Cookiebot?.consent?.marketing)) {
                    return;
                }

                if (!window.Cookiebot?.consent?.marketing) {
                    if (videoSrc) {
                        videoElement.setAttribute('data-cookieblock-src', videoSrc);
                        videoElement.removeAttribute('data-video-src');
                    }
                    videoElement.style.display = 'none';
                    blockerElement = createVideoBlocker(videoElement);
                    const blockerElementContent = blockerElement.querySelector('div');
                    blockerElementContent.style.height = height;
                    blockerElementContent.style.width = width;
                    previousStatus = 'blocked';
                    return;
                }

                if (!videoElement.getAttribute('data-video-src') && cookieblockSrc) {
                    videoElement.setAttribute('data-video-src', cookieblockSrc);
                    videoElement.removeAttribute('data-cookieblock-src');
                }
                videoElement.style.display = 'block';

                if (blockerElement) {
                    blockerElement.remove();
                    blockerElement = null;
                }

                originalWidget(config, element);
                previousStatus = 'unblocked';
            }

            videoBackgroundBlocker();
        };
    };
});