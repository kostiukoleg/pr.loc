<?php
class Func {

    public static function getStrUrl() {
        if(isset($_POST["parse_link"])){
            $str_url = $_POST["parse_link"];
        }
        return $str_url;
    }

    public static function pageUrl($str_url) {
        preg_match("/&page?=\d+$/",$str_url,$m);
        if(isset($m[0])){
            $pages = (int)str_replace("&page=","",$m[0]);
        } else {
            $pages = 1;
        }
        return $pages;
    }

    public static function normalizeString ($str = '')
    {
        $str = strip_tags($str); 
        $str = preg_replace('/[\r\n\t ]+/', ' ', $str);
        $str = preg_replace('/[\"\*\/\:\<\>\?\'\|]+/', ' ', $str);
        $str = strtolower($str);
        $str = html_entity_decode( $str, ENT_QUOTES, "utf-8" );
        $str = htmlentities($str, ENT_QUOTES, "utf-8");
        $str = preg_replace("/(&)([a-z])([a-z]+;)/i", '$2', $str);
        $str = str_replace(' ', '-', $str);
        $str = rawurlencode($str);
        $str = str_replace('%', '-', $str);
        return $str;
    }

    public static function innerHTML($node) {
        if(is_object($node)){
        return implode(array_map([$node->ownerDocument,"saveHTML"], 
                             iterator_to_array($node->childNodes)));
        }
    }

	public static function outHTML($node) {
    $html = '';
      $children = $node->childNodes;

      foreach ($children as $child) {
        $tmp_doc = new DOMDocument();
        $tmp_doc->appendChild($tmp_doc->importNode($child,true));
        $html .= $tmp_doc->saveHTML();
      } 
      
    return $html;
	}
		
	public static function parseSite($link){
		$ch = curl_init($link);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		/* 
		 * XXX: This is not a "fix" for your problem, this is a work-around.  You 
		 * should fix your local CAs 
		 */
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

		/* Set a browser UA so that we aren't told to update */
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/34.0.1847.116 Safari/537.36');

		$res = curl_exec($ch);

		if ($res === false) {
			die('error: ' . curl_error($ch));
		}

		curl_close($ch);

		$html = new DOMDocument();
		@$html->loadHTML($res);
		return $html;
	}
	
	public static function cleanDir($dir=""){
		if (file_exists($dir))
        foreach (glob($dir.'/*') as $file)
        unlink($file);
    }
    
