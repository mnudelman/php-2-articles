<?php
/**
 * Created by PhpStorm.
 * User: michael
 * Date: 15.06.15
 * Time: 18:50
 */
ini_set('display_errors', 1);
//error_reporting(E_ALL) ;
error_reporting(E_ALL ^ E_NOTICE);
header('Content-type: text/html; charset=utf-8');

$dirService = realpath('../appl/service') ;
include_once $dirService .'/autoload.php' ;
include_once $dirService .'/TaskStore.php' ;
include_once $dirService .'/DbConnector.php' ;
$pdo1 =  DbConnector::getConnect() ;
echo 'pdo1:' . TaskStore::LINE_FEED;
var_dump($pdo1);
$isSuccessful = DbConnector::$isSuccessful ;
echo 'isSuccessful:'.$isSuccessful.TaskStore::LINE_FEED ;
if($isSuccessful) {
    $pdo2 = DbConnector::getConnect();
    echo 'pdo2:' . TaskStore::LINE_FEED;
    var_dump($pdo2);
}
