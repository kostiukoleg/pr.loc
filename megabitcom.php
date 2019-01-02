<?php
include("./lib/autoload.php");
include("./lib/exelReader.php");	
include("./lib/simple_html_dom.php");	
include("./csv/index.php");

set_time_limit(0);

if (!file_exists("tempimage")) {
	mkdir("tempimage", 0777, true);
}

Func::cleanDir("tempimage");

if(file_exists(realpath("data.csv"))){
	unlink(realpath("data.csv"));
}

fopen("data.csv", "a");
$csv = new CSV(realpath("data.csv"));
$csv->setCSV(Array(mb_convert_encoding("ID товара~Артикул~Категория~URL категории~Товар~Вариант~Краткое описание~Описание~Цена~Старая цена~URL товара~Изображение~Количество~Активность~Заголовок [SEO]~Ключевые слова [SEO]~Описание [SEO]~Рекомендуемый~Новый~Сортировка~Вес~Связанные артикулы~Смежные категории	~Ссылка на товар~Валюта~Единицы измерения~Длина~Ширина~Толщина~Основная камера~Частота кадров~Разрешение видео~Фронтальная камера~Разрешение экрана~Диагональ~Тип экрана~Оперативная память~Количество ядер~Емкость аккумулятора~Процессор~Поддержка MicroSD~Количество SIM-карт~Версия ОС~Вес~Максимальный объём памяти~Размеры~Вес [prop attr=42]~Тип батареи~Точность хода~Водонепроницаемость~Объём памяти [size]~Цвет[prop attr=Смартфоны] [color]~Цвет [color]~Размер[prop attr=Детская обувь] [size]~Размер обуви [size]~Размер [size]~Размер[prop attr=Леггинсы] [size]~Окончание акции (дд.мм.гггг)~Год изготовления[prop attr=Шины] [prop attr=51]~Сложные характеристики
", 'windows-1251', 'UTF-8')));

$xpath_product_link = "//div[@class='nd__line nd__masView--about']/a[@class='nd__tabView--name nd__masView--name']";
$xpath_product_category = "//div[@id='ajax_breadcrumbs']";
$xpath_title = "//div[@class='nd__line nd__masView--about']/a[@class='nd__tabView--name nd__masView--name']";
$xpath_price = '//div[@class="nd__line clearfix nd__masView--priceWrap"]/div[@class="nd__mainPrice nd__masView--mainPrice nd__floatRigth"]';
$xpath_img = '//div[@class="nd__masView--item--contain"]/a[@class="nd__masView--pic"]/img';
$xpath_main_img = '//div.mainInfo div.fotorama_new div.fotorama_new__container div.fotorama_new__item a.mainPic img';
$xpath_product_description = '//div.newDes__tabContainer div.wts div.newDes__tabMinHeight table';
$xpath_product_articul = '//div[@class="nd__masView--item--contain"]/div[@class="nd__tabView--greyTxt"]';

$new_img = Array();
$main_arr = Array();

$h = Func::parseSite("https://msk.megabitcomp.ru/catalog/mobilnye_telefony/");
$x = new DOMXpath($h);

for ($i = 1; $i <= $x->query("//*/div[@class='nd__filter--list js-filtersList bx_filter_block']")->item(0)->childNodes->length; $i++) {
   
	if ( is_null($x->query("//*/div[@class='nd__filter--list js-filtersList bx_filter_block']")->item(0)->childNodes->item($i)) || $x->query("//*/div[@class='nd__filter--list js-filtersList bx_filter_block']")->item(0)->childNodes->item($i)->nodeType !== 1 ) { 
		continue;
	}
	
	if( !is_null($x->query("//*/div[@class='nd__filter--list js-filtersList bx_filter_block']")->item(0)->childNodes->item($i)->childNodes->item(1)) && $x->query("//*/div[@class='nd__filter--list js-filtersList bx_filter_block']")->item(0)->childNodes->item($i)->childNodes->item(1)->hasAttribute("name") ) {
		
		preg_match ("#filters\[brand\]\[([a-zA-Z' '()]+)\]$#", $x->query("//*/div[@class='nd__filter--list js-filtersList bx_filter_block']")->item(0)->childNodes->item($i)->childNodes->item(1)->getAttribute("name"), $brand);
		
		$s_url = "https://msk.megabitcomp.ru/catalog/mobilnye_telefony/?filters%5Bbrand%5D%5B".urlencode($brand[1])."%5D=Y";
		
		$b = $brand[1];

		$f = Func::parseSite($s_url);
		
		$xf = new DOMXpath($f);
		
		if ( is_object($xf->query("//div[@class='nd__pagination--arrowList clearfix']")) ) {
			$p = "&page=1";
		} else {
			$p = $xf->query("//div[@class='nd__pagination--arrowList clearfix']")->item(1)->childNodes->item(3)->getAttribute("href");
		}

		$str_url = $s_url.$p;
		$pages = Func::pageUrl($p);

		for($a=1; $a<=$pages; $a++){
			if($pages>1){
				$url = preg_replace("/&page?=\d+$/","&page={$a}",$str_url);
			} else {
				$url = $str_url;
			}
			
			$html = Func::parseSite($url);
			$xpath = new DOMXpath($html);
		
			for ($i = 0; $i < $xpath->query($xpath_product_link)->length; $i++) {
					$main_arr["links"][] = "https://reg.megabitcomp.ru".$xpath->query($xpath_product_link)->item($i)->getAttribute("href");
					$main_arr["brands"][] = $b;
					$main_arr["categories"][] = "Мобильная связь и телефония/".$main_arr["brands"][$i];//str_replace("Каталог товаров/", "", mb_convert_encoding(preg_replace( "/\s{2,}/", "", $xpath->query($xpath_product_category)->item(0)->textContent), 'windows-1252', 'UTF-8'))
					$main_arr["categories_url"][] = "mobilnaya-svyaz-i-telefoniya-mobilnye-telefony-smartfony/".Func::translitIt($main_arr["brands"][$i]);
					$main_arr["title"][] = preg_replace( "/\s{2,}/", "", mb_convert_encoding($xpath->query($xpath_product_link)->item($i)->nodeValue, 'windows-1252', 'UTF-8'));
					$main_arr["price"][] = preg_replace( "/\s{2,}р./", "", mb_convert_encoding($xpath->query($xpath_price)->item($i)->nodeValue, 'windows-1252', 'UTF-8'));
					$main_arr["articul"][] = preg_replace( "/Артикул: /", "", mb_convert_encoding($xpath->query($xpath_product_articul)->item($i)->nodeValue, 'windows-1252', 'UTF-8'));		
					$src = preg_replace( "/resize\/223x170\//", "", $xpath->query($xpath_img)->item($i)->getAttribute("src"));		
					$src = preg_replace( "/https\:\/\/office.megabitcomp.ru\/megabit_pic\/[0{4,}\-?]{5,}\.jpg$/", "70_no-img.jpg", $src);	
					$main_arr["main_img"][] = $src;	
			}
		}
	}
}

for($s = 0; $s < count($main_arr["links"]); $s++){
	$ht = Func::parseSite($main_arr["links"][$s]);
	$xp = new DOMXpath($ht);
		
	if($xp->query("//ul[@id='lightSliderTumb']/li/img")->length > 0){
		for( $j=0; $j < $xp->query("//ul[@id='lightSliderTumb']/li/img")->length; $j++ ){
			preg_match("/.[a-z]+$/", $xp->query("//ul[@id='lightSliderTumb']/li/img")->item($j)->getAttribute("src"), $matches);
			if($xp->query("//ul[@id='lightSliderTumb']/li/img")->item($j)->getAttribute("src")!="/../../i/vidPrev2.jpg"){
				if(exif_imagetype($xp->query("//ul[@id='lightSliderTumb']/li/img")->item($j)->getAttribute("src")) != IMAGETYPE_JPEG){
					if(copy($xp->query("//ul[@id='lightSliderTumb']/li/img")->item($j)->getAttribute("src"), "tempimage/".Func::normalizeString(Func::translitIt($main_arr["title"][$s]))."-".$j.$matches[0])){
						$main_arr["img"][$s][] = Func::normalizeString(Func::translitIt($main_arr["title"][$s]))."-".$j."png";
					}
				} else {
					if(copy($xp->query("//ul[@id='lightSliderTumb']/li/img")->item($j)->getAttribute("src"), "tempimage/".Func::normalizeString(Func::translitIt($main_arr["title"][$s]))."-".$j.$matches[0])){
						$main_arr["img"][$s][] = Func::normalizeString(Func::translitIt($main_arr["title"][$s]))."-".$j.".jpg";
					}
				}

			}
		}		
	} else {
		preg_match("/.[a-z]+$/", $main_arr["main_img"][$s], $match);
		if($main_arr["main_img"][$s] != "70_no-img.jpg"){
			if(copy($main_arr["main_img"][$s], "tempimage/".Func::normalizeString(Func::translitIt($main_arr["title"][$s])).$match[0])){
				$main_arr["main_img"][] = Func::normalizeString(Func::translitIt($main_arr["title"][$s])).$match[0];
			}	
		}
	}

	$prop_arr = explode("\n",preg_replace("/\t+/","",mb_convert_encoding($xp->query("//div[@class='wts']/div[@class='newDes__tabMinHeight']/table[@class='js-tabHeight tableDetails']")->item(0)->nodeValue, 'windows-1252', 'UTF-8')));
	$prop = "";
	for( $k=0; $k < count($prop_arr); $k++ ){
		if($prop_arr[$k] == "Вес" && !empty($prop_arr[$k])){
			$weight = $prop_arr[$k+1]; //Вес "2,27"
		} else {
			$weight = "";
		}
		if($k==0 && !empty($prop_arr[$k])){
			$prop = "{$prop_arr[$k]}[prop attr=телефон]=[type=assortmentCheckBox value={$prop_arr[$k+1]}##1## product_margin={$prop_arr[$k+1]}## activity=1 filter=1 description=]";
		} else {
			if($k%2==0 && !empty($prop_arr[$k])){
				$prop .= "&{$prop_arr[$k]}[prop attr=телефон]=[type=assortmentCheckBox value={$prop_arr[$k+1]}##1## product_margin={$prop_arr[$k+1]}## activity=1 filter=1 description=]";
			}
		}
	}     
	$main_arr["property"][] = $prop."&Бренд[prop attr=телефон]=[type=assortmentCheckBox value={$main_arr['brands'][$s]}##1## product_margin={$main_arr['brands'][$s]}## activity=1 filter=1 description=]";
	$prop = "";

	$category = mb_convert_encoding($main_arr["categories"][$s], 'windows-1251'); //Категория товара "Компьютерная техника/Компьютеры и ноутбуки/Ноутбуки"
	$url_category = $main_arr["categories_url"][$s]; //URL категории
	$goods = mb_convert_encoding($main_arr["title"][$s], 'windows-1251'); //Товар "Ноутбук Dell Inspiron N411Z"
	$options = ""; //Вариант "без чехла"
	$description = "";//str_replace('{$goods}',$goods,mb_convert_encoding(mysql_result($product_description,0), 'windows-1251', 'UTF-8'))
	$price = str_replace(" ", "", $main_arr["price"][$s]); //Цена "19000"
	$url = urlencode(Func::normalizeString(Func::translitIt($main_arr["title"][$s],1))); //URL "noutbuk-dell-inspiron-n411z"

	$img = "";

	if(isset($main_arr["img"][$s]) && is_array($main_arr["img"][$s])){
		foreach($main_arr["img"][$s] as $im){
		$img .= $im."[:param:][alt=$goods][title=$goods]|";
		}
		$img = substr($img, 0, -1);
	} else {
		$img .= $main_arr["main_img"][$s]."[:param:][alt=$goods][title=$goods]";	
	}

	//$img =  $main_arr["main_img"][$i]; //Изображение "noutbuk-Dell-Inspiron-N411Z.png[:param:][alt=ноутбук dell][title=ноутбук dell]|noutbuk-Dell-Inspiron-N411Z-oneside.png[:param:][alt=Ноутбук"	
	$articul = $main_arr["articul"][$s]; //Артикул "1000A"
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
	$propertis = mb_convert_encoding($main_arr["property"][$s], 'windows-1251');
	$csv->setCSV(array("~$articul~$category~$url_category~$goods~$options~~$description~$price~$old_price~$url~$img~$count~$activity~$title_seo~$kay_words~$description_seo~$reccomend~$new~$sort~$weight~$bind_articul~$neibor_category~$link_goods~$currency~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~$propertis"));
}
// $str_url = Func::getStrUrl();
// $pages = Func::pageUrl($str_url);


$host  = $_SERVER['HTTP_HOST'];
$uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
$extra = 'download.php';
header("Location: http://$host$uri/$extra");
exit();
?>