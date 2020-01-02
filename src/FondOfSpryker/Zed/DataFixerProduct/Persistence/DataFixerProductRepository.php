<?php

namespace FondOfSpryker\Zed\DataFixerProduct\Persistence;

use Generated\Shared\Transfer\DataFixerProductCriteriaFilterTransfer;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \FondOfSpryker\Zed\DataFixerProduct\Persistence\DataFixerProductPersistenceFactory getFactory()
 */
class DataFixerProductRepository extends AbstractRepository implements DataFixerProductRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\DataFixerProductCriteriaFilterTransfer $criteriaFilterTransfer
     *
     * @return \Orm\Zed\Availability\Persistence\SpyAvailability[]|\Propel\Runtime\Collection\ObjectCollection
     */
    public function getWrongStoreAvailabilities(
        DataFixerProductCriteriaFilterTransfer $criteriaFilterTransfer
    ): array {

        $query = $this->getFactory()->createAvailabilityQuery();
        $this->createFilter($criteriaFilterTransfer, $query);

        return $query->find()->getData();
    }

    /**
     * @param \Generated\Shared\Transfer\DataFixerProductCriteriaFilterTransfer $criteriaFilterTransfer
     *
     * @return \Orm\Zed\Oms\Persistence\SpyOmsProductReservation[]|\Propel\Runtime\Collection\ObjectCollection
     */
    public function getWrongStoreReservations(
        DataFixerProductCriteriaFilterTransfer $criteriaFilterTransfer
    ): array {

        $query = $this->getFactory()->createOmsProductReservationQuery();

        $this->createFilter($criteriaFilterTransfer, $query);
        return $query->find()->getData();
    }

    /**
     * @param \Generated\Shared\Transfer\DataFixerProductCriteriaFilterTransfer $criteriaFilterTransfer
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     *
     * @return void
     */
    protected function createFilter(
        DataFixerProductCriteriaFilterTransfer $criteriaFilterTransfer,
        ModelCriteria $query
    ): void {

        if ($criteriaFilterTransfer->getFkStore() !== null) {
            $query->filterByFkStore($criteriaFilterTransfer->getFkStore());
        }

        if (is_array($criteriaFilterTransfer->getSkus()) && count($criteriaFilterTransfer->getSkus()) > 0) {
            $where = '';
            foreach ($criteriaFilterTransfer->getSkus() as $sku) {
                $where .= "sku NOT LIKE '$sku' AND ";
            }
            $query->where(rtrim($where, ' AND '));
        }
    }
}
