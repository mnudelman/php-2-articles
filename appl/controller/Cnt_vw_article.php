<?php
/**
 * вывод редактирование статей
 */

class Cnt_vw_article extends Cnt_vw_base {
    private $htmlDirTop ;
    private $dirArticle ;
    //----------------------------//
    public function __construct() {
        parent::__construct() ;
        $this->htmlDirTop = TaskStore::$htmlDirTop ;
        $this->dirArticle = TaskStore::$dirArticleHeap ;
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
            'topicList'      => $this->mod->getTopicList(),
            'articles'       => $this->mod->getArticles(),
            'urlArticleEdit' => $this->URL_OWN ,
            'dirArticle' => $this->dirArticle ,
            'htmlDirTop' => $this->htmlDirTop ] ;
        $components = false;
        $dir = $this->DIR_VIEW;
        $file = 'vw_articleEdit';
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