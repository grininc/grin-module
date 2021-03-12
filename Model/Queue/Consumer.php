<?php

declare(strict_types=1);

namespace Grin\Affiliate\Model\Queue;

use Grin\Affiliate\Api\AffiliateServiceInterface;
use Grin\Affiliate\Api\Data\RequestInterface;
use Magento\Framework\Serialize\SerializerInterface;
use Psr\Log\LoggerInterface;

class Consumer
{
    /**
     * @var AffiliateServiceInterface
     */
    private $affiliateService;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @param AffiliateServiceInterface $affiliateService
     * @param LoggerInterface $logger
     * @param SerializerInterface $serializer
     */
    public function __construct(
        AffiliateServiceInterface $affiliateService,
        LoggerInterface $logger,
        SerializerInterface $serializer
    ) {
        $this->affiliateService = $affiliateService;
        $this->logger = $logger;
        $this->serializer = $serializer;
    }

    /**
     * @param RequestInterface $request
     *
     * @return void
     */
    public function process(RequestInterface $request)
    {
        $data = $this->serializer->unserialize($request->getSerializedData());
        $topic = $request->getTopic();

        $this->affiliateService->send($topic, $data);
    }
}
