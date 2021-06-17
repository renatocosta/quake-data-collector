<?php

namespace App\Console\Commands;

use Domains\Context\LogHandler\Application\EventHandlers\HumanLogFile\HumanLogFileCreatedForDeathCausesEventHandler;
use Domains\Context\LogHandler\Application\EventHandlers\HumanLogFile\HumanLogFileRejectedEventHandler;
use Domains\Context\LogHandler\Application\UseCases\Factories\QuakeDataCollector;
use Domains\Context\LogHandler\Domain\Model\HumanLogFile\HumanLogFileRejected;
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
        $deathCausesCollector = new QuakeDataCollector(new DomainEventBus());
        $deathCausesCollector->attachEventHandlerToHumanLogFile(new HumanLogFileCreatedForDeathCausesEventHandler(new MessageHandler()));
        $deathCausesCollector->attachEventHandlerToHumanLogFile(new HumanLogFileRejectedEventHandler(new MessageHandler()));
        $deathCausesCollector->dispatch();
    }
}
