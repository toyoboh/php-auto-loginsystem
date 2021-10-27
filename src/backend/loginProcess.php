<?php

include __DIR__ . "/../../vendor/autoload.php";

use SToyokura\Classes\Auth;
use SToyokura\Classes\User;

//POST以外の時は終了
if($_SERVER["REQUEST_METHOD"] === "POST") {
    return;
}

//受け取る
// $user_info = $_POST["user_info"];
// $password  = $_POST["password"];
$user_info = "sho";
$password = "password";

//認証
$auth = new Auth();
$login_result = $auth->login($user_info, $password);

//認証成功したらユーザ情報を設定
if($login_result["auth"]) {
    $user = new User($login_result);
}

if($user->isLogind()) {
    echo "ログイン済みです";
}
