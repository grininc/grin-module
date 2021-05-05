<?php

declare(strict_types=1);

namespace Grin\Module\Observer;

use Grin\Module\Api\PublisherInterface;
use Grin\Module\Model\WebhookStateInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class StockItemWebhook implements ObserverInterface
{
    /**
     * @var PublisherInterface
     */
    private $publisher;

    /**
     * @param PublisherInterface $publisher
     */
    public function __construct(PublisherInterface $publisher)
    {
        $this->publisher = $publisher;
    }

    /**
     * @inheritDoc
     */
    public function execute(Observer $observer)
    {
        $stockItem = $observer->getDataObject();
        $this->publisher->publish(
            'stock_item' . WebhookStateInterface::POSTFIX_UPDATED,
            [
                'product_id' => (int) $stockItem->getProductId(),
                'id' => (int) $stockItem->getStockId(),
            ]
        );
    }
}
