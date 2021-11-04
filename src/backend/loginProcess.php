<?php

include __DIR__ . "/../../vendor/autoload.php";

use SToyokura\Classes\Authentication;
use SToyokura\Classes\Redirect;

//GETの時に実行
if($_SERVER["REQUEST_METHOD"] !== "POST") {

    //受け取る
    // $user_info = $_POST["user_info"];
    // $password  = $_POST["password"];
    // $_POST["auto"] === "true" ? $is_auto = true : $is_auto = false;
    $user_info = "sho";
    $plaintext_password = "password";
    $is_auto = true;
    
    //認証
    $obj_auth = new Authentication();
    $is_login = $obj_auth->login($user_info, $plaintext_password, $is_auto);

    //遷移
    $obj_redirect = new Redirect();
    $is_login ? $obj_redirect->go("home") : $obj_redirect->go("login");
}
