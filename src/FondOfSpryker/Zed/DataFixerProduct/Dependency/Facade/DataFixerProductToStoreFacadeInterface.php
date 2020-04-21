<?php

namespace FondOfSpryker\Zed\DataFixerProduct\Dependency\Facade;

use Generated\Shared\Transfer\StoreTransfer;

interface DataFixerProductToStoreFacadeInterface
{
    /**
     * @param  int  $idStore
     *
     * @return \Generated\Shared\Transfer\StoreTransfer
     * @throws \Spryker\Zed\Store\Business\Model\Exception\StoreNotFoundException
     */
    public function getStoreById(int $idStore): StoreTransfer;
}
