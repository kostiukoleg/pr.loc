<?php
function __autoload($class_name) {
	if (file_exists("lib/" . $class_name . '.php')) {
		require_once ("lib/" . $class_name . '.php');
		return;
	}elseif(file_exists("classes/" . $class_name . '.php')){
		require_once ("classes/" . $class_name . '.php');
		return;
	}
}
?>