# DataFixerProduct Module
[![Build Status](https://travis-ci.org/fond-of/spryker-data-fixer-product.svg?branch=master)](https://travis-ci.org/fond-of/spryker-data-fixer-product)
[![PHP from Travis config](https://img.shields.io/travis/php-v/symfony/symfony.svg)](https://php.net/)
[![license](https://img.shields.io/github/license/mashape/apistatus.svg)](https://packagist.org/packages/fond-of-spryker/data-fixer-product)

## Installation

```
composer require fond-of-spryker/data-fixer-product
```

Register ProductAvailabilityAndReservationDataFixerPlugin in DataFixerDependencyProvider

Register ProductAvailabilityAndReservationQuantityDataFixerPlugin in DataFixerDependencyProvider

```
/**
 * @param \Spryker\Zed\Kernel\Container $container
 *
 * @return array
 */
public function getDataFixer(Container $container): array
{
    return [
        new ProductAvailabilityAndReservationDataFixerPlugin(),
        new ProductAvailabilityAndReservationQuantityDataFixerPlugin(),
    ];
}
```

## Config
IDSTORE => ['SKUPREFIX']
```
// ---------- DataFixerProduct
$config[DataFixerProductConstants::DATA_FIXER_PRODUCT_AVAILABILITY_DATA_SKU_PREFIX] = [
    1 => ['AFZ%', 'HAP%'],
    4 => ['PP%', 'W-%'],
    5 => ['ERG-%'],
    6 => ['SAT-%'],
];
```

## Usage
Remove all wrong availability and product reservation for current store.
```
vendor/bin/console data-fixer:fix -f ProductAvailabilityAndReservationWrongStoreRelationRemover
APPLICATION_STORE=STORENAME vendor/bin/console data-fixer:fix -f ProductAvailabilityAndReservationWrongStoreRelationRemover
```

Reset all product reservations to current stock of the product for current store
```
vendor/bin/console data-fixer:fix -f ProductAvailabilityAndReservationQuantity
APPLICATION_STORE=STORENAME vendor/bin/console data-fixer:fix -f ProductAvailabilityAndReservationQuantity
```
