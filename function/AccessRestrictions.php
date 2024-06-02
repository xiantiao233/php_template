<?php
function accessRestrictions(string $key, int $time, int $frequency, int $backSuccess, bool $info = false): bool|null
{
    $Redis = new Rediser();
    $key = "accessRestrictions.$key";
    $RedisKeyValue = $Redis->getValue($key);

    //如果没有值(就是没有访问过) 那么设置初始为1，过期时间为传入值
    if (is_null($RedisKeyValue)) {
        setRedisKeyValue($key,1,$time);

        if ($info) {
            return true;
        }
    }

    //如果RedisKeyValue < int，那么通过 . 设置值为(取出来的 + 1)，过期时间为time
    if ($RedisKeyValue < $frequency) {
        setRedisKeyValue($key,$RedisKeyValue+1,$time);
        return true;
    } else {
        if ($info) {
            return false;
        } else {
            xtBack($backSuccess);
        }
    }
}