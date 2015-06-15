<?php
/**
 * контроллер вывода описаний
 * Date: 23.05.15
 */

class Cnt_about extends Cnt_base {
    protected $classForView = 'Cnt_vw_about' ; // класс для формирования шаблона
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