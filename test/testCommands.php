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
