<?php

namespace App\Console\Commands;

use Domains\Context\LogHandler\Application\EventHandlers\HumanLogFile\HumanLogFileCreatedForDeathCausesEventHandler;
use Domains\Context\LogHandler\Application\EventHandlers\HumanLogFile\HumanLogFileRejectedEventHandler;
use Domains\Context\LogHandler\Application\EventHandlers\LogFile\LogFileRejectedEventHandler;
use Domains\Context\LogHandler\Application\EventHandlers\LogFile\LogFileSelectedEventHandler;
use Domains\Context\LogHandler\Application\UseCases\Factories\QuakeDataCollector;
use Domains\Context\MatchReporting\Application\EventHandlers\DeathCauses\DeathCausesEventHandler;
use Domains\Context\MatchReporting\Application\EventHandlers\DeathCauses\DeathCausesFailedEventHandler;
use Domains\Context\MatchReporting\Domain\Model\DeathCauses\DeathCausesFailed;
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
        $deathCausesCollector->attachEventHandler(new HumanLogFileCreatedForDeathCausesEventHandler($deathCausesCollector->getFindDeathCausesUseCase()));
        $deathCausesCollector->attachEventHandler(new HumanLogFileRejectedEventHandler());
        $deathCausesCollector->attachEventHandler(new DeathCausesEventHandler());
        $deathCausesCollector->attachEventHandler(new DeathCausesFailedEventHandler());
        $deathCausesCollector->dispatch();

        //Printing
        $deathCauses = $deathCausesCollector->getDeathCauses();

        if ($deathCauses->isValid()) {
            $output = json_encode([
                'game_1' => [
                    'kills_by_means' => $deathCauses->getCauses()
                ]
            ], JSON_PRETTY_PRINT);
            $this->output->writeln($output);
        } else {
            $this->output->writeln('Sorry, something went wrong:');
            $this->output->writeln(json_encode($deathCauses->getErrors()));
        }
    }
}
