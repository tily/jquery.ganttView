<?php

//DB設定、接続
define('DB_NAME', getenv('C4SA_MYSQL_DB'));
define('DB_USER', getenv('C4SA_MYSQL_USER'));
define('DB_PASSWORD', getenv('C4SA_MYSQL_PASSWORD'));
define('DB_HOST', getenv('C4SA_MYSQL_HOST'));

$dsn = "mysql:dbname=".DB_NAME.";host=".DB_HOST;
$user = DB_USER;
$password = DB_PASSWORD;

try{
    $dbh = new PDO($dsn, $user, $password);
    
    print "OK";
}catch (PDOException $e){
    print('Error:'.$e->getMessage());
    die();
}

//DBを読み込む

//JSONに変換

//?mode=projectで呼び出されたとき




//?mode=memberで呼び出されたとき


//出力

//チケット追加

//チケット更新