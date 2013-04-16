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
	$data[i]["name"] = mb_convert_encoding($row['name'], "UTF-8", "auto");
	$data[i]["project"] = mb_convert_encoding($row['project'], "UTF-8", "auto");
	$data[i]["member"] = mb_convert_encoing($row['id'], "UTF-8", "auto");
	$data[i]["start"] = $row['id'];
	$data[i]["end"] = $row['id'];
	$data[i]["color"] = $row['id'];
	$i++;
}

//JSONに変換

$data_json = json_encode($data);

//?mode=projectで呼び出されたとき




//?mode=memberで呼び出されたとき


//出力

//チケット追加

//チケット更新