<?php

namespace FondOfSpryker\Zed\DataFixerProduct\Dependency\Facade;

use Spryker\Zed\AvailabilityStorage\Business\AvailabilityStorageFacadeInterface;

class DataFixerProductToAvailabilityStorageFacadeBridge implements DataFixerProductToAvailabilityStorageFacadeInterface
{
    /**
     * @var \Spryker\Zed\AvailabilityStorage\Business\AvailabilityStorageFacadeInterface
     */
    protected $availabilityStorageFacade;

    /**
     * DataFixerProductToAvailabilityStorageFacadeBridge constructor.
     *
     * @param  \Spryker\Zed\AvailabilityStorage\Business\AvailabilityStorageFacadeInterface  $availabilityStorageFacade
     */
    public function __construct(AvailabilityStorageFacadeInterface $availabilityStorageFacade)
    {
        $this->availabilityStorageFacade = $availabilityStorageFacade;
    }

    /**
     * @param  array  $availabilityIds
     *
     * @return void
     */
    public function publish(array $availabilityIds): void
    {
        $this->availabilityStorageFacade->publish($availabilityIds);
    }

    /**
     * @param  array  $availabilityIds
     *
     * @return void
     */
    public function unpublish(array $availabilityIds): void
    {
        $this->availabilityStorageFacade->unpublish($availabilityIds);
    }
}
