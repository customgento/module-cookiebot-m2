<?php

declare(strict_types=1);

namespace CustomGento\Cookiebot\Plugin\Cms\Model;

use CustomGento\Cookiebot\Model\Config;
use CustomGento\Cookiebot\Model\ExternalVideoReplacer;
use Magento\Cms\Model\Page;

class AddVideoBlockerToCmsContentPlugin
{
    public function __construct(
        private readonly Config $config,
        private readonly ExternalVideoReplacer $externalVideoReplacer
    ) {
    }

    public function afterGetContent(Page $subject, string $result): string
    {
        // Only process if the block_videos_until_consent feature is enabled
        if (!$this->config->isBlockVideosUntilConsentEnabled()) {
            return $result;
        }

        return $this->externalVideoReplacer->replaceIframeSources($result);
    }
} 