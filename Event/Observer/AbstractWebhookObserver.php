<?php

namespace Grin\GrinModule\Event\Observer;

use Grin\GrinModule\Helper\WebhookSender;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Psr\Log\LoggerInterface;

abstract class AbstractWebhookObserver implements ObserverInterface
{
    /**
     * Postfixes
     */
    const POSTFIX_DELETED = 'deleted';
    const POSTFIX_UPDATED = 'updated';
    const POSTFIX_CREATED = 'created';

    /**
     * @var LoggerInterface
     */
    protected $_logger;

    /**
     * @var WebhookSender
     */
    private $webhookSender;

    /**
     * AfterProduct constructor.
     *
     * @param WebhookSender   $webhookSender
     * @param LoggerInterface $logger
     */
    public function __construct(WebhookSender $webhookSender, LoggerInterface $logger)
    {
        $this->_logger = $logger;
        $this->webhookSender = $webhookSender;
    }

    /**
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {
        try {
            if (!$this->isSupported($observer)) {
                return;
            }
            $object = $observer->getDataObject();

            $this->webhookSender->send($this->buildType($object), $this->buildData($object));
        } catch (\Throwable $e) {
            $this->_logger->critical(sprintf('Webhook processing error: %s trace: %s',
                $e->getMessage(),
                $e->getTraceAsString()
            ));
        }
    }

    /**
     * @param Observer $observer
     * @return bool
     */
    protected function isSupported(Observer $observer)
    {
        return true;
    }

    /**
     * @param Object $object
     * @return string
     */
    abstract protected function buildType($object);

    /**
     * @param Object $object
     * @return array
     */
    abstract protected function buildData($object);
}