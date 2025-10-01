var config = {
    config: {
        mixins: {
            'Magento_GoogleAnalytics/js/google-analytics': {
                'CustomGento_Cookiebot/js/google-analytics-mixin': true
            },
            'Magento_ProductVideo/js/fotorama-add-video-events': {
                'CustomGento_Cookiebot/js/fotorama-video-events-mixin': true
            },
            'Magento_PageBuilder/js/resource/jarallax/jarallax-video': {
                'CustomGento_Cookiebot/js/jarallax-video-mixin': true
            }
        }
    }
};
