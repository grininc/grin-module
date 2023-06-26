<?php

declare(strict_types=1);

namespace Grin\Module\Model\Queue\ResourceModel;

use Magento\Framework\App\ResourceConnection;

class ResponseHandler
{
    /**
     * @var ResourceConnection
     */
    private $resourceConnection;

    /**
     * @param ResourceConnection $resourceConnection
     */
    public function __construct(ResourceConnection $resourceConnection)
    {
        $this->resourceConnection = $resourceConnection;
    }

    /**
     * @param array $data
     */
    public function addResponse(array $data)
    {
        $connection = $this->resourceConnection->getConnection();
        $connection->insert(
            $connection->getTableName('grin_queue_message_status'),
            $data
        );

        $this->deleteQueueMessagesSucceed();
        $this->deleteQueueMessagesErrors();
    }

    private function deleteQueueMessagesSucceed()
    {
        $connection = $this->resourceConnection->getConnection();
        $queueMessageStatusTable = $connection->getTableName('queue_message_status');
        $queueMessageTable = $connection->getTableName('queue_message');

        $select = $connection->select()
            ->from($queueMessageStatusTable, 'message_id')
            ->where('updated_at <= ?', date('Y-m-d', strtotime('-30 days')))
            ->where('queue_id = 5')
            ->where('status = 4');

        $queueIds = $connection->fetchCol($select);

        if (!empty($queueIds)) {
            $connection->delete($queueMessageTable, ['id IN (?)' => $queueIds]);
        }
    }

    private function deleteQueueMessagesErrors()
    {
        $connection = $this->resourceConnection->getConnection();
        $queueMessageStatusTable = $connection->getTableName('queue_message_status');
        $queueMessageTable = $connection->getTableName('queue_message');

        $select = $connection->select()
            ->from($queueMessageStatusTable, 'message_id')
            ->where('updated_at <= ?', date('Y-m-d', strtotime('-6 months')))
            ->where('queue_id = 5')
            ->where('status <> 4');

        $queueIds = $connection->fetchCol($select);

        if (!empty($queueIds)) {
            $connection->delete($queueMessageTable, ['id IN (?)' => $queueIds]);
        }
    }
}