    public static function deleteDir($dirPath) {
        if (! is_dir($dirPath)) {
            throw new InvalidArgumentException("$dirPath must be a directory");
        }
        if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
            $dirPath .= '/';
        }
        $files = glob($dirPath . '*', GLOB_MARK);
        foreach ($files as $file) {
            if (is_dir($file)) {
                self::deleteDir($file);
            } else {
                unlink($file);
            }
        }
        rmdir($dirPath);
    }
	
    public static function minifyHTML($html){
		return preg_replace('/\s+/', ' ', $html);
	}
	
    public static function printArray($data) {
        $str ="Array(<br>";
        $i = 0;
        foreach ($data as $value) {
           $str .= '"';
           $str .= trim($value,"&nbsp;");
           $str = preg_replace("#\"\s+#", '"', $str);
           $str .= '"';
           if($i<count($data)-1){
               $str .=",<br>";
           }
           $i++;
        }
        $str .= "<br>);";
        print_r($str);
    }
    
    public static function printData($data) {
        echo '<pre>' . print_r($data, true) . '</pre>';
		exit();
    }

    public static function wrapArr($el) {
        return "`{$el}`";
    }

    public static function clean($string) {
        $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
     
        return preg_replace('/[^А-Яа-я0-9\-]/', '', $string); // Removes special chars.
    }

    public static function translitIt($str, $mode = 0) {
        $simb = '-';
        if ($mode == 1) {
            $simb = '/';
        }
        $tr = array(
            'А' => 'a',
            'Б' => 'b',
            'В' => 'v',
            'Г' => 'g',
            'Д' => 'd',
            'Е' => 'e',
            'Ё' => 'yo',
            'Ж' => 'j',
            'З' => 'z',
            'И' => 'i',
            'Й' => 'y',
            'К' => 'k',
            'Л' => 'l',
            'М' => 'm',
            'Н' => 'n',
            'О' => 'o',
            'П' => 'p',
            'Р' => 'r',
            'С' => 's',
            'Т' => 't',
            'У' => 'u',
            'Ф' => 'f',
            'Х' => 'h',
            'Ц' => 'ts',
            'Ч' => 'ch',
            'Ш' => 'sh',
            'Щ' => 'sch',
            'Ъ' => '',
            'Ы' => 'y',
            'Ь' => '',
            'Э' => 'e',
            'Ю' => 'yu',
            'Я' => 'ya',
            'а' => 'a',
            'б' => 'b',
            'в' => 'v',
            'г' => 'g',
            'д' => 'd',
            'е' => 'e',
            'ё' => 'yo',
            'ж' => 'j',
            'з' => 'z',
            'и' => 'i',
            'й' => 'y',
            'к' => 'k',
            'л' => 'l',
            'м' => 'm',
            'н' => 'n',
            'о' => 'o',
            'п' => 'p',
            'р' => 'r',
            'с' => 's',
            'т' => 't',
            'у' => 'u',
            'ф' => 'f',
            'х' => 'h',
            'ц' => 'ts',
            'ч' => 'ch',
            'ш' => 'sh',
            'щ' => 'sch',
            'ъ' => '',
            'ы' => 'y',
            'ь' => '',
            'э' => 'e',
            'ю' => 'yu',
            'я' => 'ya',
            '/' => $simb,
            '1' => '1',
            '2' => '2',
            '3' => '3',
            '4' => '4',
            '5' => '5',
            '6' => '6',
            '7' => '7',
            '8' => '8',
            '9' => '9',
            '0' => '0',
            'І' => 'i',
            'Ї' => 'i',
            'Є' => 'e',
            'Ґ' => 'g',
            'і' => 'i',
            'ї' => 'i',
            'є' => 'e',
            'ґ' => 'g',
            ' ' => '-',
            '*' => '_s',
            ',' => '',
            '"' => '',
            '.' => '',
            '[' => '',
            ']' => '',
        );

        return strtr($str, $tr);
    }

    public static function readProductDescription($fileName) {
        $myfile = fopen($fileName, "r") or die("Unable to open file!");
        echo fread($myfile, filesize($fileName));
        fclose($myfile);
    }

    public static function prepareUrl($str, $product = false, $toLower = true) {
        if ($toLower) {
            $str = strtolower($str);
        }

        $str = preg_replace('%\s%i', '-', $str);
        $str = str_replace('`', '', $str);
        $str = str_replace(array("\\", "<", ">"), "", $str);
        if ($product) {
            $pattern = '%[^/-a-zа-я\d]%i';
        } else {
            $pattern = '%[^/-a-zа-я\.\d]%i';
        }
        $str = preg_replace($pattern, '', $str);
        $str = substr($str, 0, 255);
        return $str;
    }

    public static function buildQuery($query, $array, $devide = ',') {

        if (is_array($array)) {
            $partQuery = '';

            foreach ($array as $index => $value) {

                if (is_numeric($value)) {
                    $partQuery .= ' `' . self::quote($index, true) . '` = ' . self::quote($value, true) . '' . $devide;
                } else {
                    $partQuery .= ' `' . self::quote($index, true) . '` = "' . self::quote($value, true) . '"' . $devide;
                }
            }

            $partQuery = trim($partQuery, $devide);
            $query .= $partQuery;

            return self::query($query);
        }
        return false;
    }

    public static function addProduct($array, $clone = false) {

        if (empty($array['title'])) {
            return false;
        }

        $userProperty = $array['userProperty'];
        $variants = !empty($array['variants']) ? $array['variants'] : array(); // варианты товара
        unset($array['userProperty']);
        unset($array['variants']);
        unset($array['count_sort']);
        if (empty($array['id'])) {
            unset($array['id']);
        }

        $result = array();

        $array['url'] = empty($array['url']) ? MG::translitIt($array['title']) : $array['url'];


        $maskField = array('title', 'meta_title', 'meta_keywords', 'meta_desc', 'image_title', 'image_alt');

        foreach ($array as $k => $v) {
            if (in_array($k, $maskField)) {
                $v = htmlspecialchars_decode($v);
                $array[$k] = htmlspecialchars($v);
            }
        }


        if (!empty($array['url'])) {
            $array['url'] = URL::prepareUrl($array['url']);
        }

        // Исключает дублирование.
        $dublicatUrl = false;
        $tempArray = $this->getProductByUrl($array['url']);
        if (!empty($tempArray)) {
            $dublicatUrl = true;
        }

        if ($array['weight']) {
            $array['weight'] = (double) str_replace(array(',', ' '), array('.', ''), $array['weight']);
        }

        if ($array['price']) {
            $array['price'] = (double) str_replace(array(',', ' '), array('.', ''), $array['price']);
        }


        $array['sort'] = 0;
        $array['system_set'] = 1;

        if (DB::buildQuery('INSERT INTO `' . PREFIX . 'product` SET ', $array)) {
            $id = DB::insertId();


            // Если url дублируется, то дописываем к нему id продукта.
            if ($dublicatUrl) {
                $url_explode = explode('_', $array['url']);
                if (count($url_explode) > 1) {
                    $array['url'] = str_replace('_' . array_pop($url_explode), '', $array['url']);
                }
                $this->updateProduct(array('id' => $id, 'url' => $array['url'] . '_' . $id, 'sort' => $id));
            } else {
                $this->updateProduct(array('id' => $id, 'url' => $array['url'], 'sort' => $id));
            }

            $array['id'] = $id;
            $array['sort'] = (int) $id;
            $array['userProperty'] = $userProperty;
            $userProp = array();

            if ($clone) {
                if (!empty($userProperty)) {
                    foreach ($userProperty as $property) {
                        $userProp[$property['property_id']] = $property['value'];
                        if (!empty($property['product_margin'])) {
                            $userProp[("margin_" . $property['property_id'])] = $property['product_margin'];
                        }
                    }
                    $userProperty = $userProp;
                }
            }

            if (!empty($userProperty)) {
                $this->saveUserProperty($userProperty, $id);
            }

            // Обновляем и добавляем варианты продукта.      
            $this->saveVariants($variants, $id);
            $variants = $this->getVariants($id);
            foreach ($variants as $variant) {
                $array['variants'][] = $variant;
            }

            $tempProd = $this->getProduct($id);
            $array['category_url'] = $tempProd['category_url'];
            $array['product_url'] = $tempProd['product_url'];

            $result = $array;
        }

        $this->updatePriceCourse($currencyShopIso, array($result['id']));

        $args = func_get_args();
        return MG::createHook(__CLASS__ . "_" . __FUNCTION__, $result, $args);
    }

    public static function saveUserProperty($userProperty, $id, $type = 'select') {

        $userProperty = $this->preProcessUserProperty($userProperty);

        foreach ($userProperty as $propertyId => $value) {
            $propertyId = (int) $propertyId;
            // Проверяем существует ли запись в базе о текущем свойстве.
            $res = DB::query("
        SELECT * FROM `" . PREFIX . "product_user_property`
        WHERE property_id = " . DB::quote($propertyId) . "
          AND product_id = " . DB::quote($id)
            );

            // Обновляем значение свойства если оно существовало.
            if (DB::numRows($res) && is_array($value)) {
                if (!is_array($value)) {
                    DB::query("
            UPDATE `" . PREFIX . "product_user_property`
            SET value = " . DB::quote(trim($value)) . "
            WHERE property_id = " . DB::quote($propertyId) . "
              AND product_id = " . DB::quote($id)
                    );
                } else {
                    DB::query("
            UPDATE `" . PREFIX . "product_user_property`
            SET value = " . DB::quote(trim($value['value'])) . ",
              product_margin = " . DB::quote($value['margin']) . ",
              type_view = " . DB::quote($value['type']) . "
            WHERE property_id = " . DB::quote($propertyId) . "
              AND product_id = " . DB::quote($id)
                    );
                }
            } else {

                // Создаем новую запись со значением свойства
                // если его небыло сохранено ранее.
                if (!is_array($value)) {
                    DB::query("
            INSERT INTO `" . PREFIX . "product_user_property`
            VALUES (
            " . DB::quote($id) . ",
            " . DB::quote($propertyId) . ",
            " . DB::quote(trim($value)) . ",'', " . DB::quote($type) . ")");
                } else {
                    DB::query("
            INSERT INTO `" . PREFIX . "product_user_property`
            VALUES (
            " . DB::quote($id) . ",
            " . DB::quote($propertyId) . ",
            " . DB::quote(trim($value['value'])) . ",
            " . DB::quote($value['margin']) . ",
            " . DB::quote($value['type']) . "
            )");
                }
            }
        }
    }

    public static function quote($string, $noQuote = false) {
        return (!$noQuote) ? "'" . mysqli_real_escape_string(self::$connection, $string) . "'" : mysqli_real_escape_string(self::$connection, $string);
    }

    public static function myUrlEncode($string) {
        $entities = array('%20');
        $replacements = array(' ');
        return str_replace($replacements, $entities, $string);
    }

}
