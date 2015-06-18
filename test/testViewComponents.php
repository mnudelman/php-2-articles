<?php
/**
 * Тестирование структуры представлений
 */
ini_set('display_errors', 1);
//error_reporting(E_ALL) ;
error_reporting(E_ALL ^ E_NOTICE);
header('Content-type: text/html; charset=utf-8');
include_once __DIR__ . '/local.php';
///////////////////////////////////////////////////////
class TestView {
    private $controllerName ;
    private $controllerViewPart ;       // класс - компоненты представлений
    private $cntObj ;
    private $cntVwObj ;
    private $viewDriver ;
    private $components ;
    private $parameters ;
    //--------------------------//
    public static $STAT_EMPT = 0 ;        //
    public static $STAT_CURRENT = 1 ;     // текущий
    public static $STAT_COMPONENT = 2 ;   // компонент текущего
    public static $STAT_SENIOR = 3 ;      // старший компонент
    private $currentComponent ;
    //----------------------------------//
    public function __construct() {
        $this->viewDriver = ViewDriver::getInstance() ;
    }
    public function setController($cnt,$cnt_vw) {
        $this->controllerName = $cnt ;
        $this->controllerViewPart = $cnt_vw ;
        $this->cntObj = new $this->controllerName() ;
        $this->cntVwObj = new $this->controllerViewPart() ;

    }
    public function viewGo() {
        $test = true ;
        $this->cntObj->viewGo($test) ;
        $this->makeViewComponents() ;
    }
    private function makeViewComponents() {
        $this->components = $this->viewDriver->getAllowedViews() ;
        $this->clearStatus() ;
    }
    public function getComponents() {
        return $this->components ;
    }
    public function setCurrent($compName) {
        $this->currentComponent = $compName ;
        $this->clearStatus() ;
        $this->components[$compName]['status'] = self::$STAT_CURRENT ;

    }
    private function clearStatus() {
        foreach ($this->components as $name => $comp) {
            $this->components[$name]['status'] = self::$STAT_EMPT ;
      }
    }
    public function setStatus($status) {
        switch ($status) {
            case self::$STAT_COMPONENT :
                $this->setComponentStatus() ;
                break ;
            case self::$STAT_SENIOR :
                $this->setSeniorStatus() ;
                break ;
        }
    }
    private function setComponentStatus() {
        $comps = $this->components[$this->currentComponent]['components'] ;
        if (is_array($comps)) {
            foreach ($comps as $compName) {
                $this->components[$compName]['status'] = self::$STAT_COMPONENT ;
            }
        }
    }
    private function setSeniorStatus() {
       $path =    $this->components[$this->currentComponent]['path'] ;
       if (is_array($path) && !empty($path[0])) {
           $seniorComp = $path[0] ;
           $this->components[$seniorComp]['status'] = self::$STAT_SENIOR ;
       }
    }
    public function setParameters($get,$post) {
        $this->parameters = [] ;
        if (is_array($get)) {
            $this->parameters = $_GET ;
        }
        if (is_array($post)) {
            $this->parameters = array_merge($this->parameters,$post) ;
        }
    }
    public function prepareShow() {
        $parms = $this->parameters ;
        if (isset($parms['comp'])) {
            $this->currentComponent = $parms['comp'] ;
            $this->components[$this->currentComponent]['status'] = self::$STAT_CURRENT ;
        }
        if (isset($parms['up']) ) {
            $this->setSeniorStatus() ;
        }
        if (isset($parms['down']) ) {
            $this->setComponentStatus() ;
        }
    }


}




$test = new TestView() ;
$test->setController('Cnt_navigator','Cnt_vw_navigator') ;
$test->viewGo() ;
$test->setParameters($_GET,$_POST) ;
$test->prepareShow() ;
$components = $test->getComponents() ;
$dirImg = TaskStore::$htmlDirTop.'/images' ;
$maxLevel = 3 ;
$urlTest = $_SERVER['PHP_SELF'] ; // относительный адрес для HTML-ссылок
$controllerName = 'Cnt_navigator' ;
$controllerViewPart =  'Cnt_vw_navigator' ;       // класс - компоненты представлений
include_once __DIR__.'/testViewShow.php' ;