<?php

declare(strict_types=1);

namespace CustomGento\Cookiebot\Test\Integration\Model;

use CustomGento\Cookiebot\Model\Config;
use CustomGento\Cookiebot\Model\ScriptGenerator;
use Magento\TestFramework\ObjectManager as TestFrameworkObjectManager;
use PHPUnit\Framework\TestCase;

class ScriptGeneratorTest extends TestCase
{
    /**
     * @var Config
     */
    private $config;

    protected function setUp(): void
    {
        parent::setUp();
        $this->config = TestFrameworkObjectManager::getInstance()->create(Config::class);
        $this->script = $this->_objectManager->create(ScriptGenerator::class)->generate();
    }

    public function testEuCdnEnabledReturnsFalseByDefault(): void
    {
        self::assertFalse($this->config->isEuCdn());
    }

    /**
     * @magentoConfigFixture default/web/cookiebot/enabled_eu_cdn 0
     */
    public function testIsEuCdndReturnsFalse(): void
    {
        $generatedScript = $this->_objectManager->create(ScriptGenerator::class)->generate();
        self::assertEquals('<script id="Cookiebot" data-cfasync="false" src="https://consent.cookiebot.com/uc.js" data-cbid="%s" %s type="text/javascript" async></script>', $generatedScript);
    }

    /**
     * @magentoConfigFixture default/web/cookiebot/enabled_eu_cdn 1
     */
    public function testIsEuCdndReturnsTrue(): void
    {
        $generatedScript = $this->_objectManager->create(ScriptGenerator::class)->generate();
        self::assertEquals('<script id="Cookiebot" data-cfasync="false" src="https://consent.cookiebot.eu/uc.js" data-cbid="%s" %s type="text/javascript" async></script>', $generatedScript);
    }
}
