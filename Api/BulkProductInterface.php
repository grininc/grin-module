<?php

namespace Grin\Module\Api;

use Magento\Framework\Api\Search\SearchResultInterface;

interface BulkProductInterface
{
    /**
     * Get product list
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Magento\Catalog\Api\Data\ProductSearchResultsInterface
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria)
    : \Magento\Catalog\Api\Data\ProductSearchResultsInterface;
}
