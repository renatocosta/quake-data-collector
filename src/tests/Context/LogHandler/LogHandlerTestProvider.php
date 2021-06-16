<?php

namespace Tests\Context\LogHandler;

trait LogHandlerTestProvider
{

    protected array $zeroRows = [];

    protected array $uploadFiles = ['valid_file' => 'valid_file.xlsx', 'invalid_and_unexpected_type' => 'invalid_and_unexpected_type.pdf'];

}
