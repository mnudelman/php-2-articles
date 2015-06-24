<?php
/**
 *  тестирование класса Mod_user
 */
session_start();
ini_set('display_errors', 1);
//error_reporting(E_ALL) ;
error_reporting(E_ALL ^ E_NOTICE);
header('Content-type: text/html; charset=utf-8');
include_once __DIR__ . '/local.php';
///////////////////////////////////////////////////////////////////


$modUser = new Mod_user() ;
//  устанавливаем среду
 TaskStore::setParam('currentObj','article') ;
TaskStore::setParam('userRole','admin') ;
$permissions = $modUser->getPermissions() ;
print_r($permissions) ;
echo TaskStore::LINE_FEED ;
TaskStore::setParam('userRole','user') ;
$permissions = $modUser->getPermissions() ;
print_r($permissions) ;
echo TaskStore::LINE_FEED ;

TaskStore::setParam('userRole','owner') ;
$permissions = $modUser->getPermissions() ;
print_r($permissions) ;
echo TaskStore::LINE_FEED ;

TaskStore::setParam('currentObj','topic') ;
TaskStore::setParam('userRole','owner') ;
$permissions = $modUser->getPermissions() ;
print_r($permissions) ;
echo TaskStore::LINE_FEED ;
echo (false == $permissions ) ;
echo (empty($permissions)) ;