<?php

namespace Domains\Context\PlayersInformation\Infrastructure\DataAccess;

use Domains\CrossCutting\Infrastructure\Transaction\IUnitOfWork;

final class UnitOfWork implements IUnitOfWork
{

    public function beginTransaction(): void
    {
        \DB::beginTransaction();
    }

    public function commit(): void
    {
        \DB::commit();
    }

    public function rollback(): void
    {
        \DB::rollBack();
    }

}