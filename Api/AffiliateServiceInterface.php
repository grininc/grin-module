<?php

declare(strict_types=1);

namespace Grin\Affiliate\Api;

use Magento\Framework\Exception\LocalizedException;

/**
 * @api
 */
interface AffiliateServiceInterface
{
    public const GRIN_URL = 'https://app.grin.co/ecommerce/magento/webhook';

    /**
     * @param string $topic
     * @param array $data
     *
     * @return bool
     * @throws LocalizedException
     */
    public function send(string $topic, array $data): bool;
}
