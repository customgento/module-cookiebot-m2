<?php

declare(strict_types=1);

namespace CustomGento\Cookiebot\Model;

class ScriptGenerator
{
    private const COOKIEBOT_SCRIPT_FORMAT
        = '<script
            id="Cookiebot"
            data-cfasync="false"
            src="https://consent.cookiebot.com/uc.js"
            data-cbid="%s"
            %s
            type="text/javascript" async></script>';
    private const EU_COOKIEBOT_SCRIPT_FORMAT
        = '<script
            id="Cookiebot"
            data-cfasync="false"
            src="https://consent.cookiebot.eu/uc.js"
            data-cbid="%s"
            %s
            type="text/javascript" async></script>';

    /**
     * @var Config
     */
    private $config;

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    public function generate(): string
    {
        $cookiebotId = $this->config->getId();
        $dataCulture = $this->config->getDataCulture() ?
            sprintf('data-culture="%s"', $this->config->getDataCulture()) : '';

        if ($this->config->useEuCdn()) {
            return sprintf(self::EU_COOKIEBOT_SCRIPT_FORMAT, $cookiebotId, $dataCulture);
        }

        return sprintf(self::COOKIEBOT_SCRIPT_FORMAT, $cookiebotId, $dataCulture);
    }
}
