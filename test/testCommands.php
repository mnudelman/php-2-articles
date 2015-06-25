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
echo date('c').TaskStore::LINE_FEED ;
$t1 = mktime(date('c')) ;
echo time().TaskStore::LINE_FEED ;

for($i=1; $i<=100000;$i++) {

}
echo (date('c')).TaskStore::LINE_FEED ;
$t2 = mktime(date('c'))  ;
echo 't1:'.$t1.TaskStore::LINE_FEED ;
echo 't2:'.$t2.TaskStore::LINE_FEED ;
echo 'successful:'.TaskStore::getParam('enterSuccessful') .TaskStore::LINE_FEED;