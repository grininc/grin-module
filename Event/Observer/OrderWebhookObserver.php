<?php

namespace Grin\GrinModule\Event\Observer;

use Grin\GrinModule\Event\State\OrderTracker;
use Grin\GrinModule\Helper\WebhookSender;
use Magento\Sales\Model\Order\Interceptor;
use Magento\Framework\Event\Observer;
use Magento\Framework\Interception\InterceptorInterface;
use Psr\Log\LoggerInterface;

class OrderWebhookObserver extends AbstractWebhookObserver
{
    /**
     * @var OrderTracker
     */
    private $orderTracker;

    /**
     * OrderWebhookObserver constructor.
     *
     * @param WebhookSender   $webhookSender
     * @param LoggerInterface $logger
     * @param OrderTracker    $orderTracker
     */
    public function __construct(WebhookSender $webhookSender, LoggerInterface $logger, OrderTracker $orderTracker)
    {
        parent::__construct($webhookSender, $logger);

        $this->orderTracker = $orderTracker;
    }

    /**
     * @param InterceptorInterface|Interceptor $interceptor
     * @return string
     */
    protected function buildType($interceptor)
    {
        $postfix = $this->orderTracker->isNew() ? static::POSTFIX_CREATED : static::POSTFIX_UPDATED;

        return $interceptor->getEventPrefix() . '_' . $postfix;
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

        $interceptor = $observer->getDataObject();
        if (!$interceptor instanceof Interceptor) {
            return false;
        }

        return true;
    }
}