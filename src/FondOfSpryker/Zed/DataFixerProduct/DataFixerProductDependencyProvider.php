<?php

namespace FondOfSpryker\Zed\DataFixerProduct;

use FondOfSpryker\Zed\DataFixerProduct\Dependency\Facade\DataFixerProductToAvailabilityStorageFacadeBridge;
use FondOfSpryker\Zed\DataFixerProduct\Dependency\Facade\DataFixerProductToEventBehaviorFacadeBridge;
use FondOfSpryker\Zed\DataFixerProduct\Dependency\Facade\DataFixerProductToProductFacadeBridge;
use FondOfSpryker\Zed\DataFixerProduct\Dependency\Facade\DataFixerProductToProductStorageFacadeBridge;
use FondOfSpryker\Zed\DataFixerProduct\Dependency\Facade\DataFixerProductToStockFacadeBridge;
use FondOfSpryker\Zed\DataFixerProduct\Dependency\Facade\DataFixerProductToStoreFacadeBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class DataFixerProductDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_PRODUCT = 'FACADE_PRODUCT';
    public const FACADE_AVAILABILITY_STORAGE = 'FACADE_AVAILABILITY_STORAGE';
    public const FACADE_STOCK = 'FACADE_STOCK';
    public const FACADE_STORE = 'FACADE_STORE';
    public const FACADE_EVENT_BEHAVIOR = 'FACADE_EVENT_BEHAVIOR';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container = $this->addProductFacade($container);
        $container = $this->addAvailabilityStorageFacade($container);
        $container = $this->addStockFacade($container);
        $container = $this->addStoreFacade($container);
        $container = $this->addEventBehaviorFacade($container);
        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductFacade(Container $container): Container
    {
        $container[static::FACADE_PRODUCT] = function (Container $container) {
            return new DataFixerProductToProductFacadeBridge($container->getLocator()->product()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addAvailabilityStorageFacade(Container $container): Container
    {
        $container[static::FACADE_AVAILABILITY_STORAGE] = function (Container $container) {
            return new DataFixerProductToAvailabilityStorageFacadeBridge($container->getLocator()->availabilityStorage()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addStockFacade(Container $container): Container
    {
        $container[static::FACADE_STOCK] = function (Container $container) {
            return new DataFixerProductToStockFacadeBridge($container->getLocator()->stock()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addStoreFacade(Container $container): Container
    {
        $container[static::FACADE_STORE] = function (Container $container) {
            return new DataFixerProductToStoreFacadeBridge($container->getLocator()->store()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addEventBehaviorFacade(Container $container): Container
    {
        $container[static::FACADE_EVENT_BEHAVIOR] = function (Container $container) {
            return new DataFixerProductToEventBehaviorFacadeBridge($container->getLocator()->eventBehavior()->facade());
        };

        return $container;
    }
}
