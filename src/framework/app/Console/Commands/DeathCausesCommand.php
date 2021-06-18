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
        $deathCausesCollector = new QuakeDataCollector(new DomainEventBus());
        $deathCausesCollector->attachEventHandler(new LogFileSelectedEventHandler($deathCausesCollector->getCreateHumanLogFileUseCase()));
        $deathCausesCollector->attachEventHandler(new LogFileRejectedEventHandler());
        $deathCausesCollector->attachEventHandler(new HumanLogFileCreatedForDeathCausesEventHandler());
        $deathCausesCollector->attachEventHandler(new HumanLogFileRejectedEventHandler());
        //$deathCausesCollector->attachEventHandler(new PlayersKilledEventHandler());
        //$deathCausesCollector->attachEventHandler(new PlayersKilledFailedEventHandler());
        $deathCausesCollector->dispatch();

        //Sending to Stdout
        /*$players = $deathCausesCollector->getPlayersKilled();
        $output = json_encode([
            'game_1' => [
                'total_kills' => $players->getTotalKills(),
                'players' => array_keys($players->getPlayers()),
                'kills' => $players->getPlayers()
            ]
        ], JSON_PRETTY_PRINT);

        $this->output->writeln($output);*/
    }
}
