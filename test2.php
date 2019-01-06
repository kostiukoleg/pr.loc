<?php
include __DIR__ . DIRECTORY_SEPARATOR ."lib/exelReader.php";	
include __DIR__ . DIRECTORY_SEPARATOR ."lib/simple_html_dom.php";	
include __DIR__ . DIRECTORY_SEPARATOR ."csv/index.php";

spl_autoload_register(function ($class_name) {
    include __DIR__ . DIRECTORY_SEPARATOR ."lib/".$class_name . '.php';
});

set_time_limit(0);

if (!extension_loaded('mbstring')) {
    dl('php_mbstring.dll');
}

if (!extension_loaded('curl')) {
	dl('php_curl.dll');
}

if (!extension_loaded('exif')) {
	dl('php_exif.dll');
}

if (!extension_loaded('openssl')) {
	dl('php_openssl.dll');
}

if (!file_exists(__DIR__ . DIRECTORY_SEPARATOR ."tempimage")) {
	mkdir(__DIR__ . DIRECTORY_SEPARATOR ."tempimage", 0777, true);
}

Func::cleanDir(__DIR__ . DIRECTORY_SEPARATOR ."tempimage");

if(file_exists(__DIR__ . DIRECTORY_SEPARATOR ."get_files/data.csv")){
	unlink(__DIR__ . DIRECTORY_SEPARATOR ."get_files/data.csv");
}

