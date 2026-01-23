<?php

declare(strict_types=1);

namespace CustomGento\Cookiebot\Model;

use Magento\Csp\Helper\CspNonceProvider;

class ScriptGenerator
{
    private const COOKIEBOT_SCRIPT_FORMAT = '<script
            id="Cookiebot"
            data-cfasync="false"
            src="https://consent.cookiebot.com/uc.js"
            data-cbid="%s"
            %s
            %s
            type="text/javascript" async></script>';
    private const EU_COOKIEBOT_SCRIPT_FORMAT = '<script
            id="Cookiebot"
            data-cfasync="false"
            src="https://consent.cookiebot.eu/uc.js"
            data-cbid="%s"
            %s
            %s
            type="text/javascript" async></script>';

    public function __construct(
        private readonly Config $config,
        private readonly CspNonceProvider $cspNonceProvider
    ) {
    }

    public function generate(): string
    {
        $cookiebotId = $this->config->getId();
        $dataCulture = $this->config->getDataCulture() ?
            sprintf('data-culture="%s"', $this->config->getDataCulture()) : '';
        $nonce       = sprintf('nonce="%s"', $this->cspNonceProvider->generateNonce());

        if ($this->config->useEuCdn()) {
            return sprintf(self::EU_COOKIEBOT_SCRIPT_FORMAT, $cookiebotId, $dataCulture, $nonce);
        }

        return sprintf(self::COOKIEBOT_SCRIPT_FORMAT, $cookiebotId, $dataCulture, $nonce);
    }
}
