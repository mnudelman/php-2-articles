<?php
/**
 *  вывод описания
 */

class Cnt_vw_about extends Cnt_vw_base {
    //---------------------------------//
    public function __construct() {
        parent::__construct() ;
    }
    public function setModel($model) {
        parent::setModel($model) ;
    }
    public function setViewDriver($vieDriver) {
        parent::setViewDriver($vieDriver) ;
    }
    /**
     * Формирует все компоненты шаблона
     */
    public function buildViewTree() {    //  дерево Представлений
        parent::buildViewTree() ;
    }

    /**
     * Корневой шаблон
     */
    protected function partMainDef() {
        $name = 'main' ;                               // корень дереваПредставлений
        $parameters = false ;
        $components = ['partHeadPart','partTopMenu','partContent','partFooter','partRightPanel'] ;
        $dir = $this->DIR_LAYOUT ;
        $file = 'lt_footerNo' ;                        // файл - шаблон
        $this->vwDriver->addView($name,$parameters,$components,$dir,$file) ;
    }

    /**
     * формирует тег <head>... </head>
     */
    protected function partHeadPartDef() {
        parent::partHeadPartDef() ;
    }

    /**
     * меню - начало страницы
     */
    protected function partTopMenuDef() {
        parent::partTopMenuDef() ;
    }

    /**
     * центральная часть страницы
     */
    protected function partContentDef() {
        parent::partContentDef() ;
    }

    /**
     * Вывод сообщений
     */
    protected function partMessageDef() {
        parent::partMessageDef() ;
    }
    /**
     * свой раздел центральной части
     */
    protected function partDataContentDef() {
        $name = 'partDataContent';                           // центральный вывод
        $parameters = false;
        $components = false;
        $dir = $this->DIR_VIEW;
        $file = 'vw_about';
        $this->vwDriver->addView($name, $parameters, $components, $dir, $file);

    }
    /**
     * подвал
     */
    protected function partFooterDef() {
        $this->vwDriver->addView('partFooter',false,false,false,false) ;
    }
    /**
     * правая панель
     */
    protected function partRightPanelDef() {
        $this->vwDriver->addView('partRightPanel',false,false,false,false) ;
    }


}