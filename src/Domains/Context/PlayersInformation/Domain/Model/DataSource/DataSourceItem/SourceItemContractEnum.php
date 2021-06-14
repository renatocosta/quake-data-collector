<?php

namespace Domains\Context\PlayersInformation\Domain\Model\DataSource\DataSourceItem;

class SourceItemContractEnum
{

    public const V1000 = 'V1000';

    public const V2000 = 'V2000';

    public const UNDEFINED = 'undefined';

    public const types = [
        
        self::V1000 => [
            0 =>  ['name' => self::UNDEFINED, 'value' => ''],
            1 =>  ['name' => self::UNDEFINED, 'value' => ''],
            2 =>  ['name' => 'asset_code', 'value' => ''],
            3 =>  ['name' => self::UNDEFINED, 'value' => ''],
            4 =>  ['name' => self::UNDEFINED, 'value' => ''],
            5 =>  ['name' => self::UNDEFINED, 'value' => ''],
            6 =>  ['name' => self::UNDEFINED, 'value' => ''],
            7 =>  ['name' => 'external_invoice_id', 'value' => ''],
            8 =>  ['name' => 'amount', 'value' => ''],
            9 =>  ['name' => 'description', 'value' => ''],
            10 => ['name' => 'po_number', 'value' => ''],
            11 => ['name' => 'raw', 'value' => '{}'],
        ],

        self::V2000 => []

    ];

    public const CONTRACTS = [self::V1000];

}