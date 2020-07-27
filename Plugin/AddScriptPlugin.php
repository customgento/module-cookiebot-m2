<?php

declare(strict_types=1);

namespace CustomGento\Cookiebot\Plugin;

use CustomGento\Cookiebot\Model\Config;
use CustomGento\Cookiebot\Model\ScriptGenerator;
use Magento\Framework\View\Page\Config\RendererInterface;

class AddScriptPlugin
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

    public function afterRenderHeadContent(RendererInterface $subject, string $result): string
    {
        if (!$this->config->isEnabled()) {
            return $result;
        }

        $cookiebotScript = $this->scriptGenerator->generate();
        if ($cookiebotScript !== '') {
            $result = $cookiebotScript . PHP_EOL . $result;
        }

        return $result;
    }
}
