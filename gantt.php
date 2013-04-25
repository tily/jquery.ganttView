<?php

mb_internal_encoding("UTF-8");

//DB設定、接続
define('DB_NAME', getenv('C4SA_MYSQL_DB'));
define('DB_USER', getenv('C4SA_MYSQL_USER'));
define('DB_PASSWORD', getenv('C4SA_MYSQL_PASSWORD'));
define('DB_HOST', getenv('C4SA_MYSQL_HOST'));

$dsn = "mysql:dbname=".DB_NAME.";host=".DB_HOST;
$user = DB_USER;
$password = DB_PASSWORD;

header("Content-type: application/json; charset=utf-8");

try{
    $dbh = new PDO($dsn, $user, $password);

}catch (PDOException $e){
    print('Error:'.$e->getMessage());
    die();
}


if($_SERVER["REQUEST_METHOD"] != "POST"){
	//DBを読み込む
	$data = array();
    $data_json = array();
	$i = 0;
	$sql = 'select * from json_data';
    $result = $dbh->query($sql);
  	while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
      array_push($data,$row);
  	}
	//?mode=projectで呼び出されたとき
	if($_GET['mode'] == "project"){
		//JSONに変換
		
		//キー(project)を取得
//jsonデータ形式
//		var ganttData = [
//	{
//		id: 1, name: "Feature 1", series: [
//			{ name: "Planned", start: new Date(2010,00,01), end: new Date(2010,00,03) },
//			{ name: "Actual", start: new Date(2010,00,02), end: new Date(2010,00,05), color: "#f0f0f0" }
//		]
//	},{},{}...
		$i = 0;
		$get_key_sql = 'select distinct project from json_data';
		foreach ($dbh->query($get_key_sql) as $prj_key){
			$push_array = array("id" => $i, "project" => $prj_key["project"], "series" => array());
			array_push($data_json, $push_array);
            $i++;
		}
      
		//データを格納(上のデータ形式にそって)
		for ($i = 0; $i < count($data); $i++){				
			for ($j = 0; $j < count($data_json); $j++){
                if (strcmp($data_json[$j]["project"], $data[$i]["project"]) == 0){
                  array_push($data_json[$j]["series"],$data[$i]);
				}
			}
		}	
		$json_out = json_encode($data_json);

		//出力
		print $json_out;
	}
	//?mode=memberで呼び出されたとき
	else if ($_GET['mode'] == "member"){
		$i = 0;
		$get_key_sql = 'select distinct member from json_data';
		foreach ($dbh->query($get_key_sql) as $mmb_key){
			$push_array = array("id" => $i, "member" => $mmb_key["member"], "series" => array());
			array_push($data_json, $push_array);
            $i++;
		}
		
		//データを格納(上のデータ形式にそって)
		for ($i = 0; $i < count($data); $i++){				
			for ($j = 0; $j < count($data_json); $j++){
                if (strcmp($data_json[$j]["member"], $data[$i]["member"]) == 0){
                  array_push($data_json[$j]["series"],$data[$i]);
				}
			}
		}
		
		$json_out = json_encode($data_json);

		//出力
		header("Content-type: application/json; charset=utf-8");
		print $json_out;
	}
}
else{
    $data = array();
	$data = json_decode($_POST['json'], true);
	$mode = array_shift($data);
	$id = array_shift($data);
	var_dump($data);
	if ($mode === 'add'){
		//チケット追加
      $add_sql = "insert into json_data (name, project, member, memo, start, end, progress, color, number) values (?, ?, ?, ?, ?, ?, ?, ?, ?)"; 
	  $stmt = $dbh->prepare($add_sql);
	  $exec = $stmt->execute($data);
	}
	else if ($data['mode'] === 'update'){
		//チケット更新
		if (isset($data["number"])){
			change_numbers($dbh, $data["project"], $data["number"]);
		}
		$update_sql = "update json_data set name=?, project=?, member=?, memo=?, start=?, end=?, progress=?, color=?, number=? where id = ?"; 

		$stmt = $dbh->prepare($update_sql);
		$data = push($id);
		$exec = $stmt->execute($data);
		
	}
	
}

function change_numbers($dbh, $prj, $num){
	$count_sql= "select count(*) from json_data where project=". $prj;
	$result = $dbh->query($count_sql);
	for ($i = $result; $i >= $num; $i--){
		$update_sql = 'update json_data set number='.$i++." where project =\'". $prj. "\' and number=".$i;
		$dbh->query($update_sql);
	}
}
