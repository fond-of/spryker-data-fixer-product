<?php

namespace FondOfSpryker\Zed\DataFixerProduct\Communication\Plugin;

use FondOfSpryker\Zed\DataFixer\Business\Dependency\DataFixerInterface;
use FondOfSpryker\Zed\DataFixer\Business\Dependency\DataFixerPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * Class ProductAvailabilityAndReservationDataFixerPlugin
 *
 * @package FondOfSpryker\Zed\DataFixerProduct\Communication\Plugin
 *
 * @method \FondOfSpryker\Zed\DataFixerProduct\Communication\DataFixerProductCommunicationFactory getFactory()
 * @method \FondOfSpryker\Zed\DataFixerProduct\Business\DataFixerProductFacade getFacade()
 * @method \FondOfSpryker\Zed\DataFixerProduct\DataFixerProductConfig getConfig()
 * @method \FondOfSpryker\Zed\DataFixerProduct\Persistence\DataFixerProductQueryContainerInterface getQueryContainer()
 */
class ProductAvailabilityAndReservationDataFixerPlugin extends AbstractPlugin implements DataFixerPluginInterface
{
    /**
     * @return \FondOfSpryker\Zed\DataFixer\Business\Dependency\DataFixerInterface
     */
    public function getDataFixer(): DataFixerInterface
    {
        return $this->getFactory()->createProductAvailabilityAndReservationDataFixer();
    }
}
