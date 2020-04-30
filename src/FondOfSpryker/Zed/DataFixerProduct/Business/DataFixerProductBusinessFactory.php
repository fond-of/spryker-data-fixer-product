<?php

namespace FondOfSpryker\Zed\DataFixerProduct\Business;

use FondOfSpryker\Zed\DataFixer\Business\Dependency\DataFixerInterface;
use FondOfSpryker\Zed\DataFixerProduct\Business\Fixer\ProductAvailabilityAndReservationDataFixer;
use FondOfSpryker\Zed\DataFixerProduct\Business\Fixer\ProductAvailabilityAndReservationQuantityDataFixer;
use FondOfSpryker\Zed\DataFixerProduct\DataFixerProductDependencyProvider;
use FondOfSpryker\Zed\DataFixerProduct\Dependency\Facade\DataFixerProductToAvailabilityStorageFacadeInterface;
use FondOfSpryker\Zed\DataFixerProduct\Dependency\Facade\DataFixerProductToEventBehaviorFacadeInterface;
use FondOfSpryker\Zed\DataFixerProduct\Dependency\Facade\DataFixerProductToProductFacadeInterface;
use FondOfSpryker\Zed\DataFixerProduct\Dependency\Facade\DataFixerProductToProductStorageFacadeInterface;
use FondOfSpryker\Zed\DataFixerProduct\Dependency\Facade\DataFixerProductToStockFacadeInterface;
use FondOfSpryker\Zed\DataFixerProduct\Dependency\Facade\DataFixerProductToStoreFacadeInterface;
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
            $this->getAvailabilityStorageFacade()
        );
    }

    /**
     * @return \FondOfSpryker\Zed\DataFixer\Business\Dependency\DataFixerInterface
     */
    public function createProductAvailabilityAndReservationQunatityDataFixer(): DataFixerInterface
    {
        return new ProductAvailabilityAndReservationQuantityDataFixer(
            $this->getRepository(),
            $this->getQueryContainer(),
            $this->getConfig(),
            $this->getProductFacade(),
            $this->getAvailabilityStorageFacade(),
            $this->getStockFacade(),
            $this->getStoreFacade(),
            $this->getEventBehaviorFacade()
        );
    }

    /**
     * @return \FondOfSpryker\Zed\DataFixerProduct\Dependency\Facade\DataFixerProductToProductFacadeInterface
     */
    public function getProductFacade(): DataFixerProductToProductFacadeInterface
    {
        return $this->getProvidedDependency(DataFixerProductDependencyProvider::FACADE_PRODUCT);
    }

    /**
     * @return \FondOfSpryker\Zed\DataFixerProduct\Dependency\Facade\DataFixerProductToAvailabilityStorageFacadeInterface
     */
    public function getAvailabilityStorageFacade(): DataFixerProductToAvailabilityStorageFacadeInterface
    {
        return $this->getProvidedDependency(DataFixerProductDependencyProvider::FACADE_AVAILABILITY_STORAGE);
    }

    /**
     * @return \FondOfSpryker\Zed\DataFixerProduct\Dependency\Facade\DataFixerProductToStockFacadeInterface
     */
    public function getStockFacade(): DataFixerProductToStockFacadeInterface
    {
        return $this->getProvidedDependency(DataFixerProductDependencyProvider::FACADE_STOCK);
    }

    /**
     * @return \FondOfSpryker\Zed\DataFixerProduct\Dependency\Facade\DataFixerProductToStoreFacadeInterface
     */
    public function getStoreFacade(): DataFixerProductToStoreFacadeInterface
    {
        return $this->getProvidedDependency(DataFixerProductDependencyProvider::FACADE_STORE);
    }

    /**
     * @return \FondOfSpryker\Zed\DataFixerProduct\Dependency\Facade\DataFixerProductToEventBehaviorFacadeInterface
     * @throws \Spryker\Zed\Kernel\Exception\Container\ContainerKeyNotFoundException
     */
    public function getEventBehaviorFacade(): DataFixerProductToEventBehaviorFacadeInterface
    {
        return $this->getProvidedDependency(DataFixerProductDependencyProvider::FACADE_EVENT_BEHAVIOR);
    }
}
