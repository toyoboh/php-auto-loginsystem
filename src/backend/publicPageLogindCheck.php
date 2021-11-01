<?php

use SToyokura\Classes\UsePdo;
use SToyokura\Classes\Token;

//sessionの有無確認
if(!isset($_SESSION)) session_start();

//sessionにuser_idが登録されていたらホーム画面へ
if(isset($_SESSION["user_id"])) {
    header("Location: home.php");
    exit();
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
        setcookie("cookie_token", $token, time()+60*60*24*14, "/", null, false, true);

        //古いトークンはDBから削除
        $delete_sql = "DELETE FROM t_auto_login WHERE id = :id;";
        $delete_arr = [ "id" => $row["id"]];
        new UsePdo($delete_sql, $delete_arr);

        //sessionにuser_idをセット
        $_SESSION["user_id"] = $row["user_id"];
        header("Location: home.php");
        exit();
    } else {
        //古いcookieは削除
        setcookie("cookie_token", "", time()-60);

        //古いトークンはDBから削除
        $delete_sql = "DELETE FROM t_auto_login WHERE id = :id;";
        $delete_arr = [ "id" => $row["id"]];
        new UsePdo($delete_sql, $delete_arr);
    }
}
