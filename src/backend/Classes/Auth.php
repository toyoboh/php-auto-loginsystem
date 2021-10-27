<?php
namespace SToyokura\Classes;

use SToyokura\Classes\DBConnect;

class Auth
{

    /**
     * 認証メソッド
     * @param string $user_info ユーザIDもしくはメールアドレス
     * @param string $password  パスワード
     * @return array $login_result 認証の結果
     */
    public function login($user_info, $password) {

        //ユーザ情報取得のクエリ文作成
        $sql = "SELECT user_id, user_name, password FROM t_users WHERE user_id = :user_id OR mail_address = :mail_address;";
    
        //インスタンス生成
        $db = new DBConnect();
        $pdo = $db->pdo;
    
        //クエリ文の実行
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(":user_id", $user_info);
        $stmt->bindValue(":mail_address", $user_info);
        $stmt->execute();
        $user = $stmt->fetch();
        $count = $stmt->rowCount();
    
        //認証結果を返す配列を設定
        if($count == 1 && password_verify($password, $user["password"])) {
            $login_result = [
                "auth" => true,
                "user_id" => $user["user_id"],
                "user_name" => $user["user_name"]
            ];
        } else {
            $login_result = [
                "auth" => false
            ];
        }

        return $login_result;
    }

    public function logout() {

    }
}
