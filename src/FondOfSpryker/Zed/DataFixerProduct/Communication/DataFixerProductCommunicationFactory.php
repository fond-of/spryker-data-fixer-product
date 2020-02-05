<?php

namespace FondOfSpryker\Zed\DataFixerProduct\Communication;

use FondOfSpryker\Zed\DataFixer\Business\Dependency\DataFixerInterface;
use FondOfSpryker\Zed\DataFixerProduct\Business\Fixer\ProductAvailabilityAndReservationDataFixer;
use FondOfSpryker\Zed\DataFixerProduct\DataFixerProductDependencyProvider;
use FondOfSpryker\Zed\Product\Business\ProductFacadeInterface;
use Spryker\Zed\AvailabilityStorage\Business\AvailabilityStorageFacadeInterface;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

/**
 * @method \FondOfSpryker\Zed\DataFixerProduct\DataFixerProductConfig getConfig()
 * @method \FondOfSpryker\Zed\DataFixerProduct\Persistence\DataFixerProductQueryContainer getQueryContainer()
 * @method \FondOfSpryker\Zed\DataFixerProduct\Business\DataFixerProductFacadeInterface getFacade()
 * @method \FondOfSpryker\Zed\DataFixerProduct\Persistence\DataFixerProductRepositoryInterface getRepository()
 * @method \FondOfSpryker\Zed\DataFixerProduct\Persistence\DataFixerProductEntityManagerInterface getEntityManager()
 */
class DataFixerProductCommunicationFactory extends AbstractCommunicationFactory
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
            $this->getProductFacade(),
            $this->getAvailabilityStorageFacade()
        );
    }

    /**
     * @return \FondOfSpryker\Zed\Product\Business\ProductFacadeInterface
     */
    public function getProductFacade(): ProductFacadeInterface
    {
        return $this->getProvidedDependency(DataFixerProductDependencyProvider::FACADE_PRODUCT);
    }

    /**
     * @return \Spryker\Client\AvailabilityStorage\AvailabilityStorageFacadeInterface
     */
    public function getAvailabilityStorageFacade(): AvailabilityStorageFacadeInterface
    {
        return $this->getProvidedDependency(DataFixerProductDependencyProvider::FACADE_AVAILABILITY_STORAGE);
    }
}
