<?php
/**
 * контроллер вывода описаний
 * Date: 23.05.15
 */

class cnt_about extends cnt_base {
    protected $msg ;    // сообщения класса - объект Message
    protected $parListPost = [] ;  // параметры класса
    protected $parListGet = [] ;  // параметры класса
    protected $msgTitle = '' ;
    protected $modelName = '' ;
    protected $mod ;             // объект - модель
    protected $parForView = [] ; //  параметры для передачи view
    protected $nameForView = 'cnt_about' ; // имя для передачи в ViewDriver
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
    public function getForwardCntName(&$plistGet,&$pListPost) {
        parent::getForwardCntName($plistGet,$pListPost) ;
    }
    /**
     * переход на собственную форму
     */
    public function viewGo() {
        parent::viewGo() ;
    }
}