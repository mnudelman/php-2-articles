<?php
session_start() ;
/**
 * по $_GET определяет передачу управления
 * контроллерам 2 уровня
 *  параметр: определяет имя контроллера 2 уровня {?user | ?topic | ....}
 */
?>
<?php
ini_set('display_errors', 1);
//error_reporting(E_ALL) ;
error_reporting(E_ALL ^ E_NOTICE);
header('Content-type: text/html; charset=utf-8');
include_once __DIR__ . '/local.php';
// загружаем параметры---//
$taskPar = TaskParameters::getInstance() ;
$taskPar->setParameters($_POST,$_GET) ;
$session = Mod_session::getInstace() ;
$session->setTime() ;   // отметка момента входа на странуцу
$router = new Router();
$router->controllerGo() ;
