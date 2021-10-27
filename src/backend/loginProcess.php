<?php

include __DIR__ . "/../../vendor/autoload.php";

use SToyokura\Classes\Auth;

//postの時に確認
if($_SERVER["REQUEST_METHOD"] !== "POST") {
    //受け取る
    // $user_info = $_POST["user_info"];
    // $password  = $_POST["password"];
    $user_info = "sho";
    $password = "password";

    $login_result = Auth::login($user_info, $password);

}
