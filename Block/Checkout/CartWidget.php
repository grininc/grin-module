<?php

namespace Grin\GrinModule\Block\Checkout;

use Magento\Checkout\Model\CompositeConfigProvider;
use Magento\Checkout\Model\Session;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Store\Model\ScopeInterface;

class CartWidget extends Template
{
    const XML_PATH_ACTIVE = 'grincheckout/cartwidget/active';

    /**
     * @var CompositeConfigProvider
     */
    protected $configProvider;

    /**
     * CartWidget constructor.
     *
     * @param Context                 $context
     * @param Session                 $checkoutSession
     * @param CompositeConfigProvider $configProvider
     * @param array                   $data
     */
    public function __construct(Template\Context $context, Session $checkoutSession, CompositeConfigProvider $configProvider, array $data = [])
    {
        $this->configProvider = $configProvider;
        parent::__construct($context, $data);
    }

    /**
     * @return array
     */
    public function getQuoteId()
    {
        return $this->configProvider->getConfig()['quoteData']['entity_id'];
    }

    /**
     * @return string
     */
    protected function _toHtml()
    {
        return parent::_toHtml();
    }

    /**
     * Whether is active
     *
     * @return bool
     */
    public function isEnabled()
    {
        return $this->_scopeConfig->isSetFlag(self::XML_PATH_ACTIVE, ScopeInterface::SCOPE_STORE);
    }
}
