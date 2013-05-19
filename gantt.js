var record = new Object();

$(function () {
        //-----[models]------------------------------
        var Project = Backbone.Model.extend({
                urlRoot: "/projects"
        });
        var Task = Backbone.Model.extend({
                initialize: function(options) {
                      this.project = options.project;
                }
        });

        //-----[collections]------------------------------
        var Projects = Backbone.Collection.extend({
                model: Project,
                url: "/projects"
        });
        var Tasks = Backbone.Collection.extend({
                model: Task,
                url: function() {
                  return "/projects/" + this.project.id;
                },
                initialize: function(options) {
                        this.project = options.project;
                }
        });

        //-----[views]------------------------------
        var ProjectView = Backbone.View.extend({
                tagName: "li",
                template: _.template($('#project-template').html()),
                render: function() {
                        this.$el.html(this.template(this.model.toJSON()));
                        return this;
                }
        });
        var TaskView = Backbone.View.extend({
                tagName: "li",
                template: _.template($('#task-template').html()),
                render: function() {
                        this.$el.html(this.template(this.model.toJSON()));
                        return this;
                }
        });
        var ProjectsView = Backbone.View.extend({
                el: $("#projects"),
                events: {
                        "click .add": "add",
                        "click .delete": "delete"
                },
                initialize: function(projects) {
                        this.projects = projects;
                        this.input = this.$(".name");
                        this.listenTo(projects, 'add', this.onAdd);
                        this.listenTo(projects, 'delete', this.onDelete);
                        this.projects.fetch();
                },
                add: function(e) {
                        this.projects.create({name: this.input.val()}, {wait: true});
                        this.input.val("");
                },
                onAdd: function(project) {
                        var projectView = new ProjectView({model: project}); 
                        this.$(".list").append(projectView.render().el);
                },
                delete: function(e) {
                },
                onDelete: function(project) {
                }
        });
        var TasksView = Backbone.View.extend({
                el: $("#projects"),
                events: {
                        "click .add": "add"
                },
                initialize: function() {
                        this.input = this.$(".name");
                        this.listenTo(tasks, 'add', this.onAdd);
                        tasks.fetch();
                },
                add: function(e) {
                        projects.create({name: this.input.val()});
                        this.input.val("");
                }
        });

        //-----[controllers]------------------------------

        var Router = Backbone.Router.extend({
                routes: {
                  "": "projects",
                  ":project_id": "projectTasks"
                },
                projects: function() {
                        this._projects = new Projects();
                        projectsView = new ProjectsView(this._projects);
                },
                projectTasks: function(projectId) {
                        var project = this._projects.get(projectId);
                        tasks = new Tasks({project: project});
                        tasksView = new TasksView(tasks);
                }
        });

        //-----[main]------------------------------
        new Router();
        Backbone.history.start();

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
