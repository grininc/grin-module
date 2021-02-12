<?php

namespace Grin\GrinModule\Event\Observer;

use Grin\GrinModule\Event\State\OrderTracker;
use Magento\Framework\Event\ObserverInterface;
use Magento\Sales\Model\Order\Interceptor;
use Magento\Framework\Event\Observer;

class OrderStateObserver implements ObserverInterface
{
    /**
     * @var OrderTracker
     */
    private $orderTracker;

    public function __construct(OrderTracker $orderTracker)
    {
        $this->orderTracker = $orderTracker;
    }


    /**
     * @param Observer $observer
     * @return bool
     */
    protected function isSupported(Observer $observer)
    {
        $interceptor = $observer->getDataObject();

        if (!$interceptor instanceof Interceptor) {
            return false;
        }

        return $interceptor->isObjectNew();
    }

    /**
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {
        if ($this->isSupported($observer)) {
            $this->orderTracker->setModel($observer->getDataObject());
        }
    }
}