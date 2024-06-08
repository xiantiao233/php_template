<?php
require_once __DIR__.'/../function/all.php';

accessRestrictions("abc",10,2);

$Client = new Client();

//构建 关于API 的Json
$jsonData = array(
    'mysql' => (new MySQLer())->createMySQLConn(),
    'YourIP' => $Client->getClientIP(),
    'APIServerURL' => getConfig('info.APIServerURL'),
    'APIName'  => getConfig('info.APIName'),
    'APIVersion'  => getConfig('info.APIVersion')
);

echo json_encode($jsonData);