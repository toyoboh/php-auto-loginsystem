<?php
namespace SToyokura\Classes;

/**
 * DBに接続するクラス
 * インスタンスを生成し、pdoプロパティを用いることでDBの操作が可能
 */
class DBConnect
{
    private $pdo = null;

    private $dsn = "";
    private $username = "";
    private $password = "";
    private $options = [
        \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
        \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
        \PDO::ATTR_EMULATE_PREPARES => false
    ];

    /**
     * コンストラクタ
     * 
     */
    public function __construct() {
        $this->setDbInfo();
        $this->connect();
    }

    /**
     * DBの必要情報を設定する関数。必要な情報はinfo.iniファイルから読み込む
     * @param void
     * @return void
     */
    public function setDbInfo() {
        $ini_array = parse_ini_file(__DIR__ . "/../../../info.ini", true);
        $dbname    = $ini_array["db_info_test"]["db_name"];
        $host      = $ini_array["db_info_test"]["host"];
        $charset   = $ini_array["db_info_test"]["charset"];
        $username  = $ini_array["db_info_test"]["username"];
        $password  = $ini_array["db_info_test"]["password"];
        
        $this->dsn      = "mysql:dbname={$dbname};host={$host};charset={$charset}";
        $this->username = $username;
        $this->password = $password;
    }

    /**
     * DBに接続し、接続情報(pdo)を設定するメソッド
     * @param void
     * @return void
     */
    public function connect() {
        try{
            $this->pdo = new \PDO($this->dsn, $this->username, $this->password, $this->options);
        } catch(\PDOException $e) {
            var_dump($e->getMessage());
        }
    }

    /**
     * 
     */
    public function getPdo() {
        return $this->pdo;
    }
}
