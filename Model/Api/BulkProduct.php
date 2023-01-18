<?php

namespace Grin\Module\Model\Api;

use Grin\Module\Api\BulkProductInterface;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Api\Data\ProductSearchResultsInterface;
use Magento\Catalog\Api\ProductAttributeRepositoryInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory as ProductCollectionFactory;
use Magento\CatalogInventory\Model\Stock\StockItemRepository;
use Magento\CatalogInventory\Model\StockRegistry;
use Magento\Eav\Api\AttributeRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResultsFactory;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Exception\NoSuchEntityException;

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
    protected $searchCriteriaBuilder;

    /**
     * @var AttributeRepositoryInterface
     */
    protected $productAttributeRepository;

    /**
     * @var RequestInterface
     */
    protected $request;

    /**
     * @var StockRegistry
     */
    protected $stockRegistry;

    public function __construct(
        ProductRepositoryInterface $productRepository,
        ProductCollectionFactory $productCollectionFactory,
        SearchResultsFactory $searchResultsFactory,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        ProductAttributeRepositoryInterface $productAttributeRepository,
        RequestInterface $request,
        StockRegistry $stockRegistry
    ) {
        $this->productRepository = $productRepository;
        $this->productCollectionFactory = $productCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->productAttributeRepository = $productAttributeRepository;
        $this->request = $request;
        $this->stockRegistry = $stockRegistry;
    }

    /**
     */
    public function getList(SearchCriteriaInterface $searchCriteria): ProductSearchResultsInterface
    {
        $withChildren = $this->request->getParam('withChildren');
        $products = $this->productRepository->getList($searchCriteria);

        foreach ($products->getItems() as $product) {
            if ($withChildren) {
                $this->setChildren($product, $searchCriteria);
            }

            $this->setProductFullAttributes($product);
            $this->setProductStockStatus($product);
        }

        return $products;
    }

    /**
     * @param ProductInterface $product
     * @param SearchCriteriaInterface $searchCriteria
     * @return void
     */
    public function setChildren(
        ProductInterface $product,
        SearchCriteriaInterface $searchCriteria
    ): void {
        $childrenProductsId = $product->getExtensionAttributes()->getConfigurableProductLinks();

        if (!$childrenProductsId) {
            $bundleLinks = $product->getExtensionAttributes()->getBundleProductOptions();
            if (!$bundleLinks) {
                return;
            }
            foreach ($bundleLinks as $bundleLink) {
                foreach ($bundleLink->getProductLinks() as $p) {
                    $childrenProductsId[] = $p->getId();
                }
            }
        }

        $searchCriteria = $this->searchCriteriaBuilder->addFilter('entity_id', $childrenProductsId,
            'in')->create();
        $childProducts = $this->productRepository->getList($searchCriteria);

        foreach ($childProducts->getItems() as $childProduct) {
            $this->setProductFullAttributes($childProduct);
            $this->setProductStockStatus($childProduct);
        }

        $product->getExtensionAttributes()->setChildren($childProducts);
    }

    /**
     * @param ProductInterface $product
     * @return void
     */
    public function setProductFullAttributes(ProductInterface $product): void
    {
        $attrData = [];
        foreach ($product->getCustomAttributes() as $customAttribute) {
            try {
                $attrData[] = $this->productAttributeRepository->get($customAttribute->getAttributeCode());
            } catch (NoSuchEntityException $exception) {

            }
        }
        $product->getExtensionAttributes()->setFullAttributes($attrData);
    }

    /**
     * @param ProductInterface $product
     * @return void
     */
    public function setProductStockStatus(ProductInterface $product): void
    {
        $stock = $this->stockRegistry->getStockStatus($product->getId());
        $product->getExtensionAttributes()->setStockStatus($stock);
    }
}
