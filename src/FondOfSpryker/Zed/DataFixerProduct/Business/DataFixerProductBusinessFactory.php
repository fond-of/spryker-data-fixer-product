<?php

namespace FondOfSpryker\Zed\DataFixerProduct\Business;

use FondOfSpryker\Zed\DataFixer\Business\Dependency\DataFixerInterface;
use FondOfSpryker\Zed\DataFixer\DataFixerDependencyProvider;
use FondOfSpryker\Zed\DataFixerProduct\Business\Fixer\ProductAvailabilityAndReservationDataFixer;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \FondOfSpryker\Zed\DataFixerProduct\DataFixerProductConfig getConfig()
 * @method \FondOfSpryker\Zed\DataFixerProduct\Persistence\DataFixerProductQueryContainer getQueryContainer()
 * @method \FondOfSpryker\Zed\DataFixerProduct\Persistence\DataFixerProductEntityManagerInterface getEntityManager()
 * @method \FondOfSpryker\Zed\DataFixerProduct\Persistence\DataFixerProductRepositoryInterface getRepository()
 */
class DataFixerProductBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \FondOfSpryker\Zed\DataFixer\Business\Dependency\DataFixerInterface
     */
    public function createProductAvailabilityAndReservationDataFixer(): DataFixerInterface
    {
        return new ProductAvailabilityAndReservationDataFixer(
            $this->getRepository(),
            $this->getQueryContainer(),
            $this->getConfig(),
            $this->getProvidedDependency(DataFixerDependencyProvider::FACADE_PRODUCT),
            $this->getProvidedDependency(DataFixerDependencyProvider::FACADE_AVAILABILITY_STORAGE)
        );
    }
}
