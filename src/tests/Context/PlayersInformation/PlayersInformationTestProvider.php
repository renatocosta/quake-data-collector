<?php

namespace Tests\Context\PlayersInformation;

use Domains\CrossCutting\Domain\Model\ValueObjects\Money\Currency;
use Domains\Context\PlayersInformation\Domain\Model\DataSource\DataSourceItem\SourceItemContractEnum;
use Domains\Context\PlayersInformation\Domain\Model\DataSource\DataSourceItem\SourceItemStatus;
use Domains\Context\PlayersInformation\Domain\Model\DataSource\DataSourceItem\SourceItemStatusEnum;

trait PlayersInformationTestProvider
{

    public $validData;

    public $invalidData;

    public $validItemStatus;

    public $invoiceDetails;

    public function loadProviders()
    {
        $this->validData();
        $this->invalidData();
        $this->validItemStatus();
        $this->invoiceDetails();
    }

    public function invalidData()
    {
        $this->invalidData = [
            'contract' => SourceItemContractEnum::V1000, 'remote_file_id' => 10911, 'company_id' =>  1, 'source_type' => 'YARDI_EXPORT_EXCEL',
            'items' => []
        ];
    }


    public function validData()
    {
        $this->validData = [
            'contract' => SourceItemContractEnum::V1000, 'remote_file_id' => 10911, 'company_id' =>  1, 'source_type' => 'YARDI_EXPORT_EXCEL',
            'items' => [
                ['asset_code' => 'nl002015', 'po_number' => 'P-23411', 'amount' => '42.01', 'description' => 'Description 222', 'external_invoice_id' => '89122', 'invoice_id' => '22211', 'invoiced' => true, 'trade_id' => '33221', 'status' => new SourceItemStatus(SourceItemStatusEnum::SUCCESS), 'raw' => '{"0":"xxs"}'],
                ['asset_code' => 'nl002015', 'po_number' => 'P-23411', 'amount' => '42.01', 'description' => 'Description 222', 'external_invoice_id' => '89122', 'invoice_id' => '22211', 'invoiced' => true, 'trade_id' => '33221', 'status' => new SourceItemStatus(SourceItemStatusEnum::SUCCESS), 'raw' => '{"0":"xxs"}']
            ]
        ];
    }

    public function invalidDataSourceHeader()
    {
        return [
            ['remote_file_id' => 0, 'company_id' =>  1, 'source_type' => 'YARDI_EXPORT_EXCEL'],
            ['remote_file_id' => 10911, 'company_id' =>  0, 'source_type' => 'YARDI_EXPORT_EXCEL']
        ];
    }

    public function validItemStatus()
    {
        $this->validItemStatus = ['id' => 1222, 'asset_code' => 'nl002015', 'po_number' => 'P-23411', 'amount' => new Currency('42.01'), 'external_invoice_id' => '89122', 'invoiced' => true, 'invoice_id' => '123331', 'status' => new SourceItemStatus(SourceItemStatusEnum::CREATED), 'raw_data' => '{"0":"xxs"}'];
    }

    public function invoiceDetails()
    {
        $this->invoiceDetails = [
            'is_duplicate_po_number' => false,
            'trade_id' => '22221',
            'recurring_registry_entries' => false,
            'item_was_previously_added' => false,
            'item_previously_added' => ['amount' => new Currency('42.01')],
            'item_was_previously_added_in_repo' => false
        ];
    }
}
