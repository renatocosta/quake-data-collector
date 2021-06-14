<?php

namespace Domains\CrossCutting\Domain\Application\Services\Common;

class MessageHandler
{

    public array $messages = [];

    public array $errors = [];

    public function isValid(): bool
    {
        return count($this->errors) === 0;
    }

    public function isInvalid(): bool
    {
        return count($this->errors) > 0;
    }

    public function addEntity($entity)
    {
        $this->messages = [$entity];
    }
    public function addList(array $list)
    {
        $this->messages = array_merge($this->messages, $list);
    }

    public function addListError(array $list)
    {
        foreach ($list as $key => $value) {
            if (is_array($value)) {
                $value = $value['key'];
            }
            $this->addKeyError($value);
        }
    }

    public function addKeyError(string $key)
    {
        $this->errors[] = ['key' => $key];
    }
}
