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

include("./lib/simple_html_dom.php");	
include("./csv/index.php");

$con = new ConPDO();

$site_id = $con->query('SELECT site_id FROM sites', PDO::FETCH_ASSOC)->fetch()['site_id'];

$product_description = $con->query('SELECT product_description FROM html  WHERE id='.$site_id, PDO::FETCH_ASSOC)->fetch()['product_description'];
$parse_link = $con->query('SELECT parse_link FROM html WHERE id='.$site_id, PDO::FETCH_ASSOC)->fetch()['parse_link'];
$xpath_product_link = $con->query('SELECT xpath_product_link FROM html WHERE id='.$site_id, PDO::FETCH_ASSOC)->fetch()['xpath_product_link'];
$xpath_product_description = $con->query('SELECT xpath_product_description FROM html WHERE id='.$site_id, PDO::FETCH_ASSOC)->fetch()['xpath_product_description'];
$xpath_title = $con->query('SELECT xpath_title FROM html WHERE id='.$site_id, PDO::FETCH_ASSOC)->fetch()['xpath_title'];
$xpath_img = $con->query('SELECT xpath_img FROM html WHERE id='.$site_id, PDO::FETCH_ASSOC)->fetch()['xpath_img'];
$xpath_main_img = $con->query('SELECT xpath_main_img FROM html WHERE id='.$site_id, PDO::FETCH_ASSOC)->fetch()['xpath_main_img'];
$product_category = $con->query('SELECT product_category FROM html WHERE id='.$site_id, PDO::FETCH_ASSOC)->fetch()['product_category'];
$product_url_category = $con->query('SELECT product_url_category FROM html WHERE id='.$site_id, PDO::FETCH_ASSOC)->fetch()['product_url_category'];
$pure_site_link_chk = $con->query('SELECT pure_site_link_chk FROM html WHERE id='.$site_id, PDO::FETCH_ASSOC)->fetch()['pure_site_link_chk'];
$pure_site_link = $con->query('SELECT pure_site_link FROM html WHERE id='.$site_id, PDO::FETCH_ASSOC)->fetch()['pure_site_link'];
$xpath_price = $con->query('SELECT xpath_price FROM html WHERE id='.$site_id, PDO::FETCH_ASSOC)->fetch()['xpath_price'];

Func::cleanDir("tempimage");

if(file_exists(realpath("data.csv"))){
	unlink(realpath("data.csv"));
}
fopen("data.csv", "a");

$csv = new CSV(realpath("data.csv"));

$csv->setCSV(Array("Категория~URL категории~Товар~Вариант~Описание~Цена~URL~Изображение~Артикул~Количество~Активность~Заголовок [SEO]~Ключевые слова [SEO]~Описание [SEO]~Старая цена~Рекомендуемый~Новый~Сортировка~Вес~Связанные артикулы~Смежные категории~Ссылка на товар~Валюта~Свойства"));

$html = file_get_html($parse_link);

$k = 0;
$new_img = Array();
$main_arr = Array();

foreach($html->find($xpath_product_link) as $element) {
	$main_arr["link"][] = $pure_site_link_chk == 1 ? $pure_site_link.$element->href : $element->href;
}

foreach($html->find($xpath_title) as $element) {
	$main_arr["title"][] = $element->plaintext;
}
if($xpath_price == "null"){
	for( $i=0; $i<count($html->find($xpath_product_link)); $i++) {
		$main_arr["price"][] = 0;
	}
}else{
	foreach($html->find($xpath_price) as $element) {
		$main_arr["price"][] = $element->plaintext;
	}
}

if (!file_exists("tempimage")) {
		mkdir("tempimage", 0777, true);
}

