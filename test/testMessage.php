<?php
/**
 * Created by PhpStorm.
 * User: michael
 * Date: 15.06.15
 * Time: 19:45
 */
ini_set('display_errors', 1);
//error_reporting(E_ALL) ;
error_reporting(E_ALL ^ E_NOTICE);
header('Content-type: text/html; charset=utf-8');

$dirService = realpath('../appl/service') ;
include_once $dirService .'/autoload.php' ;
include_once $dirService .'/TaskStore.php' ;
include_once $dirService .'/Message.php' ;
$msg = Message::getInstace() ;
$msg->addMessage('message__________1') ;
$msg->addMessage('message__________2') ;
$msg->addMessage('message__________3') ;
$messages = $msg->getMessages() ;
foreach($messages as $txt) {
    echo$txt.TaskStore::LINE_FEED ;
}
$msg2 = Message::getInstace() ;
$messages = $msg2->getMessages() ;
foreach($messages as $txt) {
    echo$txt.TaskStore::LINE_FEED ;
}
var_dump($msg) ;
var_dump($msg2) ;