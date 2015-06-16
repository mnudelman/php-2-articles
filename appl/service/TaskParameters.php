<?php
/**
 * Класс содержит текущий список параметров задачи
 * реализует  singleton - список параметров единый
 * - это аналог совокупности $_GET,$_POST
 * Для контроллера или модели параметры различаются по именам, а не по источнику
 * Если потребуется контролировать источник параметра, то добавлю атрибут
 * В задаче все параметры равнозначны не зависимо от источника
 * Time: 16:18
 */

class TaskParameters {
    private $taskParameters ;
    private static $instance = null ;
    private $NOT_DEFINED_VALUE = false ;
    private function __construct() {

    }
    public static function getInstance() {
        if (is_null(self::$instance) ) {
            self::$instance = new self()  ;
        }
        return self::$instance ;
    }
    public function setParameters($parameters,$parameters2 = false) {
        if (is_array($parameters)) {
            $this->taskParameters = $parameters;
        }else {
            $this->taskParameters = [] ;
        }
        if (is_array($parameters2)) {
            $this->addParameters($parameters2) ;

        }
    }
    /**
     * @param $newParameters - добавляемые параметры
     */
    public function addParameters($newParameters) {
        if (is_array($newParameters)) {
            foreach ($newParameters as $var => $value ) {
                $this->taskParameters[$var] = $value ;
            }
        }
        return $this->taskParameters ;
    }
    public function getParameters() {
        return $this->taskParameters ;
    }
    public function setParameter($parName,$parMean) {
        $this->taskParameters[$parName] = $parMean ;
    }
    public function getParameter($parName) {
        return( ($this->isDefined($parName))  ?
                  $this->taskParameters[$parName] : $this->NOT_DEFINED_VALUE ) ;
    }
    public function isDefined($parName) {
        return isset($this->taskParameters[$parName]) ;
    }
}