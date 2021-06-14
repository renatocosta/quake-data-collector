<?php

namespace Domains\CrossCutting\Domain\Application\Event;

abstract class AbstractEvent implements EventInterface
{

    /**
     * @var string
     */
    private $eventName;

    /**
     * @var \DateTimeImmutable
     */
    private $createdAt;

    public function __construct()
    {
        $this->setEventName();
        $this->createdAt = new \DateTimeImmutable();
    }

    private function setEventName(): void
    {
        $path = explode('\\', get_class($this));
        $this->eventName = array_pop($path);
    }

    public function getEventName(): string
    {
        return $this->eventName;
    }

    public function createdAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

}