<?php

namespace Tests\Context\MatchReporting\UnitTests;

use DG\BypassFinals;
use Domains\Context\MatchReporting\Application\UseCases\PlayersKilled\FindPlayersKilledInput;
use Domains\Context\MatchReporting\Application\UseCases\PlayersKilled\FindPlayersKilledUseCase;
use Domains\Context\MatchReporting\Domain\Model\PlayersKilled\Matcher;
use Domains\Context\MatchReporting\Domain\Model\PlayersKilled\PlayersKilledEntity;
use Domains\CrossCutting\Domain\Application\Event\Bus\DomainEventBus;
use Tests\TestCase;

class PlayersKilledTest extends TestCase
{

    public static function setUpBeforeClass(): void
    {
        BypassFinals::enable();
    }

    /**
     * @testWith ["ronaldinho", ""]
     *           ["", "Fagundes"]
     *           ["", ""]
     */
    public function testShouldFailToMatchIfValuesAreMissing(string $whoKilled, string $whoDied)
    {
        $matcher = new Matcher($whoKilled, $whoDied);
        $this->assertFalse($matcher->isValid());
    }

    /**
     * @testWith ["ronaldinho", "ronaldinho"]
     *           ["rivaldo", "RiVAldo"]
     *           ["", ""]
     */
    public function testShouldFailToPlayersAreTheSameName(string $whoKilled, string $whoDied)
    {
        $matcher = new Matcher($whoKilled, $whoDied);
        $this->assertFalse($matcher->isValid());
    }

    public function testShouldFailToCountableRowsIfEntryIsInvalid()
    {
        $playersKilled = \Mockery::spy(new PlayersKilledEntity(new DomainEventBus()));
        $playersKilled->computeKills(new Matcher('', 'ciclano'));
        $playersKilled->computeKills(new Matcher('', ''));
        $playersKilled->computeKills(new Matcher('garotinho', ''));
        $playersKilled->find();

        $playersKilled->shouldNotHaveReceived('isKillerFound');
        $playersKilled->shouldNotHaveReceived('isEligibleToBeAPlayer');

        $this->assertFalse($playersKilled->isValid());
    }

    public function testShouldBeAbleToFindPlayersKilledSuccessfully()
    {
        $playersKilled = \Mockery::spy(new PlayersKilledEntity(new DomainEventBus()));
        $findPlayersKilledUseCase = new FindPlayersKilledUseCase($playersKilled);
        $rows = [['who_killed' => 'fulano', 'who_died' => 'ciclano'], ['who_killed' => 'ciclano', 'who_died' => 'garotinho']];
        $findPlayersKilledUseCase->execute(new FindPlayersKilledInput($rows));
        $playersKilled->shouldHaveReceived('find')->once();
        $this->assertCount(count($rows), $playersKilled->getPlayers());
    }
}
