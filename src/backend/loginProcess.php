<?php

include __DIR__ . "/../../vendor/autoload.php";

use SToyokura\Classes\DbConnect;

$test = new DbConnect();
var_dump($test->pdo);
