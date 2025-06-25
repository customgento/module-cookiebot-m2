<?php

declare(strict_types=1);

namespace CustomGento\Cookiebot\Plugin\PageBuilder\Model\Filter;

use CustomGento\Cookiebot\Model\Config;
use CustomGento\Cookiebot\Model\ExternalVideoReplacer;
use Magento\PageBuilder\Model\Filter\Template;

class TemplatePlugin
{
    /**
     * @var Config
     */
    private $config;

    /**
     * @var ExternalVideoReplacer
     */
    private $externalVideoReplacer;

    /**
     * @param Config                $config
     * @param ExternalVideoReplacer $externalVideoReplacer
     */
    public function __construct(Config $config, ExternalVideoReplacer $externalVideoReplacer)
    {
        $this->config                = $config;
        $this->externalVideoReplacer = $externalVideoReplacer;
    }

    /**
     * Replace YouTube iframe sources for page builder templates
     */
    public function afterFilter(Template $subject, string $result): string
    {
        // Only process if the block_videos_until_consent feature is enabled
        if (!$this->config->isBlockVideosUntilConsentEnabled()) {
            return $result;
        }

        return $this->externalVideoReplacer->replaceIframeSources($result);
    }
} 