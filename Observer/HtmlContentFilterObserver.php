<?php

declare(strict_types=1);

namespace CustomGento\Cookiebot\Observer;

use CustomGento\Cookiebot\Model\Config;
use CustomGento\Cookiebot\Model\ExternalVideoReplacer;
use Magento\Framework\App\Response\Http;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Psr\Log\LoggerInterface;

class HtmlContentFilterObserver implements ObserverInterface
{
    public function __construct(
        private readonly LoggerInterface $logger,
        private readonly Config $config,
        private readonly ExternalVideoReplacer $externalVideoReplacer
    ) {
    }

    public function execute(Observer $observer): void
    {
        try {
            /** @var Http $response */
            $response = $observer->getData('response');

            if (!$this->config->isBlockVideosUntilConsentEnabled()) {
                return;
            }

            if (!$response instanceof Http) {
                return;
            }

            $content = $response->getBody();

            if (empty($content) || !is_string($content)) {
                return;
            }

            $modifiedContent = $this->externalVideoReplacer->replaceIframeSources($content);

            $response->setBody($modifiedContent);
        } catch (\Exception $e) {
            $this->logger->error('Error in HtmlContentFilterObserver: ' . $e->getMessage());
        }
    }
} 