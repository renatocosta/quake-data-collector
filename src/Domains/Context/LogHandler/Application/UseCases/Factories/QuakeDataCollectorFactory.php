<?php

namespace Domains\Context\LogHandler\Application\UseCases\Factories;

use Domains\CrossCutting\Domain\Application\Event\Bus\DomainEventBus;
use Domains\CrossCutting\Domain\Application\Event\DomainEventHandler;
use Domains\Context\LogHandler\Domain\Model\LogFile\LogFile;
use Domains\Context\LogHandler\Domain\Model\LogFile\LogFileEntity;
use Domains\Context\LogHandler\Application\Services\HumanRowMapper;
use Domains\Context\LogHandler\Application\UseCases\HumanLogFile\CreateHumanLogFileUseCase;
use Domains\Context\LogHandler\Application\UseCases\HumanLogFile\ICreateHumanLogFileUseCase;
use Domains\Context\LogHandler\Application\UseCases\LogFile\ISelectLogFileUseCase;
use Domains\Context\LogHandler\Application\UseCases\LogFile\SelectLogFileUseCase;
use Domains\Context\LogHandler\Domain\Model\HumanLogFile\HumanLogFile;
use Domains\Context\LogHandler\Domain\Model\HumanLogFile\HumanLogFileEntity;
use Domains\Context\LogHandler\Domain\Model\LogFile\LogFileInfo;
use Domains\Context\MatchReporting\Application\UseCases\DeathCauses\FindDeathCausesUseCase;
use Domains\Context\MatchReporting\Application\UseCases\DeathCauses\IFindDeathCausesUseCase;
use Domains\Context\MatchReporting\Application\UseCases\PlayersKilled\FindPlayersKilledUseCase;
use Domains\Context\MatchReporting\Application\UseCases\PlayersKilled\IFindPlayersKilledUseCase;
use Domains\Context\MatchReporting\Domain\Model\DeathCauses\DeathCauses;
use Domains\Context\MatchReporting\Domain\Model\DeathCauses\DeathCausesEntity;
use Domains\Context\MatchReporting\Domain\Model\PlayersKilled\PlayersKilled;
use Domains\Context\MatchReporting\Domain\Model\PlayersKilled\PlayersKilledEntity;

abstract class QuakeDataCollectorFactory
{

    protected DomainEventBus $domainEventBus;

    protected LogFile $logFile;

    protected ISelectLogFileUseCase $selectLogFileUseCase;

    protected HumanLogFile $humanLogFile;

    protected ICreateHumanLogFileUseCase $createHumanLogFileUseCase;

    protected PlayersKilled $playersKilled;

    protected IFindPlayersKilledUseCase $findPlayersKilledUseCase;

    protected DeathCauses $deathCauses;

    protected IFindDeathCausesUseCase $findDeathCausesUseCase;

    protected string $fileName;

    public function __construct(DomainEventBus $domainEventBus, string $fileName = LogFileInfo::DEFAULT_FILE_NAME)
    {
        $this->domainEventBus = $domainEventBus;
        $this->fileName = $fileName;
        $this->build();
    }

    public function attachEventHandler(DomainEventHandler $eventHandlerAttachment): void
    {
        $this->domainEventBus->subscribe($eventHandlerAttachment);
    }

    //Set up to Human Log File Use Case
    protected function addHumanLogFile(): void
    {
        $this->humanLogFile = new HumanLogFileEntity($this->domainEventBus);
    }

    public function getHumanLogFile(): HumanLogFile
    {
        return $this->humanLogFile;
    }

    protected function addHumanLogFileUseCase(): void
    {
        $this->createHumanLogFileUseCase = new CreateHumanLogFileUseCase($this->humanLogFile, new HumanRowMapper());
    }

    public function getCreateHumanLogFileUseCase(): ICreateHumanLogFileUseCase
    {
        return $this->createHumanLogFileUseCase;
    }

    //Set up Log File Use Case
    protected function addLogFile(): void
    {
        $this->logFile = new LogFileEntity($this->domainEventBus);
    }

    protected function addLogFileUseCase(): void
    {
        $this->selectLogFileUseCase = new SelectLogFileUseCase($this->logFile);
    }

    //Set up to Players Killed Use Case
    protected function addPlayersKilled(): void
    {
        $this->playersKilled = new PlayersKilledEntity($this->domainEventBus);
    }

    public function getPlayersKilled(): PlayersKilled
    {
        return $this->playersKilled;
    }

    protected function addPlayersKilledUseCase(): void
    {
        $this->findPlayersKilledUseCase = new FindPlayersKilledUseCase($this->playersKilled);
    }

    public function getFindPlayersKilledUseCase(): IFindPlayersKilledUseCase
    {
        return $this->findPlayersKilledUseCase;
    }

    //Set up to Death Causes Use Cases
    protected function addDeathCauses(): void
    {
        $this->deathCauses = new DeathCausesEntity($this->domainEventBus);
    }

    public function getDeathCauses(): DeathCauses
    {
        return $this->deathCauses;
    }

    protected function addDeathCausesUseCase(): void
    {
        $this->findDeathCausesUseCase = new FindDeathCausesUseCase($this->deathCauses);
    }

    public function getFindDeathCausesUseCase(): IFindDeathCausesUseCase
    {
        return $this->findDeathCausesUseCase;
    }

    protected abstract function build(): void;

    public abstract function dispatch(): void;
}
