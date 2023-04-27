<?php

namespace D3nysm\Bundle\StatsTablesCleaner;

use D3nysm\Bundle\StatsTablesCleaner\DependencyInjection\StatsTablesCleanerExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class StatsTablesCleanerBundle extends Bundle
{
    /**
     * @return string
     */
    public function getContainerExtensionClass()
    {
        return StatsTablesCleanerExtension::class;
    }
}