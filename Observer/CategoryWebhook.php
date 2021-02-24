<?php

declare(strict_types=1);

namespace Grin\Affiliate\Observer;

use Grin\Affiliate\Model\WebhookSender;
use Grin\Affiliate\Model\WebhookStateInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Catalog\Api\Data\CategoryInterface;

class CategoryWebhook implements ObserverInterface
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
     * @param CategoryInterface $category
     * @return string
     */
    private function buildType(CategoryInterface $category): string
    {
        $prefix = $category->getEventPrefix() . '_';

        if ($category->isDeleted()) {
            return $prefix . WebhookStateInterface::POSTFIX_DELETED;
        }

        if ($category->isObjectNew()) {
            return $prefix . WebhookStateInterface::POSTFIX_CREATED;
        }

        return $prefix . WebhookStateInterface::POSTFIX_UPDATED;
    }

    /**
     * @param CategoryInterface $category
     * @return array
     */
    private function buildData(CategoryInterface $category): array
    {
        return [
            'id' => $category->getId(),
        ];
    }
}
