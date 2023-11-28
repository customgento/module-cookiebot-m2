<?php

declare(strict_types=1);

namespace CustomGento\Cookiebot\Test\Integration\Model;

use CustomGento\Cookiebot\Model\Config;
use CustomGento\Cookiebot\Model\ScriptGenerator;
use Magento\Framework\App\ObjectManager;
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
    }

    /**
     * @magentoConfigFixture current_store web/cookiebot/use_eu_cdn 0
     */
    public function testWhenUseEuCdnIsDisabled(): void
    {
        $generatedScript = ObjectManager::getInstance()->create(ScriptGenerator::class)->generate();
        self::assertEquals('<script id="Cookiebot" data-cfasync="false" src="https://consent.cookiebot.com/uc.js" data-cbid=""  type="text/javascript" async></script>',
            $generatedScript);
    }

    /**
     * @magentoConfigFixture current_store web/cookiebot/use_eu_cdn 1
     */
    public function testWhenUseEuCdnIsEnabled(): void
    {
        $generatedScript = ObjectManager::getInstance()->create(ScriptGenerator::class)->generate();
        self::assertEquals('<script id="Cookiebot" data-cfasync="false" src="https://consent.cookiebot.eu/uc.js" data-cbid=""  type="text/javascript" async></script>',
            $generatedScript);
    }
}
