<?php

namespace FondOfSpryker\Zed\DataFixerProduct\Business\Fixer;

use FondOfSpryker\Zed\DataFixer\Business\Dependency\DataFixerInterface;
use FondOfSpryker\Zed\DataFixerProduct\DataFixerProductConfig;
use FondOfSpryker\Zed\DataFixerProduct\Dependency\Facade\DataFixerProductToAvailabilityStorageFacadeInterface;
use FondOfSpryker\Zed\DataFixerProduct\Exception\SkuPrefixesNotConfiguredException;
use FondOfSpryker\Zed\DataFixerProduct\Persistence\DataFixerProductQueryContainerInterface;
use FondOfSpryker\Zed\DataFixerProduct\Persistence\DataFixerProductRepositoryInterface;
use FondOfSpryker\Zed\Product\Business\ProductFacadeInterface;
use Generated\Shared\Transfer\DataFixerProductCriteriaFilterTransfer;
use Spryker\Shared\Log\LoggerTrait;
use Spryker\Zed\AvailabilityStorage\Business\AvailabilityStorageFacadeInterface;

class ProductAvailabilityAndReservationDataFixer implements DataFixerInterface
{
    use LoggerTrait;

    public const NAME = 'ProductAvailabilityAndReservationWrongStoreRelationRemover';

    /**
     * @var int
     */
    protected $unpublishCollectionCount = 1;

    /**
     * @var \FondOfSpryker\Zed\DataFixerProduct\Persistence\DataFixerProductQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var \FondOfSpryker\Zed\DataFixerProduct\Persistence\DataFixerProductRepositoryInterface
     */
    protected $repository;

    /**
     * @var \FondOfSpryker\Zed\DataFixerProduct\Dependency\Facade\DataFixerProductToAvailabilityStorageFacadeInterface
     */
    protected $availabilityStorageFacade;

    /**
     * @var \FondOfSpryker\Zed\DataFixerProduct\DataFixerProductConfig
     */
    protected $config;

    /**
     * ProductAvailabilityAndReservationDataFixer constructor.
     *
     * @param  \FondOfSpryker\Zed\DataFixerProduct\Persistence\DataFixerProductRepositoryInterface  $repository
     * @param  \FondOfSpryker\Zed\DataFixerProduct\Persistence\DataFixerProductQueryContainerInterface  $queryContainer
     * @param  \FondOfSpryker\Zed\DataFixerProduct\DataFixerProductConfig  $config
     * @param  \FondOfSpryker\Zed\DataFixerProduct\Dependency\Facade\DataFixerProductToAvailabilityStorageFacadeInterface  $availabilityStorageFacade
     */
    public function __construct(
        DataFixerProductRepositoryInterface $repository,
        DataFixerProductQueryContainerInterface $queryContainer,
        DataFixerProductConfig $config,
        DataFixerProductToAvailabilityStorageFacadeInterface $availabilityStorageFacade
    ) {
        $this->repository = $repository;
        $this->queryContainer = $queryContainer;
        $this->config = $config;
        $this->availabilityStorageFacade = $availabilityStorageFacade;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return static::NAME;
    }

    /**
     * @param array $stores
     *
     * @return bool
     */
    public function fix(array $stores): bool
    {
        foreach ($stores as $storeName => $storeId) {
            $criteriaFilter = $this->prepareCriteriaFilter($storeName, $storeId);

            $this->fixAvailabilityData($criteriaFilter);
            $this->fixReservationData($criteriaFilter);
        }

        return true;
    }

    /**
     * @param string $storeName
     * @param int $storeId
     *
     * @throws \FondOfSpryker\Zed\DataFixerProduct\Exception\SkuPrefixesNotConfiguredException
     *
     * @return \Generated\Shared\Transfer\DataFixerProductCriteriaFilterTransfer
     */
    public function prepareCriteriaFilter(string $storeName, int $storeId): DataFixerProductCriteriaFilterTransfer
    {
        $configData = $this->config->getAvailabilityData();

        $criteriaFilter = new DataFixerProductCriteriaFilterTransfer();
        $criteriaFilter->setFkStore($storeId);

        if (!array_key_exists($storeId, $configData) || count($configData[$storeId]) === 0) {
            throw new SkuPrefixesNotConfiguredException(sprintf(
                'No sku defined in config for %s with id %s',
                $storeName,
                $storeId
            ));
        }

        $criteriaFilter->setSkus($configData[$storeId]);

        return $criteriaFilter;
    }

    /**
     * @param \Generated\Shared\Transfer\DataFixerProductCriteriaFilterTransfer $criteriaFilter
     *
     * @return void
     */
    protected function fixAvailabilityData(DataFixerProductCriteriaFilterTransfer $criteriaFilter): void
    {
        $unpublishIds = [];
        foreach ($this->repository->getWrongStoreAvailabilities($criteriaFilter) as $availability) {
            $availabilityAbstract = $availability->getSpyAvailabilityAbstract();
            $unpublishIds[] = $availabilityAbstract->getIdAvailabilityAbstract();
            $availability->delete();
            $this->getLogger()->info(sprintf(
                '%s deleting availability %s in store %s',
                $this->getName(),
                $availability->getSku(),
                $criteriaFilter->getFkStore()
            ), $availability->toArray());
            $availabilityAbstract->delete();
            $this->getLogger()->info(sprintf(
                '%s deleting availability abstract %s in store %s',
                $this->getName(),
                $availabilityAbstract->getAbstractSku(),
                $criteriaFilter->getFkStore()
            ), $availabilityAbstract->toArray());

            if (count($unpublishIds) === $this->unpublishCollectionCount) {
                $this->availabilityStorageFacade->unpublish($unpublishIds);
                $this->getLogger()->info(sprintf(
                    '%s unpublished availability storage id(s) %s in store %s',
                    $this->getName(),
                    implode(',', $unpublishIds),
                    $criteriaFilter->getFkStore()
                ), $unpublishIds);

                $unpublishIds = [];
            }
        }
        if (count($unpublishIds) > 0) {
            $this->availabilityStorageFacade->unpublish($unpublishIds);
            $this->getLogger()->info(sprintf(
                '%s unpublished ids %s in store %s',
                $this->getName(),
                implode(',', $unpublishIds),
                $criteriaFilter->getFkStore()
            ), $unpublishIds);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\DataFixerProductCriteriaFilterTransfer $criteriaFilter
     *
     * @return void
     */
    protected function fixReservationData(DataFixerProductCriteriaFilterTransfer $criteriaFilter): void
    {
        foreach ($this->repository->getWrongStoreReservations($criteriaFilter) as $productReservation) {
            $productReservation->delete();
            $this->getLogger()->info(sprintf(
                '%s deleting product reservation %s in store %s',
                $this->getName(),
                $productReservation->getSku(),
                $criteriaFilter->getFkStore()
            ), $productReservation->toArray());
        }
    }
}
