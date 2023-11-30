<?php

declare(strict_types=1);

namespace CustomGento\Cookiebot\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class Config
{
    public const XML_PATH_COOKIEBOT_ENABLED = 'web/cookiebot/enabled';
    public const XML_PATH_COOKIEBOT_ID = 'web/cookiebot/id';
    public const XML_PATH_DATA_CULTURE = 'web/cookiebot/data_culture';
    public const XML_PATH_USE_EU_CDN = 'web/cookiebot/use_eu_cdn';

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
        return $this->scopeConfig->isSetFlag(self::XML_PATH_COOKIEBOT_ENABLED, ScopeInterface::SCOPE_STORE);
    }

    public function getId(): string
    {
        return (string)$this->scopeConfig->getValue(self::XML_PATH_COOKIEBOT_ID, ScopeInterface::SCOPE_STORE);
    }

    public function getDataCulture(): string
    {
        return (string)$this->scopeConfig->getValue(self::XML_PATH_DATA_CULTURE, ScopeInterface::SCOPE_STORE);
    }

    public function useEuCdn(): bool
    {
        return $this->scopeConfig->isSetFlag(self::XML_PATH_USE_EU_CDN, ScopeInterface::SCOPE_STORE);
    }
}
