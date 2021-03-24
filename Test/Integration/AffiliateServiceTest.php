<?php

namespace Grin\Affiliate\Test\Integration;

use Magento\TestFramework\Helper\Bootstrap;
use PHPUnit\Framework\TestCase;
use Grin\Affiliate\Model\AffiliateService;

class AffiliateServiceTest extends TestCase
{
    /**
     * @magentoConfigFixture default_store grin_integration/webhook/active 1
     * @magentoConfigFixture default_store grin_integration/webhook/token integration_tests
     * @dataProvider dataProvider
     */
    public function testSend(string $token, array $data)
    {
        $service = Bootstrap::getObjectManager()->get(AffiliateService::class);
        $this->assertTrue($service->send($token, $data));
    }

    /**
     * @return array[]
     */
    public function dataProvider(): array
    {
        return [
            ['sales_order_created', ['id' => 1]],
            ['sales_order_updated', ['id' => 1]],
            ['catalog_category_created', ['id' => 1]],
            ['catalog_category_updated', ['id' => 1]],
            ['catalog_category_deleted', ['id' => 1]],
            ['catalog_product_created', ['id' => 1, 'sku' => 'simple-test']],
            ['stock_item_updated', []],
            ['catalog_product_updated', ['id' => 1, 'sku' => 'simple-test']],
            ['catalog_product_deleted', ['id' => 1, 'sku' => 'simple-test']],
        ];
    }

    /**
     * @magentoConfigFixture default_store grin_integration/webhook/active 0
     * @magentoConfigFixture default_store grin_integration/webhook/token integration_tests
     */
    public function testSendNotActive()
    {
        $service = Bootstrap::getObjectManager()->get(AffiliateService::class);
        $this->assertFalse($service->send('test_topic', []));
    }

    /**
     * @magentoConfigFixture default_store grin_integration/webhook/active 1
     * @magentoConfigFixture default_store grin_integration/webhook/token
     */
    public function testSendWithoutToken()
    {
        $service = Bootstrap::getObjectManager()->get(AffiliateService::class);
        $this->assertFalse($service->send('test_topic', []));
    }
}
