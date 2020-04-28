<?php

namespace FondOfSpryker\Zed\DataFixerProduct\Dependency\Facade;

use Spryker\Zed\ProductStorage\Business\ProductStorageFacadeInterface;

class DataFixerProductToProductStorageFacadeBridge implements DataFixerProductToProductStorageFacadeInterface
{
    /**
     * @var \Spryker\Zed\ProductStorage\Business\ProductStorageFacadeInterface
     */
    protected $productStorageFacade;

    /**
     * DataFixerProductToProductStorageFacadeBridge constructor.
     *
     * @param \Spryker\Zed\ProductStorage\Business\ProductStorageFacadeInterface $productStorageFacade
     */
    public function __construct(ProductStorageFacadeInterface $productStorageFacade)
    {
        $this->productStorageFacade = $productStorageFacade;
    }

    /**
     * @param array $productAbstractIds
     *
     * @return void
     */
    public function publishAbstractProducts(array $productAbstractIds): void
    {
        $this->productStorageFacade->publishAbstractProducts($productAbstractIds);
    }
}
