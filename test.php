<?php
include("./lib/autoload.php");
include("./lib/exelReader.php");	
include("./lib/simple_html_dom.php");	
include("./csv/index.php");

set_time_limit(0);

$xpath_product_link = "//div[@class='nd__line nd__masView--about']/a[@class='nd__tabView--name nd__masView--name']";
$xpath_product_category = "//div[@id='ajax_breadcrumbs']";
$xpath_title = "//div[@class='nd__line nd__masView--about']/a[@class='nd__tabView--name nd__masView--name']";
$xpath_price = '//div[@class="nd__line clearfix nd__masView--priceWrap"]/div[@class="nd__mainPrice nd__masView--mainPrice nd__floatRigth"]';
$xpath_img = '//div[@class="nd__masView--item--contain"]/a[@class="nd__masView--pic"]/img';
$xpath_main_img = '//div.mainInfo div.fotorama_new div.fotorama_new__container div.fotorama_new__item a.mainPic img';
$xpath_product_description = '//div.newDes__tabContainer div.wts div.newDes__tabMinHeight table';
$xpath_product_articul = '//div[@class="nd__masView--item--contain"]/div[@class="nd__tabView--greyTxt"]';

if(file_exists(realpath("test.csv"))){
	unlink(realpath("test.csv"));
}

fopen("test.csv", "a");
$csv = new CSV(realpath("test.csv"));
$csv->setCSV(Array(mb_convert_encoding("links~brands~categories~categories_url~title~price~articul", 'windows-1251', 'UTF-8')));

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
		
			for ($i = 0; $i < 10; $i++) {
                $links = "https://reg.megabitcomp.ru".$xpath->query($xpath_product_link)->item($i)->getAttribute("href");
                $brands = $b;
                $categories = "Мобильная связь и телефония/".$main_arr["brands"][$i];//str_replace("Каталог товаров/", "", mb_convert_encoding(preg_replace( "/\s{2,}/", "", $xpath->query($xpath_product_category)->item(0)->textContent), 'windows-1252', 'UTF-8'))
                $categories_url = "mobilnaya-svyaz-i-telefoniya-mobilnye-telefony-smartfony/".Func::translitIt($main_arr["brands"][$i]);
                $title = preg_replace( "/\s{2,}/", "", mb_convert_encoding($xpath->query($xpath_product_link)->item($i)->nodeValue, 'windows-1252', 'UTF-8'));
                $price = preg_replace( "/\s{2,}р./", "", mb_convert_encoding($xpath->query($xpath_price)->item($i)->nodeValue, 'windows-1252', 'UTF-8'));
                $articul = preg_replace( "/Артикул: /", "", mb_convert_encoding($xpath->query($xpath_product_articul)->item($i)->nodeValue, 'windows-1252', 'UTF-8'));		
                $src = preg_replace( "/resize\/223x170\//", "", $xpath->query($xpath_img)->item($i)->getAttribute("src"));		
                $src = preg_replace( "/https\:\/\/office.megabitcomp.ru\/megabit_pic\/[0{4,}\-?]{5,}\.jpg$/", "70_no-img.jpg", $src);	
                $main_img = $src;	
                $csv->setCSV(array("$links~$brands~$categories~$categories_url~$title~$price~$articul~$main_img"));
			}
		}
	}
}
?>