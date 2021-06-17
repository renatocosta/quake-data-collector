<?php

namespace Tests\Context\LogHandler\UnitTests;

use DG\BypassFinals;
use Domains\Context\LogHandler\Application\UseCases\Factories\QuakeDataCollector;
use \Domains\Context\LogHandler\Application\UseCases\LogFile\SelectLogFileException;
use Mockery;
use Tests\TestCase;

class LogFileTest extends TestCase
{

    use \Tests\Context\LogHandler\LogHandlerFactoryTestProvider;

    public static function setUpBeforeClass(): void
    {
        BypassFinals::enable();
    }

    public function setup(): void
    {
        parent::setUp();
        $this->loadDependencies();
    }

    public function testShouldFailToLogFileForInvalidName()
    {
        $this->expectException(SelectLogFileException::class);

        $deathCausesCollector = new QuakeDataCollector($this->domainEventBus, 'invalid_filename.log');

        $deathCausesCollectorMock = \Mockery::mock($deathCausesCollector)->shouldAllowMockingProtectedMethods();
        $deathCausesCollectorMock->dispatch();
    }

    public function testShouldFailToLogFileForInvalidContent()
    {
        $this->expectException(SelectLogFileException::class);

        $deathCausesCollector = new QuakeDataCollector($this->domainEventBus, 'blank_file.log');

        $deathCausesCollectorMock = \Mockery::mock($deathCausesCollector)->shouldAllowMockingProtectedMethods();
        $deathCausesCollectorMock->dispatch();
    }
}
