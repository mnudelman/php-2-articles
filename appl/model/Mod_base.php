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
    protected $taskParms ;      // объект класса   TaskParameters - параметры задачи
    //--------------------------//
    public function __construct() {
        $this->msg = Message::getInstace() ;
        if (false !== $this->dbClass) {
            $dbClass = $this->dbClass ;
            $this->db = new $dbClass() ;
        }
        $this->taskParms = TaskParameters::getInstance() ;
        $this->parameters = $this->taskParms->getParameters() ;
        $this->init() ;
    }
    /**
     * это передача атрибутов из контроллера
     */


    /**
     *  определение собственных свойств из параметров
     */
    protected function init() {

    }



}