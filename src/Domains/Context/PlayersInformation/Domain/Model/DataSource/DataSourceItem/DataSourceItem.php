<?php

namespace Domains\Context\PlayersInformation\Domain\Model\DataSource\DataSourceItem;

use Domains\CrossCutting\Model\ValueObjects\Money\Currency;

final class DataSourceItem implements DataSourcedItem
{

    public int $id;

    private $assetCode;

    private $poNumber;

    private Currency $amount;

    private string $description;

    private string $externalInvoiceId;

    private string $raw;

    private string $invoiceId;

    private bool $invoiced;

    private string $tradeId;

    private SourceItemStatus $status;

    public function __construct(
        $assetCode,
        $poNumber,
        Currency $amount,
        string $description,
        string $externalInvoiceId,
        string $raw,
        string $invoiceId,
        bool $invoiced,
        string $tradeId,
        SourceItemStatus $status
    ) {
        $this->assetCode = $assetCode;
        $this->poNumber = $poNumber;
        $this->amount = $amount;
        $this->description = $description;
        $this->externalInvoiceId = $externalInvoiceId;
        $this->raw = $raw;
        $this->invoiceId = $invoiceId;
        $this->invoiced = $invoiced;
        $this->tradeId = $tradeId;
        $this->status = $status;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getAssetCode(): ?string
    {
        return $this->assetCode;
    }

    public function getPoNumber(): ?string
    {
        return $this->poNumber;
    }

    public function getAmount(): Currency
    {
        return $this->amount;
    }

    public function getDescription(): string
    {
        return $this->description;
    }    
    
    public function getExternalInvoiceId(): string
    {
        return $this->externalInvoiceId;
    }

    public function getStatus(): SourceItemStatus
    {
        return $this->status;
    }

    public function wasPreviouslyInvoiced(): bool
    {
        return $this->invoiced;
    }

    public function setTradeId(string $tradeId): void
    {
        $this->tradeId = $tradeId;
    }

    public function getTradeId(): string
    {
        return $this->tradeId;
    }  

    public function getInvoiceId(): string
    {
        return $this->invoiceId;
    }

    public function getRaw(): string
    {
        return $this->raw;
    }
}
