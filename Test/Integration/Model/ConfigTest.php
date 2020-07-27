<?php

declare(strict_types=1);

namespace CustomGento\Cookiebot\Test\Unit\Model;

use CustomGento\Cookiebot\Model\Config;
use Magento\TestFramework\ObjectManager as TestFrameworkObjectManager;
use PHPUnit\Framework\TestCase;

class ConfigTest extends TestCase
{
    /**
     * @var Config
     */
    private $config;

    protected function setUp(): void
    {
        $this->config = TestFrameworkObjectManager::getInstance()->create(Config::class);
    }

    public function testIsEnabledReturnsFalseByDefault(): void
    {
        self::assertFalse($this->config->isEnabled());
    }

    /**
     * @magentoConfigFixture default/web/cookiebot/enabled 0
     */
    public function testIsEnabledReturnsFalse(): void
    {
        self::assertFalse($this->config->isEnabled());
    }

    /**
     * @magentoConfigFixture default/web/cookiebot/enabled 1
     */
    public function testIsEnabledReturnsTrue(): void
    {
        self::assertTrue($this->config->isEnabled());
    }

    public function testGetIdReturnsEmptyStringByDefault(): void
    {
        self::assertEquals('', $this->config->getId());
    }

    /**
     * @magentoConfigFixture default/web/cookiebot/id 123-456-789
     */
    public function testGetIdReturnsId(): void
    {
        self::assertEquals('123-456-789', $this->config->getId());
    }
}
