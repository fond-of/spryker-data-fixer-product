<?php

namespace FondOfSpryker\Zed\DataFixerProduct\Dependency\Facade;

use Generated\Shared\Transfer\StoreTransfer;

interface DataFixerProductToStockFacadeInterface
{
    /**
     * @param  int  $idProductConcrete
     * @param  \Generated\Shared\Transfer\StoreTransfer  $storeTransfer
     *
     * @return \Generated\Shared\Transfer\StockProductTransfer[]
     */
    public function findStockProductsByIdProductForStore(int $idProductConcrete, StoreTransfer $storeTransfer): array;
}
