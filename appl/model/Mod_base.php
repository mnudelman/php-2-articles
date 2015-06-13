<?php
/**
 * Базовый класс для моделей
 * Time: 18:47
 */

abstract class Mod_base {
    protected $msg ;                    // объект для вывода сообщений
    protected $db = false ;             // объект класса для связи с БД
    protected $dbClass = false ;        //  имя класса для работы с БД
    protected $parameters = []; // параметры, принимаемые от контроллера
    //--------------------------//
    public function __construct() {
        $this->msg = TaskStore::getMessage() ;
        if (false !== $this->dbClass) {
            $dbClass = $this->dbClass ;
            $this->db = new $dbClass() ;
        }
    }
    /**
     * это передача атрибутов из контроллера
     */
    public function setParameters($parameters) {
        $this->parameters = $parameters;
        $this->init();
    }

    /**
     *  определение собственных свойств из параметров
     */
    protected function init() {

    }



}