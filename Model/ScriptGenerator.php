<?php

declare(strict_types=1);

namespace CustomGento\Cookiebot\Model;

class ScriptGenerator
{
    private const COOKIEBOT_SCRIPT_FORMAT = '<script id="Cookiebot" src="https://consent.cookiebot.com/uc.js" data-cbid="%s" data-blockingmode="auto" type="text/javascript"></script>';

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

        return sprintf(self::COOKIEBOT_SCRIPT_FORMAT, $cookiebotId);
    }
}
