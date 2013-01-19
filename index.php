<?php
	require_once("model.php");
	$projects = getProjects();
	$tasks = getTasks();
?>
<!DOCTYPE html>
<html>
<head>
	<title>Simple TODO list</title>
	<meta charset="utf-8" />
	<link rel="stylesheet" href="css/style.css" />
	<link rel="stylesheet" href="css/datepicker.css" />
	<script type="text/javascript" src="javascript/jquery-1.7.2.min.js"></script>
	<script type="text/javascript" src="javascript/main.js"></script>
	<script type="text/javascript" src="javascript/jquery.ui.core.js"></script>
	<script type="text/javascript" src="javascript/jquery.ui.datepicker.js"></script>
</head>
<body>
	<section id="wrap">
		<header>
			<div>
				<h1>Simple TODO lists</h1>
				<h2>From Ruby Garage</h2>
			</div>
		</header>
		<div id="ajax-projects">
		<section id="projects"> 
		<? foreach($projects as $key => $project): ?> <!--���� �� �������-->
		<article class="project" id="p-<?= $project['id']?>">
			<div class="header">

				<ul class="project-buttons">
					<li><a href="#" title="edit project"><img src="images/edit.png" alt="edit task"></a></li>
					<li><a href="#" title="delete project"><img src="images/delete.png" alt="delete task"></a></li>
				</ul>
				<h3><?= $project['name'] ?></h3>			
			</div>
			<div class="panel">
				<img src="images/plus.png" alt="add task" title="add task" /> <!--������� ������-->
				<input type="text" placeholder="Start typing to create task" /> <!--���� ����� ������-->
				<input type="submit" value="Add task" /> <!--������-->
			</div>
			<div class="ajax-tasks"> <!--������ �������-->
				<ul  class="tasks" id="pl-<?=$project['id'] ?>">
				<? if(empty($tasks[$project["id"]])): ?> <!--�������� �� ������������� ����� � �������-->
					<li class="empty">
						<div class="task empty">Empty project. Add some tasks here.</div> <!--���� ��� ������ ��������� ��� ���������-->
					</li>
				<? else: ?>
				<? foreach($tasks[$project["id"]] as $k => $task): ?> <!--���� �� �������-->
					<li id="t-<?= $task["id"] ?>">
						<nav class="buttons">
							<a href="#" class="up" title="up"><img src="images/up.gif" alt="up"></a>
							<a href="#" class="down" title="down"><img src="images/down.gif" alt="down"></a>
							<a href="#" class="edit" title="edit"><img src="images/edit.png" alt="edit"></a>
							<a href="#" class="delete" title="delete"><img src="images/delete.png" alt="delete"></a>
						</nav> <!--������ ��������� �������-->
						<div class="task-status"><input type="checkbox" <?= $task['status'] ? "checked" : "" ?> ></div> <!--������ ������ �������� ��� ���-->
						<div class="task<?= $task['status'] ? ' task-done' : '' ?>"> <!--����� ������ ������-->
							<div class="deadline"><?= date("m/d/Y", strtotime($task['deadline'])) ?></div> <!--����� ����-->
							<input type="text" class="datepicker" value="<?= date("m/d/Y", strtotime($task['deadline'])) ?>" size="10" /> <!--����� �����������-->
							<div class="name"><?= $task['name'] ?></div> <!--��� ������-->
						</div>
					</li>
				<? endforeach; ?>
				<? endif ?>
				</ul>
			</div>
		</article>
		<? endforeach; ?>
		</section>
		</div>
		<div id="project-managment"> <!--���� ���������� ���������-->
			<input type="text" class="add-project" id="project-name" placeholder="Project name" /> <!--���� ������ �������-->
			<button type="button" id="project-button" class="large-btn"><a href="#"><img src="images/plus.png" alt="plus"/>Add project</a></button> <!--������ ��������� �������-->
		</div>
		<div class="clear"></div>
		<footer> Samira</footer>
	</section>
</body>
</html>