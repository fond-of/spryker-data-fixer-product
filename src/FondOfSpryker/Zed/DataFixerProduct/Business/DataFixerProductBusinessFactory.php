<?php

namespace FondOfSpryker\Zed\DataFixerProduct\Business;

use FondOfSpryker\Zed\DataFixer\Business\Dependency\DataFixerInterface;
use FondOfSpryker\Zed\DataFixerProduct\Business\Fixer\ProductAvailabilityAndReservationDataFixer;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \FondOfSpryker\Zed\DataFixerProduct\DataFixerProductConfig getConfig()
 * @method \FondOfSpryker\Zed\DataFixerProduct\Persistence\DataFixerProductQueryContainer getQueryContainer()
 */
class DataFixerProductBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \FondOfSpryker\Zed\DataFixer\Business\Dependency\DataFixerInterface
     */
    public function createProductAvailabilityAndReservationDataFixer(): DataFixerInterface
    {
        return new ProductAvailabilityAndReservationDataFixer();
    }
}
