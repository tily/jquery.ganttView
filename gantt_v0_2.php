<?php

if($_SERVER["REQUEST_METHOD"] != "POST"){
    // jsonを要求された場合
    fopen();
}else{
    // フォームからPOSTによって要求された場合
}
//json読み込み

//DBを読み込む
$data = [];
$i = 0;
$sql = 'select * from json_data';
foreach ($dbh->query($sql) as $row) {
	$data[i]["id"] = $row['id'];
	$data[i]["name"] = $row['name'], "UTF-8", "auto");
	$data[i]["project"] = $row['project'], "UTF-8", "auto");
	$data[i]["member"] = $row['id'], "UTF-8", "auto");
	$data[i]["memo"] = $row['memo'], "UTF-8", "auto");
	$data[i]["start"] = $row['id'];
	$data[i]["end"] = $row['id'];
	$data[i]["color"] = $row['id'];
	$data[i]["number"] = $row['number'];
	$i++;
}




if(isset($_GET['mode'])){
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
		foreach ($dbh->query($get_key_sql as $prj_key){
			$data_json[i]["id"] = $i;
			$data_json[i]["project"] = $prj_key;
		}
		
		//データを格納(上のデータ形式にそって)
		for ($i = 0; $i < count($data), $i++){				
			for ($j = 0; $j < count($data_json["project"]), $j++){
				if (strcmp($data_json[j]["project"], $data[i]["project"]) == 0){
					array_splice($data_json[j]["project"]["series"], $data[j]["number"], 0, $data[j]);
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
		foreach ($dbh->query($get_key_sql as $mmb_key){
			$data_json[i]["id"] = $i;
			$data_json[i]["member"] = $mmb_key;
		}
		
		//データを格納(上のデータ形式にそって)
		for ($i = 0; $i < count($data), $i++){				
			for ($j = 0; $j < count($data_json["member"]), $j++){
				if (strcmp($data_json[j]["member"], $data[i]["member"]) == 0){
					array_splice($data_json[j]["member"]["series"], $data[j]["number"], 0, $data[j]);
				}
			}
		}
		
		$json_out = json_encode($data_json);

		//出力
		print $json_out;
	}
}
else if (isset($_POST['json'])){
	if ($_POST['mode'] == 'add'){
		//チケット追加
		$add_sql = 'insert ';
		
	}
	else if ($_POST['mode'] == 'update'){
		//チケット更新
		$update_sql = '';
		
	}
	
}

