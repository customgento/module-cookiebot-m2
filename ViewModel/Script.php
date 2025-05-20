<?php

declare(strict_types=1);

namespace CustomGento\Cookiebot\ViewModel;

use CustomGento\Cookiebot\Model\Config;
use CustomGento\Cookiebot\Model\ScriptGenerator;
use Magento\Framework\View\Element\Block\ArgumentInterface;

class Script implements ArgumentInterface
{
    /**
     * @var Config
     */
    private $config;

    /**
     * @var ScriptGenerator
     */
    private $scriptGenerator;

    public function __construct(Config $config, ScriptGenerator $scriptGenerator)
    {
        $this->config          = $config;
        $this->scriptGenerator = $scriptGenerator;
    }

    public function getScript(): string
    {
        if (!$this->config->isEnabled()) {
            return '';
        }

        return $this->scriptGenerator->generate();
    }

    public function isGoogleConsentModeEnabled(): bool
    {
        return $this->config->isGoogleConsentModeEnabled();
    }

    public function isBlockVideosUntilConsentEnabled(): bool
    {
        return $this->config->isBlockVideosUntilConsentEnabled();
    }
}
