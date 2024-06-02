<?php

use JetBrains\PhpStorm\NoReturn;

#[NoReturn] function xtSBack(Status|int|string $status = null,Exception $e = null) : void
{
    if ($e!==null && getConfig('debug')) {
        die($e->getMessage().'<br>'.$e->getTraceAsString());
    }

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
            'dataArray' => $status->getDataArray()
        ]));
    } elseif (is_int($status)) {
        die(json_encode([
            'code' => $status,
            'message' => getErrorMsg($status)
        ]));
    } else {
        http_response_code(500);
        die($status);
    }
}