<?php

namespace FondOfSpryker\Zed\DataFixerProduct\Persistence;

use Generated\Shared\Transfer\DataFixerProductCriteriaFilterTransfer;

interface DataFixerProductRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\DataFixerProductCriteriaFilterTransfer $criteriaFilterTransfer
     *
     * @throws \Spryker\Zed\Propel\Business\Exception\AmbiguousComparisonException
     *
     * @return \Orm\Zed\Availability\Persistence\SpyAvailability[]|\Propel\Runtime\Collection\ObjectCollection
     */
    public function getWrongStoreAvailabilities(
        DataFixerProductCriteriaFilterTransfer $criteriaFilterTransfer
    ): array;

    /**
     * @param \Generated\Shared\Transfer\DataFixerProductCriteriaFilterTransfer $criteriaFilterTransfer
     *
     * @return \Orm\Zed\Oms\Persistence\SpyOmsProductReservation[]|\Propel\Runtime\Collection\ObjectCollection
     */
    public function getWrongStoreReservations(
        DataFixerProductCriteriaFilterTransfer $criteriaFilterTransfer
    ): array;
}
