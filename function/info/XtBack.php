<?php

use JetBrains\PhpStorm\NoReturn;

#[NoReturn] function xtSBack($status = null) : void
{
    header('Content-Type: application/json');
    if ($status == null) {
        die(json_encode([
            'code' => 0
        ]));
    }
    if ($status instanceof Status) {
        die(json_encode([
            'code' => $status->getCode(),
            'message' => $status->getMessage(),
            'cookies' => $status->getCookies()
        ]));
    }
    die(json_encode([
        'code' => $status
    ]));
}