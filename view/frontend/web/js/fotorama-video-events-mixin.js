define([
    'jquery',
], function ($) {
    'use strict';

    return function (widget) {
        $.widget('mage.AddFotoramaVideoEvents', widget, {
            _clickHandler: function (e) {
                const blockVideoConsentConfig = window.cookiebotConfig && window.cookiebotConfig.blockVideosUntilConsent;

                if (Cookiebot?.consent?.marketing) {
                    this._super(e);
                    return;
                }

                if (!Cookiebot?.consent?.marketing && blockVideoConsentConfig) {
                    const videoElement = event.target.querySelector('.product-video');

                    const linkElement = document.createElement("a");
                    const divElement = document.createElement("div");
                    const paragraphElement = document.createElement("p");
                    const iframeHeight = videoElement?.getBoundingClientRect().height || 300;
                    const iframeWidth = videoElement?.getBoundingClientRect().width || 400;


                    divElement.innerHTML = `
                                        <div style="background-color:#CCC;display:inline-block;height:${iframeHeight}px;position:relative;width:${iframeWidth}px; z-index: 1000;">
                                            <div style="background-color:#848484;border-radius:15px;height:50%;position:absolute;transform:translate(50%,50%);width:50%;">
                                                <p style="color:#FFF;font-size:7.5em;position:relative;top:50%;left:50%;margin:0;text-align:center;transform:translate(-50%,-50%);">&ctdot;</p>
                                            </div>
                                        </div>
                                    `;

                    divElement.classList.add('cookieconsent-optout-marketing');
                    linkElement.textContent = 'accept marketing cookies';
                    linkElement.href = "javascript:Cookiebot.renew()";
                    paragraphElement.append(
                        document.createTextNode("Please "),
                        linkElement,
                        document.createTextNode(' to view this content.')
                    );

                    divElement.style.fontSize = "1.4rem";
                    divElement.append(paragraphElement);
                    paragraphElement.style.zIndex = "1000";
                    paragraphElement.style.position = "relative";
                    videoElement?.parentNode.insertBefore(divElement, videoElement);

                    return;
                }
                this._super(e);
            },

            _initialize: function () {
                this._super();
                addEventListener("CookiebotOnAccept", () => {
                    const videoIframes = document.querySelectorAll(".pagebuilder-video-container iframe");

                    videoIframes.forEach((iframe) => {
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
                });
            }
        });

        return $.mage.AddFotoramaVideoEvents;
    };
}); 