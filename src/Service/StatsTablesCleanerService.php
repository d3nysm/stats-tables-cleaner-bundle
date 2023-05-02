<?php

namespace D3nysm\Bundle\StatsTablesCleaner\Service;


use D3nysm\Bundle\StatsTablesCleaner\Annotation\CleanOldData;
use D3nysm\Bundle\StatsTablesCleaner\Event\CleanOldDataEvent;
use Doctrine\Common\Annotations\Reader;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\ParameterType;
use Doctrine\ORM\EntityManagerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;

class StatsTablesCleanerService
{
    /** @var EntityManagerInterface */
    private $entityManager;
    /** @var Reader */
    private $annotationReader;
    /** @var EventDispatcherInterface */
    private $eventDispatcher;

    public function __construct(
        EntityManagerInterface $entityManager,
        Reader $annotationReader,
        EventDispatcherInterface $eventDispatcher
    )
    {
        $this->entityManager = $entityManager;
        $this->annotationReader = $annotationReader;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @return CleanOldData[]
     */
    public function findEntityAnnotations(): array
    {
        $entities = [];
        foreach ($this->entityManager->getMetadataFactory()->getAllMetadata() as $metadata) {
            $annotation = $this->annotationReader->getClassAnnotation($metadata->getReflectionClass(), CleanOldData::class);

            if ($annotation) {
                $entities[$metadata->getName()] = $annotation;
            }
        }

        return $entities;
    }

    /**
     * @throws Exception|\Doctrine\DBAL\Driver\Exception
     */
    public function deleteOldEntriesByAnnotation(string $entityClass, CleanOldData $annotation): int
    {
        if ($annotation->getEventName()) {
            $this->eventDispatcher->dispatch(
                new CleanOldDataEvent($entityClass, $annotation), $annotation->getEventName()
            );
        }

        $dql = $this->entityManager->createQuery("delete from $entityClass e where e.{$annotation->getDateProp()} < :date");
        $sql = $dql->getSQL().' limit ?';
        return $this->deleteOldEntriesByDate($sql, $annotation->getDate(), $annotation->getBatchSize());
    }

    /**
     * @throws Exception|\Doctrine\DBAL\Driver\Exception
     */
    protected function deleteOldEntriesByDate(string $sql, \DateTime $dateTo, int $batchSize): int
    {
        return $this->deleteEntries($sql, [
            1 => $dateTo->format('c'), 2 => $batchSize
        ], [
            1=> ParameterType::STRING, 2 => ParameterType::INTEGER
        ]);
    }

    /**
     * @throws \Doctrine\DBAL\Driver\Exception|Exception
     */
    protected function deleteEntries(string $sql, array $values = [], array $types = []): int
    {
        $total = 0;
        $stmt = $this->entityManager->getConnection()->prepare($sql);
        foreach ($values as $param => $value) {
            $type = $types[$param] ?? null;
            $stmt->bindValue($param, $value, $type);
        }

        do {
            $result = $stmt->executeQuery();
            $total += $result->rowCount();
        } while ($result->rowCount() > 0);

        return $total;
    }
}