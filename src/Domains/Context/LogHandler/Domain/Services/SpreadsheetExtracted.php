<?php

namespace Domains\Context\LogHandler\Domain\Services;

interface SpreadsheetExtracted
{

    public function setup($file): void;

    public function find(): void;

    public function rows(): array;

}