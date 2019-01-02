<?php
$directory = "./tempimage";
$allowed_types=array("jpg", "png", "gif");
$file_parts = array();
$ext="";
$title="";
$i=0;
$dir_handle = @opendir($directory) or die("Невозможно открыть папку !!!");
while ($file = readdir($dir_handle)){ 
  if($file=="." || $file == "..") continue; 
  $file_parts = explode(".",$file);
  $ext = strtolower(array_pop($file_parts));
  if(in_array($ext,$allowed_types)){
	$i++;
  }
  $images[] = $file;
}
closedir($dir_handle);
$error = "";
if(isset($_POST['createpdf']))
{

if(extension_loaded('zip'))
{
if(isset($images) && count($images) > 0)
{
// проверяем выбранные файлы
$zip = new ZipArchive(); // подгружаем библиотеку zip
$zip_name = time().".zip"; // имя файла
if($zip->open($zip_name, ZIPARCHIVE::CREATE)!==TRUE)
{

$error .= "* Sorry ZIP creation failed at this time";
}
foreach($images as $file)
{
$zip->addFile("./tempimage/".$file, $file); // добавляем файлы в zip архив
}
$zip->close();

if(file_exists($zip_name))
{

// отдаём файл на скачивание
header('Content-type: application/zip');
header('Content-Disposition: attachment; filename="'.$zip_name.'"');
readfile($zip_name);
// удаляем zip файл если он существует
unlink($zip_name);
}
}
else
$error .= "* Please select file to zip ";
}
else
$error .= "* You dont have ZIP extension";
}
print_r("<a href='/'>Главная</a><br>");
print_r("<a href='/data.csv'>Скачать data.csv спарсеный файл</a><br>");
print_r("<form name='zips' method='post'><p><input type='submit' name='createpdf' value='Скачать архив картинок' /></p></form>");
?>