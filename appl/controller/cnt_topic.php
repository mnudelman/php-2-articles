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
    protected $modelName = 'mod_article' ;
    protected $mod ;
    protected $parForView = [] ;   // параметры для передачи view
    protected $nameForView = 'cnt_topic' ;  // имя для передачи в ViewDriver
    protected $nameForStore = 'cnt_topic' ; // имя строки параметров в TaskStore
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
        $this->topicEditStat = TaskStore::TOPIC_STAT_SHOW ;   // по умолчанию - просмотр
        $this->currentTopicId = TaskStore::getParam('topicId') ;
        $this->URL_TO_TOPIC = TaskStore::$htmlDirTop.'/index.php?cnt=cnt_topic' ;

        parent::__construct($getArray,$postArray) ;
    }
    protected function prepare() {
        $this->topicEditStat = (isset($this->parListPost['topicEditStat'])) ?
            $this->parListPost['topicEditStat'] : TaskStore::TOPIC_STAT_SHOW ;

        if (isset($this->parListPost['exit'])) {      // выход (в "главный" index )
            $this->forwardCntName = $this->CNT_HOME ;
        }

        if (isset($this->parListPost['changeStat'])) {       // сменить режим ( SHOW <-> EDIT )
            $this->changeStat();
        }

        $this->defTopicList() ;      // список доступных галерей
        if (isset($this->parListPost['currentTopicId'])) {          // текущая галерея
            $this->currentTopicId = $this->parListPost['currentTopicId'] ;
            $this->currentTopicSave() ;     // сохранить атрибуты тек альбома
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
            $this->addTopic();
        }

        parent::prepare() ;
    }
    /**
     * формирует списокТемПубликаций
     * для просмотра доступны все, для редактирования только свои
     */
    private  function defTopicList() {
        $this->topicList = $this->mod->getTopic();
     }
    /**
     * Сохранить атрибуты тек темы
     */
    private function currentTopicSave() {
        $tId = $this->currentTopicId ;
        $curTopic = $this->topicList[$tId] ;
        $tName = $curTopic['topicname'] ;
        TaskStore::setParam('topicId',$tId) ;
        TaskStore::setParam('topicName',$tName) ;
    }

    private function changeStat() {
        $userStat = TaskStore::getParam('userStatus');
        if ($userStat < TaskStore::USER_STAT_USER) {        //  если не зарегистрирован, то только просмотр
            $this->topicEditStat = TaskStore::TOPIC_STAT_SHOW ;
        }else {
            $this->topicEditStat = ($this->topicEditStat == TaskStore::TOPIC_STAT_SHOW) ?
                TaskStore::TOPIC_STAT_EDIT : TaskStore::TOPIC_STAT_SHOW ;
        }
    }
    private function addTopic() {
        $owner = TaskStore::getParam('userLogin') ;
        $newT = $this->parListPost['addTopic'] ;
        $tId = $this->mod->putTopic($newT) ;
        $this->topicList = $this->mod->getTopic() ;
        $this->currentTopicSave($tId)  ;
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
        $this->topicStatName = ($this->topicEditStat == TaskStore::TOPIC_STAT_SHOW) ?
            TaskStore::STAT_SHOW_NAME : TaskStore::STAT_EDIT_NAME ;
        $editFlag = ($this->topicEditStat == TaskStore::TOPIC_STAT_EDIT) ;
        $addTopicFlag = (TaskStore::getParam('userStatus') >= TaskStore::USER_STAT_ADMIN );
        $this->parForView = [
            'topicList'    => $this->topicList ,
            'currentTopicId' => $this->currentTopicId ,
            'urlToTopic' => $this->URL_TO_TOPIC ,
            'topicEditStat' => $this->topicEditStat,
            'topicStatName' => $this->topicStatName,
            'editFlag'        => $editFlag,
            'addTopicFlag' => $addTopicFlag ] ;


        parent::viewGo() ;
    }
}