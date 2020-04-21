<?php

namespace FondOfSpryker\Zed\DataFixerProduct\Persistence;

use FondOfSpryker\Shared\DataFixerProduct\DataFixerProductConstants;
use FondOfSpryker\Zed\DataFixerProduct\Exception\SkuPatternNotAllowedException;
use Generated\Shared\Transfer\DataFixerProductCriteriaFilterTransfer;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \FondOfSpryker\Zed\DataFixerProduct\Persistence\DataFixerProductPersistenceFactory getFactory()
 */
class DataFixerProductRepository extends AbstractRepository implements DataFixerProductRepositoryInterface
{
    protected $allowedPattern = [
        DataFixerProductConstants::SKU_PATTERN_NOT_LIKE_AND,
        DataFixerProductConstants::SKU_PATTERN_LIKE_AND,
        DataFixerProductConstants::SKU_PATTERN_LIKE_OR,
    ];

    /**
     * @param  \Generated\Shared\Transfer\DataFixerProductCriteriaFilterTransfer  $criteriaFilterTransfer
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
     * @param  \Generated\Shared\Transfer\DataFixerProductCriteriaFilterTransfer  $criteriaFilterTransfer
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
     * @param  \Generated\Shared\Transfer\DataFixerProductCriteriaFilterTransfer  $criteriaFilterTransfer
     * @param  \Propel\Runtime\ActiveQuery\ModelCriteria  $query
     *
     * @return void
     *
     * @throws \FondOfSpryker\Zed\DataFixerProduct\Exception\SkuPatternNotAllowedException
     */
    protected function createFilter(
        DataFixerProductCriteriaFilterTransfer $criteriaFilterTransfer,
        ModelCriteria $query
    ): void {

        if ($criteriaFilterTransfer->getFkStore() !== null) {
            $query->filterByFkStore($criteriaFilterTransfer->getFkStore());
        }

        if (($pattern = $criteriaFilterTransfer->getSkusCriteriaPattern()) === null) {
            $pattern = DataFixerProductConstants::SKU_PATTERN_NOT_LIKE_AND;
        }

        $this->validateSkuPattern($pattern);

        if (is_array($criteriaFilterTransfer->getSkus()) && count($criteriaFilterTransfer->getSkus()) > 0) {
            $where = '';
            foreach ($criteriaFilterTransfer->getSkus() as $sku) {
                $where .= sprintf($pattern, $sku);
            }

            $query->where($this->cleanCondition($where));
        }
    }

    /**
     * @param  string  $pattern
     *
     * @return bool
     * @throws \FondOfSpryker\Zed\DataFixerProduct\Exception\SkuPatternNotAllowedException
     */
    protected function validateSkuPattern(string $pattern): bool
    {
        if (in_array($pattern, $this->allowedPattern, true)) {
            return true;
        }
        throw new SkuPatternNotAllowedException(sprintf('Pattern %s not allowed! Available pattern %s', $pattern,
            implode(', ', $this->allowedPattern)));
    }

    /**
     * @param  string  $where
     *
     * @return string
     */
    protected function cleanCondition(string $where): string
    {
        $where = rtrim($where, ' OR ');
        $where = rtrim($where, ' AND ');
        $where = str_replace('"', "'", $where);
        return $where;
    }
}
