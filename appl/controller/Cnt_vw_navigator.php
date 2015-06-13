<?php
/**
 * Класс передачи данных модели в Представление
 * выделенная часть контроллера
 */

class cnt_vw_navigator extends Cnt_vw_base {
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
        parent::__construct() ;
    }
    public function setModel($model) {
      parent::setModel($model) ;
    }
    public function setViewDriver($vieDriver) {
        parent::setViewDriver($vieDriver) ;
    }
    public function buildViewTree() {    //  дерево Представлений
        parent::buildViewTree() ;
    }
    protected function partMainDef() {
        $name = 'main' ;
        $parameters = false ;
        $components = ['partHeadPart','partTopMenu','partContent','partFooter','partRightPanel'] ;
        $dir = $this->DIR_LAYOUT ;
        $file = 'lt_footerHalf' ;
        $this->vwDriver->addView($name,$parameters,$components,$dir,$file) ;
    }
    protected function partHeadPartDef() {
      parent::partHeadPartDef() ;
    }
    protected function partTopMenuDef() {
      parent::partTopMenuDef() ;
    }
    protected function partContentDef() {
        $name = 'partContent' ;                              // центральный content
        $parameters = false ;
        $components = ['partMessage','partDataContent'] ;
        $dir = $this->DIR_VIEW ;
        $file = 'contentPart' ;
        $this->vwDriver->addView($name,$parameters,$components,$dir,$file) ;
    }
    protected function partMessageDef() {
        parent::partMessageDef() ;
    }
    protected function partDataContentDef() {
        $name = 'partDataContent';                           // центральный вывод
        $parameters = [
            'dirArticle' => $this->DIR_ARTICLE,
            'article'    => $this->mod->getCurrentArticle(),
            'dirImages'  => $this->DIR_IMAGE ];
        $components = false;
        $dir = $this->DIR_VIEW;
        $file = 'vw_articleShow';
        $this->vwDriver->addView($name, $parameters, $components, $dir, $file);
    }
    protected function partFooterDef() {
        $name = 'partFooter';                           // подвал страницы
        $parameters = [
            'article'        => $this->mod->getCurrentArticle() ,
            'topicList'      => $this->mod->getTopicList() ,
            'currentTopicId' => $this->mod->getCurrentTopicId() ,
            'currentPage'    => $this->mod->getCurrentPage(),// № тек страницы
            'navPageMin'     => $this->mod->getNavPageMin(), // min N страницы в указателе навигатора
            'navPageMax'     => $this->mod->getNavPageMax(), // max N ---------""-------------------
            'urlNavigator'   => $this->URL_OWN,        // адрес для передачи в контроллер
            'dirImages'         => $this->DIR_IMAGE ] ;
        $components = false;
        $dir = $this->DIR_VIEW;
        $file = 'vw_navigator';
        $this->vwDriver->addView($name, $parameters, $components, $dir, $file);

    }
    protected function partRightPanelDef() {
        $name = 'partRightPanel';                           // правая панель
        $parameters = [
            'articles'    => $this->mod->getArticles() ,
            'htmlDirTop'  => $this->HTML_DIR_TOP ] ;
        $components = false;
        $dir = $this->DIR_VIEW;
        $file = 'vw_articleList';
        $this->vwDriver->addView($name, $parameters, $components, $dir, $file);
    }
}