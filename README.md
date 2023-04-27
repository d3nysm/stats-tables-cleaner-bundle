# The StatsTablesCleanerBundle

This bundle helps with cleaning tables with statistics, logs, etc. 
The necessary thing is an entity must have date or datetime column.

## Installation

```console
$ composer require d3nysm/stats-tables-cleaner-bundle
```

## Quick Start

1. Add the notation to your entity:
    ```php
    use App\Repository\StatsEntryRepository;
    use Doctrine\ORM\Mapping as ORM;
    use D3nysm\Bundle\StatsTablesCleaner\Annotation\CleanOldData;

    /**
     * @ORM\Entity(repositoryClass=StatsEntryRepository::class)
     * @ORM\Table(indexes={
     *     @ORM\Index(name="stat_date", columns={"date"})})
     * @CleanOldData(interval="-3 month")
    */
    class StatsEntry
     ```
   
2. Run the command and add to you scheduler system:
    ```console
    $ php bin/console stats-tables-cleaner:clean
    ```
