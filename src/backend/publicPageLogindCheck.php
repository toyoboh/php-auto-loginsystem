<?php

use SToyokura\Classes\UsePdo;
use SToyokura\Classes\Token;
use SToyokura\Classes\Cookie;
use SToyokura\Classes\Redirect;

if(!isset($_SESSION)) session_start();

if(isset($_SESSION["user_id"])) {
    $obj_redirect = new Redirect();
    $obj_redirect->go("home");
}

if(isset($_COOKIE["cookie_token"])) {
    $old_cookie_token = $_COOKIE["cookie_token"];

    $obj_cookie = new Cookie();
    $obj_datetime = new DateTime("-14 days");
    $expiration_datetime = $obj_datetime->format("Y-m-d H:i:s");

    list($is_expiration, $cookie_token_record_db) = $obj_cookie->checkExpirationDate($old_cookie_token, $expiration_datetime);

    if(!$is_expiration) {
        $obj_cookie->delete("cookie_token");
        $obj_cookie->deleteForDb($cookie_token_record_db["id"]);
    } else {
        //token生成
        $new_cookie_token = Token::getToken();
        
        //tokenをDBとcookieに登録
        $obj_cookie->registerForDb($cookie_token_record_db["user_id"], $new_cookie_token);
        $obj_cookie->register("cookie_token", $token, time()+60*60*24*14);

        //古いトークンはDBから削除
        $obj_cookie->deleteForDb($cookie_token_record_db["id"]);

        //session_idの値を変える
        session_regenerate_id(true);
        //sessionにuser_idをセット
        $_SESSION["user_id"] = $cookie_token_record_db["user_id"];
        
        $obj_redirect = new Redirect();
        $obj_redirect->go("home");
    }
}
