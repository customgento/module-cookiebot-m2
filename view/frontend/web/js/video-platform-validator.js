define([], function () {
    'use strict';

    return function isSupportedVideoPlatform(url) {
        if (!url) {
            return false;
        }

        const youtubePattern = /^https?:\/\/(www\.)?(youtube\.com|youtu\.be)\//i;
        const youtubeNocookiePattern = /^https?:\/\/(www\.)?youtube-nocookie\.com\//i;
        const vimeoPattern = /^https?:\/\/(www\.)?(vimeo\.com|player\.vimeo\.com)\//i;

        return youtubePattern.test(url) ||
            youtubeNocookiePattern.test(url) ||
            vimeoPattern.test(url);
    };
});