fopen(__DIR__ . DIRECTORY_SEPARATOR ."get_files/data.csv", "a");
$csv = new CSV(__DIR__ . DIRECTORY_SEPARATOR ."get_files/data.csv");
$csv->setCSV(Array(mb_convert_encoding("ID товара~Артикул~Категория~URL категории~Товар~Вариант~Краткое описание~Описание~Цена~Старая цена~URL товара~Изображение~Количество~Активность~Заголовок [SEO]~Ключевые слова [SEO]~Описание [SEO]~Рекомендуемый~Новый~Сортировка~Вес~Связанные артикулы~Смежные категории	~Ссылка на товар~Валюта~Единицы измерения~Длина~Ширина~Толщина~Основная камера~Частота кадров~Разрешение видео~Фронтальная камера~Разрешение экрана~Диагональ~Тип экрана~Оперативная память~Количество ядер~Емкость аккумулятора~Процессор~Поддержка MicroSD~Количество SIM-карт~Версия ОС~Вес~Максимальный объём памяти~Размеры~Вес [prop attr=42]~Тип батареи~Точность хода~Водонепроницаемость~Объём памяти [size]~Цвет[prop attr=Смартфоны] [color]~Цвет [color]~Размер[prop attr=Детская обувь] [size]~Размер обуви [size]~Размер [size]~Размер[prop attr=Леггинсы] [size]~Окончание акции (дд.мм.гггг)~Год изготовления[prop attr=Шины] [prop attr=51]~Сложные характеристики
", 'windows-1251', 'UTF-8')));

$test = new CSV(__DIR__ . DIRECTORY_SEPARATOR ."tempfile/test.csv");

$data = $test->getCSV();

for($i = 1; $i < count($data); $i++) {
	
	$main_arr = array();

	$ht = Func::parseSite($data[$i][0]);
	$xp = new DOMXpath($ht);

	if($xp->query("//ul[@id='lightSliderTumb']/li/img")->length > 0){
		for( $j = 0; $j < $xp->query("//ul[@id='lightSliderTumb']/li/img")->length; $j++ ){
			preg_match("/.[a-z]+$/", $xp->query("//ul[@id='lightSliderTumb']/li/img")->item($j)->getAttribute("src"), $matches);
			if($xp->query("//ul[@id='lightSliderTumb']/li/img")->item($j)->getAttribute("src")!="/../../i/vidPrev2.jpg"){
				if(exif_imagetype($xp->query("//ul[@id='lightSliderTumb']/li/img")->item($j)->getAttribute("src")) != IMAGETYPE_JPEG){
					if(copy($xp->query("//ul[@id='lightSliderTumb']/li/img")->item($j)->getAttribute("src"), __DIR__ . DIRECTORY_SEPARATOR ."tempimage/".Func::normalizeString(Func::translitIt(mb_convert_encoding($data[$i][4], 'UTF-8', 'windows-1251')))."-".$i."-".$j.".png")){
						$main_arr["img"][] = Func::normalizeString(Func::translitIt(mb_convert_encoding($data[$i][4], 'UTF-8', 'windows-1251')))."-".$i."-".$j.".png";
					}
				} else {
					if(copy($xp->query("//ul[@id='lightSliderTumb']/li/img")->item($j)->getAttribute("src"), __DIR__ . DIRECTORY_SEPARATOR ."tempimage/".Func::normalizeString(Func::translitIt(mb_convert_encoding($data[$i][4], 'UTF-8', 'windows-1251')))."-".$i."-".$j.".jpg")){
						$main_arr["img"][] = Func::normalizeString(Func::translitIt(mb_convert_encoding($data[$i][4], 'UTF-8', 'windows-1251')))."-".$i."-".$j.".jpg";
					}
				}

			}
		}		
	} else {
		preg_match("/.[a-z]+$/", $data[$i][7], $match);
		if($data[$i][7] != "70_no-img.jpg"){
			if(exif_imagetype($data[$i][7]) != IMAGETYPE_JPEG){
				if(copy($data[$i][7], __DIR__ . DIRECTORY_SEPARATOR ."tempimage/".Func::normalizeString(Func::translitIt(mb_convert_encoding($data[$i][4], 'UTF-8', 'windows-1251')))."-".$i.".png")){
					$main_arr["main_img"] = Func::normalizeString(Func::translitIt(mb_convert_encoding($data[$i][4], 'UTF-8', 'windows-1251')))."-".$i.".png";
				}	
			} else {
				if(copy($data[$i][7], __DIR__ . DIRECTORY_SEPARATOR ."tempimage/".Func::normalizeString(Func::translitIt(mb_convert_encoding($data[$i][4], 'UTF-8', 'windows-1251')))."-".$i.".jpg")){
					$main_arr["main_img"] = Func::normalizeString(Func::translitIt(mb_convert_encoding($data[$i][4], 'UTF-8', 'windows-1251')))."-".$i.".jpg";
				}	
			}
		} else {
			$main_arr["main_img"] = "70_no-img.jpg";
		}
	}

	$prop_arr = explode("\n",preg_replace("/\t+/","",mb_convert_encoding($xp->query("//div[@class='wts']/div[@class='newDes__tabMinHeight']/table[@class='js-tabHeight tableDetails']")->item(0)->nodeValue, 'windows-1252', 'UTF-8')));
	$prop = "";
	
	for( $k = 0; $k < count($prop_arr); $k++ ){

		if($prop_arr[$k] == "Вес" && !empty($prop_arr[$k])){
			$weight = $prop_arr[$k+1]; //Вес "2,27"
		} else {
			$weight = "";
		}

		if( $k == 0 && !empty($prop_arr[$k]) ){
			$prop = "{$prop_arr[$k]}[prop attr=телефон]=[type=assortmentCheckBox value={$prop_arr[$k+1]}##1## product_margin={$prop_arr[$k+1]}## activity=1 filter=1 description=]";
		} else {
			if($k%2==0 && !empty($prop_arr[$k])){
				$prop .= "&{$prop_arr[$k]}[prop attr=телефон]=[type=assortmentCheckBox value={$prop_arr[$k+1]}##1## product_margin={$prop_arr[$k+1]}## activity=1 filter=1 description=]";
			}
		}
	}     

	$main_arr["property"] = $prop."&Бренд[prop attr=телефон]=[type=assortmentCheckBox value={$data[$i][1]}##1## product_margin={$data[$i][1]}## activity=1 filter=1 description=]";
	$prop = "";

	$category = $data[$i][2]; //Категория товара "Компьютерная техника/Компьютеры и ноутбуки/Ноутбуки"
	$url_category = $data[$i][3]; //URL категории
	$goods = $data[$i][4]; //Товар "Ноутбук Dell Inspiron N411Z"
	$options = ""; //Вариант "без чехла"
	$description = "";//str_replace('{$goods}',$goods,mb_convert_encoding(mysql_result($product_description,0), 'windows-1251', 'UTF-8'))
	$price = $data[$i][5]; //Цена "19000"
	$url = urlencode(Func::normalizeString(Func::translitIt($goods,1))); //URL "noutbuk-dell-inspiron-n411z"

	$img = "";

	if(isset($main_arr["img"]) && is_array($main_arr["img"])){
		foreach($main_arr["img"] as $im){
		$img .= $im."[:param:][alt=$goods][title=$goods]|";
		}
		$img = substr($img, 0, -1);
	} else {
		$img .= $main_arr["main_img"]."[:param:][alt=$goods][title=$goods]";	
	}

	//$img =  $main_arr["main_img"][$i]; //Изображение "noutbuk-Dell-Inspiron-N411Z.png[:param:][alt=ноутбук dell][title=ноутбук dell]|noutbuk-Dell-Inspiron-N411Z-oneside.png[:param:][alt=Ноутбук"	
	$articul = $data[$i][6]; //Артикул "1000A"
	$count = "-1"; //Количество "-1 нет на складе"
	$activity = "1"; //Активность 1 включон 0 выключен
	$title_seo = ""; //Заголовок [SEO]
	$kay_words = ""; //Ключевые слова [SEO]
	$description_seo = ""; //Описание [SEO]
	$old_price = ""; //Старая цена
	$reccomend = "0"; //Рекомендуемый
	$new = "0"; //Новый
	$sort = ""; //Сортировка
	$bind_articul = ""; //Связанные артикулы
	$neibor_category = ""; //Смежные категории
	$link_goods = ""; //Ссылка на товар
	$currency = "RUR"; //Валюта
	$propertis = mb_convert_encoding($main_arr["property"], 'windows-1251');
	$csv->setCSV(array("~$articul~$category~$url_category~$goods~$options~~$description~$price~$old_price~$url~$img~$count~$activity~$title_seo~$kay_words~$description_seo~$reccomend~$new~$sort~$weight~$bind_articul~$neibor_category~$link_goods~$currency~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~$propertis"));
}
?>