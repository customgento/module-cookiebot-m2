# CustomGento_Cookiebot
This Magento 2 module integrates Cookiebot into your store. This is useful to give the customer the possibility to block unwanted cookies and only allow wanted cookies. It requires an active Cookiebot subscription and can be completely configured in your Cookiebot account. 
   
## Description
This extension adds the Cookiebot script to all pages.

## Installation
- `composer require customgento/module-cookiebot-m2`
- `bin/magento module:enable CustomGento_Cookiebot`
- `bin/magento setup:upgrade`
- `bin/magento setup:di:compile`
- `bin/magento cache:flush`

## Configuration
After installing the extension, just go to Stores > Configuration > Web > Cookiebot Settings to enable Cookiebot and enter your Cookiebot ID. You find the ID in your Cookiebot account under Settings > Your scripts. All other configuration regarding the cookie overlay is done in your Cookiebot account.

Please mind that this extension uses the manual cookie blocking mode. Hence, you still need to update all scripts, which set not-strictly-necessary cookies in your system as described in the [Cookiebot manual implementation guide](https://www.cookiebot.com/en/manual-implementation/).

## Manual vs. Automatic Cookie Blocking
Unfortunately, Cookiebot currently does not support automatic blocking when RequireJS is used as stated in their [docs](https://support.cookiebot.com/hc/en-us/articles/360015039559-Installing-Cookiebot-in-Magento-2-3-4). Since Magento 2 does use RequireJS, we need to use the manual cookie blocking mode :-(

## Disclaimer
You need a [Cookiebot](https://www.cookiebot.com/) account, so that this extension does anything useful for you.

## Copyright
&copy; 2020 CustomGento GmbH
