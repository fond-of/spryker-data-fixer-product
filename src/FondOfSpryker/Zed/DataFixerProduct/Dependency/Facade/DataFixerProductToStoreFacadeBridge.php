<?php

namespace FondOfSpryker\Zed\DataFixerProduct\Dependency\Facade;

use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\Store\Business\StoreFacadeInterface;

class DataFixerProductToStoreFacadeBridge implements DataFixerProductToStoreFacadeInterface
{
    /**
     * @var \Spryker\Zed\Store\Business\StoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * DataFixerProductToStoreFacadeBridge constructor.
     *
     * @param \Spryker\Zed\Store\Business\StoreFacadeInterface $storeFacade
     */
    public function __construct(StoreFacadeInterface $storeFacade)
    {
        $this->storeFacade = $storeFacade;
    }

    /**
     * @param int $idStore
     *
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    public function getStoreById(int $idStore): StoreTransfer
    {
        return $this->storeFacade->getStoreById($idStore);
    }
}
