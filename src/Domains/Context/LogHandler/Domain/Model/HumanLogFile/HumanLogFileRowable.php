<?php

namespace Domains\Context\LogHandler\Domain\Model\HumanLogFile;

use Domains\CrossCutting\Domain\Model\Common\Validatable;

interface HumanLogFileRowable extends Validatable
{

    public function getPlayerWhoKilled(): string;

    public function getPlayerWhoDied(): string;

    public function getMeanOfDeath(): string;

    public function validation(): void;
}
