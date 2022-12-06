<?php

namespace Grin\Module\Model\Api;

use Grin\Module\Api\BulkProductInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory as ProductCollectionFactory;
use Magento\Framework\Api\Search\SearchResult;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResultsFactory;

class BulkProduct implements BulkProductInterface
{
    /**
     * @var ProductRepositoryInterface
     */
    protected $productRepository;

    /**
     * @var ProductCollectionFactory
     */
    protected $productCollectionFactory;

    /**
     * @var SearchResultsFactory
     */
    protected $searchResultsFactory;

    /**
     * @var SearchCriteriaBuilder
     */
    protected  $searchCriteriaBuilder;

    public function __construct(
        ProductRepositoryInterface $productRepository,
        ProductCollectionFactory $productCollectionFactory,
        SearchResultsFactory $searchResultsFactory,
        SearchCriteriaBuilder $searchCriteriaBuilder
    )
    {
        $this->productRepository = $productRepository;
        $this->productCollectionFactory = $productCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
    }

    public function getList(SearchCriteriaInterface $searchCriteria)
    : \Magento\Catalog\Api\Data\ProductSearchResultsInterface {
        $products = $this->productRepository->getList($searchCriteria);

        foreach ($products->getItems() as $product) {
            $childrenProductsId = $product->getExtensionAttributes()->getConfigurableProductLinks();

            if(!$childrenProductsId){
                $bundleLinks = $product->getExtensionAttributes()->getBundleProductOptions();
                if(!$bundleLinks){
                    continue;
                }
                foreach ($bundleLinks as $bundleLink){
                    foreach ($bundleLink->getProductLinks() as $p){
                        $childrenProductsId[] = $p->getId();
                    }
                }
            }

            $searchCriteria = $this->searchCriteriaBuilder->addFilter('entity_id', $childrenProductsId, 'in')->create();
            $childProducts = $this->productRepository->getList($searchCriteria);

            $product->getExtensionAttributes()->setChildren($childProducts);
        }

        return $products;
    }
}