foreach($main_arr["link"] as $ln){
	
	$ht = file_get_html( $ln );
	
	if($xpath_product_description == "null"){
		$main_arr["xpath_product_description"][] = $product_description;
	}else{
		foreach($ht->find($xpath_product_description) as $element) {
			$main_arr["xpath_product_description"][] = $element->outertext;
		}
	}
	if($ht->find($xpath_img)){
		foreach($ht->find($xpath_img) as $el) {
		
			if($el->src==false) continue;
			
			//$img_link_src = preg_replace("/node-list/", "node", $el->src);
			
			preg_match("/[a-zA-z\-0-9() ]+.[a-zA-z\-0-9]+$/",$el->src,$new_img);

			if (copy($el->src,"tempimage/".$new_img[0])) {//$new_img[0]
				$main_arr["img"][$k][] = $new_img[0];	//$img_link_src $new_img[0]		
			}
		}
	}else{
		foreach($ht->find($xpath_main_img) as $el) {
		
			if($el->src==false) continue;
			
			//$img_link_src = preg_replace("/node-list/", "node", $el->src);
			
			preg_match("/[a-zA-z\-0-9() ]+.[a-zA-z\-0-9]+$/",$el->src,$new_img);
			if (copy($el->src,"tempimage/".$new_img[0])) {//$new_img[0]
				$main_arr["img"][$k][] = $new_img[0];		
			}			
		}
	}
	
	$category = mb_convert_encoding($product_category, 'windows-1251', 'UTF-8'); //Категория товара "Компьютерная техника/Компьютеры и ноутбуки/Ноутбуки"
	$url_category = $product_url_category; //URL категории
	$goods = mb_convert_encoding($main_arr["title"][$k], 'windows-1251', 'UTF-8'); //Товар "Ноутбук Dell Inspiron N411Z"
	$options = ""; //Вариант "без чехла"
	$description = mb_convert_encoding(preg_replace( "/\r|\n/", "", $main_arr["xpath_product_description"][$k] ), 'windows-1251', 'UTF-8');//str_replace('{$goods}',$goods,mb_convert_encoding(mysql_result($product_description,0), 'windows-1251', 'UTF-8'))
	$price = substr($main_arr["price"][$k], 0, -2); //Цена "19000"
	$url = ""; //URL "noutbuk-dell-inspiron-n411z"
	$img = ""; //Изображение "noutbuk-Dell-Inspiron-N411Z.png[:param:][alt=ноутбук dell][title=ноутбук dell]|noutbuk-Dell-Inspiron-N411Z-oneside.png[:param:][alt=Ноутбук"

	if(is_array($main_arr["img"][$k])){
		foreach($main_arr["img"][$k] as $im){
		$img .= $im."[:param:][alt=$goods][title=$goods]|";
		}
		$img = substr($img, 0, -1);
	} else {
		$img .= $main_arr["img"][$k]."[:param:][alt=$goods][title=$goods]";
	}
	
	$articul = ""; //Артикул "1000A"
	$count = "-1"; //Количество "-1 нет на складе"
	$activity = "1"; //Активность 1 включон 0 выключен
	$title_seo = ""; //Заголовок [SEO]
	$kay_words = ""; //Ключевые слова [SEO]
	$description_seo = ""; //Описание [SEO]
	$old_price = ""; //Старая цена
	$reccomend = "0"; //Рекомендуемый
	$new = "0"; //Новый
	$sort = ""; //Сортировка
	$weight = "0,27"; //Вес "2,27"
	$bind_articul = ""; //Связанные артикулы
	$neibor_category = ""; //Смежные категории
	$link_goods = ""; //Ссылка на товар
	$currency = "UAH"; //Валюта

	$pr = array(
		 "PB\/SB" => "полированная латунь / матовая латунь",
		 "MACC" => "матовая бронза",
		 "AB" => "старая бронза",
		 "SN\/CP" => "матовый никель / полированный хром",
		 "MBN" => "матовая темная сталь",
		 "White" => "белый",
		 "MOC" => "матовый старый хром",
		 "MA" => "матовый антрацит",
		 "MC" => "матовый хром",
		 "BN\/SBN" => "черный никель / матовый черный никель",
		 "BLACK" => "черный",
		 "CP" => "полированный хром",
		 "PCF" => "полированная бронза",
		 "MACC\/PCF" => "матова бронза/полірована бронза",
		 "MCF" => "матовая темная бронза",
		 "SN" => "матовый никель",
		 "SS" => "нержавеющая сталь",
		 "BN" => "черный никель",
	);
	
	foreach($pr as $p => $v){
		if(preg_match("/\s".$p."$/",$main_arr["title"][$k])){
			$propertis = "Цвет покрытия=$v"; //Свойства
		}else{
			$propertis = "";
		}
	}

	$csv->setCSV(array("$category~$url_category~$goods~$options~$description~$price~$url~$img~$articul~$count~$activity~$title_seo~$kay_words~$description_seo~$old_price~$reccomend~$new~$sort~$weight~$bind_articul~$neibor_category~$link_goods~$currency~$propertis"));
	
	$k++;
}	
Func::printData($main_arr);
$directory = "./tempimage";    // Папка с изображениями
$allowed_types=array("jpg", "png", "gif");  //разрешеные типы изображений
$file_parts = array();
$ext="";
$title="";
$i=0;
//пробуем открыть папку
$dir_handle = @opendir($directory) or die("Ошибка при открытии папки !!!");
while ($file = readdir($dir_handle))    //поиск по файлам
  {
  if($file=="." || $file == "..") continue;  //пропустить ссылки на другие папки
  $file_parts = explode(".",$file);          //разделить имя файла и поместить его в массив
  $ext = strtolower(array_pop($file_parts));   //последний элеменет - это расширение
  if(in_array($ext,$allowed_types))
  {
 $i++;
  }
  $images[] = $file;
  }

closedir($dir_handle);  //закрыть папку

$error = "";
if(isset($_POST['createpdf']))
{

$file_folder = "tempimage/"; // папка с файлами
if(extension_loaded('zip'))
{
if(isset($images) and count($images) > 0)
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
$zip->addFile($file_folder.$file); // добавляем файлы в zip архив
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
print_r("<a href='/index.php'>Home</a><br>");
print_r("<a href='/data.csv'>Download data.csv parser file</a><bt>");
print_r("<form name='zips' method='post'><p><input type='submit' name='createpdf' value='Download Images as ZIP' /></p></form>");
?>