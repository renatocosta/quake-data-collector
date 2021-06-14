<?php

namespace Domains\Context\PlayersInformation\Domain\Model\DataSource\DataSourceItem;

use Domains\CrossCutting\Model\ValueObjects\Money\Currency;

interface DataSourcedItem
{

    /**
     * @param int $id
     */
    public function setId(int $id): void;

    /**
     * @return int
     */
    public function getId(): int;

    /**
     * @return string|null
     */
    public function getAssetCode(): ?string;

    /**
     * @return string|null
     */
    public function getPoNumber(): ?string;

    /**
     * @return Currency
     */
    public function getAmount(): Currency;

    public function getDescription(): string;

    /**
     * @return string
     */
    public function getExternalInvoiceId(): string;

    /**
     * @return SourceItemStatus
     */
    public function getStatus(): SourceItemStatus;

    public function wasPreviouslyInvoiced(): bool;

    public function getInvoiceId(): string;

    public function setTradeId(string $tradeId): void;

    public function getTradeId(): string;    

    /**
     * @return string
     */
    public function getRaw(): string;

}