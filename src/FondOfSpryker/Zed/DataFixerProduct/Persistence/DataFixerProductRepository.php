<?php

namespace FondOfSpryker\Zed\DataFixerProduct\Persistence;

use FondOfSpryker\Shared\DataFixerProduct\DataFixerProductConstants;
use FondOfSpryker\Zed\DataFixerProduct\Exception\SkuPatternNotAllowedException;
use Generated\Shared\Transfer\DataFixerProductCriteriaFilterTransfer;
use Orm\Zed\Availability\Persistence\Map\SpyAvailabilityTableMap;
use Orm\Zed\Oms\Persistence\Map\SpyOmsProductReservationTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
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
     * @param \Generated\Shared\Transfer\DataFixerProductCriteriaFilterTransfer $criteriaFilterTransfer
     *
     * @return \Orm\Zed\Availability\Persistence\SpyAvailability[]|\Propel\Runtime\Collection\ObjectCollection
     */
    public function getWrongStoreAvailabilities(
        DataFixerProductCriteriaFilterTransfer $criteriaFilterTransfer
    ): array {

        $query = $this->getFactory()
            ->createAvailabilityQuery()
            ->addJoin(SpyAvailabilityTableMap::COL_SKU, SpyProductTableMap::COL_SKU, Criteria::LEFT_JOIN)
            ->withColumn(SpyProductTableMap::COL_ID_PRODUCT, 'id_product')
            ->withColumn(SpyProductTableMap::COL_FK_PRODUCT_ABSTRACT, 'id_product_abstract');

        $this->createFilter($criteriaFilterTransfer, $query, SpyAvailabilityTableMap::COL_SKU);
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

        $query = $this->getFactory()
            ->createOmsProductReservationQuery()
            ->addJoin(SpyOmsProductReservationTableMap::COL_SKU, SpyProductTableMap::COL_SKU, Criteria::LEFT_JOIN)
            ->withColumn(SpyProductTableMap::COL_ID_PRODUCT, 'id_product')
            ->withColumn(SpyProductTableMap::COL_FK_PRODUCT_ABSTRACT, 'id_product_abstract');
        $this->createFilter($criteriaFilterTransfer, $query, SpyOmsProductReservationTableMap::COL_SKU);
        return $query->find()->getData();
    }

    /**
     * @param string $snakeCaseString
     *
     * @return string
     */
    protected function createAlias(string $snakeCaseString): string
    {
        $parts = explode('_', $snakeCaseString);
        return implode('', array_map('ucfirst', $parts));
    }

    /**
     * @param \Generated\Shared\Transfer\DataFixerProductCriteriaFilterTransfer $criteriaFilterTransfer
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     * @param string $tablePrefix
     *
     * @return void
     */
    protected function createFilter(
        DataFixerProductCriteriaFilterTransfer $criteriaFilterTransfer,
        ModelCriteria $query,
        string $tablePrefix
    ): void {

        if ($criteriaFilterTransfer->getFkStore() !== null) {
            $query->filterByFkStore($criteriaFilterTransfer->getFkStore());
        }

        if (($pattern = $criteriaFilterTransfer->getSkusCriteriaPattern()) === null) {
            $pattern = DataFixerProductConstants::SKU_PATTERN_NOT_LIKE_AND;
        }

        $this->validateSkuPattern($pattern);

        $pattern = str_replace('sku', $tablePrefix, $pattern);

        if (is_array($criteriaFilterTransfer->getSkus()) && count($criteriaFilterTransfer->getSkus()) > 0) {
            $where = '';
            foreach ($criteriaFilterTransfer->getSkus() as $sku) {
                $where .= sprintf($pattern, $sku);
            }

            $query->where($this->cleanCondition($where));
        }
    }

    /**
     * @param string $pattern
     *
     * @throws \FondOfSpryker\Zed\DataFixerProduct\Exception\SkuPatternNotAllowedException
     *
     * @return bool
     */
    protected function validateSkuPattern(string $pattern): bool
    {
        if (in_array($pattern, $this->allowedPattern, true)) {
            return true;
        }
        throw new SkuPatternNotAllowedException(sprintf(
            'Pattern %s not allowed! Available pattern %s',
            $pattern,
            implode(', ', $this->allowedPattern)
        ));
    }

    /**
     * @param string $where
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
