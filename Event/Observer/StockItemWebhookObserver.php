<?php

namespace Grin\GrinModule\Event\Observer;

use Magento\Framework\Event\Observer;
use Magento\CatalogInventory\Model\Adminhtml\Stock\Item\Interceptor;

class StockItemWebhookObserver extends AbstractWebhookObserver
{
    /**
     * @param Observer $observer
     * @return bool
     */
    protected function isSupported(Observer $observer)
    {
        if (!$observer->getDataObject() instanceof Interceptor) {
            return false;
        }

        return  true;
    }

    /**
     * @param Object $object
     * @return string
     */
    protected function buildType($object)
    {
        return 'stock_item_' . AbstractWebhookObserver::POSTFIX_UPDATED;
    }

    /**
     * @param Interceptor $interceptor
     * @return array
     */
    protected function buildData($interceptor)
    {
        return [
            'product_id' => $interceptor->getProductId(),
            'id' => $interceptor->getStockId(),
        ];
    }
}