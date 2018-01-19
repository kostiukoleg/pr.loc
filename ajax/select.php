<?php 
function __autoload($class_name) {
	if (file_exists("../lib/" . $class_name . '.php')) {
		require_once ("../lib/" . $class_name . '.php');
		return;
	}elseif(file_exists("../classes/" . $class_name . '.php')){
		require_once ("../classes/" . $class_name . '.php');
		return;
	}
}

$con = new ConPDO("../setting.ini");

$site_id=$_POST['site_id'];
$con->prepare('UPDATE sites SET site_id='.$site_id.' WHERE id=1',[PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION])->execute();
?>