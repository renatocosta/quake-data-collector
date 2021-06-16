<?php

namespace Domains\Context\LogHandler\Domain\Model\HumanLogFile;

use Domains\CrossCutting\Domain\Model\Common\Validatable;
use Generator;

interface HumanLogFile extends Validatable
{

    public function create(Generator $content): void;
 
}