<?php

namespace Grin\GrinModule\Model\Carrier;

use Magento\Authorization\Model\UserContextInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Quote\Model\Quote\Address\RateRequest;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Quote\Model\Quote\Address\RateResult\ErrorFactory;
use Magento\Shipping\Model\Rate\Result;
use Psr\Log\LoggerInterface;
use Magento\Shipping\Model\Rate\ResultFactory;
use Magento\Quote\Model\Quote\Address\RateResult\MethodFactory;
use Magento\Framework\App\State;
use Magento\Shipping\Model\Carrier\AbstractCarrier;
use Magento\Shipping\Model\Carrier\CarrierInterface;
use Magento\Backend\App\Area\FrontNameResolver;
use Magento\Framework\App\Action\Context;


class Method extends AbstractCarrier implements CarrierInterface
{
    /**
     * Shipment carrier code
     */
    const CODE = 'grininfluencershipping';

    /**
     * @var string
     */
    protected $_code = self::CODE;

    /**
     * @var bool
     */
    protected $_isFixed = true;

    /**
     * @var ResultFactory
     */
    protected $rateResultFactory;

    /**
     * @var MethodFactory
     */
    protected $rateMethodFactory;

    /**
     * @var State
     */
    protected $appState;

    /**
     * @var Context
     */
    private $context;

    /**
     * @param ScopeConfigInterface $scopeConfig
     * @param ErrorFactory         $rateErrorFactory
     * @param LoggerInterface      $logger
     * @param ResultFactory        $rateResultFactory
     * @param MethodFactory        $rateMethodFactory
     * @param State                $appState
     * @param UserContextInterface $context
     * @param array                $data
     */
    public function __construct(
        ScopeConfigInterface    $scopeConfig,
        ErrorFactory            $rateErrorFactory,
        LoggerInterface         $logger,
        ResultFactory           $rateResultFactory,
        MethodFactory           $rateMethodFactory,
        State                   $appState,
        UserContextInterface    $context,
        array                   $data = []
    ) {
        $this->rateResultFactory = $rateResultFactory;
        $this->rateMethodFactory = $rateMethodFactory;
        $this->appState          = $appState;
        $this->context           = $context;

        parent::__construct(
            $scopeConfig,
            $rateErrorFactory,
            $logger,
            $data
        );
    }

    /**
     * FreeShipping Rates Collector
     *
     * @param RateRequest $request
     * @return Result|bool
     * @throws LocalizedException
     */
    public function collectRates(RateRequest $request)
    {
        if (!$this->getConfigFlag('active') || !$this->isAdmin()) {
            return false;
        }

        /** @var Result $result */
        $result = $this->rateResultFactory->create();

        /** @var \Magento\Quote\Model\Quote\Address\RateResult\Method $method */
        $method = $this->rateMethodFactory->create();

        $method->setCarrier(static::CODE);
        $method->setCarrierTitle($this->getConfigData('title'));

        $method->setMethod(static::CODE);
        $method->setMethodTitle($this->getConfigData('name'));

        $method->setPrice('0.00');
        $method->setCost('0.00');

        $result->append($method);

        return $result;
    }

    /**
     * @return array
     */
    public function getAllowedMethods()
    {
        return [static::CODE => $this->getConfigData('name')];
    }

    /**
     * Checks if it's htmladmin area or admin user or integration access type
     *
     * @return bool
     * @throws LocalizedException
     */
    protected function isAdmin()
    {
        return $this->appState->getAreaCode() === FrontNameResolver::AREA_CODE
            || in_array($this->context->getUserType(), [UserContextInterface::USER_TYPE_ADMIN, UserContextInterface::USER_TYPE_INTEGRATION], true)
            ;
    }
}