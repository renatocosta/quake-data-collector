<?php

namespace App\Console\Commands;

use Domains\Context\LogHandler\Application\EventHandlers\HumanLogFile\HumanLogFileCreatedForPlayersKilledEventHandler;
use Domains\Context\LogHandler\Application\EventHandlers\HumanLogFile\HumanLogFileRejectedEventHandler;
use Domains\Context\LogHandler\Application\UseCases\Factories\QuakeDataCollector;
use Domains\CrossCutting\Domain\Application\Event\Bus\DomainEventBus;
use Domains\CrossCutting\Domain\Application\Services\Common\MessageHandler;
use Illuminate\Console\Command;

class PlayersKilledCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'playersKilled';

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
        $playersKilledCollector->attachEventHandlerToHumanLogFile(new HumanLogFileCreatedForPlayersKilledEventHandler(new MessageHandler()));
        $playersKilledCollector->attachEventHandlerToHumanLogFile(new HumanLogFileRejectedEventHandler(new MessageHandler()));
        $playersKilledCollector->dispatch();
    }
}
