<?php
/**
 * класс для работы с темами(рубриками) статей
 * Date: 09.06.15
 * Time: 15:02
 */

class mod_topic extends mod_base {
    private $db ;                      // объект для связи с БД
    private $parameters ;              //
    private $topicList ;               // список доступных альбомов
    private $currentTopicId ;          // Id текущей галереи
    private $topicEditStat ;           // редактирование/просмотр
    private $topicStatName ;           // тоже, только имя
    //----------------------------------------//
   public function __construct() {
       $this->db = new db_article() ;
       $this->topicEditStat = TaskStore::TOPIC_STAT_SHOW ;   // по умолчанию - просмотр
       $this->currentTopicId = TaskStore::getParam('topicId') ;
   }
    /**
     * это передача атрибутов пофиля из контроллера
     */
    public function setParameters($parameters) {
        $this->parameters = $parameters ;
        $this->init() ;
    }
    private function init() {
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