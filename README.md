# CustomGento_Cookiebot
Magento 2 module, which integrates Cookiebot.

## Description
This extension adds the Cookiebot script as the first script on the page. This is required, so that Cookiebot can automatically block cookies from all included scripts. We currently only support the auto-blocking mode. Since using the manual mode requires [manual changes](https://www.cookiebot.com/en/manual-implementation/) anyway, we do not see value in adding support for this.

## Installation
- composer require customgento/module-cookiebot-m2
- bin/magento module:enable CustomGento_Cookiebot
- bin/magento setup:upgrade
- bin/magento setup:di:compile
- bin/magento cache:flush

## Configuration
After installing the extension, just go to Stores > Configuration > Web > Cookiebot Settings to enable Cookiebot and enter your Cookiebot ID. You find the ID in your Cookiebot account under Settings > Your scripts. All other configuration regarding the cookie overlay is done in your Cookiebot account.

## Disclaimer
You need a [Cookiebot](https://www.cookiebot.com/) account, so that this extension does anything useful for you.

## Copyright
&copy; 2020 CustomGento GmbH
