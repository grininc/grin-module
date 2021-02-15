<?php

declare(strict_types=1);

namespace Grin\GrinModule\Observer;

use Grin\GrinModule\Model\WebhookSender;
use Grin\GrinModule\Model\WebhookStateInterface;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class ProductWebhook implements ObserverInterface
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
        $object = $observer->getDataObject();
        $this->webhookSender->send($this->buildType($object), $this->buildData($object));
    }

    /**
     * @param ProductInterface $product
     * @return string
     */
    private function buildType(ProductInterface $product)
    {
        $prefix = $product->getEventPrefix() . '_';

        if ($product->isDeleted()) {
            return $prefix . WebhookStateInterface::POSTFIX_DELETED;
        }

        if ($product->isObjectNew()) {
            return $prefix . WebhookStateInterface::POSTFIX_CREATED;
        }

        return $prefix . WebhookStateInterface::POSTFIX_UPDATED;
    }

    /**
     * @param ProductInterface $product
     * @return array
     */
    private function buildData(ProductInterface $product): array
    {
        return [
            'id' => $product->getId(),
            'sku' => $product->getSku(),
        ];
    }
}
