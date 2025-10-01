var CustomVideoWorker = null;

define([
    'jquery',
    'CustomGento_Cookiebot/js/video-blocker-widget'
], function ($, createVideoBlocker) {
    'use strict';

    addEventListener('CookiebotOnAccept', () => {

    });

    function overrideVideoWorker() {
        CustomVideoWorker = window.VideoWorker || null;
        if (window.VideoWorker && window.VideoWorker.prototype) {
            const VideoWorker = window.VideoWorker;

            if (VideoWorker.prototype.parseURL) {
                const originalParseURL = VideoWorker.prototype.parseURL;
                VideoWorker.prototype.parseURL = function (url) {
                    const blockVideoConsentConfig = window.cookiebotConfig && window.cookiebotConfig.blockVideosUntilConsent;
                    const videoElement = document.querySelector('.pagebuilder-slide-wrapper.jarallax');
                    let videoElementsStatus = 'block';
                    let result;

                    if (Cookiebot?.consent?.marketing || !blockVideoConsentConfig) {
                        videoElement.style.display = videoElementsStatus;

                        return originalParseURL.call(this, url);
                    }

                    result = originalParseURL.call(this, '');

                    videoElementsStatus = 'none';
                    createVideoBlocker(videoElement);
                    console.log('videoElementsStatus', videoElementsStatus);
                    videoElement.setAttribute('data-cookieblock-src', videoElement.getAttribute('data-video-src'));
                    videoElement.removeAttribute('data-video-src');
                    videoElement.style.display = videoElementsStatus;

                    return result;
                };
            }
            return true;
        }
        return false;
    }

    return function () {
        overrideVideoWorker();
    };
});