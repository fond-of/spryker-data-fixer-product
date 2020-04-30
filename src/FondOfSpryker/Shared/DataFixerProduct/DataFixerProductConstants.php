<?php

namespace FondOfSpryker\Shared\DataFixerProduct;

interface DataFixerProductConstants
{
    public const SKU_PATTERN_NOT_LIKE_AND = 'sku NOT LIKE "%s" AND ';
    public const SKU_PATTERN_LIKE_AND = 'sku LIKE "%s" AND ';
    public const SKU_PATTERN_LIKE_OR = 'sku LIKE "%s" OR ';

    public const DATA_FIXER_PRODUCT_AVAILABILITY_DATA_SKU_PREFIX = 'DATA_FIXER_PRODUCT_AVAILABILITY_DATA_SKU_PREFIX';
}
