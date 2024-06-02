<?php // 服务端验证登入消息
$user = (int) $_GET['user']; // 获取用户QQ
$password = explode('-', $_GET['password']); // 获取签名的密码，0为签名，1为时间戳

// 防止签名时间与现在时间相差10s
if (abs(time() - $password[1]) > 10) {
    die('password is obsolete');
}

$database_password = getUserPassword($user); // 来自数据库的用户密码
if ($database_password === null) {die('invalid user');} // 判空

$countedSign = sha1($user.$password[1].$database_password); // 模拟客户端计算-(用户QQ,来自数据库的用户密码,客户端传递的签名时间戳)
if ($countedSign === $password[0]){ //如果签名值与客户端传送的一样
    echo('ok');
} else {
    echo('fail');
}

/**
 * 模拟数据库
 * @param int $user 用户QQ
 * @return string|null
 */
function getUserPassword(int $user): ?string
{
    $users = [
        'users' => ['3054587546' => [
                // 真实密码(123456)的sha1
                'password' => "7c4a8d09ca3762af61e59520943dc26494f8941b"
        ]]];
    if (isset($users['users']["$user"])) {
        return $users['users']["$user"]['password'];
    } else return null;
}


