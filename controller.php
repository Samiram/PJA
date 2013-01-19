<?php
	require_once("model.php");
	if (isset($_POST['mode'])) {
		switch($_POST['mode']) {
			case "add":
				addTask($_POST['pid'], $_POST['name']); break;
			case "del":	
				deleteTask($_POST['tid']); break;
			case "update":
				updateTask($_POST["tid"], $_POST["name"]); break;
			case "status":
				changeStatus($_POST["tid"]); break;
			case "position":
				swapPosition($_POST["tid"], $_POST['sid']); break;
			case "project_update":
				updateProject($_POST["pid"], $_POST["name"]); break;
			case "project_del":
				deleteProject($_POST["pid"]); break;
			case "deadline":
				updateDeadline($_POST['tid'], $_POST["date"]); break;
			case "project_add":
				addProject($_POST["name"]); break;
		}
	}
?>