<?php

namespace Grin\GrinModule\Api;

use Magento\CatalogInventory\Model\ResourceModel\Stock\Item\StockItemCriteria;
use Magento\CatalogInventory\Model\Stock\StockItemRepository;
use Magento\Framework\Phrase;
use Magento\Framework\Validation\ValidationException;
use Magento\Framework\Api\SearchCriteriaInterface;

class StockItems implements StockItemsInterface
{
    /**
     * @var StockItemRepository
     */
    private $repository;

    /**
     * StockItems constructor.
     *
     * @param StockItemRepository $repository
     */
    public function __construct(StockItemRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @inheritDoc
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        return $this->repository->getList($this->transformCriteria($searchCriteria));
    }

    /**
     * @param SearchCriteriaInterface $searchCriteria
     * @return StockItemCriteria
     * @throws ValidationException
     */
    protected function transformCriteria(SearchCriteriaInterface $searchCriteria)
    {
        $criteria = new StockItemCriteria();

        $products = [];
        /** @var \Magento\Framework\Api\Search\FilterGroup $filterGroup */
        foreach ($searchCriteria->getFilterGroups() as $filterGroup) {
            /** @var \Magento\Framework\Api\Filter $filter */
            foreach ($filterGroup->getFilters() as $filter) {
                if ($filter->getField() !== 'product_id') {
                    throw new ValidationException(new Phrase('Only filtering by productId is supported'));
                }
                if (!in_array($filter->getConditionType(), ['eq', 'in'], true)) {
                    throw new ValidationException(new Phrase('Only "eq" and "in" condition types are supported'));
                }
                if ($filter->getConditionType() === 'eq') {
                    $products[] = $filter->getValue();

                    break 2;
                }
                if ($filter->getConditionType() === 'in') {
                    $products = array_merge(explode(',', $filter->getValue()), $products);

                    break 2;
                }
            }
        }
        if (!$products) {
            throw new ValidationException(new Phrase('Please define at least one product filter'));
        }


        $criteria->setProductsFilter($products);

        $pageSize = $searchCriteria->getPageSize() ?: 100;

        $criteria->setLimit($pageSize * ($searchCriteria->getCurrentPage() - 1), $pageSize);

        return $criteria;
    }
}