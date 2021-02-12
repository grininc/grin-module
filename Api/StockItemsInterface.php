<?php

namespace Grin\GrinModule\Api;

interface StockItemsInterface
{
    /**
     * Return StockItems by product skus
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Magento\CatalogInventory\Api\Data\StockItemCollectionInterface
     * @api
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);
}