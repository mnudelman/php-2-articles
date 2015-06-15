<?php
/**
 * контроллер заставки
 * Date: 23.05.15
 */

class Cnt_default extends Cnt_base {
    protected $msg ;              // сообщения  - объект Message
    protected $parListGet = [] ;  // параметры класса - аналог $_GET
    protected $parListPost = [] ; // параметры класса - аналог $_POST
    protected $modelName = '' ;   // имя класса-модели
    protected $mod ;              // объект класса-модели
    protected $parForView = [] ;   // параметры для передачи view
    protected $classForView = 'Cnt_vw_default' ;  //  класс для формирования шаблона
    protected $nameForStore = '' ; // имя строки параметров в TaskStore
    protected $ownStore = [] ;     // собственные сохраняемые параметры
    protected $forwardCntName = false ; // контроллер, которому передается управление
    //--------------------------------//
    public function __construct() {
        parent::__construct() ;
    }
    protected function prepare() {
        $this->msg->addMessage('Для начала работы из меню выберите тему статей');
        $this->msg->addMessage('Для загрузки на сайт собственных статей надо пройти регисрацию');
        $this->msg->addMessage('Подробности о работе сайта пункт меню about');
    }
    /**
     * переход на собственную форму
     */
    public function viewGo() {
        parent::viewGo() ;
    }
}