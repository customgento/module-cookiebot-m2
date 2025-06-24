<?php

declare(strict_types=1);

namespace CustomGento\Cookiebot\Plugin\PageBuilder\Model\Filter;

use CustomGento\Cookiebot\Model\Config;
use CustomGento\Cookiebot\Model\YouTubeReplacer;
use Magento\PageBuilder\Model\Filter\Template;

class TemplatePlugin
{
    /**
     * @var Config
     */
    private $config;

    /**
     * @var YouTubeReplacer
     */
    private $youTubeReplacer;

    /**
     * @param Config $config
     * @param YouTubeReplacer $youTubeReplacer
     */
    public function __construct(Config $config, YouTubeReplacer $youTubeReplacer)
    {
        $this->config = $config;
        $this->youTubeReplacer = $youTubeReplacer;
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

        return $this->youTubeReplacer->replaceIframeSources($result);
    }
} 