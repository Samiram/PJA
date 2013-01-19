$(document).ready(function(){

	// adding tasks
	$(".panel").live("keydown", function(event) {
		if (event.which == 13) {
			addTask($(this));
		}
	}); 

	$(".panel :submit").live("click", function() {
		addTask($(this).parents());
	});

	$(".deadline").live("click", function() {
		$(this).hide();
		$(this).parents().children(".datepicker").show();
		$(this).parents().children(".datepicker").trigger("click");
	});

	$(".datepicker").live("click", function() {
		$(this).datepicker('destroy').datepicker().focus();
	});
// по нажатию  на кнопку с id= project-button, выполниться функция добавление проекта
	$("#project-button").click(function() {
		addProject();
	});
// по нажатию  на кнопку с id= 13( кнопка ентер), выполниться функция добавление проекта
	$("#project-managment").keydown(function(event) {
		if (event.which == 13) {
			addProject();
		}
	});

	$(".datepicker").live("change", function() {
		if (isDate($(this).val(), "/")) {
			var tid = $(this).parents("li").attr("id").substr(2);
			var date = $(this).val();
			$.ajax({
				type: "POST",
				url: "controller.php",
				data: "mode=deadline&tid="+tid+"&date="+date,
				success: function() {}
			});
			$(this).hide();
			$(this).parents().children(".deadline").text(date).show();
		} else {
			alert("Incorrect date");
		}
	});

	$(":checkbox").live("click", function(){
		var tid = $(this).parents("li").attr("id").substr(2);
		var task = $(this).parents("li").children(".task");
		$.ajax({
			type: "POST",
			url: "controller.php",
			data: "mode=status&tid=" + tid, 
			success: function() {
				task.toggleClass("task-done");
			}
		});
	});
// изменение текста на поле ввода
	$("a[title='edit project']").live("click", function() {
		alert("here");
		console.log("here");
		var h3 = $(this).parents(".header").children(".name");
		var name = h3.text();
		h3.html("<input type='text' id='edittask' value='" + name + "' />");
		$("#edittask").live("blur", function() {
			submit_project(h3); //когда снимаем фокус с поле ввода, вызывается эта функция, которая сохраняет загловок проекта
		});
		$("#edittask").focus();
		return false;
	});

	$("a[title='delete project']").live("click", function() {
		var project = $(this).parents("article");
		var pid = project.attr("id").substr(2);
		var d = "mode=project_del&pid="+pid;
		$.ajax({
			type: "POST",
			url: "controller.php",
			data: d,
			success: function(data) {
				project.remove();
				return false;
			}
		});
		return false;
	});

	$("a.up").live("click", function() {
		var current = $(this).parents("li");
		var prev = current.prev();	
		$.ajax({
			type: "POST", 
			url: "controller.php", 
			data: "mode=position&tid=" + current.attr("id").substr(2) + "&sid=" + prev.attr("id").substr(2),
			success: function() {
				current.after(prev);
			}
		});
		return false;
	});	
	$("a.down").live("click", function() {
		var current = $(this).parents("li");
		var nxt = current.next();	

		$.ajax({
			type: "POST", 
			url: "controller.php", 
			data: "mode=position&tid=" + current.attr("id").substr(2) + "&sid=" + nxt.attr("id").substr(2),
			success: function() {
				current.before(nxt);
			}
		});
		return false;
	});

	$("a.edit").live('click', function() {
		var task = $(this).parents("li").children(".task").children(".name");
		console.log("task = " + task.text());
		if (!task.hasClass("task-done")) {
			task.html("<input type='text' id='editbox' value='"+task.text()+"' />");
			$("#editbox").live('blur', function() {
				submit_task($(this).parents("div.task").children(".name"));
			});
			$("#editbox").focus();
		}
		return false;
	});

	$(".task").live("keydown", function(event){  
		if(event.which == 13)  {  
			submit_task($(this).children(".name")); 
		}
	});  

	$(".header").live("keydown", function(event){  
		if(event.which == 13)  {  
			submit_project($(this).children("h3")); 
		}
	});  

	$("a[title=delete]").live("click", function() {
		var d = "mode=del&tid="+$(this).parents("li").attr("id").substr(2);
		var li = $(this).parents("li");
		$.ajax({
				type: "POST",  
				url:"controller.php",  
				data: d,
				success: function(data){  
					li.remove();
					return false;
				}});  
		return false;
	});


});

function addTask(e) {
	var name = e.children(":text").val().trim();
	if (name.length < 5) {
		alert("Incorrect task!");
		return false;
	}
	var pid = e.parents("article").attr("id").substr(2);
	var d = "mode=add&pid="+pid+ "&name=" + name;
	var tasks = $("#p-"+pid);
	$.ajax({type: "POST",  
			url:"controller.php",  
			data: d,
			success: function(data){ 
				$("#pl-"+pid).parents(".ajax-tasks").load("index.php #pl-" + pid);
				e.children(":text").val("");
			}});  
}

function submit_task(e) {
	var tid = e.parents("li").attr("id").substr(2);
	var name = $("#editbox").attr("value").trim();
	if (name.length < 5) {
		alert("Incorrect task!");
		$("#editbox").focus();
		return false;
	}
	$.ajax({type: "POST",
		url: "controller.php",
		data: "mode=update&tid=" + tid + "&name=" + name,
		success: function() {
			e.html(name);
		}
	});
}

function submit_project(e) {
	var pid = e.parents("article").attr("id").substr(2);
	var name = $("#edittask").val().trim();
	if (name.length < 5) {
		alert("Incorrect project");
		$("#edittask").focus();
		return false;
	}
	$.ajax({type: "POST",
		url: "controller.php",
		data: "mode=project_update&pid=" + pid + "&name=" + name,
		success: function() {
			e.html(name);
		}
	});
}
// добавление проекта
function addProject() {
	var name = $("#project-name").val().trim(); // объявление переменных
	if (name.length < 5) {
		alert("Incorrect project name!");
		$("#project-name").focus();
		return false;
	}
	//  если имя длинне 5-ти символов,то выполняется запрос к файлу controller.php
	$.ajax({
		type: "POST", 
		url: "controller.php",
		data: "mode=project_add&name=" + name, // передаем параметры,с которыми этот запрос выполняется
		//  функция перезагрузки части страницы с новым проектом
		success: function() {
			$("#projects").parents("#ajax-projects").load("index.php #projects"); // очистка поля ввода
			$("#project-name").val(""); //  измение значений поле ввода на пустой
			$("#project-name").blur(); // делает поле ввода неактивным
		}
	});
	return false;
}

function isDate(txtDate, separator) {
    var aoDate,  ms,  month, day, year; 
    if (separator === undefined) {
        separator = '/';
    }
    aoDate = txtDate.split(separator);
    if (aoDate.length !== 3) {
        return false;
    }
    month = aoDate[0] - 1;
    day = aoDate[1] - 0;
    year = aoDate[2] - 0;
    // test year range
    if (year < 1000 || year > 3000) {
        return false;
    }
    ms = (new Date(year, month, day)).getTime();
    aoDate = new Date();
    aoDate.setTime(ms);
    if (aoDate.getFullYear() !== year ||
        aoDate.getMonth() !== month ||
        aoDate.getDate() !== day) {
        return false;
    }
    return true;
}
