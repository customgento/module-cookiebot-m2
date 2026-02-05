define([
    'jquery',
    'CustomGento_Cookiebot/js/video-blocker-widget'
], function ($, createVideoBlocker) {
    'use strict';

    return function (originalWidget) {
        function isSupportedVideoPlatform(url) {
            if (!url) {
                return false;
            }

            var youtubePattern = /^https?:\/\/(www\.)?(youtube\.com|youtu\.be)\//i;
            var youtubeNocookiePattern = /^https?:\/\/(www\.)?youtube-nocookie\.com\//i;
            var vimeoPattern = /^https?:\/\/(www\.)?(vimeo\.com|player\.vimeo\.com)\//i;

            return youtubePattern.test(url) ||
                youtubeNocookiePattern.test(url) ||
                vimeoPattern.test(url);
        }

        return function (config, element) {
            const videoElementContainer = $(element);
            const videoElement = videoElementContainer[0];
            const videoElementStyles = window.getComputedStyle(element);
            const height = videoElementStyles.minHeight || '300px';
            const width = videoElementStyles.width || '400px';

            if (videoElementContainer.data('background-type') !== 'video') {
                originalWidget(config, element);
                return;
            }

            var blockVideoConsentConfig = window.cookiebotConfig && window.cookiebotConfig.blockVideosUntilConsent;
            var videoSrc = videoElement.getAttribute('data-video-src');
            var cookieblockSrc = videoElement.getAttribute('data-cookieblock-src');
            var src = videoSrc || cookieblockSrc;
            var previousStatus = '';
            var blockerElement = null;

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