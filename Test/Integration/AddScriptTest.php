<?php

declare(strict_types=1);

namespace CustomGento\Cookiebot\Test\Integration\Plugin;

use CustomGento\Cookiebot\Model\ScriptGenerator;
use Magento\TestFramework\TestCase\AbstractController;

class AddScriptTest extends AbstractController
{
    /**
     * @var string
     */
    private $script;

    protected function setUp(): void
    {
        parent::setUp();
        $this->script = $this->_objectManager->create(ScriptGenerator::class)->generate();
    }

    /**
     * @magentoConfigFixture default/web/cookiebot/enabled 1
     * @magentoConfigFixture default/web/cookiebot/id 123-456-789
     * @magentoConfigFixture current_store web/cookiebot/use_eu_cdn 1
     */
    public function testScriptAddedOnHomepage(): void
    {
        $this->dispatch('/');
        self::assertContains($this->script, $this->getResponse()->getBody());
        self::assertEquals('<script id="Cookiebot" data-cfasync="false" src="https://consent.cookiebot.eu/uc.js" data-cbid=""  type="text/javascript" async></script>',
            $this->script);
    }

    /**
     * @magentoDataFixture   Magento/Catalog/_files/product_simple.php
     * @magentoConfigFixture default/web/cookiebot/enabled 1
     * @magentoConfigFixture default/web/cookiebot/id 123-456-789
     * @magentoConfigFixture current_store web/cookiebot/use_eu_cdn 0
     */
    public function testScriptAddedOnProductPage(): void
    {
        $this->dispatch('/catalog/product/view/id/1');
        self::assertContains($this->script, $this->getResponse()->getBody());
        self::assertEquals('<script id="Cookiebot" data-cfasync="false" src="https://consent.cookiebot.com/uc.js" data-cbid=""  type="text/javascript" async></script>',
            $this->script);
    }

    /**
     * @magentoDataFixture   Magento/Catalog/_files/category.php
     * @magentoConfigFixture default/web/cookiebot/enabled 1
     * @magentoConfigFixture default/web/cookiebot/id 123-456-789
     * @magentoConfigFixture current_store web/cookiebot/use_eu_cdn 1
     */
    public function testScriptAddedOnCategoryPage(): void
    {
        $this->dispatch('/catalog/category/view/id/333');
        self::assertContains($this->script, $this->getResponse()->getBody());
        self::assertEquals('<script id="Cookiebot" data-cfasync="false" src="https://consent.cookiebot.eu/uc.js" data-cbid=""  type="text/javascript" async></script>',
            $this->script);
    }

    /**
     * @magentoDataFixture   Magento/Cms/_files/pages.php
     * @magentoConfigFixture default/web/cookiebot/enabled 1
     * @magentoConfigFixture default/web/cookiebot/id 123-456-789
     * @magentoConfigFixture current_store web/cookiebot/use_eu_cdn 1
     */
    public function testScriptAddedOnCmsPage(): void
    {
        $this->dispatch('/page100');
        self::assertContains($this->script, $this->getResponse()->getBody());
        self::assertEquals('<script id="Cookiebot" data-cfasync="false" src="https://consent.cookiebot.eu/uc.js" data-cbid=""  type="text/javascript" async></script>',
            $this->script);
    }
}
