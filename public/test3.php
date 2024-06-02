<?php
require_once __DIR__.'/../function/all.php';

$Status = new Status();
$Status->setData("123","456");

xtSBack($Status->setCode(10000000)->setMessage("你好"));