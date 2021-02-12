<?php

namespace Grin\GrinModule\Block\Footer;

use Magento\Checkout\Model\Session;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Store\Model\ScopeInterface;

class FooterScript extends Template
{
    const XML_PATH_ACTIVE = 'grinaffiliate/scripts/active';

    /**
     * @var Session
     */
    protected $checkoutSession;

    /**
     * @var ScopeConfigInterface
     */
    protected $helper;

    /**
     * @var OrderRepositoryInterface
     */
    protected $orderRepository;

    /**
     * @var OrderInterface
     */
    protected $order;

    /**
     * @var array
     */
    protected $templateArray;

    /**
     * Core store config
     *
     * @var ScopeConfigInterface
     */
    protected $_scopeConfig;

    /**
     * @param Context              $context
     * @param ScopeConfigInterface $scopeConfig
     * @param array                $data
     */
    public function __construct(
        Context              $context,
        ScopeConfigInterface $scopeConfig,
        array $data = []
    )
    {
        parent::__construct($context, $data);
        $this->_scopeConfig = $scopeConfig;
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

    /**
     * @return string
     */
    protected function _toHtml()
    {
        return parent::_toHtml();
    }

    /**
     * @return string
     */
    public function getActiveScripts()
    {
        if (!$this->isEnabled()) {
            return '';
        }

        return "<script>(function () {Grin = window.Grin || (window.Grin = []);var s = document.createElement('script');s.type = 'text/javascript';s.async = true;s.src = 'https://d38xvr37kwwhcm.cloudfront.net/js/grin-sdk.js';var x = document.getElementsByTagName('script')[0];x.parentNode.insertBefore(s, x);})();</script>";

    }
}
