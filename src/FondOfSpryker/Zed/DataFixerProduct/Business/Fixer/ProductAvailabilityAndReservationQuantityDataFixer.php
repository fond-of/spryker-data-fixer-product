<?php

namespace FondOfSpryker\Zed\DataFixerProduct\Business\Fixer;

use Exception;
use FondOfSpryker\Shared\DataFixerProduct\DataFixerProductConstants;
use FondOfSpryker\Zed\DataFixer\Business\Dependency\DataFixerInterface;
use FondOfSpryker\Zed\DataFixerProduct\DataFixerProductConfig;
use FondOfSpryker\Zed\DataFixerProduct\Dependency\Facade\DataFixerProductToAvailabilityStorageFacadeInterface;
use FondOfSpryker\Zed\DataFixerProduct\Dependency\Facade\DataFixerProductToProductFacadeInterface;
use FondOfSpryker\Zed\DataFixerProduct\Dependency\Facade\DataFixerProductToStockFacadeInterface;
use FondOfSpryker\Zed\DataFixerProduct\Dependency\Facade\DataFixerProductToStoreFacadeInterface;
use FondOfSpryker\Zed\DataFixerProduct\Exception\ProductNotFoundException;
use FondOfSpryker\Zed\DataFixerProduct\Exception\SkuPrefixesNotConfiguredException;
use FondOfSpryker\Zed\DataFixerProduct\Exception\StockNotFoundException;
use FondOfSpryker\Zed\DataFixerProduct\Persistence\DataFixerProductQueryContainerInterface;
use FondOfSpryker\Zed\DataFixerProduct\Persistence\DataFixerProductRepositoryInterface;
use Generated\Shared\Transfer\DataFixerProductCriteriaFilterTransfer;
use Generated\Shared\Transfer\StockProductTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\Availability\Persistence\SpyAvailability;
use Orm\Zed\Availability\Persistence\SpyAvailabilityAbstract;
use Spryker\Shared\Log\LoggerTrait;

class ProductAvailabilityAndReservationQuantityDataFixer implements DataFixerInterface
{
    use LoggerTrait;

    public const NAME = 'ProductAvailabilityAndReservationQuantity';

    /**
     * @var int
     */
    protected $publishCollectionCount = 1;

    /**
     * @var \FondOfSpryker\Zed\DataFixerProduct\Persistence\DataFixerProductQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var \FondOfSpryker\Zed\DataFixerProduct\Persistence\DataFixerProductRepositoryInterface
     */
    protected $repository;

    /**
     * @var \FondOfSpryker\Zed\DataFixerProduct\Dependency\Facade\DataFixerProductToProductFacadeInterface
     */
    protected $productFacade;

    /**
     * @var \FondOfSpryker\Zed\DataFixerProduct\DataFixerProductConfig
     */
    protected $config;

    /**
     * @var \FondOfSpryker\Zed\DataFixerProduct\Dependency\Facade\DataFixerProductToAvailabilityStorageFacadeInterface
     */
    protected $availabilityStorageFacade;

    /**
     * @var \FondOfSpryker\Zed\DataFixerProduct\Dependency\Facade\DataFixerProductToStockFacadeInterface
     */
    protected $stockFacade;

    /**
     * @var \FondOfSpryker\Zed\DataFixerProduct\Dependency\Facade\DataFixerProductToStoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @var \Generated\Shared\Transfer\StoreTransfer[]
     */
    protected $storeCache = [];

    /**
     * ProductAvailabilityAndReservationQuantityDataFixer constructor.
     *
     * @param \FondOfSpryker\Zed\DataFixerProduct\Persistence\DataFixerProductRepositoryInterface $repository
     * @param \FondOfSpryker\Zed\DataFixerProduct\Persistence\DataFixerProductQueryContainerInterface $queryContainer
     * @param \FondOfSpryker\Zed\DataFixerProduct\DataFixerProductConfig $config
     * @param \FondOfSpryker\Zed\DataFixerProduct\Dependency\Facade\DataFixerProductToProductFacadeInterface $productFacade
     * @param \FondOfSpryker\Zed\DataFixerProduct\Dependency\Facade\DataFixerProductToAvailabilityStorageFacadeInterface $availabilityStorageFacade
     * @param \FondOfSpryker\Zed\DataFixerProduct\Dependency\Facade\DataFixerProductToStockFacadeInterface $stockFacade
     * @param \FondOfSpryker\Zed\DataFixerProduct\Dependency\Facade\DataFixerProductToStoreFacadeInterface $storeFacade
     */
    public function __construct(
        DataFixerProductRepositoryInterface $repository,
        DataFixerProductQueryContainerInterface $queryContainer,
        DataFixerProductConfig $config,
        DataFixerProductToProductFacadeInterface $productFacade,
        DataFixerProductToAvailabilityStorageFacadeInterface $availabilityStorageFacade,
        DataFixerProductToStockFacadeInterface $stockFacade,
        DataFixerProductToStoreFacadeInterface $storeFacade
    ) {
        $this->repository = $repository;
        $this->queryContainer = $queryContainer;
        $this->config = $config;
        $this->productFacade = $productFacade;
        $this->stockFacade = $stockFacade;
        $this->storeFacade = $storeFacade;
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

            $this->fixReservationData($criteriaFilter);
            $this->fixAvailabilityData($criteriaFilter);
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
        $criteriaFilter->setSkusCriteriaPattern(DataFixerProductConstants::SKU_PATTERN_LIKE_OR);

        return $criteriaFilter;
    }

