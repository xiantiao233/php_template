<?php
# 当引入all.php之后执行
header('Content-Type: application/json');

/*
 * 从配置中获取 Access-Control-Allow-Origin 值，添加到头
 */
$origins = getConfig('info.returnHeader.Access-Control-Allow-Origin');
if (is_array($origins)) {
    foreach ($origins as $origin) {
        header("Access-Control-Allow-Origin: $origin", false);
    }
} elseif (is_string($origins)) {
    header("Access-Control-Allow-Origin: $origins");
}

// 是否开启DEBUG
if (getConfig('debug', 'bool')) {
    ini_set('display_errors', 'On');//打开错误提示
    ini_set('error_reporting', E_ALL);//显示所有错误
} else {
    ini_set('display_errors', 'Off');
}