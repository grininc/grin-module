<?php

declare(strict_types=1);

namespace Grin\Affiliate\Model\Data;

use Grin\Affiliate\Api\Data\RuleStagingInterface;
use Grin\Affiliate\Api\Data\StagingDataInterface;
use Magento\SalesRule\Api\Data\RuleInterface;

class RuleStaging implements RuleStagingInterface
{
    /**
     * @var int
     */
    private $entityId;

    /**
     * @var StagingDataInterface
     */
    private $stagingData;

    /**
     * @var RuleInterface
     */
    private $entityData;

    /**
     * @inheritDoc
     */
    public function getEntityId()
    {
        return $this->entityId;
    }

    /**
     * @inheritDoc
     */
    public function setEntityId(int $entityId)
    {
        $this->entityId = $entityId;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getStagingData()
    {
        return $this->stagingData;
    }

    /**
     * @inheritDoc
     */
    public function setStagingData(StagingDataInterface $stagingData)
    {
        $this->stagingData = $stagingData;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getEntityData()
    {
        return $this->entityData;
    }

    /**
     * @inheritDoc
     */
    public function setEntityData(RuleInterface $entityData)
    {
        $this->entityData = $entityData;

        return $this;
    }
}
