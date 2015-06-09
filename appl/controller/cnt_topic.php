<?php
/**
 * класс - контроллер альбомов
 * Date: 25.05.15
 * Time: 23:21
 */

class cnt_topic extends cnt_base {
    protected $msg ;    // сообщения класса - объект Message
    protected $parListGet = [] ;  // параметры класса
    protected $parListPost = [] ;  // параметры класса
    protected $msgTitle = '' ;
    protected $msgName = '' ;
    protected $modelName = 'mod_topic' ;
    protected $mod ;
    protected $parForView = [] ;   // параметры для передачи view
    protected $nameForView = 'cnt_topic' ;  // имя для передачи в ViewDriver
    protected $nameForStore = 'cnt_topicStore' ; // имя строки параметров в TaskStore
    protected $ownStore = [] ;     // собственные сохраняемые параметры
    protected $forwardCntName = false ; // контроллер, которому передается управление
    //-----------------------------------------------//
    //-------параметры передачи в Представление---//
    private $topicList ;               // список доступных альбомов
    private $currentTopicId ;          // Id текущей галереи
    private $topicEditStat ;           // редактирование/просмотр
    private $topicStatName ;           // тоже, только имя
    //-------- url ------------------------------//
    private $CNT_HOME = 'cnt_default' ;      // контроллер пустой
    private $CNT_ARTICLE = 'cnt_article' ;   // контроллер статей
    private $URL_TO_TOPIC ;         // адрес для перехода из формы в контроллер
    //--------------------------------------------//
    private $articleStatEdit ;

    public function __construct($getArray,$postArray) {
        $this->URL_TO_TOPIC = TaskStore::$htmlDirTop.'/index.php?cnt=cnt_topic' ;

        parent::__construct($getArray,$postArray) ;
    }
    protected function prepare() {
        $this->mod->setParameters($this->parListPost) ;  // все параметры в модель

        if (isset($this->parListPost['exit'])) {      // выход (в "главный" index )
            $this->forwardCntName = $this->CNT_HOME ;
        }

        if (isset($this->parListPost['changeStat'])) {       // сменить режим ( SHOW <-> EDIT )
            $this->mod->changeStat();
        }

        if (isset($this->parListPost['goShow'])) {   //   в просмотр альбома
            $this->forwardCntName = $this->CNT_ARTICLE;
            $this->articleStatEdit = TaskStore::ARTICLE_STAT_SHOW;
        }
        if (isset($this->parListPost['editArticle'])) {   //   редактировать
            $this->forwardCntName = $this->CNT_ARTICLE;
            $this->articleStatEdit = TaskStore::ARTICLE_STAT_EDIT ;
        }

        if (isset($this->parListPost['addTopicExec'])) {   //   добавить в список новыйАльбом
            $this->mod->addTopic();
        }

        parent::prepare() ;
    }
    ////////////////////////////////////////////////////////////////////////////////
    /**
     *  построить массив $ownStore - собственные параметры
     */
    protected function buildOwnStore() {
        parent::buildOwnStore() ;
    }
    protected function saveOwnStore() {
        parent::saveOwnStore() ;
    }
    /**
     * выдает имя контроллера для передачи управления
     * альтернатива viewGo
     * Через  $pListGet , $pListPost можно передать новые параметры
     */
    public function getForwardCntName(&$plistGet,&$pListPost) {
        $plistGet = [] ;
        $plistPost = [] ;
        if ($this->forwardCntName == $this->CNT_ARTICLE) {
            if ($this->articleStatEdit == TaskStore::ARTICLE_STAT_SHOW) {
                $plistGet = ['show' => true] ;
            }else {
                $plistGet = ['edit' => true] ;
            }
        }
        return $this->forwardCntName ;
//        parent::getForwardCntName($plistGet,$pListPost) ;
    }
    public function viewGo() {
        $this->parForView = [
            'topicList'       => $this->mod->getTopicList() ,
            'currentTopicId'  => $this->mod->getTopicId() ,
            'urlToTopic'      => $this->URL_TO_TOPIC ,
            'topicEditStat'   => $this->mod->getEditStat(),
            'topicStatName'   => $this->mod->getStatName(),
            'editFlag'        => $this->mod->getEditFlag(),
            'addTopicFlag'    => $this->mod->getAddTopicFlag() ] ;


        parent::viewGo() ;
    }
}