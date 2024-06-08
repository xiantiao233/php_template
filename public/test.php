<?php
require_once __DIR__ . '/../function/all.php';
header('Content-Type: text/html');

accessRestrictions_rules('test','1/60-3/600-8/3600');
accessRestrictions('test',60,1);

