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
    }
}
