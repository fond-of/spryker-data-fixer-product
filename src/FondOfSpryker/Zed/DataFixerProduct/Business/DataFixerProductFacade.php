<?php

namespace FondOfSpryker\Zed\DataFixerProduct\Business;

use FondOfSpryker\Zed\DataFixer\Business\Dependency\DataFixerInterface;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \FondOfSpryker\Zed\DataFixerProduct\Business\DataFixerProductBusinessFactory getFactory()
 * @method \FondOfSpryker\Zed\DataFixerProduct\Persistence\DataFixerProductEntityManagerInterface getEntityManager()
 * @method \FondOfSpryker\Zed\DataFixerProduct\Persistence\DataFixerProductRepositoryInterface getRepository()
 */
class DataFixerProductFacade extends AbstractFacade implements DataFixerProductFacadeInterface
{
    /**
     * @return \FondOfSpryker\Zed\DataFixer\Business\Dependency\DataFixerInterface
     */
    public function getProductAvailabilityAndReservationDataFixer(): DataFixerInterface
    {
        //ToDo not good to return whole fixer in facade, so fix it someday
        return $this->getFactory()->createProductAvailabilityAndReservationDataFixer();
    }

    /**
     * @return \FondOfSpryker\Zed\DataFixer\Business\Dependency\DataFixerInterface
     */
    public function getProductAvailabilityAndReservationQunatityDataFixer(): DataFixerInterface
    {
        //ToDo not good to return whole fixer in facade, so fix it someday
        return $this->getFactory()->createProductAvailabilityAndReservationQunatityDataFixer();
    }
}
