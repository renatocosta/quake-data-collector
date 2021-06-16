<?php

namespace Domains\Context\LogHandler\Domain\Model\LogFile;

use Domains\CrossCutting\Domain\Model\Common\Validatable;
use Generator;

interface LogFile extends Validatable
{

    public function extractOf(\SplFileObject $file, LogFileMetadata $metadata): void;

    public function getContent(): Generator;

    public function getMetadata(): LogFileMetadata;
 
}