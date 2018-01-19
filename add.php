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

$site_name = $con->query("SELECT site_name FROM html WHERE id=".$site_id, PDO::FETCH_ASSOC)->fetch()['site_name'];
//Func::printData($site_name);
if (!empty($_POST)) {
	$str_key ="(";
	$str_value="(";
	
	foreach ($_POST as $key => $value){		
		if(!empty($value)){
			$str_key .= $key .", ";
			if($value == "on"){
				$value = "1";
			}
			$str_value .= $con->quote($value).', ';
		}else{
			$str_key .= $key .", ";
			$str_value .= $con->quote($value).', ';
		}
	}
	
	$str_key = substr($str_key,0, -2).")";
	$str_value = substr($str_value,0, -2).")";
	
	if(!empty($_POST['site_name'])){
		$query = "INSERT INTO html ".$str_key." VALUES ".$str_value;	
		$con->prepare($query,[PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION])->execute();
	}
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">
  </head>
  <body>
  	<p>
      <a href="/index.php">На головну</a>
    </p>
<form class="bd-example" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
  <fieldset>
    <legend>Добавить сайт в базу парсера</legend>
	
    <p>
      <label for="select">Назва сайту, що потрібно парсити*<input type="text" name="site_name" placeholder="<?php echo $site_name; ?>"></label>
    </p>
	<p>
      <label>
        <input type="checkbox" name="pure_site_link_chk" id="pure_site_link_chk">
        Чистий лінк на сайт, якщо потрібно
      </label>
	  <span class="result"></span>
	</p>
	<p>
	  <label for="input">Чистий лінк на сайт
      <input type="text" name="pure_site_link" placeholder="<?php echo $pure_site_link; ?>"></label>
    </p>
    <p>
      <label for="input">Ссилка на сайт що потрібно спарсити</label>
      <input type="text" name="parse_link" placeholder="<?php echo $parse_link ;?>">
    </p>
	
    <p>
      <label for="input">Xpath на Title</label>
      <input type="text" name="xpath_title" placeholder="<?php echo $xpath_title; ?>">
    </p>
	
    <p>
      <label for="input">Xpath на цену</label>
      <input type="text" name="xpath_price" placeholder="<?php echo $xpath_price; ?>">
    </p>
		
    <p>
      <label for="input">Xpath на багато картинок</label>
      <input type="text" id="input" name="xpath_img" placeholder="<?php echo $xpath_img;?>">
    </p>
	
	<p>
      <label for="input">Xpath на одну картинку</label>
      <input type="text" id="input" name="xpath_main_img" placeholder="<?php echo $xpath_main_img;?>">
    </p>
		
    <p>
      <label for="input">Xpath на товар</label>
      <input type="text" name="xpath_product_link" placeholder="<?php echo $xpath_product_link; ?>">
    </p>
	
	<p>
      <label for="input">Xpath на описание товара</label>
      <input type="text" id="input" name="xpath_product_description" placeholder="<?php echo $xpath_product_description;?>">
    </p>
	
	<p>
      <label for="input">Категорія на сайті</label>
      <input type="text" name="product_category" placeholder="<?php echo $product_category; ?>">
    </p>
		
	<p>
      <label for="input">URL Категорії на сайті</label>
      <input type="text" name="product_url_category" placeholder="<?php echo $product_url_category; ?>">
    </p>

    <p>
      <label for="textarea">Описание товара</label>
      <textarea id="textarea" name="product_description" rows="8" class="form-control col-xs-12"><?php echo $product_description; ?></textarea>
    </p>


    <p>
      <input type="submit" value="Створити">
    </p>
  </fieldset>
</form>
    <!-- jQuery first, then Tether, then Bootstrap JS. -->
    <script src="js/jquery-3.2.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script>
	<script src="js/javascript.js"></script>
  </body>
</html>