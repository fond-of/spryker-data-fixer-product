<?php

namespace FondOfSpryker\Zed\DataFixerProduct\Dependency\Facade;

use Generated\Shared\Transfer\ProductConcreteTransfer;

interface DataFixerProductToProductFacadeInterface
{
    /**
     * @param  string  $sku
     *
     * @return int|null
     */
    public function findProductAbstractIdBySku($sku): ?int;

    /**
     * @param  int  $idProduct
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer|null
     */
    public function findProductConcreteById($idProduct): ?ProductConcreteTransfer;
}
