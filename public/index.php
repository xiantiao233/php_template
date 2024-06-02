<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__.'/../function/all.php';

$Client = new Client();

//构建 关于API 的Json
$jsonData = array(
    'test' => sha1(time()."114514"),
    'YourIP' => $Client->getClientIP(),
    'APIServerURL' => getConfig('info.APIServerURL'),
    'APIName'  => getConfig('info.APIName'),
    'APIVersion'  => getConfig('info.APIVersion')
);

echo json_encode($jsonData);