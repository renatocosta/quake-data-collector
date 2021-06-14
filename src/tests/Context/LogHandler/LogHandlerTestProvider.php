<?php

namespace Tests\Context\LogHandler;

trait LogHandlerTestProvider
{

    protected array $zeroRows = [];

    protected array $zeroRowsWithHeader = [['Account code', 'Account', 'description', 'Asset code', 'Asset name', 'Vendor name', 'Date occurred', 'Post month', 'Invoice ID', 'Amount', 'Notes', 'PO Number']];

    protected array $uploadFiles = ['valid_file' => 'valid_file.xlsx', 'invalid_and_unexpected_type' => 'invalid_and_unexpected_type.pdf'];

}
