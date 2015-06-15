<?php
/**
 * Класс содержит текущий список параметров задачи
 * Для понимания можно считать - это аналог совокупности $_GET,$_POST
 * Для контроллера или модели параметры различаются по именам, а не по источнику
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
    public function setParameters($parameters) {
        if (is_array($parameters)) {
            $this->taskParameters = $parameters;
        }else {
            $this->taskParameters = [] ;
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