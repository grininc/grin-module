<?php

declare(strict_types=1);

namespace Grin\Module\Model\Queue;

use Magento\Framework\App\ObjectManager;
use Magento\Framework\Exception\LocalizedException;

class InvokerFactory
{
    /**
     * @var ObjectManager
     */
    private $objectManager;

    /**
     * @param ObjectManager $objectManager
     */
    public function __construct(ObjectManager $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    /**
     * @return \Magento\Framework\MessageQueue\CallbackInvoker|\Magento\Framework\MessageQueue\CallbackInvokerInterface
     * @throws LocalizedException
     */
    public function get()
    {
        if (class_exists(\Magento\Framework\MessageQueue\CallbackInvokerInterface::class, false)) {
            return $this->objectManager->get(\Magento\Framework\MessageQueue\CallbackInvokerInterface::class);
        }

        if (class_exists(\Magento\Framework\MessageQueue\CallbackInvoker::class, false)) {
            return $this->objectManager->get(\Magento\Framework\MessageQueue\CallbackInvoker::class);
        }

        throw new LocalizedException(__('The invoker is not identified'));
    }
}
