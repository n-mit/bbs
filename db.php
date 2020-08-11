<?php

class DB_Connection {

    const DSN = "oneline_bbs";
    const HOST = "localhost";
    const UTF = "utf8";
    const USER = "root";
    const PASSWORD = "";

    protected $dbh;

    //インスタンス生成時にDB接続を実行
    function __construct() {
        $dsn = "mysql:dbname=".self::DSN.";host=".self::HOST.";charset=".self::UTF;
        $user = self::USER;
        $pass = self::PASSWORD;

    try {
        $this->dbh = new PDO ($dsn, $user, $pass);
        //静的プレースホルダを指定(SQL文をBD側にあらかじめ送信して、実行前にSQL文の構文解析などを準備しておく方式)
        $this->dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        //DBエラー発生時は例外を投げる設定
        $this->dbh->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
    } catch (Exception $e) {
        echo mb_convert_encoding($e->getMessage(), "utf-8", "sjis");
        exit();
    }
    }
}

class S_contact extends DB_Connection {

    function __construct() {
        parent::__construct();
    }

    // INSERT文
    function ins($name, $comment, $created_at) {
    try {
        //SQL文 value以下は名前付きプレースホルダ
        $stmt = $this->dbh->prepare("INSERT INTO post(name, comment, created_at) value(:name, :comment, :created_at)");
        //名前付きプレースホルダに変数(引数)の値をバインド
        $stmt->bindValue(":name", $name);
        $stmt->bindValue(":comment", $comment);
        $stmt->bindValue(":created_at", $created_at);
        $stmt->execute();
        //接続終了
        $this->dbh = null;
        } catch (Exception $e) {
            echo mb_convert_encoding($e->getMessage(), "utf-8", "sjis");
        exit();
        }
    }

    //SELECT文
    function sel() {
        try {
            //SQL文 名前付きプレースホルダ
            $stmt = $this->dbh->prepare("SELECT name, comment, created_at FROM post where 1 ORDER BY created_at DESC");
            $stmt->bindValue(1,1);
            $stmt->execute();
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $data;
        } catch (Exception $e) {
            echo mb_convert_encoding($e->getMessage(), "utf-8", "sjis");
            exit();
        }
    }
}

?>