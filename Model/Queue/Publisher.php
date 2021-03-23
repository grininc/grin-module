<?php

declare(strict_types=1);

namespace Grin\Affiliate\Model\Queue;

use Grin\Affiliate\Api\PublisherInterface as GrinPublisherInterface;
use Grin\Affiliate\Api\Data\RequestInterfaceFactory;
use Magento\Framework\MessageQueue\PublisherInterface;
use Magento\Framework\Serialize\Serializer\Json;

class Publisher implements GrinPublisherInterface
{
    /**
     * @var PublisherInterface
     */
    private $publisher;

    /**
     * @var RequestInterfaceFactory
     */
    private $requestFactory;

    /**
     * @var Json
     */
    private $json;

    /**
     * @param PublisherInterface $publisher
     * @param RequestInterfaceFactory $requestFactory
     * @param Json $json
     */
    public function __construct(
        PublisherInterface $publisher,
        RequestInterfaceFactory $requestFactory,
        Json $json
    ) {
        $this->publisher = $publisher;
        $this->requestFactory = $requestFactory;
        $this->json = $json;
    }

    /**
     * @inheritDoc
     */
    public function publish(string $topic, array $data)
    {
        $request = $this->requestFactory->create();
        $request->setTopic($topic);
        $request->setSerializedData($this->json->serialize($data));

        $this->publisher->publish(self::TOPIC, $request);

        // \Magento\MysqlMq\Model\ResourceModel\Queue
    }
}
