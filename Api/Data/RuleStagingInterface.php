<?php

declare(strict_types=1);

namespace Grin\Affiliate\Api\Data;

interface RuleStagingInterface
{
    /**
     * Return rule id
     *
     * @return int|null
     */
    public function getEntityId();

    /**
     * Set rule id
     *
     * @param int $entityId
     * @return $this
     */
    public function setEntityId(int $entityId);

    /**
     * Return staging data
     *
     * @return \Grin\Affiliate\Api\Data\StagingDataInterface
     */
    public function getStagingData();

    /**
     * Set staging data
     *
     * @param \Grin\Affiliate\Api\Data\StagingDataInterface $stagingData
     * @return $this
     */
    public function setStagingData(\Grin\Affiliate\Api\Data\StagingDataInterface $stagingData);

    /**
     * Return entity data
     *
     * @return \Magento\SalesRule\Api\Data\RuleInterface
     */
    public function getEntityData();

    /**
     * Set entity data
     *
     * @param \Magento\SalesRule\Api\Data\RuleInterface $entityData
     * @return $this
     */
    public function setEntityData(\Magento\SalesRule\Api\Data\RuleInterface $entityData);
}
