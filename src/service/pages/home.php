<?php
include __DIR__ . "/../../../vendor/autoload.php";

use SToyokura\Classes\Session;

var_dump($_COOKIE);
echo "<br>";
echo "<br>";
$ses = new Session();
var_dump($ses->getAll());
echo "<br>";
echo "<br>";
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    Home Page
</body>
</html>
