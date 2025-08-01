<?php

declare(strict_types=1);

namespace CustomGento\Cookiebot\Observer;

use CustomGento\Cookiebot\Model\Config;
use CustomGento\Cookiebot\Model\ExternalVideoReplacer;
use Exception;
use Magento\Framework\App\Response\Http;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Psr\Log\LoggerInterface;

class HtmlContentFilterObserver implements ObserverInterface
{
    private LoggerInterface $logger;

    private Config $config;

    private ExternalVideoReplacer $externalVideoReplacer;

    public function __construct(
        LoggerInterface $logger,
        Config $config,
        ExternalVideoReplacer $externalVideoReplacer
    ) {
        $this->externalVideoReplacer = $externalVideoReplacer;
        $this->config                = $config;
        $this->logger                = $logger;
    }

    public function execute(Observer $observer): void
    {
        if (!$this->config->isBlockVideosUntilConsentEnabled()) {
            return;
        }

        try {
            $response = $observer->getData('response');

            if (!$response instanceof Http) {
                return;
            }

            $content = $response->getBody();

            if (empty($content) || !is_string($content)) {
                return;
            }

            $modifiedContent = $this->externalVideoReplacer->replaceIframeSources($content);

            $response->setBody($modifiedContent);
        } catch (Exception $e) {
            $this->logger->error('Error in HtmlContentFilterObserver: ' . $e->getMessage());
        }
    }
}
