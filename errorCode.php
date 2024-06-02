<?php
function errorCode_2c17c6393771ee3048ae34d6b380c5ec(): array
{
    return [
        10000000 => '访问过快 %s次/%s',
        10000010 => '链接Redis服务器失败，请联系网站管理员',
        10000011 => '操作Redis服务器失败，请联系网站管理员',
        10000020 => '链接MySQL服务器失败，请联系网站管理员',
        10000021 => '操作MySQL服务器失败，请联系网站管理员',
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