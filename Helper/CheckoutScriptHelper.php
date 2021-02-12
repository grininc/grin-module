<?php

namespace Grin\GrinModule\Helper;

use Magento\Framework\App\Helper\AbstractHelper;

class CheckoutScriptHelper extends AbstractHelper
{
    protected $variables = [
        'order_id' => null,
        'total' => null,
        'sub_total' => null,
        'shipping' => null,
        'tax' => null,
        'coupon_code' => null,
        'discount' => null,
    ];

    const TEMPLATE_START = '{{';
    const TEMPLATE_END = '}}';

    /**
     * Format Price
     *
     * @param $price
     * @return float
     */
    public function formatPrice($price)
    {
        return (float)sprintf('%.2F', $price);
    }

    /**
     * @return array
     */
    public function getVariables()
    {
        return $this->variables;
    }

    /**
     * @param $key
     * @param $value
     * @return $this
     */
    public function setVariableData($key, $value)
    {
        if (array_key_exists($key, $this->variables)) {
            $this->variables[$key] = $value;
        }

        return $this;
    }

    /**
     * @return array
     */
    public function getTemplateVariableKey()
    {
        $keys = [];

        foreach ($this->getVariables() as $key => $val) {
            $keys[] = self::TEMPLATE_START . $key . self::TEMPLATE_END;
        }

        return $keys;
    }

    /**
     * @return array
     */
    public function getTemplateVariable()
    {
        $values = [];

        foreach ($this->getVariables() as $key => $val) {
            $values[self::TEMPLATE_START . $key . self::TEMPLATE_END] = $val;
        }

        return $values;
    }
}
