<?php
function getDatabaseConnection(){
    $env = parse_ini_file('.env');
    foreach ($env as $key => $value){
        putenv("$key=$value");
    }
    $db_name = $env['DB_NAME'];
    $db_user = $env['DB_USERNAME'];
    $db_password = $env['DB_PASSWORD'];
    $db_host = $env['DB_HOST'];

    $db_success = false;
    try{
        $db = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_password);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        $db_success = true;
    }catch (PDOException $e) {
        echo $e->getMessage();
    }

    return $db;
}
