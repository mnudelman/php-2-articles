<?php
/**
 * класс для работы с темами(рубриками) статей
 * Date: 09.06.15
 * Time: 15:02
 */

class mod_topic extends mod_base {
    protected $msg ;                    // объект для вывода сообщений
    protected $db = false ;             // объект класса для связи с БД
    protected $dbClass = 'db_article' ; //  имя класса для работы с БД
    protected $parameters = [];         // параметры, принимаемые от контроллера
    //------------------------------//
    private $topicList ;               // список доступных альбомов
    private $currentTopicId ;          // Id текущей галереи
    private $topicEditStat ;           // редактирование/просмотр
    private $topicStatName ;           // тоже, только имя
    //----------------------------------------//
   public function __construct() {
       parent::__construct() ;
   }
    /**
     * это передача атрибутов пофиля из контроллера
     */
    public function setParameters($parameters) {
        parent::setParameters($parameters) ;
    }
    /**
     *  определение собственных свойств из параметров
     */
    protected function init() {
        $this->topicEditStat = TaskStore::TOPIC_STAT_SHOW ;   // по умолчанию - просмотр
        $this->currentTopicId = TaskStore::getParam('topicId') ;
        $this->topicEditStat = (isset($this->parameters['topicEditStat'])) ?
            $this->parameters['topicEditStat'] : TaskStore::TOPIC_STAT_SHOW ;
        $this->topicList = $this->db->getTopic();
        $this->currentTopicId = $this->parameters['currentTopicId'] ;
    }


    public function changeStat() {
        $userStat = TaskStore::getParam('userStatus');
        if ($userStat < TaskStore::USER_STAT_USER) {        //  если не зарегистрирован, то только просмотр
            $this->topicEditStat = TaskStore::TOPIC_STAT_SHOW ;
        }else {
            $this->topicEditStat = ($this->topicEditStat == TaskStore::TOPIC_STAT_SHOW) ?
                TaskStore::TOPIC_STAT_EDIT : TaskStore::TOPIC_STAT_SHOW ;
        }
    }
    public function addTopic() {
        $owner = TaskStore::getParam('userLogin') ;
        $newT = $this->parameters['addTopic'] ;
        $tId = $this->db->putTopic($newT) ;
        $this->topicList = $this->db->getTopic() ;
        $this->currentTopicId = $tId   ;
        $this->currentTopicStore()  ;
    }

    /**
     * Сохранить атрибуты тек темы
     */
    private function currentTopicStore() {
        $tId = $this->currentTopicId    ;
        $curTopic = $this->topicList[$tId] ;
        $tName = $curTopic['topicname'] ;
        TaskStore::setParam('topicId',$tId) ;
        TaskStore::setParam('topicName',$tName) ;
    }
    ////////////////////////////////////////////////////////////////////////////////
   public function getStatName() {
       $statName = ($this->topicEditStat == TaskStore::TOPIC_STAT_SHOW) ?
           TaskStore::STAT_SHOW_NAME : TaskStore::STAT_EDIT_NAME ;
       return $statName ;
   }
   public function getEditFlag() {
       return  ($this->topicEditStat == TaskStore::TOPIC_STAT_EDIT) ;
   }

    /**
     * Возможность добавления новых тем
     * @return bool
     */
   public function getAddTopicFlag() {
       return (TaskStore::getParam('userStatus') >= TaskStore::USER_STAT_ADMIN );
   }
   public function getTopicList() {
       return $this->topicList ;
    }
    public function getEditStat() {
        return $this->topicEditStat ;
    }
    public function getTopicId() {
        return $this->currentTopicId ;
    }
}