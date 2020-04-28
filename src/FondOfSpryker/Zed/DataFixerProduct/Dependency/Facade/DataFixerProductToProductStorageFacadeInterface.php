<?php

namespace FondOfSpryker\Zed\DataFixerProduct\Dependency\Facade;

interface DataFixerProductToProductStorageFacadeInterface
{
    /**
     * @param array $productAbstractIds
     */
    public function publishAbstractProducts(array $productAbstractIds): void;
}
