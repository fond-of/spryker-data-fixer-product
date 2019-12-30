<?php

namespace FondOfSpryker\Zed\DataFixerProduct;

use FondOfSpryker\Shared\DataFixerProduct\DataFixerProductConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class DataFixerProductConfig extends AbstractBundleConfig
{
    /**
     * @return array
     */
    public function getAvailabilityData(): array
    {
        return $this->get(DataFixerProductConstants::DATA_FIXER_PRODUCT_AVAILABILITY_DATA_SKU_PREFIX, []);
    }
}
