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
    public function __construct($getArray,$postArray) {
        parent::__construct($getArray,$postArray) ;
    }
    protected function prepare() {
        parent::prepare() ;
    }
    /**
     * выдает имя контроллера для передачи управления
     * альтернатива viewGo
     * Через  $pListGet , $pListPost можно передать новые параметры
     */
    public function getForwardCntName() {
        parent::getForwardCntName() ;
    }
    /**
     * переход на собственную форму
     */
    public function viewGo() {
        parent::viewGo() ;
    }
}