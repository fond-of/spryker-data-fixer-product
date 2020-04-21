<?php

namespace FondOfSpryker\Zed\DataFixerProduct\Dependency\Facade;

interface DataFixerProductToAvailabilityStorageFacadeInterface
{
    /**
     * @param  array  $availabilityIds
     *
     * @return void
     */
    public function publish(array $availabilityIds): void;

    /**
     * @param  array  $availabilityIds
     *
     * @return void
     */
    public function unpublish(array $availabilityIds): void;
}
