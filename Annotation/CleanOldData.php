<?php

namespace D3nysm\Bundle\StatsTablesCleaner\Annotation;

use Doctrine\Common\Annotations\Annotation\NamedArgumentConstructor;

/**
 * @Annotation
 * @NamedArgumentConstructor
 * @Target("CLASS")
 */
class CleanOldData
{
    /** @var string */
    private $interval;
    /** @var string */
    private $dateProp;
    /** @var int */
    private $batchSize;
    /** @var string|null */
    private $eventName;

    /**
     * @throws \Exception
     */
    public function __construct(string $interval, string $dateProp = 'date', int $batchSize = 20000, ?string $eventName = null)
    {
        $this->interval = $interval;
        $this->dateProp = $dateProp;
        $this->batchSize = $batchSize;
        $this->eventName = $eventName;
    }

    /**
     * @return string
     */
    public function getDateProp(): string
    {
        return $this->dateProp;
    }

    /**
     * @return string
     */
    public function getInterval(): string
    {
        return $this->interval;
    }

    /**
     * @return int
     */
    public function getBatchSize(): int
    {
        return $this->batchSize;
    }

    /**
     * @return \DateTime
     * @throws \Exception
     */
    public function getDate(): \DateTime
    {
        return new \DateTime($this->interval);
    }

    /**
     * @return string|null
     */
    public function getEventName(): ?string
    {
        return $this->eventName;
    }
}