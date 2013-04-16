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
$data = [];
$i = 0;
$sql = 'select * from json_data';
foreach ($dbh->query($sql) as $row) {
	$data[i]["id"] = $row['id'];
	$data[i]["name"] = $row['name'];
	$data[i]["project"] = $row[''];
	$data[i]["member"] = $row['id'];
	$data[i]["start"] = $row['id'];
	$data[i]["end"] = $row['id'];
	$data[i]["color"] = $row['id'];
	$i++;
}

//JSONに変換

//?mode=projectで呼び出されたとき




//?mode=memberで呼び出されたとき


//出力

//チケット追加

//チケット更新