<?php
class Status
{
    var int $code = 0;
    var string $message = '';
    var array $dataArray = [];

    public function setCode(int $code): Status
    {
        $this->code = $code;
        return $this;
    }

    public function setMessage(string $message): Status
    {
        $this->message = $message;
        return $this;
    }

    public function getMessage(): string {
        return $this->message;
    }

    public function getDataArray(): array {
        return $this->dataArray;
    }

    public function getCode(): int {
        return $this->code;
    }

    public function setData($key, $value): Status
    {
        $this->dataArray[$key] = $value;
        return $this;
    }
}