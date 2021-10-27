<?php
namespace SToyokura\Classes;

use SToyokura\Classes\DBConnect;

class UsePdo
{

    private $executed_stmt = null;

    /**
     * 
     * @param string $sql クエリ文
     * @param array  $pdo_item_arr SQLインジェクションで使うための配列
     */
    public function __construct($sql, $pdo_item_arr = null) {
        //pdoオブジェクト取得
        $db_connect = new DBConnect();
        $pdo = $db_connect->getPdo();

        //$pdo_item_arrがnullの場合、sqlをそのまま実行
        if($pdo_item_arr === null) {
            $stmt = $pdo->query($sql);
        } else {
            $stmt = $pdo->prepare($sql);
            forEach($pdo_item_arr as $key => $value) {
                $stmt->bindValue(":{$key}", $value);
            }
            $stmt->execute();
        }
        $this->executed_stmt = $stmt;
    }

    /**
     * 実行したsqlからfetchでデータ取得し、返すメソッド
     * @param void
     * @return array 取得結果
     */
    public function stmtFetch() {
        return $this->executed_stmt->fetch();
    }

    /**
     * 実行したsqlからrowCountでデータ取得し、返すメソッド
     */
    public function stmtRowCount() {
        return $this->executed_stmt->rowCount();
    }
}
