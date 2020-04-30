<?php

namespace FondOfSpryker\Zed\DataFixerProduct\Dependency\Facade;

use FondOfSpryker\Zed\Product\Business\ProductFacadeInterface;
use Generated\Shared\Transfer\ProductConcreteTransfer;

class DataFixerProductToProductFacadeBridge implements DataFixerProductToProductFacadeInterface
{
    /**
     * @var \FondOfSpryker\Zed\Product\Business\ProductFacadeInterface
     */
    protected $productFacade;

    /**
     * DataFixerProductToProductFacadeBridge constructor.
     *
     * @param \FondOfSpryker\Zed\Product\Business\ProductFacadeInterface $productFacade
     */
    public function __construct(ProductFacadeInterface $productFacade)
    {
        $this->productFacade = $productFacade;
    }

    /**
     * @param string $sku
     *
     * @return int|null
     */
    public function findProductAbstractIdBySku($sku): ?int
    {
        return $this->productFacade->findProductAbstractIdBySku($sku);
    }

    /**
     * @param int $idProduct
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer|null
     */
    public function findProductConcreteById($idProduct): ?ProductConcreteTransfer
    {
        return $this->productFacade->findProductConcreteById($idProduct);
    }
}
