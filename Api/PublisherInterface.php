<?php

declare(strict_types=1);

namespace Grin\Affiliate\Api;

/**
 * @api
 */
interface PublisherInterface
{
    public const TOPIC = 'grin_affiliate_webhook';

    /**
     * @param string $topic
     * @param array $data
     * @return void
     */
    public function publish(string $topic, array $data);
}
