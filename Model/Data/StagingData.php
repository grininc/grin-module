<?php

declare(strict_types=1);

namespace Grin\Affiliate\Model\Data;

use Grin\Affiliate\Api\Data\StagingDataInterface;
use Magento\Framework\DataObject;

class StagingData extends DataObject implements StagingDataInterface
{
    /**
     * @inheritDoc
     */
    public function getName()
    {
        return $this->getData(self::NAME);
    }

    /**
     * @inheritDoc
     */
    public function setName(string $name)
    {
        return $this->setData(self::NAME, $name);
    }

    /**
     * @inheritDoc
     */
    public function getDescription()
    {
        return $this->getData(self::DESCRIPTION);
    }

    /**
     * @inheritDoc
     */
    public function setDescription(string $description)
    {
        return $this->setData(self::DESCRIPTION, $description);
    }

    /**
     * @inheritDoc
     */
    public function getEndTime()
    {
        return $this->getData(self::END_TIME);
    }

    /**
     * @inheritDoc
     */
    public function setEndTime(string $endTime)
    {
        return $this->setData(self::END_TIME, $endTime);
    }

    /**
     * @inheritDoc
     */
    public function getStartTime()
    {
        return $this->getData(self::START_TIME);
    }

    /**
     * @inheritDoc
     */
    public function setStartTime(string $startTime)
    {
        return $this->setData(self::START_TIME, $startTime);
    }
}
