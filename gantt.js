var record = new Object();

$(function () {
	$("#ganttChart").ganttView({ 
		dataUrl: "./gantt.php?mode=project",
		slideWidth: 900,
		behavior: {
			onClick: function (data) { 
				appendHTML(data);
			},
			onResize: function (data) { 
				appendHTML(data);
				editRecord("update");
				//var msg = "You resized an event: { start: " + data.start.toString("M/d/yyyy") + ", end: " + data.end.toString("M/d/yyyy") + " }";
				$("#eventMessage").text(msg);
			},
			onDrag: function (data) { 
				appendHTML(data);
				editRecord("update");
				//var msg = "You dragged an event: { start: " + data.start.toString("M/d/yyyy") + ", end: " + data.end.toString("M/d/yyyy") + " }";
				$("#eventMessage").text(msg);
			}
		}
	});
	

});

function sendJSON(mode){
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
}

function appendHTML(data){
	$("#id > input").attr({value : data.id});
	$("#prj > input").attr({value : data.project});
	$("#name > input").attr({value : data.name});
	$("#member > input").attr({value : data.member});
	$("#memo > input").attr({value : data.memo});
	$("#start > input").attr({value : data.start.toString("yyyy-M-d")});
	$("#end > input").attr({value : data.end.toString("yyyy-M-d")});
	$("#progress > input").attr({value : data.progress});
	$("#color > input").attr({value : data.color});
	$("#number > input").attr({value : data.number});
	$("#milestone > input").attr({value : data.milestone});
	$("#miledate > input").attr({value : data.miledate});
}

function editRecord(mode){
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
	record.number = $("#number > input").attr("value");
	record.milestone = $("#milestone > input").attr("value");
	record.miledate = $("#miledate > input").attr("value");

	sendJSON(record.mode);
}

$('#delete').click(function () {
	editRecord("delete");
});

$('#submit').click(function (data) {
	editRecord("add");
});

$('#update').click(function (data) {
	editRecord("update");	
});

$('#up').click(function () {
});

$('#down').click(function () {
});
