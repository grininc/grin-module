<?php

namespace Grin\GrinModule\Block\Checkout;

use Grin\GrinModule\Helper\CheckoutScriptHelper;
use Magento\Checkout\Model\Session;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Store\Model\ScopeInterface;

class Success extends Template
{
    const XML_PATH_ACTIVE = 'grinaffiliate/scripts/active';

    /**
     * @var Session
     */
    protected $checkoutSession;

    /**
     * @var CheckoutScriptHelper
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
     * @param Context $context
     * @param CheckoutScriptHelper $helper
     * @param Session $checkoutSession
     * @param OrderRepositoryInterface $orderRepository
     * @param array $data
     */
    public function __construct(
        Context $context,
        CheckoutScriptHelper $helper,
        Session $checkoutSession,
        OrderRepositoryInterface $orderRepository,
        array $data = []
    ) {
        $this->helper = $helper;
        $this->checkoutSession = $checkoutSession;
        $this->orderRepository = $orderRepository;
        parent::__construct($context, $data);
    }

    /**
     * @return string
     */
    protected function _toHtml()
    {
        return parent::_toHtml();
    }

    /**
     * @return OrderInterface
     */
    protected function getOrder()
    {
        if (!$this->order) {
            $this->order = $this->orderRepository->get($this->checkoutSession->getLastOrderId());
        }

        return $this->order;
    }

    /**
     * @param $order
     * @return $this
     */
    public function setOrder($order)
    {
        $this->order = $order;
        return $this;
    }

    /**
     * @return array
     */
    protected function getVariablesArray()
    {
        $order = $this->getOrder();

        $this->helper->setVariableData('order_id', $order->getIncrementId());
        $this->helper->setVariableData('total', $this->helper->formatPrice($order->getBaseGrandTotal()));
        $this->helper->setVariableData('sub_total', $this->helper->formatPrice($order->getBaseSubtotal() - abs($order->getDiscountAmount() ?: 0)));
        $this->helper->setVariableData('shipping', $this->helper->formatPrice($order->getBaseShippingAmount()));
        $this->helper->setVariableData('tax', $this->helper->formatPrice($order->getTaxAmount()));
        $this->helper->setVariableData('coupon_code', $order->getCouponCode() ?: '');
        $this->helper->setVariableData('discount', $this->helper->formatPrice(abs($order->getDiscountAmount())));

        return $this->helper->getTemplateVariable();
    }

    /**
     * @param $string
     * @return mixed
     */
    protected function processTemplate($string)
    {
        if (empty($this->templateArray)) {
            $template = $this->getVariablesArray();

            $this->templateArray = str_replace(array_keys($template), array_values($template), $string);
        }

        return $this->templateArray;
    }

    /**
     * @return string
     */
    public function getActiveScripts()
    {
        if (!$this->isEnabled()) {
            return '';
        }
        $script = "<script>Grin = window.Grin || (window.Grin = []);var order_number = '{{order_id}}', amount = '{{sub_total}}';Grin.push(['conversion', amount, {order_number: order_number}]);</script>";

        return $this->processTemplate($script);
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
