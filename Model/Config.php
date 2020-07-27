<?php

declare(strict_types=1);

namespace CustomGento\Cookiebot\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;

class Config
{
    public const XML_PATH_COOKIEBOT_ENABLED = 'web/cookiebot/enabled';
    public const XML_PATH_COOKIEBOT_ID = 'web/cookiebot/id';

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    public function __construct(ScopeConfigInterface $scopeConfig)
    {
        $this->scopeConfig = $scopeConfig;
    }

    public function isEnabled(): bool
    {
        return $this->scopeConfig->isSetFlag(self::XML_PATH_COOKIEBOT_ENABLED);
    }

    public function getId(): string
    {
        return (string)$this->scopeConfig->getValue(self::XML_PATH_COOKIEBOT_ID);
    }
}
