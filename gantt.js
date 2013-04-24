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
				$("#start > input").attr({value : data.start.toString("yyyy-M-d")});
				$("#end > input").attr({value : data.end.toString("yyyy-M-d")});
				$("#progress > input").attr({value : data.progress});
				$("#color > input").attr({value : data.color});
			},
			onResize: function (data) { 
				var msg = "You resized an event: { start: " + data.start.toString("M/d/yyyy") + ", end: " + data.end.toString("M/d/yyyy") + " }";
				$("#eventMessage").text(msg);
			},
			onDrag: function (data) { 
				var msg = "You dragged an event: { start: " + data.start.toString("M/d/yyyy") + ", end: " + data.end.toString("M/d/yyyy") + " }";
				$("#eventMessage").text(msg);
			}
		}
	});
	
	$('#submit').click(function (data) {
		alert(data);
	});
	// $("#ganttChart").ganttView("setSlideWidth", 600);
});
