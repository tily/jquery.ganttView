var record = new Object();

$(function () {
	$("#ganttChart").ganttView({ 
		dataUrl: "./gantt.php?mode=project",
		slideWidth: 900,
		behavior: {
			onClick: function (data) { 
				$("#id > input").attr({value : data.id});
				$("#prj > input").attr({value : data.project});
				$("#name > input").attr({value : data.name});
				$("#member > input").attr({value : data.member});
				$("#memo > input").attr({value : data.memo});
				$("#start > input").attr({value : data.start.toString("yyyy-M-d")});
				$("#end > input").attr({value : data.end.toString("yyyy-M-d")});
				$("#progress > input").attr({value : data.progress});
				$("#color > input").attr({value : data.color});
			},
			onResize: function (data) { 
				$("#id > input").attr({value : data.id});
				$("#prj > input").attr({value : data.project});
				$("#name > input").attr({value : data.name});
				$("#member > input").attr({value : data.member});
				$("#memo > input").attr({value : data.memo});
				$("#start > input").attr({value : data.start.toString("yyyy-M-d")});
				$("#end > input").attr({value : data.end.toString("yyyy-M-d")});
				$("#progress > input").attr({value : data.progress});
				$("#color > input").attr({value : data.color});
				inputRecord("update");
				var json_text = JSON.stringify(record,"\t");
				//alert(json_text);
				$.ajax({
					type: "POST",
					url: "./gantt.php",
					data: {"json": json_text},
					success: function(){
						location.reload();
						//alert("ok");
					}
				});

				//var msg = "You resized an event: { start: " + data.start.toString("M/d/yyyy") + ", end: " + data.end.toString("M/d/yyyy") + " }";
				$("#eventMessage").text(msg);
			},
			onDrag: function (data) { 
				$("#id > input").attr({value : data.id});
				$("#prj > input").attr({value : data.project});
				$("#name > input").attr({value : data.name});
				$("#member > input").attr({value : data.member});
				$("#memo > input").attr({value : data.memo});
				$("#start > input").attr({value : data.start.toString("yyyy-M-d")});
				$("#end > input").attr({value : data.end.toString("yyyy-M-d")});
				$("#progress > input").attr({value : data.progress});
				$("#color > input").attr({value : data.color});
				inputRecord("update");
				var json_text = JSON.stringify(record,"\t");
				//alert(json_text);
				$.ajax({
					type: "POST",
					url: "./gantt.php",
					data: {"json": json_text},
					success: function(){
						location.reload();
						//alert("ok");
					}
				});

				//var msg = "You dragged an event: { start: " + data.start.toString("M/d/yyyy") + ", end: " + data.end.toString("M/d/yyyy") + " }";
				$("#eventMessage").text(msg);
			}
		}
	});
	

});

function inputRecord(mode){
	record.mode = mode;
	record.id = $("#id > input").attr("value");
	record.project = $("#prj > input").attr("value");
	record.name = $("#name > input").attr("value");
	record.member = $("#member > input").attr("value");
	record.memo = $("#memo > input").attr("value");
	record.start = $("#start > input").attr("value");
	record.end = $("#end > input").attr("value");
	record.progress = $("#progress > input").attr("value");
	record.color = $("#color > input").attr("value");
	record.number = "1";
}

$('#submit').click(function (data) {
	inputRecord("add");
	var json_text = JSON.stringify(record,"\t");
	$.ajax({
		type: "POST",
		url: "./gantt.php",
		data: {"json": json_text},
		success: function(){
			location.reload();
			//alert("ok");
		}
	});
});

$('#update').click(function (data) {
	inputRecord("update");	
	var json_text = JSON.stringify(record,"\t");
	//alert(json_text);
	$.ajax({
		type: "POST",
		url: "./gantt.php",
		data: {"json": json_text},
		success: function(){
			location.reload();
			//alert("ok");
		}
	});
});
