<?php

declare(strict_types=1);

namespace Grin\Affiliate\Ui\DataProvider\MessageQueue;

use Grin\Affiliate\Api\PublisherInterface;
use Magento\Framework\View\Element\UiComponent\DataProvider\DataProvider;

class ListingDataProvider extends DataProvider
{
    /**
     * @inheritDoc
     */
    public function getSearchResult()
    {
        $result = parent::getSearchResult();

        if ($result->isLoaded()) {
            return $result;
        }

        $result->getSelect()->joinLeft(
            ['queue' => $result->getTable('queue')],
            'queue.id = main_table.queue_id',
            []
        );

        $result->getSelect()->where('queue.name = ?', PublisherInterface::TOPIC);

        return $result;
    }
}
