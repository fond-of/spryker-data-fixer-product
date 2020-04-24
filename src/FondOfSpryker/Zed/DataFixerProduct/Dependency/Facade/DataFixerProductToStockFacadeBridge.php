<?php

namespace FondOfSpryker\Zed\DataFixerProduct\Dependency\Facade;

use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\Stock\Business\StockFacadeInterface;

class DataFixerProductToStockFacadeBridge implements DataFixerProductToStockFacadeInterface
{
    /**
     * @var \Spryker\Zed\Stock\Business\StockFacadeInterface
     */
    protected $stockFacade;

    /**
     * DataFixerProductToStockFacadeBridge constructor.
     *
     * @param \Spryker\Zed\Stock\Business\StockFacadeInterface $stockFacade
     */
    public function __construct(StockFacadeInterface $stockFacade)
    {
        $this->stockFacade = $stockFacade;
    }

    /**
     * @param int $idProductConcrete
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\StockProductTransfer[]
     */
    public function findStockProductsByIdProductForStore(int $idProductConcrete, StoreTransfer $storeTransfer): array
    {
        return $this->stockFacade->findStockProductsByIdProductForStore($idProductConcrete, $storeTransfer);
    }
}
