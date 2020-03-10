<?php

namespace FondOfSpryker\Zed\DataFixerProduct\Business;

use FondOfSpryker\Zed\DataFixer\Business\Dependency\DataFixerInterface;

interface DataFixerProductFacadeInterface
{
    /**
     * @return \FondOfSpryker\Zed\DataFixer\Business\Dependency\DataFixerInterface
     */
    public function getProductAvailabilityAndReservationDataFixer(): DataFixerInterface;

    /**
     * @return \FondOfSpryker\Zed\DataFixer\Business\Dependency\DataFixerInterface
     */
    public function getProductAvailabilityAndReservationQunatityDataFixer(): DataFixerInterface;
}
