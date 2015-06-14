<?php
/**
 */
$host = 'localhost' ;
$dbname = 'articles' ;
$user = 'root' ;
$password = 'root' ;
$charset = "utf-8" ;
$dbSuccessful = true ; // успех подключения к БД
$dsn = 'mysql:host='.$host.';dbname='.$dbname ;
$opt = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO:: FETCH_ASSOC ] ;
try {
    $pdo = new PDO($dsn, $user, $password, $opt) ;
}catch (PDOException $e) {
    $dbSuccessful = false;
    echo 'ERROR:подключение:' . $e->getMessage() . LINE_FEED;
}
setlocale(LC_ALL,"ru_RU.UTF-8") ;
mb_internal_encoding("UTF-8") ;
return $dbSuccessful ;