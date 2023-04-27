<?php

namespace D3nysm\Bundle\StatsTablesCleaner\Event;

use D3nysm\Bundle\StatsTablesCleaner\Annotation\CleanOldData;
use Symfony\Contracts\EventDispatcher\Event;

class CleanOldDataEvent extends Event
{
    /** @var string */
    private $entityClass;
    /** @var CleanOldData */
    private $annotation;

    public function __construct(string $entityClass, CleanOldData $annotation)
    {
        $this->entityClass = $entityClass;
        $this->annotation = $annotation;
    }

    public function getEntityClass(): string
    {
        return $this->entityClass;
    }

    public function getAnnotation(): CleanOldData
    {
        return $this->annotation;
    }
}