<?php

declare(strict_types=1);

namespace Grin\GrinModule\Observer;

use Grin\GrinModule\Model\WebhookStateInterface;
use Grin\GrinModule\Model\WebhookSender;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class StockItemWebhook implements ObserverInterface
{
    /**
     * @var WebhookSender
     */
    private $webhookSender;

    /**
     * @param WebhookSender $webhookSender
     */
    public function __construct(WebhookSender $webhookSender)
    {
        $this->webhookSender = $webhookSender;
    }

    /**
     * @inheritDoc
     */
    public function execute(Observer $observer)
    {
        $stockItem = $observer->getDataObject();
        $this->webhookSender->send(
            'stock_item_' . WebhookStateInterface::POSTFIX_UPDATED,
            [
                'product_id' => $stockItem->getProductId(),
                'id' => $stockItem->getStockId(),
            ]
        );
    }
}
