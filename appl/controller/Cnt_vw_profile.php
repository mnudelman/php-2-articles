<?php
/**
 * вывод редактирование профиля
 */

class Cnt_vw_profile extends Cnt_vw_base {
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
        $name = 'main' ;
        $parameters = false ;
        $components = ['partHeadPart','partTopMenu','partContent','partFooter','partRightPanel'] ;
        $dir = $this->DIR_LAYOUT ;
        $file = 'lt_footerNo' ;
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
        $parameters = [
            'urlToProfile'    => $this->URL_OWN,
            'urlToDefault'    => $this->mod->getUrlDefault(),
            'login'           => $this->mod->getLogin(),
            'password'        => $this->mod->getPassword() ,
            'profileEditFlag' => $this->mod->getEditFlag(),
            'successfulSave'  => $this->mod->getSuccessful(),
            'profile'         => $this->mod->getProfile(),
            'profileError'    => $this->mod->getError() ] ;
        $components = false;
        $dir = $this->DIR_VIEW;
        $file = 'vw_userProfile';
        $this->vwDriver->addView($name, $parameters, $components, $dir, $file);
    }

    /**
     * подвал
     */
    protected function partFooterDef() {
        $name = 'partFooter';                           // подвал страницы
        $parameters = false;
        $components = false;
        $dir = $this->DIR_VIEW;
        $file = '';
        $this->vwDriver->addView($name, $parameters, $components, $dir, $file);
    }

    /**
     * правая панель
     */
    protected function partRightPanelDef() {
        $name = 'partRightPanel';                           // правая панель
        $parameters = false ;
        $components = false;
        $dir = $this->DIR_VIEW;
        $file = '';
        $this->vwDriver->addView($name, $parameters, $components, $dir, $file);
    }
}