<?php

namespace Tests\Context\PlayersInformation\UnitTests;

use Domains\CrossCutting\Domain\Model\ValueObjects\Money\Currency;
use DG\BypassFinals;
use Exception;
use Domains\Context\PlayersInformation\Domain\Model\DataSource\DataSourceItem\SourceItemStatusEnum;
use Tests\TestCase;

class StatusCriteriaTest extends TestCase
{

    use \Tests\Context\PlayersInformation\PlayersInformationFactoryTestProvider;
    use \Tests\Context\PlayersInformation\PlayersInformationTestProvider;

    public static function setUpBeforeClass(): void
    {
        BypassFinals::enable();
    }

    public function setup(): void
    {
        parent::setUp();
        $this->loadPlayersInformationDependencies();
        $this->loadProviders();
    }

    public function testWhenMandatoryAssetCodeIsMissingExpectWorkflowToFail()
    {
        $dataMapper['item'] = array_merge($this->validItemStatus, ['asset_code' => '']);
        $dataMapper = array_merge($dataMapper, $this->invoiceDetails);
        $status = $this->statusItemFlow->matches($dataMapper);

        $this->assertTrue($status == SourceItemStatusEnum::ASSET_CODE_MISSING);
    }

    public function testWhenMandatoryPoNumberIsMissingExpectWorkflowToFail()
    {
        $dataMapper['item'] = array_merge($this->validItemStatus, ['po_number' => '']);
        $dataMapper = array_merge($dataMapper, $this->invoiceDetails);
        $status = $this->statusItemFlow->matches($dataMapper);
        $this->assertTrue($status == SourceItemStatusEnum::PO_MISSING);
    }

    public function testWhenMandatoryExternalInvoiceIdIsMissingExpectWorkflowToFail()
    {
        $dataMapper['item'] = array_merge($this->validItemStatus, ['external_invoice_id' => '']);
        $dataMapper = array_merge($dataMapper, $this->invoiceDetails);
        $status = $this->statusItemFlow->matches($dataMapper);
        $this->assertTrue($status == SourceItemStatusEnum::EXTERNAL_INVOICE_ID_MISSING);
    }

    public function testWhenAssetCodeNotFoundInProprliExpectWorkflowToFail()
    {
        $this->createAssetModelNotFound();
        $this->createStatusCriteria();
        $dataMapper = array_merge(['item' => $this->validItemStatus], $this->invoiceDetails);
        $status = $this->statusItemFlow->matches($dataMapper);
        $this->assertTrue($status == SourceItemStatusEnum::ASSET_CODE_NOT_FOUND);
    }

    public function testWhenDuplicatePoNumberInProprliExpectWorkflowToFail()
    {
        $this->invoiceDetails['is_duplicate_po_number'] = true;
        $dataMapper = array_merge(['item' => $this->validItemStatus], $this->invoiceDetails);
        $status = $this->statusItemFlow->matches($dataMapper);
        $this->assertTrue($status == SourceItemStatusEnum::DUPLICATE_PO_IN_PROPRLI);
    }

    public function testWhenPoNumberNotFoundInProprliExpectWorkflowToFail()
    {
        $this->invoiceDetails['trade_id'] = '';
        $dataMapper = array_merge(['item' => $this->validItemStatus], $this->invoiceDetails);
        $status = $this->statusItemFlow->matches($dataMapper);
        $this->assertTrue($status == SourceItemStatusEnum::PO_NUMBER_NOT_FOUND);
    }

    public function testWhenRecurringRegistryEntriesInFileExpectWorkflowToFail()
    {
        $this->invoiceDetails['item_previously_added'] = ['amount' => new Currency('1642.29')];
        $this->invoiceDetails['recurring_registry_entries'] = true;
        $dataMapper = array_merge(['item' => $this->validItemStatus], $this->invoiceDetails);
        $status = $this->statusItemFlow->matches($dataMapper);
        $this->assertTrue($status == SourceItemStatusEnum::RECURRING_REGISTRY_ENTRIES);
    }

    public function testWhenInvoiceValueChangedInProprliExpectWorkflowToFail()
    {
        $this->invoiceDetails['item_was_previously_added'] = true;
        $this->invoiceDetails['item_was_previously_added_in_repo'] = false;
        $this->invoiceDetails['item_previously_added'] = ['amount' => new Currency('1642.29')];
        $dataMapper = array_merge(['item' => $this->validItemStatus], $this->invoiceDetails);
        $status = $this->statusItemFlow->matches($dataMapper);
        $this->assertTrue($status == SourceItemStatusEnum::INVOICE_VALUE_CHANGED);
    }

    public function testWhenWasPreviouslyInvoicedInProprliExpectWorkflowSuccessfully()
    {
        $this->invoiceDetails['item_was_previously_added'] = true;
        $this->invoiceDetails['item_was_previously_added_in_repo'] = true;
        $dataMapper = array_merge(['item' => $this->validItemStatus], $this->invoiceDetails);
        $status = $this->statusItemFlow->matches($dataMapper);
        $this->assertTrue($status == SourceItemStatusEnum::SUCCESSFULLY_IMPORTED_PREVIOUSLY);
    }
}
