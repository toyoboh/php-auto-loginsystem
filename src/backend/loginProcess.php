<?php

include __DIR__ . "/../../vendor/autoload.php";

use SToyokura\Classes\Authentication;

//GETの時に実行
if($_SERVER["REQUEST_METHOD"] !== "POST") {

    //受け取る
    // $user_info = $_POST["user_info"];
    // $password  = $_POST["password"];
    $user_info = "sho";
    $password = "password";
    
    //認証
    $obj_auth = new Authentication();
    $obj_auth->login($user_info, $password);

}
