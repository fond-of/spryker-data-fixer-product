<?php

namespace FondOfSpryker\Zed\DataFixerProduct\Dependency\Facade;

interface DataFixerProductToEventBehaviorFacadeInterface
{
    /**
     * @param  array  $resources
     * @param  array  $ids
     */
    public function executeResolvedPluginsBySources(array $resources, array $ids = []): void;
}
