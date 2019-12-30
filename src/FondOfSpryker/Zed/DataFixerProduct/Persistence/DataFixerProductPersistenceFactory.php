<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace FondOfSpryker\Zed\DataFixerProduct\Persistence;

use Orm\Zed\Availability\Persistence\SpyAvailability;
use Orm\Zed\Availability\Persistence\SpyAvailabilityAbstract;
use Orm\Zed\Availability\Persistence\SpyAvailabilityAbstractQuery;
use Orm\Zed\Availability\Persistence\SpyAvailabilityQuery;
use Orm\Zed\AvailabilityStorage\Persistence\SpyAvailabilityStorage;
use Orm\Zed\AvailabilityStorage\Persistence\SpyAvailabilityStorageQuery;
use Orm\Zed\Oms\Persistence\SpyOmsProductReservation;
use Orm\Zed\Oms\Persistence\SpyOmsProductReservationQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \FondOfSpryker\Zed\DataFixerProduct\DataFixerProductConfig getConfig()
 * @method \FondOfSpryker\Zed\DataFixerProduct\Persistence\DataFixerProductQueryContainer getQueryContainer()
 * @method \FondOfSpryker\Zed\DataFixerProduct\Persistence\DataFixerProductRepositoryInterface getRepository()
 * @method \FondOfSpryker\Zed\DataFixerProduct\Persistence\DataFixerProductEntityManagerInterface getEntityManager()
 */
class DataFixerProductPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\Availability\Persistence\SpyAvailabilityQuery
     */
    public function createAvailabilityQuery(): SpyAvailabilityQuery
    {
        return new SpyAvailabilityQuery();
    }

    /**
     * @return \Orm\Zed\Availability\Persistence\SpyAvailabilityAbstractQuery
     */
    public function createAvailabilityAbstractQuery(): SpyAvailabilityAbstractQuery
    {
        return new SpyAvailabilityAbstractQuery();
    }

    /**
     * @return \Orm\Zed\Oms\Persistence\SpyOmsProductReservationQuery
     */
    public function createOmsProductReservationQuery(): SpyOmsProductReservationQuery
    {
        return new SpyOmsProductReservationQuery();
    }

    /**
     * @return \Orm\Zed\AvailabilityStorage\Persistence\SpyAvailabilityStorageQuery
     */
    public function createAvailabilityStorageQuery(): SpyAvailabilityStorageQuery
    {
        return new SpyAvailabilityStorageQuery();
    }
}
