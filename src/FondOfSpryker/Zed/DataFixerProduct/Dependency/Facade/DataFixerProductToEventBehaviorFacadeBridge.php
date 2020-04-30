<?php

namespace FondOfSpryker\Zed\DataFixerProduct\Dependency\Facade;

use Spryker\Zed\EventBehavior\Business\EventBehaviorFacadeInterface;

class DataFixerProductToEventBehaviorFacadeBridge implements DataFixerProductToEventBehaviorFacadeInterface
{
    /**
     * @var \Spryker\Zed\EventBehavior\Business\EventBehaviorFacadeInterface
     */
    protected $eventBehaviorFacade;

    /**
     * DataFixerProductToEventBehaviorFacadeBridge constructor.
     *
     * @param  \Spryker\Zed\EventBehavior\Business\EventBehaviorFacadeInterface  $eventBehaviorFacade
     */
    public function __construct(EventBehaviorFacadeInterface $eventBehaviorFacade)
    {
        $this->eventBehaviorFacade = $eventBehaviorFacade;
    }

    /**
     * @param  array  $resources
     * @param  array  $ids
     */
    public function executeResolvedPluginsBySources(array $resources, array $ids = []): void
    {
        $this->eventBehaviorFacade->executeResolvedPluginsBySources($resources, $ids);
    }
}
