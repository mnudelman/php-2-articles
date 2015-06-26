<?php
/**
 * проверка работы команд
 */
ini_set('display_errors', 1);
//error_reporting(E_ALL) ;
error_reporting(E_ALL ^ E_NOTICE);
header('Content-type: text/html; charset=utf-8');

$dirService = realpath('../appl/service') ;

include_once $dirService .'/autoload.php' ;
include_once $dirService .'/TaskStore.php' ;
include_once $dirService .'/DbConnector.php' ;
include_once __DIR__.'/local.php' ;
echo date('c').TaskStore::LINE_FEED ;
$t1 = mktime(date('c')) ;
echo 'successful:'.TaskStore::getParam('enterSuccessful') .TaskStore::LINE_FEED;
echo 'htmlDirTop:'.TaskStore::$htmlDirTop .TaskStore::LINE_FEED;