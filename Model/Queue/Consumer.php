<?php

declare(strict_types=1);

namespace Grin\Module\Model\Queue;

use Grin\Module\Api\GrinServiceInterface;
use Grin\Module\Api\Data\RequestInterface;
use Magento\Framework\Serialize\SerializerInterface;
use Psr\Log\LoggerInterface;

class Consumer
{
    /**
     * @var GrinServiceInterface
     */
    private $grinService;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @param GrinServiceInterface $grinService
     * @param LoggerInterface $logger
     * @param SerializerInterface $serializer
     */
    public function __construct(
        GrinServiceInterface $grinService,
        LoggerInterface $logger,
        SerializerInterface $serializer
    ) {
        $this->grinService = $grinService;
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

        $this->grinService->send($topic, $data);
    }
}
