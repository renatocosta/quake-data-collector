<?php

namespace App\Console\Commands;

use Domains\Context\LogHandler\Application\EventHandlers\HumanLogFile\HumanLogFileCreatedForDeathCausesEventHandler;
use Domains\Context\LogHandler\Application\EventHandlers\HumanLogFile\HumanLogFileRejectedEventHandler;
use Domains\Context\LogHandler\Application\EventHandlers\LogFile\LogFileRejectedEventHandler;
use Domains\Context\LogHandler\Application\EventHandlers\LogFile\LogFileSelectedEventHandler;
use Domains\Context\LogHandler\Application\UseCases\Factories\QuakeDataCollector;
use Domains\CrossCutting\Domain\Application\Event\Bus\DomainEventBus;
use Domains\CrossCutting\Domain\Application\Services\Common\MessageHandler;
use Illuminate\Console\Command;

class DeathCausesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'deathCauses';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';



    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $playersKilledCollector = new QuakeDataCollector(new DomainEventBus());
        $playersKilledCollector->attachEventHandler(new LogFileSelectedEventHandler($playersKilledCollector->getCreateHumanLogFileUseCase(), new MessageHandler()));
        $playersKilledCollector->attachEventHandler(new LogFileRejectedEventHandler());
        $playersKilledCollector->attachEventHandler(new HumanLogFileCreatedForDeathCausesEventHandler());
        $playersKilledCollector->attachEventHandler(new HumanLogFileRejectedEventHandler());
        $playersKilledCollector->dispatch();
    }
}
