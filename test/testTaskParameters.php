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
include_once __DIR__ .'/local.php' ;
include_once $dirService .'/autoload.php' ;
include_once $dirService .'/TaskStore.php' ;
include_once $dirService .'/TaskParameters.php' ;
class TestParameters {
    private $taskPar ;
    private $msg ;
    public function __construct() {
        $this->taskPar = TaskParameters::getInstance() ;
        $this->msg = Message::getInstace() ;
    }
    public function simpleTest() {
        $parameters = ['a' =>1, 'b' =>2 ] ;

        $this->taskPar->setParameters($parameters) ;
        $newPar = $this->taskPar->getParameters() ;
        var_dump($newPar) ;
        $addParameters = ['c' => 3,'d'=>4] ;
        var_dump($addParameters) ;
        $newPar = $this->taskPar->addParameters($addParameters) ;

        var_dump($newPar) ;

        echo 'a:'.$this->taskPar->getParameter('a').'<br>' ;
        echo 'z:'.$this->taskPar->getParameter('z').'<br>' ;
        $this->taskPar->setParameter('z',26) ;
        echo 'z:'.$this->taskPar->getParameter('z').'<br>' ;

        echo 'сразу 2 списка параметров:'.TaskStore::LINE_FEED ;
        $parameters = ['a' =>1, 'b' =>2 ] ;
        $addParameters = ['c' => 3,'d'=>4] ;
        $this->taskPar->setParameters($parameters,$addParameters) ;
        $newPar = $this->taskPar->getParameters() ;
        var_dump($newPar) ;
    }
    public function urlClearTest_0() {
        $urlString = 'Cnt_article/edit/0/show/1' ;
        echo 'test_0:urlString-'.$urlString.TaskStore::LINE_FEED ;
        $this->taskPar->setParameters([]) ;
        $this->taskPar->addClearUrlParameters($urlString) ;
        $parameters = $this->taskPar->getParameters() ;
        echo TaskStore::LINE_FEED ;
        print_r($parameters) ;
        echo TaskStore::LINE_FEED ;
    }
    public function urlClearTest_1() {
        $urlString = 'Cnt_article/edit/0' ;
        echo 'test_1:urlString-'.$urlString.TaskStore::LINE_FEED ;
        $this->taskPar->setParameters([]) ;
        $notError = $this->taskPar->addClearUrlParameters($urlString) ;
        $parameters = $this->taskPar->getParameters() ;
        print_r($parameters) ;
        echo TaskStore::LINE_FEED ;
        if (!$notError) {
            $messages = $this->msg->getMessages() ;
            foreach ($messages as $message) {
                echo $message.TaskStore::LINE_FEED ;
            }
        }

    }
    public function urlClearTest_2() {
        $urlString = 'Cnt_article' ;
        echo 'test_2:urlString-'.$urlString.TaskStore::LINE_FEED ;
        $this->taskPar->setParameters([]) ;
        $notError = $this->taskPar->addClearUrlParameters($urlString) ;
        $parameters = $this->taskPar->getParameters() ;
        print_r($parameters) ;
        echo TaskStore::LINE_FEED ;
        if (!$notError) {
            $messages = $this->msg->getMessages() ;
            foreach ($messages as $message) {
                echo $message.TaskStore::LINE_FEED ;
            }
        }

    }
}
$test = new TestParameters() ;
$test->urlClearTest_0() ;
$test->urlClearTest_1() ;
$test->urlClearTest_2() ;