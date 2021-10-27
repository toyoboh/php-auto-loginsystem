<?php

include __DIR__ . "/../../vendor/autoload.php";

use SToyokura\Classes\Auth;
use SToyokura\Classes\User;

//postの時に確認
if($_SERVER["REQUEST_METHOD"] !== "POST") {
    //受け取る
    // $user_info = $_POST["user_info"];
    // $password  = $_POST["password"];
    $user_info = "sho";
    $password = "password";

    //認証
    $login_result = Auth::login($user_info, $password);

    //認証成功したらユーザ情報を設定
    if($login_result["auth"]) {
        User::setUser($login_result);
    }

}
