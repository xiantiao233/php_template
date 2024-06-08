<?php
require_once __DIR__ . '/../errorCode.php';
require_once __DIR__ . '/database/Rediser.php';

function accessRestrictions_rules(string $key, string $rules, bool $info = false): bool|null {
    foreach (explode('-', $rules) as $rule) {
        $frequency = explode('/', $rule)[0];
        $time = explode('/', $rule)[1];
        $ban = accessRestrictions("$key-$time",$time,$frequency,$info);
        if (!$ban) return false;
    }
    return true;
}

function accessRestrictions(string $key, int $time, int $frequency, bool $info = false): bool|null {
    $Redis = new Rediser();
    $key = "accessRestrictions.$key";
    $RedisKeyValue = (int) $Redis->getValue($key);

    //如果没有值(就是没有访问过) 那么设置初始为1，过期时间为传入值
    if ($RedisKeyValue === 0) {
        $Redis->setValue($key,1,$time);
        if ($info) return true;
    }

    //如果RedisKeyValue < int，那么通过 . 设置值为(取出来的 + 1)，过期时间为time
    if ($RedisKeyValue < $frequency) {
        $Redis->setValue($key,($RedisKeyValue)+1,$time);
        return true;
    } else {
        if ($info) {
            return false;
        } else {
            $Status = new Status();
            $Status->setCode(10000000);
            $Status->setMessage(sprintf(getErrorMsg(10000000), $frequency, $time.'s'));
            xtSBack($Status);
        }
    }
}