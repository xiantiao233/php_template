<?php

class Status
{
    var int $code = 0;
    var string $message = '';
    var array $cookies = [];

    public function setCode(int $code): void {
        $this->code = $code;
    }

    public function setMessage(string $message): void {
        $this->message = $message;
    }

    public function getMessage(): string {
        return $this->message;
    }

    public function getCookies(): array {
        return $this->cookies;
    }

    public function getCode(): int {
        return $this->code;
    }

    public function setCookie($key, $value): void {
        $this->cookies[$key] = $value;
    }
}