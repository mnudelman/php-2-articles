<?php
/**
 * Тестирование структуры представлений
 */
session_start();
ini_set('display_errors', 1);
//error_reporting(E_ALL) ;
error_reporting(E_ALL ^ E_NOTICE);
header('Content-type: text/html; charset=utf-8');
include_once __DIR__ . '/local.php';

///////////////////////////////////////////////////////
class TestView
{
    private $controllerName;
    private $controllerViewPart;       // класс - компоненты представлений
    private $selectName;
    private $cntObj;
    private $cntVwObj;
    private $viewDriver;
    private $components;
    private $parameters;
    private $maxLevel = 0;
    //--------------------------//
    public static $STAT_EMPT = 0;        //
    public static $STAT_CURRENT = 1;     // текущий
    public static $STAT_COMPONENT = 2;   // компонент текущего
    public static $STAT_SENIOR = 3;      // старший компонент
    private $currentComponent;
    private $clickComponent;             // нажата клавиша с именем
    public static $controllers = ['about', 'article', 'default', 'navigator', 'profile', 'topic', 'user'];

    //----------------------------------//
    public function __construct()
    {
        $this->viewDriver = ViewDriver::getInstance();
    }

    public function setController($selectName)
    {
        if (empty($selectName)) {
            $this->selectName = self::$controllers[0];
        }
        $this->init();

    }

    private function init()
    {
        $this->controllerName = 'Cnt_' . $this->selectName;
        $this->controllerViewPart = 'Cnt_vw_' . $this->selectName;
        $this->cntObj = new $this->controllerName();
        $this->cntVwObj = new $this->controllerViewPart();
        $this->viewGo();
    }

    public function viewGo()
    {
        $test = true;
        $this->cntObj->viewGo($test);
        $this->makeViewComponents();
    }

    private function makeViewComponents()
    {
        $this->components = $this->viewDriver->getAllowedViews();
        $this->clearStatus();
    }

    public function getComponents()
    {
        return $this->components;
    }

    private function clearStatus()
    {

        foreach ($this->components as $name => $comp) {
            $this->components[$name]['status'] = self::$STAT_EMPT;
            if (is_array($comp['path'])) {
                $this->maxLevel = max($this->maxLevel, sizeof($comp['path']) + 1);
            }
        }
    }

    /**
     * дочерние компоненты
     */
    private function setComponentStatus()
    {
        $comps = $this->components[$this->currentComponent]['components'];
        if (is_array($comps)) {
            foreach ($comps as $compName) {
                $this->components[$compName]['status'] = self::$STAT_COMPONENT;
            }
        }
    }

    /**
     * senior component
     */
    private function setSeniorStatus()
    {
        $path = $this->components[$this->currentComponent]['path'];
        if (is_array($path) && !empty($path[0])) {
            $seniorComp = $path[0];
            $seniorComp = ('partMain' == $seniorComp) ? 'main' : $seniorComp;
            $this->components[$seniorComp]['status'] = self::$STAT_SENIOR;
        }
    }

    private function setCurrentComponent($compName)
    {
        $this->currentComponent = $compName;
        $this->clearStatus();
        $this->components[$this->currentComponent]['status'] = self::$STAT_CURRENT;


    }

    /**
     * общий список параметров из $_GET,$_POST
     */
    public function setParameters($get, $post)
    {
        $this->parameters = [];
        if (is_array($get)) {
            $this->parameters = $_GET;
        }
        if (is_array($post)) {
            $this->parameters = array_merge($this->parameters, $post);
        }
    }

    /**
     * Диалог с формой
     */
    public function prepareShow()
    {
        $parms = $this->parameters;

        if (isset($parms['cntName'])) {              // выбор контроллера для анализа
            $this->selectName = $parms['cntName'];
        } else {
            if (isset($_SESSION['selectName'])) {
                $this->selectName = $_SESSION['selectName'];
            } else {
                $this->selectName = self::$controllers[0];
            }

        }
        $this->init();
        $_SESSION['selectName'] = $this->selectName;

        if (isset($parms['comp'])) {    // это имя из $_GET - текущая компонента
            $this->setCurrentComponent($parms['comp']);
        }
        if (isset($parms['up'])) {      // показать старшую компоненту
            $this->setSeniorStatus();
        }
        if (isset($parms['down'])) {    // показать дочерние
            $this->setComponentStatus();
        }

        // нажатие клавиши с именем компоненты - для вывода справки
        $this->clickComponent = false;
        foreach ($this->components as $name => $comp) {
            $clickName = 'click_' . $name;
            if (isset($parms[$clickName])) {    // нажата клавиша с именем компоненты
                $this->setCurrentComponent($name);
                $this->clickComponent = true;
                break;
            }
        }

    }

    public function getControllerName()
    {
        return $this->controllerName;
    }

    public function getControllerViewPart()
    {
        return $this->controllerViewPart;
    }

    public function getMaxLevel()
    {
        return $this->maxLevel;
    }

    public function getSelectName()
    {
        return $this->selectName;
    }

    public function isClickComponent()
    {
        return $this->clickComponent;
    }

    public function getCurrentComponent()
    {
        return $this->components[$this->currentComponent];
    }

    public function getCurrentName()
    {
        return $this->currentComponent;
    }
}


$test = new TestView();
$test->setParameters($_GET, $_POST);
$test->prepareShow();
// Данные  - параметры вывода
$components = $test->getComponents();             // все компоненты

$dirImg = TaskStore::$htmlDirTop . '/images';

$urlTest = $_SERVER['PHP_SELF'];                // относительный адрес для HTML-ссылок

$controllerName = $test->getControllerName();   // текущий коптроллер

$controllerViewPart = $test->getControllerViewPart();  // класс - компоненты представлений

$maxLevel = $test->getMaxLevel();        // уровень дерева

$selectName = $test->getSelectName();    // имя контроллера без префиксов

$controllers = TestView::$controllers;   // список контроллеров

//форма - справка по компоненте
if ($compClick = $test->isClickComponent()) {   // вызов справки

    $comp = $test->getCurrentComponent();       // текущая
    $compName = $test->getCurrentName();

    $compParameters = '';                       // параметры
    if (is_array($comp['parameters'])) {
        foreach ($comp['parameters'] as $name => $par) {
            $compParameters .= $name . ',';
        }
    }

    $compComponets = (is_array($comp['components'])) ?         // дочерние компоненты
        implode(',', $comp['components']) : $comp['components'];
    $compDir = $comp['dir'];
    $compFile = $comp['file'];
    $compPath = implode(',', $comp['path']);
    $compOk = ($comp['ok']) ? 'true' : 'false';

}


include_once __DIR__ . '/testViewShow.php';