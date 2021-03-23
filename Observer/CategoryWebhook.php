<?php

declare(strict_types=1);

namespace Grin\Affiliate\Observer;

use Grin\Affiliate\Api\PublisherInterface;
use Grin\Affiliate\Model\WebhookStateInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Catalog\Api\Data\CategoryInterface;
use Grin\Affiliate\Api\Data\RequestInterfaceFactory;

class CategoryWebhook implements ObserverInterface
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
        $category = $observer->getDataObject();
        $this->publisher->publish(
            $this->buildType($category),
            [
                'id' => $category->getId(),
            ]
        );
    }

    /**
     * @param CategoryInterface $category
     * @return string
     */
    private function buildType(CategoryInterface $category): string
    {
        $prefix = $category->getEventPrefix();

        if ($category->isDeleted()) {
            return $prefix . WebhookStateInterface::POSTFIX_DELETED;
        }

        if ($category->isObjectNew()) {
            return $prefix . WebhookStateInterface::POSTFIX_CREATED;
        }

        return $prefix . WebhookStateInterface::POSTFIX_UPDATED;
    }
}
