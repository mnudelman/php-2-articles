<?php
/**
 * Контроллер - редактирование статей
 * Редактируются заголовки и темы(рубрики) статей
 * Date: 25.05.15
 * Time: 23:21
 */

class Cnt_article extends Cnt_base {
    protected $msg ;    // сообщения класса - объект Message
    protected $viewDriver ;       // объект класса viewDriver
    protected $parameters = [] ;  // параметры класса
    protected $msgTitle = '' ;
    protected $msgName = '' ;
    protected $modelName = 'Mod_article' ;
    protected $mod ;
    protected $parForView = [] ;   // параметры для передачи view
    protected $classForView = 'Cnt_vw_article' ;  // класс для передачи в ViewDriver
    protected $nameForStore = 'cnt_articleStore' ; // имя строки параметров в TaskStore
    protected $ownStore = false ;     // собственные сохраняемые параметры
    protected $forwardCntName = false ; // контроллер, которому передается управление
    protected $URL_OWN ;
    //-----------------------------------------------------------//
    private $FORWARD_CNT_NAVIGATOR = 'Cnt_navigator' ; // имя для передачи управления
    private $htmlDirTop ;
    private $dirArticle ;
    //---------------------------------------------------------------//

    public function __construct($getArray,$postArray) {
        $this->URL_OWN = TaskStore::$htmlDirTop.'/index.php?cnt=Cnt_article' ;
        $this->htmlDirTop = TaskStore::$htmlDirTop ;
        $this->dirArticle = TaskStore::$dirArticleHeap ;
        parent::__construct($getArray,$postArray) ;

    }
    protected function prepare() {
        //------- работа   ------------//
        //$this->parameters['dirArticle'] = $this->dirArticle ; // в параметры
        //$this->mod->setParameters($this->parameters) ; // параметры в модель
        $this->taskParms->setParameter('dirArticle',$this->dirArticle) ;

        if (isset($this->parameters['show']) || isset($this->parListGet['show'])) {   // просмотр
            $this->forwardCntName = $this->FORWARD_CNT_NAVIGATOR ;  // передача управления для
        }
       if (isset($this->parameters['save'])) {   // сохранить и выйти
            $this->mod->saveArticle() ;
        }
        if (isset($this->parameters['add'])) {   // добавить статьи
            $this->mod->addArticle() ;
        }
        if (isset($this->parameters['del'])) {   // удалить отмеченные
            $this->mod->delCheckedArticle() ;
        }
        parent::prepare() ;
    }
////////////////////////////////////////////////////////////////////////////////////////
    /**
     *  построить массив $ownStore - собственные параметры
     */
    protected function buildOwnStore() {

    }
    protected function saveOwnStore() {
        parent::saveOwnStore() ;
    }
    /**
     * выдает имя контроллера для передачи управления
     * альтернатива viewGo
     * Через  $pListGet , $pListPost можно передать новые параметры
     */
    public function getForwardCntName() {
        $plistGet = [] ;
        $plistPost = [] ;
        $this->taskParms->setParameters($plistGet,$plistPost) ;
        return $this->forwardCntName ;
    }
     /**
      * подготовка и вывод представления
     */
    public function viewGo() {
        parent::viewGo() ;
    }

}