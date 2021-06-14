<?php

namespace Domains\Context\PlayersInformation\Domain\Model\DataSource\DataSourceItem;

use Domains\CrossCutting\Model\ValueObjects\Money\Currency;

final class DataSourceItemNotFound implements DataSourcedItem
{

    public function setId(int $id): void
    {
        //Not implemented
    }

    public function getId(): int
    {
        //Not implemented
    }

    public function getAssetCode(): ?string
    {
        return null;
    }

    public function getPoNumber(): ?string
    {
        return null;
    }

    public function getAmount(): Currency
    {
        return new Currency('0.00');
    }

    public function getDescription(): string
    {
        return '';
    }

    public function getExternalInvoiceId(): string
    {
        return '';
    }

    public function getStatus(): SourceItemStatus
    {
        return $this->status;
    }

    public function wasPreviouslyInvoiced(): bool
    {
        return false;
    }

    public function getInvoiceId(): string
    {
        return '';
    }

    public function setTradeId(string $tradeId): void
    {
        
    }

    public function getTradeId(): string
    {
        return '';
    }  

    public function getRaw(): string
    {
        return '';
    }

}