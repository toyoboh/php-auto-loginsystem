<?php
// setcookie("cookie_token", "", time()-60, "/", null, false, false);
// var_dump($_COOKIE);
// echo "<br>";
// echo "<br>";
// var_dump($_SESSION);

setcookie("cookie_token", time()-60);
session_start();
$_SESSION = array();
session_destroy();

var_dump($_COOKIE);
echo "<br>";
var_dump($_SESSION);
