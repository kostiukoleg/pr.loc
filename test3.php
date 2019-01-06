<?php

if( !extension_loaded('zip') ) {
    dl("php_zip.dll");
}

$m_d_h = @opendir( __DIR__ . DIRECTORY_SEPARATOR ."get_files" ) or die( "Невозможно открыть папку !!!" );

$a_t = array( "zip" );

while( $f = readdir( $m_d_h ) ) { 

    if( $f == "." || $f == ".." ) continue; 

    $f_p = explode( ".", $f );

    $extentions = strtolower( array_pop( $f_p ) );

    if( in_array( $extentions, $a_t ) ){

        print_r($f."\n");
        unlink( __DIR__ . DIRECTORY_SEPARATOR ."get_files/".$f );

    }
}
closedir($m_d_h);

$directory = __DIR__ . DIRECTORY_SEPARATOR ."tempimage";

$allowed_types = array( "jpg", "png", "gif" );

$file_parts = array();

$ext = "";

$title = "";

$i = 0;

$dir_handle = @opendir ( $directory ) or die( "Невозможно открыть папку !!!" );

while ( $file = readdir( $dir_handle ) ) { 
  
    if( $file == "." || $file == ".." ) continue; 

    $file_parts = explode( ".", $file );

    $ext = strtolower( array_pop( $file_parts ) );

    if( in_array( $ext, $allowed_types ) ) {

    $i++;

    }

    $images[] = $file;
}

closedir( $dir_handle );

$error = "";

if( isset( $images ) && count( $images ) > 0) {
    // проверяем выбранные файлы
    $zip = new ZipArchive(); // подгружаем библиотеку zip
    
    $zip_name = __DIR__ . DIRECTORY_SEPARATOR ."get_files/".time().".zip"; // имя файла
    
    if( $zip->open( $zip_name, ZIPARCHIVE::CREATE) !== TRUE ) {

        $error .= "* Sorry ZIP creation failed at this time";

    }
    
    foreach( $images as $file ) {

        $zip->addFile( __DIR__ . DIRECTORY_SEPARATOR ."tempimage/".$file, $file ); // добавляем файлы в zip архив

    }
    
    $zip->close();

} else  {

    $error .= "* Please select file to zip ";

}

if( $error ) {

    print_r($error."\n");

}
?>