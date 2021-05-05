<?php

declare(strict_types=1);

namespace Grin\Module\Model\Data;

use Grin\Module\Api\Data\RequestInterface;

class Request implements RequestInterface
{
    /**
     * @var string
     */
    private $topic;

    /**
     * @var string
     */
    private $serializedData;

    /**
     * @inheritDoc
     */
    public function getTopic(): string
    {
        return $this->topic;
    }

    /**
     * @inheritDoc
     */
    public function setTopic(string $topic): RequestInterface
    {
        $this->topic = $topic;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getSerializedData(): string
    {
        return $this->serializedData;
    }

    /**
     * @inheritDoc
     */
    public function setSerializedData(string $serializedData): RequestInterface
    {
        $this->serializedData = $serializedData;

        return $this;
    }
}
