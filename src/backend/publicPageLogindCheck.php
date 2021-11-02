<?php

use SToyokura\Classes\UsePdo;
use SToyokura\Classes\Token;
use SToyokura\Classes\Cookie;
use SToyokura\Classes\Redirect;

//sessionの有無確認
if(!isset($_SESSION)) session_start();

//sessionにuser_idが登録されていたらホーム画面へ
if(isset($_SESSION["user_id"])) {
    $obj_redirect = new Redirect();
    $obj_redirect->go("home");
}

//cookieトークンがあったら
if(isset($_COOKIE["cookie_token"])) {
    $cookie_token = $_COOKIE["cookie_token"];
    $expiration_date = new DateTime("-14 days");
    $expiration_date = $expiration_date->format("Y-m-d H:i:s");
    $arr = [
        "cookie_token" => $cookie_token,
        "expiration_date" => $expiration_date
    ];
    $sql = "SELECT * FROM t_auto_login WHERE cookie_token = :cookie_token AND created_at >= :expiration_date;";
    $obj_use_pdo = new UsePdo($sql, $arr);
    $row_count = $obj_use_pdo->stmtRowCount();
    $row = $obj_use_pdo->stmtFetch();

    if($row_count == 1) {
        //token生成
        $token = Token::getToken();
        
        //tokenをDBとcookieに登録
        $insert_arr = [
            "user_id" => $row["user_id"],
            "cookie_token" => $token
        ];
        $insert_sql = "INSERT INTO t_auto_login(user_id, cookie_token) VALUES(:user_id, :cookie_token);";
        new UsePdo($insert_sql, $insert_arr);

        //cookieセット
        Cookie::set("cookie_token", $token, time()+60*60*24*14);

        //古いトークンはDBから削除
        Cookie::deleteForDb($row["id"]);

        //session_idの値を変える
        session_regenerate_id(true);
        //sessionにuser_idをセット
        $_SESSION["user_id"] = $row["user_id"];
        
        $obj_redirect = new Redirect();
        $obj_redirect->go("home");
    } else {
        Cookie::delete("cookie_token");
        Cookie::deleteForDb($row["id"]);
    }
}
