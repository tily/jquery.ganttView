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
//		id: 1, project: "Feature 1", series: [
//			{ name: "Planned",member: "okawa", memo: "memo", start: new Date(2010,00,01), end: new Date(2010,00,03) },
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
else {
    $data = array();
	$data = json_decode($_POST['json'], true);
	$mode = array_shift($data);
	$id = array_shift($data);
	if ($mode === 'add'){
		//チケット追加
		$number = count_number($dbh);
		$data['number']	= strval($number + 1);
		$add_sql = "insert into json_data (project, name, member, memo, start, end, progress, color, number) values (:project, :name, :member, :memo, :start, :end, :progress, :color, :number , :milestone, :miledate)"; 
		$stmt = $dbh->prepare($add_sql);
		$exec = $stmt->execute($data);
	}
	else if ($mode === 'update'){
		//number取得
		
    	$update_sql = "update json_data set project= :project, name= :name, member= :member, memo= :memo, start= :start, end= :end, progress= :progress, color= :color, number= :number, milestone= :milestone, miledate= :miledate where id = :id"; 

		$stmt = $dbh->prepare($update_sql);
     	$data["id"] =  $id;
		$exec = $stmt->execute($data);
		
	}
	else if ($mode === 'delete'){
		$record = json_decode($_POST['json'], true);
		$num = $record['number'];
		$row_num = count_number($dbh);
		for ($i = $num; $i <= $row_num; $i++){
			$update_num_sql = "update json_data set number= :number where number= :oldNumber";
			$stmt = $dbh->prepare($update_num_sql);
			$stmt->bindValue(':number', ($i - 1), PDO::PARAM_INT);
			$stmt->bindValue(':oldNumber', $i, PDO::PARAM_INT);
			$exec = $stmt->execute();
		}


		$delete_sql = "delete from json_data where id = :id";
		$stmt = $dbh->prepare($delete_sql);
		$stmt->bindValue(':id', $id, PDO::PARAM_INT);
		$exec = $stmt->execute();
	}	
}

function count_number($dbh){
	$count_sql= "select count(*) from json_data ";
	$sth = $dbh->query($count_sql);
	$result = $sth->fetch(PDO::FETCH_NUM);
	return $result[0];
}
