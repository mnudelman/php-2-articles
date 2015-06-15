<?php
/**
 * контроллер вывода описаний
 * Date: 23.05.15
 */

class Cnt_about extends Cnt_base {
    protected $msg ;    // сообщения класса - объект Message
    protected $parameters = [] ;  // параметры класса
    protected $msgTitle = '' ;
    protected $modelName = '' ;
    protected $mod ;             // объект - модель
    protected $parForView = [] ; //  параметры для передачи view
    protected $classForView = 'Cnt_vw_about' ;  //  класс для формирования шаблона
    protected $forwardCntName = false ; // контроллер, которому передается управление
    //--------------------------------//
    public function __construct() {
        parent::__construct() ;
    }
    protected function prepare() {
        parent::prepare() ;
    }
    /**
     * переход на собственную форму
     */
    public function viewGo() {
        parent::viewGo() ;
    }
}