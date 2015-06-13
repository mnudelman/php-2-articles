<?php
/**
 * класс - формирователь страницы - посредник между контроллером и предствлением
 *
 */

abstract class Cnt_vw_base {
    protected $vwDriver ;                // объект класса ViewDriver -
    protected $msg;                      // сообщения  - объект Message
    protected $mod ;                     // объект-модель
    protected $URL_OWN;                  // ссылка для формы котроллера
    protected $DIR_TOP ;
    protected $HTML_DIR_TOP ;
    protected $DIR_VIEW ;
    protected $DIR_LAYOUT ;
    protected $DIR_ARTICLE ;
    protected $DIR_IMAGE ;
    public function __construct() {
        $this->msg = TaskStore::getMessage() ;
        $this->DIR_TOP =TaskStore::$dirTop ;
        $this->HTML_DIR_TOP = TaskStore::$htmlDirTop ;
        $this->DIR_VIEW = TaskStore::$dirView ;
        $this->DIR_LAYOUT =TaskStore::$dirLayout ;
        $this->DIR_ARTICLE = TaskStore::$dirArticleHeap ;
        $this->DIR_IMAGE = TaskStore::$htmlDirTop .'/images' ;
    }
    public function setModel($model) {
        $this->mod = $model ;
    }
    public function setViewDriver($vieDriver) {
        $this->vwDriver = $vieDriver ;
    }

    /**
     * @param $url - адрес собственного контроллера
     */
    public function setUrlOwn($url) {
        $this->URL_OWN = $url ;
    }
    /**
     * Формирует все компоненты шаблона
     */
    public function buildViewTree() {    //  дерево Представлений
        $this->partMainDef() ;          //
        $this->partHeadPartDef() ;      // <head> .. </head>
        $this->partTopMenuDef() ;       // меню-начало страницы
        $this->partContentDef() ;       // центральная часть
        $this->partMessageDef() ;       // сообщения
        $this->partDataContentDef() ;
        $this->partFooterDef() ;        // подвал
        $this->partRightPanelDef() ;    // правая панель
    }

    /**
     * Корневой шаблон
     */
    protected function partMainDef() {
        $name = 'main' ;
        $parameters = false ;
        $components = ['partHeadPart','partTopMenu','partContent','partFooter','partRightPanel'] ;
        $dir = $this->DIR_LAYOUT ;
        $file = 'lt_footerHalf' ;
        $this->vwDriver->addView($name,$parameters,$components,$dir,$file) ;
    }

    /**
     * формирует тег <head>... </head>
     */
    protected function partHeadPartDef() {
        $name = 'partHeadPart' ;                              // тег <head></head>
        $parameters = [ 'htmlDirTop' => $this->HTML_DIR_TOP ] ;
        $components = false ;
        $dir = $this->DIR_VIEW ;
        $file = 'headPart' ;
        $this->vwDriver->addView($name,$parameters,$components,$dir,$file) ;
    }

    /**
     * меню - начало страницы
     */
    protected function partTopMenuDef() {
        $name = 'partTopMenu' ;                              // меню страницы
        $topicStore  = TaskStore::getParam('topicName') ;
        $topicName = ( empty($topicStore)) ? 'тема не выбрана' : $topicStore ;
        $parameters = [
            'htmlDirTop' => $this->HTML_DIR_TOP,
            'topicName' =>  $topicName,
            'userName'  => TaskStore::getParam('userName') ];
        $components = false ;
        $dir = $this->DIR_VIEW ;
        $file = 'topMenu' ;
        $this->vwDriver->addView($name,$parameters,$components,$dir,$file) ;
    }

    /**
     * центральная часть страницы
     */
    protected function partContentDef() {
        $name = 'partContent' ;                              // центральный content
        $parameters = false ;
        $components = ['partMessage','partDataContent'] ;
        $dir = $this->DIR_VIEW ;
        $file = 'contentPart' ;
        $this->vwDriver->addView($name,$parameters,$components,$dir,$file) ;
    }

    /**
     * Вывод сообщений
     */
    protected function partMessageDef() {
        $name = 'partMessage' ;                              // Вывод сообщений
        $parameters  = ['messages' => $this->msg->getMessages() ] ;
        $components = false ;
        $dir = $this->DIR_VIEW ;
        $file = 'messageForm' ;
        $this->vwDriver->addView($name,$parameters,$components,$dir,$file) ;
    }

    /**
     * свой раздел центральной части
     */
    protected function partDataContentDef() {
        $name = 'partDataContent';                           // центральный вывод
        $parameters = false;
        $components = false;
        $dir = $this->DIR_VIEW;
        $file = 'contentPart';
        $this->vwDriver->addView($name, $parameters, $components, $dir, $file);
    }

    /**
     * подвал
     */
    protected function partFooterDef() {
        $name = 'partFooter';                           // подвал страницы
        $parameters = false ;
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