<?php
/**
 * тестирование класса Mod_session
 */
session_start();
ini_set('display_errors', 1);
//error_reporting(E_ALL) ;
error_reporting(E_ALL ^ E_NOTICE);
header('Content-type: text/html; charset=utf-8');
include_once __DIR__ . '/local.php';

$msg = Message::getInstace() ;
$session = Mod_session::getInstace() ;

//echo ',без cookies::'.TaskStore::LINE_FEED ;

TaskStore::setParam('userLogin','mnudelman') ;
TaskStore::setParam('userPassword','12345') ;
TaskStore::setParam('userStatus',TaskStore::USER_STAT_USER) ;
TaskStore::setParam('userRole',TaskStore::ROLE_USER) ;
TaskStore::setParam('enterSuccessful',true) ;
//$session->newSession() ;
//echo 'sessionId:'.TaskStore::getParam('sessionId').TaskStore::LINE_FEED ;
//echo  'cookies:'.$_COOKIE["fingerprint"].TaskStore::LINE_FEED ;
//
//
echo 'cookies::'.TaskStore::LINE_FEED ;
//TaskStore::setParam('enterSuccessful',true) ;
//TaskStore::setParam('passwSave',true) ;
//echo 'sessionId:'.TaskStore::getParam('sessionId').TaskStore::LINE_FEED ;
//var_dump($_COOKIE) ;
echo  'cookies:'.$_COOKIE["fingerprint"].TaskStore::LINE_FEED ;
$session->setTime() ;

$messages = $msg->getMessages() ;
if (is_array($messages)) {
    foreach ($messages as $text) {
        print_r($text);
        echo TaskStore::LINE_FEED;
    }
}