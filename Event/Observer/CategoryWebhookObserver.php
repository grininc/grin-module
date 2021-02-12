<?php

namespace Grin\GrinModule\Event\Observer;

use Magento\Catalog\Model\Category\Interceptor;
use Magento\Framework\Event\Observer;
use Magento\Framework\Interception\InterceptorInterface;

class CategoryWebhookObserver extends AbstractWebhookObserver
{
    /**
     * @param InterceptorInterface|Interceptor $interceptor
     * @return string
     */
    protected function buildType($interceptor)
    {
        $prefix = $interceptor->getEventPrefix() . '_';

        if ($interceptor->isDeleted()) {
            return $prefix . static::POSTFIX_DELETED;
        }

        if ($interceptor->isObjectNew()) {
            return $prefix . static::POSTFIX_CREATED;
        }

        return $prefix . static::POSTFIX_UPDATED;
    }

    /**
     * @param InterceptorInterface|Interceptor $interceptor
     * @return array
     */
    protected function buildData($interceptor)
    {
        return [
            'id'  => $interceptor->getId(),
        ];
    }

    /**
     * @param Observer $observer
     * @return bool
     */
    protected function isSupported(Observer $observer)
    {
        if (!parent::isSupported($observer)) {
            return false;
        }

        if (!$observer->getDataObject() instanceof Interceptor) {
            return false;
        }

        return true;
    }
}
