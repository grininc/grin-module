<?php

declare(strict_types=1);

namespace Grin\Module\Model;

use Magento\Framework\HTTP\PhpEnvironment\Request;
use Magento\SalesRule\Model\Rule;

class SalesRuleValidator
{
    private const TOKEN_HEADER = 'Validation-Grin-Token';

    /**
     * @var Request
     */
    private $request;

    /**
     * @var SystemConfig
     */
    private $systemConfig;

    /**
     * @param Request $request
     * @param SystemConfig $systemConfig
     */
    public function __construct(Request $request, SystemConfig $systemConfig)
    {
        $this->request = $request;
        $this->systemConfig = $systemConfig;
    }

    /**
     * @param Rule $rule
     * @param string $couponCode
     * @return bool
     */
    public function isValid(Rule $rule, string $couponCode): bool
    {
        if ($rule->getData('is_grin_only') && ($rule->getPrimaryCoupon()->getCode() === $couponCode)) {
            return $this->request->getHeader(self::TOKEN_HEADER) === $this->systemConfig->getSalesRuleToken();
        }

        return true;
    }
}
