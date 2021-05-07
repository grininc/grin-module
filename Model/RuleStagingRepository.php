<?php

declare(strict_types=1);

namespace Grin\Module\Model;

use Grin\Module\Api\RuleStagingRepositoryInterface;
use Grin\Module\Api\Data\RuleStagingInterface;
use Magento\SalesRule\Api\Data\RuleInterface;
use Magento\Staging\Model\Entity\Update\Save as StagingUpdateSave;

class RuleStagingRepository implements RuleStagingRepositoryInterface
{
    /**
     * @var StagingUpdateSave
     */
    private $stagingUpdateSave;

    /**
     * @param StagingUpdateSave $stagingUpdateSave
     */
    public function __construct(
        StagingUpdateSave $stagingUpdateSave
    ) {
        $this->stagingUpdateSave = $stagingUpdateSave;
    }

    /**
     * @param RuleStagingInterface $ruleStaging
     * @return RuleInterface
     */
    public function save(RuleStagingInterface $ruleStaging)
    {
        $stagingData = $ruleStaging->getStagingData()->getData();
        $stagingData['mode'] = 'save';

        $this->stagingUpdateSave->execute(
            [
                'entityId' => $ruleStaging->getEntityId(),
                'stagingData' => $stagingData,
                'entityData' => $ruleStaging->getEntityData()->__toArray(),
            ]
        );

        return $ruleStaging->getEntityData();
    }
}
