<?php

/**
 * 验证码管理器
 * @param string $key 唯一键，区分验证码
 * @param string $value 验证码值，为'-'时为获取验证码
 * @param int $time 验证码过期时间
 * @return string|void|null
 */
function verificationCodeManage(string $key, string $value, int $time)
{
    $Redis = new Rediser();
    $key = "verificationCodeManage.$key";

    if ($value === '-') {
        return $Redis->getValue($key);
    }

    $Redis->setValue($key,$value,$time);
}