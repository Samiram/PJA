<?php
	require_once("db.php");
// берет покючение к БД
	function getProjects() {
		$db = DataBase::getInstance();
		$sql = "select * from projects"; // запрос к БД
		$q = $db->prepare($sql); // выполнение запроса
		$q->execute();
		$q->setFetchMode(PDO::FETCH_ASSOC); // выставка метод получения данных, возвращает массив, индексами является имена колонок, а значение массива- значением строк БД
		$result = array();
		while($r = $q->fetch()){
			$result[] = $r;
		}
		return $result;
	} 
	
  // выборка задач проекта
	function getTasks() {
		$db = DataBase::getInstance();
		$sql = "select t.name, t.deadline, t.project_id, t.id, t.status from tasks t join projects p on (t.project_id = p.id) order by t.position asc";
		$q = $db->prepare($sql);
		$q->execute();
		$q->setFetchMode(PDO::FETCH_ASSOC);		
		$result = array();
		while($r = $q->fetch()){
			$result[$r['project_id']][] = $r;
		}
		return $result;		
	}
 // добавление задачи в проект
	function addTask($project, $name) {
		$db = DataBase::getInstance(); // для нумерации задачи в проекте 
		$sql = "select max(position) as pos from tasks where project_id = $project";
		$q = $db->query($sql);
		$r = $q->fetch();
		$position = $r["pos"];
		$sql = "insert into tasks (project_id, name, position) values (:project, :name, $position+1)"; // вставка новой задачи в таблицу задач
		$q = $db->prepare($sql); //  
		$q->bindParam(':project', $project, PDO::PARAM_INT);
		$q->bindParam(':name', $name, PDO::PARAM_STR);
		$q->execute();
	}
// обновление имени задач
	function updateTask($id, $name) {
		$db = DataBase::getInstance();
		$sql = "update tasks set name = :name where id = :id";
		$q = $db->prepare($sql);
		$q->bindParam(':name', $name, PDO::PARAM_STR);
		$q->bindParam(':id', $id, PDO::PARAM_INT);
		$q->execute(); // выполнение запроса
	}
// обновение даты 
	function updateDeadline($id, $date) {
		$db = DataBase::getInstance();
		$sql = "update tasks set deadline = str_to_date(:deadline, '%m/%d/%Y') where id = :id";
		$q = $db->prepare($sql);
		$q->bindParam(':deadline', $date, PDO::PARAM_STR);
		$q->bindParam(':id', $id, PDO::PARAM_INT);
		$q->execute();
	}
// обновление имени проекта
	function updateProject($id, $name) {
		$db = DataBase::getInstance();
		$sql = "update projects set name = :name where id = :id";
		$q = $db->prepare($sql);
		$q->bindParam(':name', $name, PDO::PARAM_STR);
		$q->bindParam(':id', $id, PDO::PARAM_INT);
		$q->execute();
	}
// удаление задач
	function deleteTask($id) {
		$db = DataBase::getInstance();// получение соединений с БД
		$sql = "delete from tasks where id = :id"; 
		$q = $db->prepare($sql); // формирование запроса
		$q->bindParam(':id', $id, PDO::PARAM_INT); // удаление запроса
		$q->execute(); // выполнение запроса
	}
// удаление проекта
	function deleteProject($id) {
		$db = DataBase::getInstance();
		$sql = "delete from projects where id = :id";
		$q = $db->prepare($sql);
		$q->bindParam(':id', $id, PDO::PARAM_INT);
		$q->execute();
	}
	//  изменение статуса задач
	function changeStatus($id) {
		$db = DataBase::getInstance();
		$sql = "update tasks set status = not status where id = :id"; // при выполение этого запроса статус меняется на противоположный
		$q = $db->prepare($sql); // формирование запроса
		$q->bindParam(':id', $id, PDO::PARAM_INT);
		$q->execute();
	}
// 
	function swapPosition($id, $sid) {
		$db = DataBase::getInstance();
		$sql = "select position from tasks where id = :id"; // получение позиций данных для перой задач
		$q = $db->prepare($sql); 
		$q->bindParam(':id', $id, PDO::PARAM_INT);
		$q->execute();
		$p1 = $q->fetch();
		$p1 = $p1["position"];	
		$sql = "select position from tasks where id = :id"; // получение позиций данных для второй задачи
		$q = $db->prepare($sql);
		$q->bindParam(':id', $sid, PDO::PARAM_INT);
		$q->execute();
		$p2 = $q->fetch(); // выборка строки
		$p2 = $p2["position"];
		$sql = "update tasks set position = :p where id = :id"; // меняем задачи местами для 1-ого случая
		$q = $db->prepare($sql);
		$q->bindParam(":p", $p2, PDO::PARAM_INT);
		$q->bindParam(":id", $id, PDO::PARAM_INT);
		$q->execute();
		$sql = "update tasks set position = :p where id = :id"; //  меняем задачи местами для 2-ого случая
		$q = $db->prepare($sql);
		$q->bindParam(":p", $p1, PDO::PARAM_INT);
		$q->bindParam(":id", $sid, PDO::PARAM_INT);
		$q->execute();
	}
// добавение проекта
	function addProject($name) {
		$db = DataBase::getInstance();
		$sql = "insert into projects (name) values (:name)";
		$q = $db->prepare($sql);
		$q->bindParam(':name', $name, PDO::PARAM_STR);
		$q->execute();
	}
?>