<?php
/**
 * контроллер заставки
 * Date: 23.05.15
 */

class Cnt_default extends Cnt_base {
    protected $classForView = 'Cnt_vw_default' ;  //  класс для формирования шаблона
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