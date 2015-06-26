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
     * Корневой шаблон
     */
    public function partMainDef() {
     return [
        'name' => 'main',
        'parameters' => false,
        'components' =>
            ['partHeadPart','partTopMenu','partContent'],
        'dir'       => $this->DIR_LAYOUT,
        'file'      => 'lt_footerNo'
        ] ;
    }
    /**
     * свой раздел центральной части
     */
    public function partDataContentDef() {
        return [
        'name' => 'partDataContent',
        'parameters' => [
            'urlArticleEdit' => $this->URL_OWN ,
        ] ,
        'components' => ['partArticleEditTable','partArticleEditCommands'] ,
        'dir' => $this->DIR_VIEW,
        'file' => 'vw_articleEdit'
        ] ;
    }
    /**
     * таблица формы редактирования
     */
    public function partArticleEditTableDef() {
        return [
        'name' => 'partArticleEditTable',
        'parameters' => [
            'topicList'      => $this->mod->getTopicList(),
            'articles'       => $this->mod->getArticles(),
            'dirArticle' => $this->dirArticle ,
            'htmlDirTop' => $this->htmlDirTop ],
        'components' => false ,
        'dir' => $this->DIR_VIEW,
        'file' => 'vw_articleEditTable'
        ] ;
    }
    /**
     * команды формы редактирования
     */
    public function partArticleEditCommandsDef() {
        $permissions = $this->mod->geCmdPermissions() ;
        $addFlag = (in_array('create',$permissions)) ;
        $editFlag = (in_array('edit',$permissions)) ;
        $delFag = (in_array('delete',$permissions)) ;

        return [
        'name' => 'partArticleEditCommands' ,
        'parameters' => [
            'topicList'      => $this->mod->getTopicList(),
            'articles'       => $this->mod->getArticles(),
            'urlArticleEdit' => $this->URL_OWN ,
            'dirArticle' => $this->dirArticle ,
            'htmlDirTop' => $this->htmlDirTop,
            'addFlag' => $addFlag,
            'editFlag' => $editFlag,
            'delFlag'  => $delFag ] ,
        'components' => false,
        'dir' => $this->DIR_VIEW,
        'file' => 'vw_articleEditCommands'
        ] ;
    }

}