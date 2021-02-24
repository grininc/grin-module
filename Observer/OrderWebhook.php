<?php

declare(strict_types=1);

namespace Grin\GrinModule\Observer;

use Grin\GrinModule\Model\WebhookStateInterface;
use Grin\GrinModule\Model\OrderTracker;
use Grin\GrinModule\Model\WebhookSender;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Sales\Api\Data\OrderInterface;

class OrderWebhook implements ObserverInterface
{
    /**
     * @var WebhookSender
     */
    private $webhookSender;

    /**
     * @var OrderTracker
     */
    private $orderTracker;

    /**
     * @param WebhookSender $webhookSender
     * @param OrderTracker $orderTracker
     */
    public function __construct(WebhookSender $webhookSender, OrderTracker $orderTracker)
    {
        $this->webhookSender = $webhookSender;
        $this->orderTracker = $orderTracker;
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
     * @param OrderInterface $order
     * @return string
     */
    private function buildType(OrderInterface $order): string
    {
        $postfix = $this->orderTracker->isNew()
            ? WebhookStateInterface::POSTFIX_CREATED : WebhookStateInterface::POSTFIX_UPDATED;

        return $order->getEventPrefix() . '_' . $postfix;
    }

    /**
     * @param OrderInterface $order
     * @return array
     */
    private function buildData(OrderInterface $order): array
    {
        return [
            'id' => $order->getId(),
        ];
    }
}
