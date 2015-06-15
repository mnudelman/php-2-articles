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
    /**
     * Формирует все компоненты шаблона
     */
    public function buildViewTree() {    //  дерево Представлений
        parent::buildViewTree() ;
        // дополнительные компоненты - разбиение формы на 2 части
        $this->partArticleEditTableDef() ;     // 1.Таблица
        $this->partArticleEditCommandsDef() ;  // 2. Команды
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
     * свой раздел центральной части
     */
    protected function partDataContentDef() {
        $name = 'partDataContent';                           // центральный вывод
        $parameters = false  ;
        $components = ['partArticleEditTable','partArticleEditCommands'] ;
        $dir = $this->DIR_VIEW;
        $file = 'vw_articleEdit';
        $this->vwDriver->addView($name, $parameters, $components, $dir, $file);
    }
    /**
     * таблица формы редактирования
     */
    protected function partArticleEditTableDef() {
        $name = 'partArticleEditTable';
        $parameters = [
            'topicList'      => $this->mod->getTopicList(),
            'articles'       => $this->mod->getArticles(),
            'urlArticleEdit' => $this->URL_OWN ,
            'dirArticle' => $this->dirArticle ,
            'htmlDirTop' => $this->htmlDirTop ] ;
        $components = false;
        $dir = $this->DIR_VIEW;
        $file = 'vw_articleEditTable';
        $this->vwDriver->addView($name, $parameters, $components, $dir, $file);
    }
    /**
     * таблица формы редактирования
     */
    protected function partArticleEditCommandsDef() {
        $name = 'partArticleEditCommands';
        $parameters = [
            'topicList'      => $this->mod->getTopicList(),
            'articles'       => $this->mod->getArticles(),
            'urlArticleEdit' => $this->URL_OWN ,
            'dirArticle' => $this->dirArticle ,
            'htmlDirTop' => $this->htmlDirTop ] ;
        $components = false;
        $dir = $this->DIR_VIEW;
        $file = 'vw_articleEditCommands';
        $this->vwDriver->addView($name, $parameters, $components, $dir, $file);
    }

}