    /**
     * @param \Generated\Shared\Transfer\DataFixerProductCriteriaFilterTransfer $criteriaFilter
     *
     * @return void
     */
    protected function fixAvailabilityData(DataFixerProductCriteriaFilterTransfer $criteriaFilter): void
    {
        $publishIds = [];
        foreach ($this->repository->getWrongStoreAvailabilities($criteriaFilter) as $availability) {
            $availabilityAbstract = $availability->getSpyAvailabilityAbstract();
            try {
                $stock = $this->getStock($criteriaFilter->getFkStore(), $availability->getVirtualColumn('id_product'));
            } catch (Exception $exception) {
                $this->logException($exception);
                continue;
            }

            $publishIds[] = $availabilityAbstract->getIdAvailabilityAbstract();
            try {
                $stock = $stock->getQuantity() > 0 ? $stock->getQuantity() : 0;
                $this->updateAvailability($criteriaFilter, $availability, $stock);
                $this->updateAvailabilityAbstract($criteriaFilter, $availabilityAbstract, $stock);
            } catch (Exception $exception) {
                $this->logException($exception);
                continue;
            }

            if (count($publishIds) === $this->publishCollectionCount) {
                $publishIds = $this->publishAvailability($criteriaFilter, $publishIds);
            }
        }
        if (count($publishIds) > 0) {
            $this->publishAvailability($criteriaFilter, $publishIds);
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
            $productReservation->setReservationQuantity(0);
            $productReservation->save();
            $this->getLogger()->info(sprintf(
                '%s reset product reservation %s in store %s to 0',
                $this->getName(),
                $productReservation->getSku(),
                $criteriaFilter->getFkStore()
            ), $productReservation->toArray());
        }
    }

    /**
     * @param int $idStore
     *
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    protected function getStore(int $idStore): StoreTransfer
    {

        if (!array_key_exists($idStore, $this->storeCache)) {
            $this->storeCache[$idStore] = $this->storeFacade->getStoreById($idStore);
        }

        return $this->storeCache[$idStore];
    }

    /**
     * @param int $idStore
     * @param int $idProductConcrete
     *
     * @throws \FondOfSpryker\Zed\DataFixerProduct\Exception\ProductNotFoundException
     * @throws \FondOfSpryker\Zed\DataFixerProduct\Exception\StockNotFoundException
     *
     * @return \Generated\Shared\Transfer\StockProductTransfer
     */
    protected function getStock(
        int $idStore,
        int $idProductConcrete
    ): StockProductTransfer {
        $product = $this->productFacade->findProductConcreteById($idProductConcrete);

        if ($product === null) {
            throw new ProductNotFoundException(sprintf('No product concrete found with id %s in store with id %s', $idProductConcrete, $idStore));
        }

        $store = $this->getStore($idStore);

        $stocks = $this->stockFacade->findStockProductsByIdProductForStore($idProductConcrete, $store);

        foreach ($stocks as $stockProductTransfer) {
            if ($stockProductTransfer->getSku() === $product->getSku()) {
                return $stockProductTransfer;
            }
        }

        throw new StockNotFoundException(sprintf(
            'Stock for product concrete with idProductConcrete %s',
            $idProductConcrete
        ));
    }

    /**
     * @param \Generated\Shared\Transfer\DataFixerProductCriteriaFilterTransfer $criteriaFilter
     * @param \Orm\Zed\Availability\Persistence\SpyAvailability $availability
     * @param int $stock
     *
     * @return void
     */
    protected function updateAvailability(
        DataFixerProductCriteriaFilterTransfer $criteriaFilter,
        SpyAvailability $availability,
        int $stock
    ): void {
        $availability->setQuantity($stock);
        $availability->save();
        $this->getLogger()->info(sprintf(
            '%s resetting availability %s in store %s to stock quantity %s',
            $this->getName(),
            $availability->getSku(),
            $criteriaFilter->getFkStore(),
            $stock
        ), $availability->toArray());
    }

    /**
     * @param \Generated\Shared\Transfer\DataFixerProductCriteriaFilterTransfer $criteriaFilter
     * @param \Orm\Zed\Availability\Persistence\SpyAvailabilityAbstract $availabilityAbstract
     * @param int $stock
     *
     * @return void
     */
    protected function updateAvailabilityAbstract(
        DataFixerProductCriteriaFilterTransfer $criteriaFilter,
        SpyAvailabilityAbstract $availabilityAbstract,
        int $stock
    ): void {
        $availabilityAbstract->setQuantity($stock);
        $availabilityAbstract->save();
        $this->getLogger()->info(sprintf(
            '%s resetting availability abstract %s in store %s to stock quantity %s',
            $this->getName(),
            $availabilityAbstract->getAbstractSku(),
            $criteriaFilter->getFkStore(),
            $stock
        ), $availabilityAbstract->toArray());
    }

    /**
     * @param \Generated\Shared\Transfer\DataFixerProductCriteriaFilterTransfer $criteriaFilter
     * @param array $publishIds
     *
     * @return array
     */
    protected function publishAvailability(
        DataFixerProductCriteriaFilterTransfer $criteriaFilter,
        array $publishIds
    ): array {
        $this->availabilityStorageFacade->publish($publishIds);
        $this->getLogger()->info(sprintf(
            '%s publish availability storage id(s) %s in store %s',
            $this->getName(),
            implode(',', $publishIds),
            $criteriaFilter->getFkStore()
        ), $publishIds);

        $publishIds = [];
        return $publishIds;
    }

    /**
     * @param \Exception $exception
     *
     * @return void
     */
    protected function logException(Exception $exception): void
    {
        $this->getLogger()->error(
            sprintf('%s: %s', $this->getName(), $exception->getMessage()),
            $exception->getTrace()
        );
    }
}
