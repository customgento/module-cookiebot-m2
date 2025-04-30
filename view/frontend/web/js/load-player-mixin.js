define([
    'jquery'
], function ($) {
    'use strict';

    function createCookiebotPlaceholders() {
        ((d,i,m)=>{let ct=t=>d.createTextNode(t);let ce=e=>d.createElement(e);d.querySelectorAll(i)
            .forEach(e=>{const a=ce('a'),div=ce('div'),p=ce('p'),s=e.dataset.cookieblockSrc,sp=
                /google\.com\/maps\/embed/.test(s)?'Google Maps':/player\.vimeo\.com\/video\//
                    .test(s)?'Vimeo':/youtube(-nocookie)?\.com\/embed\//.test(s)?'YouTube':undefined;
                if(!sp)return;div.innerHTML=`<div style="background-color:#CCC;z-index:9999;display:inline-`+
                    `block;height:${e.getBoundingClientRect().height}px;position:relative;width:${e.getBoundingClientRect().width}px;"><div style=`+
                    '"background-color:#848484;border-radius:15px;height:50%;position:absolute;'+
                    'transform:translate(50%,50%);width:50%;"><p style="color:#FFF;font-size:7.5em;'+
                    'position:relative;top:50%;left:50%;margin:0;text-align:center;transform:translate'
                    +'(-50%,-50%);">&ctdot;</p></div>';div.classList.add(`cookieconsent-optout-${m}`);
                a.textContent=`accept ${m} cookies`;a.href='javascript:Cookiebot.renew()';p.append(
                    ct('Please '), a, ct(` to view this ${sp} content.`));div.append(p);e.parentNode
                    .insertBefore(div, e);})})(document, 'iframe[data-cookieblock-src]', 'marketing');
    }

    return function (originalWidget) {

        $.widget('mage.videoYoutube', $.mage.videoYoutube, {
            _create: function () {
                var self = this;

                this._initialize();

                this.element.append('<div></div>');

                this._on(window, {
                    'youtubeapiready': function () {
                        var host = 'https://www.youtube.com';

                        if (self.useYoutubeNocookie) {
                            host = 'https://www.youtube-nocookie.com';
                        }

                        if (self._player !== undefined) {
                            return;
                        }
                        self._autoplay = true;

                        if (self._autoplay) {
                            self._params.autoplay = 1;
                        }

                        if (!self._rel) {
                            self._params.rel = 0;
                        }

                        self._player = new window.YT.Player(self.element.children(':first')[0], {
                            height: self._height,
                            width: self._width,
                            videoId: self._code,
                            playerVars: self._params,
                            host: host,
                            events: {
                                'onReady': function onPlayerReady() {
                                    console.log('onReady - can be used for youtube')
                                    self._player.getDuration();
                                    self.element.closest('.fotorama__stage__frame')
                                        .addClass('fotorama__product-video--loaded');

                                    // Add Cookiebot integration
                                    const iframes = document.querySelectorAll('.product-video[data-type="youtube"] iframe');
                                    iframes.forEach((iframe) => {
                                        if (!Cookiebot.consent.marketing) {
                                            iframe.setAttribute('data-cookieblock-src', iframe.src);
                                            iframe.setAttribute('data-cookieconsent', 'marketing');
                                            iframe.removeAttribute('src');
                                        }
                                    });

                                    createCookiebotPlaceholders();
                                },
                                onStateChange: function (data) {
                                    switch (window.parseInt(data.data, 10)) {
                                        case 1:
                                            self._playing = true;
                                            break;
                                        default:
                                            self._playing = false;
                                            break;
                                    }

                                    self._trigger('statechange', {}, data);

                                    if (data.data === window.YT.PlayerState.ENDED && self._loop) {
                                        self._player.playVideo();
                                    }
                                }
                            }
                        });
                    }
                });

                this._loadApi();
            }
        });

        $.widget('mage.videoVimeo', $.mage.videoVimeo, {
            _create: function () {
                var timestamp,
                    additionalParams = '',
                    src,
                    id;

                this._initialize();
                timestamp = new Date().getTime();
                this._autoplay = true;

                if (this._autoplay) {
                    additionalParams += '&autoplay=1';
                }

                if (this._loop) {
                    additionalParams += '&loop=1';
                }
                src = 'https://player.vimeo.com/video/' +
                    this._code + '?api=1&player_id=vimeo' +
                    this._code +
                    timestamp +
                    additionalParams;
                id = 'vimeo' + this._code + timestamp;
                this.element.append(
                    $('<iframe></iframe>')
                        .attr('frameborder', 0)
                        .attr('id', id)
                        .attr('width', this._width)
                        .attr('height', this._height)
                        .attr('src', src)
                        .attr('data-cookieblock-src', src)
                        .attr('data-cookieconsent', 'marketing')
                        .attr('webkitallowfullscreen', '')
                        .attr('mozallowfullscreen', '')
                        .attr('allowfullscreen', '')
                        .attr('referrerPolicy', 'origin')
                        .attr('allow', 'autoplay')
                );

                /* eslint-disable no-undef */
                this._player = new Vimeo.Player(this.element.children(':first')[0]);

                this._player.ready().then(function () {
                    $('#' + id).closest('.fotorama__stage__frame').addClass('fotorama__product-video--loaded');
                    
                    // Add Cookiebot integration
                    const iframes = document.querySelectorAll('.product-video[data-type="vimeo"] iframe');

                    iframes.forEach((iframe) => {
                        if (!Cookiebot.consent.marketing) {
                            iframe.setAttribute('data-cookieblock-src', iframe.src);
                            iframe.setAttribute('data-cookieconsent', 'marketing');
                            iframe.removeAttribute('src');
                        }
                    });

                    createCookiebotPlaceholders();
                });
            }
        });

        return originalWidget;
    };
});