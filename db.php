<?php
	class DataBase {
		private static $instance = null;
// получение данных дл доступа к БД
		private static function getParams() {  
			$set = file_get_contents("db.settings.ini");
			$set = split("\n", $set);
			$settings = array();
			$i=0;
			foreach ($set as $k => $v) {
				list($key, $value) = split(": ", $v);
				$settings[$i++] = $value;
			}
			return $settings;
		}

// если нет подключения, то создаем новый
		public static function getInstance() {
			if (!self::$instance) {
				$settings = DataBase::getParams();
				list($host, $db, $user, $password) = $settings;
				self::$instance = new PDO("mysql:host=$host;dbname=$db", $user, $password) or die("error");
				self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			}
			return self::$instance; 
		}
	}
?> 