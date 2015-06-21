<?php
/**
 * тестирование класса TaskParameters
 * Time: 17:01
 */
ini_set('display_errors', 1);
//error_reporting(E_ALL) ;
error_reporting(E_ALL ^ E_NOTICE);
header('Content-type: text/html; charset=utf-8');

$dirService = realpath('../appl/service') ;
echo $dirService ;
include_once $dirService .'/autoload.php' ;
include_once $dirService .'/TaskStore.php' ;
include_once $dirService .'/TaskParameters.php' ;
$parameters = ['a' =>1, 'b' =>2 ] ;
$taskPar = TaskParameters::getInstance() ;
$taskPar->setParameters($parameters) ;
$newPar = $taskPar->getParameters() ;
var_dump($newPar) ;
$addParameters = ['c' => 3,'d'=>4] ;
var_dump($addParameters) ;
$newPar = $taskPar->addParameters($addParameters) ;

var_dump($newPar) ;

echo 'a:'.$taskPar->getParameter('a').'<br>' ;
echo 'z:'.$taskPar->getParameter('z').'<br>' ;
$taskPar->setParameter('z',26) ;
echo 'z:'.$taskPar->getParameter('z').'<br>' ;

echo 'сразу 2 списка параметров:'.TaskStore::LINE_FEED ;
$parameters = ['a' =>1, 'b' =>2 ] ;
$addParameters = ['c' => 3,'d'=>4] ;
$taskPar->setParameters($parameters,$addParameters) ;
$newPar = $taskPar->getParameters() ;
var_dump($newPar) ;