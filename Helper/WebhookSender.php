<?php


namespace Grin\GrinModule\Helper;


use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\ScopeInterface;

class WebhookSender extends AbstractHelper
{
    const XML_PATH_ACTIVE = 'grinwebhook/webhook/active';
    const XML_PATH_TOKEN  = 'grinwebhook/webhook/token';
    const GRIN_URL = 'https://app.grin.co/ecommerce/magento/webhook';

    /**
     * @var ScopeConfigInterface
     */
    private $_scopeConfig;

    /**
     * WebhookSender constructor.
     *
     * @param Context              $context
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(Context $context, ScopeConfigInterface $scopeConfig)
    {
        parent::__construct($context);
        $this->_scopeConfig = $scopeConfig;
    }

    /**
     * @param string $topic
     * @param array  $data
     */
    public function send($topic, $data)
    {
        if (!$this->canSend()) {
            return;
        }

        $payload = json_encode($data);

        $ch = curl_init(static::GRIN_URL);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($payload),
            'Authorization: ' . $this->getToken(),
            'Magento-Webhook-Topic: ' . $topic
        ]);

        $this->_logger->info(sprintf('Sending the webhook "%s" %s', $topic, $payload));

        $result = curl_exec($ch);

        if ($result === false) {
            $this->_logger->critical(sprintf('Grin Webhook sending failed (err: %s)', curl_error($ch)));
        }
        if (curl_getinfo($ch, CURLINFO_HTTP_CODE) !== 200) {
            $this->_logger->critical(sprintf('Grin Webhook processing error (err: %s)', json_encode($result)));
        }

        curl_close($ch);
    }

    /**
     * Whether is active
     *
     * @return bool
     */
    private function getToken()
    {
        return $this->_scopeConfig->getValue(self::XML_PATH_TOKEN, ScopeInterface::SCOPE_STORE);
    }

    /**
     * Whether is active
     *
     * @return bool
     */
    private function isEnabled()
    {
        return $this->_scopeConfig->isSetFlag(self::XML_PATH_ACTIVE, ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return bool
     */
    private function canSend()
    {
        if (!$this->isEnabled()) {
            return false;
        }
        if (!$this->getToken()) {
            $this->_logger->critical('Authentication token has not been set up for Grin Webhooks');

            return false;
        }

        return true;
    }
}