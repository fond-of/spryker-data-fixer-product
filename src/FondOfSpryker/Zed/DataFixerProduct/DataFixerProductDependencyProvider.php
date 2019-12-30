<?php

namespace FondOfSpryker\Zed\DataFixerProduct;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class DataFixerProductDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_PRODUCT = 'FACADE_PRODUCT';
    public const FACADE_AVAILABILITY_STORAGE = 'FACADE_AVAILABILITY_STORAGE';

    /**
     * @param  \Spryker\Zed\Kernel\Container  $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container = $this->addProductFacade($container);
        $container = $this->addAvailabilityStorageFacade($container);
        return $container;
    }

    /**
     * @param  \Spryker\Zed\Kernel\Container  $container
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductFacade(Container $container): Container
    {
        $container[static::FACADE_PRODUCT] = function (Container $container) {
            return $container->getLocator()->product()->facade();
        };

        return $container;
    }

    /**
     * @param  \Spryker\Zed\Kernel\Container  $container
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addAvailabilityStorageFacade(Container $container): Container
    {
        $container[static::FACADE_AVAILABILITY_STORAGE] = function (Container $container) {
            return $container->getLocator()->availabilityStorage()->facade();
        };

        return $container;
    }
}
