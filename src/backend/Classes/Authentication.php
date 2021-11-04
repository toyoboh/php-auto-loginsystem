<?php
namespace SToyokura\Classes;

use SToyokura\Classes\UsePdo;
use SToyokura\Classes\User;
use SToyokura\Classes\Cookie;
use SToyokura\Classes\Token;

class Authentication
{

    /**
     * PCに登録されているcookie_tokenを用いたログイン
     * @param void
     * @return boolean 認証結果
     */
    public function cookieTokenLogin() {

        $old_cookie_token = $_COOKIE["cookie_token"];

        $obj_cookie = new Cookie();
        $obj_datetime = new \DateTime("-14 days");
        $expiration_datetime = $obj_datetime->format("Y-m-d H:i:s");
    
        list($is_expiration, $cookie_token_record_db) = $obj_cookie->checkExpirationDate($old_cookie_token, $expiration_datetime);
    
        if(!$is_expiration) {
            $obj_cookie->delete("cookie_token");
            $obj_cookie->deleteForDb($cookie_token_record_db["id"]);
            return false;
        } else {
            $new_cookie_token = Token::getToken();
            
            $obj_cookie->registerForDb($cookie_token_record_db["user_id"], $new_cookie_token);
            $obj_cookie->register("cookie_token", $token, time()+60*60*24*14);
    
            $obj_cookie->deleteForDb($cookie_token_record_db["id"]);
    
            $obj_session = new Session();
            $obj_session->regenerate();
            $obj_session->set("user_id", $cookie_token_record_db["user_id"]);

            return true;
        }
    }

    /**
     * 認証メソッド
     * @param string  $user_info ユーザIDもしくはメールアドレス
     * @param string  $password  パスワード
     * @return object $user      Userインスタンス
     */
    public function login($user_info, $password) {

    }

}
