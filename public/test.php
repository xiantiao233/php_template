<?php // 客户端签名密码算法
// 客户端应该传入sha1之后的值

echo(
passwordHider('3054587546', sha1('123456')) // 计算密码sha1并且调用隐藏器处理并且输出
);

/**
 * 密码
 * @param string $user 用户QQ
 * @param string $password 用户密码
 */
function passwordHider(string $user, string $password): string
{
    $time = time();
    return sprintf('%s-%s', sha1($user.$time.$password), $time);
}

