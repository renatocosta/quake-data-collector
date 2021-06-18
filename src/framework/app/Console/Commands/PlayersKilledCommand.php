<?php

namespace App\Console\Commands;

use Domains\Context\LogHandler\Application\EventHandlers\HumanLogFile\HumanLogFileCreatedForPlayersKilledEventHandler;
use Domains\Context\LogHandler\Application\EventHandlers\HumanLogFile\HumanLogFileRejectedEventHandler;
use Domains\Context\LogHandler\Application\EventHandlers\LogFile\LogFileRejectedEventHandler;
use Domains\Context\LogHandler\Application\EventHandlers\LogFile\LogFileSelectedEventHandler;
use Domains\Context\LogHandler\Application\UseCases\Factories\QuakeDataCollector;
use Domains\Context\MatchReporting\Application\EventHandlers\PlayersKilled\PlayersKilledEventHandler;
use Domains\Context\MatchReporting\Application\EventHandlers\PlayersKilled\PlayersKilledFailedEventHandler;
use Domains\CrossCutting\Domain\Application\Event\Bus\DomainEventBus;
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
        $playersKilledCollector->attachEventHandler(new LogFileSelectedEventHandler($playersKilledCollector->getCreateHumanLogFileUseCase()));
        $playersKilledCollector->attachEventHandler(new LogFileRejectedEventHandler());
        $playersKilledCollector->attachEventHandler(new HumanLogFileCreatedForPlayersKilledEventHandler($playersKilledCollector->getFindPlayersKilledUseCase()));
        $playersKilledCollector->attachEventHandler(new HumanLogFileRejectedEventHandler());
        $playersKilledCollector->attachEventHandler(new PlayersKilledEventHandler());
        $playersKilledCollector->attachEventHandler(new PlayersKilledFailedEventHandler());
        $playersKilledCollector->dispatch();

        //Sending to Stdout
        $players = $playersKilledCollector->getPlayersKilled();
        $output = json_encode([
            'game_1' => [
                'total_kills' => $players->getTotalKills(),
                'players' => array_keys($players->getPlayers()),
                'kills' => $players->getPlayers()
            ]
        ], JSON_PRETTY_PRINT);

        $this->output->writeln($output);
    }
}
