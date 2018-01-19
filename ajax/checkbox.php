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

$pure_site_link_chk=$_POST['pure_site_link_chk'];
if($pure_site_link_chk == "true"){
	$con->prepare('UPDATE html SET pure_site_link_chk=1 WHERE id=1',[PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION])->execute();
} elseif($pure_site_link_chk == "false") {
	$con->prepare('UPDATE html SET pure_site_link_chk=0 WHERE id=1',[PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION])->execute();
}
?>