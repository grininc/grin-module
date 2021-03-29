<?php

declare(strict_types=1);

namespace Grin\Affiliate\Observer;

use Grin\Affiliate\Model\OrderTracker;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;
use Magento\Sales\Api\Data\OrderInterface;

class OrderState implements ObserverInterface
{
    /**
     * @var OrderTracker
     */
    private $orderTracker;

    /**
     * @param OrderTracker $orderTracker
     */
    public function __construct(OrderTracker $orderTracker)
    {
        $this->orderTracker = $orderTracker;
    }

    /**
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {
        $order = $observer->getDataObject();

        if ($order instanceof OrderInterface) {
            $this->orderTracker->setNew($order->isObjectNew());
        }
    }
}
