<?php

namespace Domains\Context\PlayersInformation\Domain\Services;

use Domains\Context\PlayersInformation\Domain\Model\DataSource\DataSourceItem\SourceItemStatus;

abstract class StatusItemCriteria
{

    protected self $next;

    /**
     * @param self $next
     */
    public function linkNext(self $next): self
    {
        $this->next = $next;
 
        return $next;
    }

    /**
     * @param array $data
     */
    protected function next(array $data)
    {
        if ($this->next) {
            return $this->next->matches($data);
        }
    }

    abstract public function matches(array $data);

    /**
     * @return SourceItemStatus
     */
    public function status(string $status): SourceItemStatus
    {
        return new SourceItemStatus($status);
    }

}