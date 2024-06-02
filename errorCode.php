<?php
function errorCode_2c17c6393771ee3048ae34d6b380c5ec(): array
{
    return [
        10000000 => '人机验证失败'
    ];
}

function getErrorMsg(string $key): string
{
    $keys = explode('.', $key);
    $errorMsg = errorCode_2c17c6393771ee3048ae34d6b380c5ec();

    foreach ($keys as $key) {
        if (isset($errorMsg[$key])) {
            $errorMsg = $errorMsg[$key];
        } else {
            http_response_code(500);
            exit("Configuration key '$key' not found.");
        }
    }
    return $errorMsg;
}