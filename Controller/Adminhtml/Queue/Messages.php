<?php

declare(strict_types=1);

namespace Grin\Module\Controller\Adminhtml\Queue;

use Magento\Backend\App\Action;
use Magento\Framework\App\Action\HttpGetActionInterface as HttpGetActionInterface;
use Magento\Framework\Controller\ResultFactory;

class Messages extends Action implements HttpGetActionInterface
{
    /**
     * @inheritDoc
     */
    public function execute()
    {
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        $resultPage->setActiveMenu('Magento_Support::support_report');
        $resultPage->getConfig()->getTitle()->prepend((__('Grin Module Queue Messages')));

        return $resultPage;
    }
}
