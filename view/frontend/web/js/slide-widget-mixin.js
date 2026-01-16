define([
    'jquery',
    'underscore',
    'Magento_PageBuilder/js/widget/show-on-hover',
    'Magento_PageBuilder/js/widget/video-background',
    'CustomGento_Cookiebot/js/video-blocker-widget'
], function ($, _, showOnHover, videoBackground, createVideoBlocker) {
    'use strict';

    return function (originalWidget) {
        function isSupportedVideoPlatform(url) {
            if (!url) {
                return false;
            }
            
            // Regex patterns for supported video platforms
            const youtubePattern = /^https?:\/\/(www\.)?(youtube\.com|youtu\.be)\//i;
            const youtubeNocookiePattern = /^https?:\/\/(www\.)?youtube-nocookie\.com\//i;
            const vimeoPattern = /^https?:\/\/(www\.)?(vimeo\.com|player\.vimeo\.com)\//i;
            
            return youtubePattern.test(url) || 
                   youtubeNocookiePattern.test(url) || 
                   vimeoPattern.test(url);
        }

        return function (config, element) {
            const videoElement = element[0].querySelector('[data-background-type=video]');

            if (!videoElement) {
                originalWidget(config, element);
                return;
            }

            const blockVideoConsentConfig = window.cookiebotConfig && window.cookiebotConfig.blockVideosUntilConsent;
            const videoSrc = videoElement.getAttribute('data-video-src');
            const cookieblockSrc = videoElement.getAttribute('data-cookieblock-src');
            const src = videoSrc || cookieblockSrc;
            let previousStatus = '';
            
            if (!blockVideoConsentConfig || !isSupportedVideoPlatform(src)) {
                originalWidget(config, element);
                return;
            }

            const viewportElement = document.createElement('div');


            addEventListener('CookiebotOnLoad', sliderVideoBlocker);

            function sliderVideoBlocker() {
                if (previousStatus === 'blocked' && (!Cookiebot?.consent?.marketing)) {
                    return;
                }

                if (!Cookiebot?.consent?.marketing) {
                    videoElement.setAttribute('data-cookieblock-src', videoElement.getAttribute('data-video-src'));
                    videoElement.removeAttribute('data-video-src');
                    createVideoBlocker(videoElement);
                    videoElement.style.display = 'none';
                    previousStatus = 'blocked';
                    return;
                }

                viewportElement.classList.add('jarallax-viewport-element');

                if (!videoElement) {
                    return;
                }

                if (!videoElement.getAttribute('data-video-src')) {
                    videoElement.setAttribute('data-video-src', videoElement.getAttribute('data-cookieblock-src'));
                    videoElement.style.display = 'block';
                    videoElement.removeAttribute('data-cookieblock-src');

                    const customSlide = videoElement.closest('.slick-slide');
                    if (customSlide) {
                        const consentBlocker = customSlide.querySelector('.cookieconsent-optout-marketing');
                        consentBlocker?.remove();
                    }
                }
                videoElement.setAttribute('data-element-in-viewport', '.jarallax-viewport-element');
                videoElement.appendChild(viewportElement);
                videoBackground(config, videoElement);
                videoElement.style.display = 'block';
                previousStatus = 'unblocked';

                originalWidget(config, element);
            }

            sliderVideoBlocker();
        };
    };
});