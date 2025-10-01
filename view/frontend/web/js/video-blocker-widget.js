define([
    'jquery',
    'mage/translate'
], function ($, $t) {
    'use strict';

    return function (videoElement) {
        'use strict';

        const divElement = document.createElement('div');
        const paragraphElement = document.createElement('p');
        const iframeHeight = videoElement?.getBoundingClientRect().height || 300;
        const iframeWidth = videoElement?.getBoundingClientRect().width || 400;

        // Create the video blocker HTML
        divElement.innerHTML = `
            <div style="background-color:#CCC;display:inline-block;height:${iframeHeight}px;position:relative;width:${iframeWidth}px; z-index: 1000;">
                <div style="background-color:#848484;border-radius:15px;height:50%;position:absolute;transform:translate(50%,50%);width:50%;">
                    <p style="color:#FFF;font-size:7.5em;position:relative;top:50%;left:50%;margin:0;text-align:center;transform:translate(-50%,-50%);">&ctdot;</p>
                </div>
            </div>
        `;

        // Add CSS classes and styling
        divElement.classList.add('cookieconsent-optout-marketing');
        paragraphElement.innerHTML = $t('Please <a href="javascript:Cookiebot.renew()">accept marketing cookies</a> to view this content.');
        
        divElement.style.fontSize = '1.4rem';
        divElement.append(paragraphElement);
        paragraphElement.style.zIndex = '10001';
        paragraphElement.style.position = 'relative';

        // Insert the blocker before the video element
        if (videoElement?.parentNode) {
            videoElement.parentNode.insertBefore(divElement, videoElement);
        }

        return divElement;
    };
});
