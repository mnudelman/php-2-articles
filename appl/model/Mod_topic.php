<?php
/**
 * класс для работы с темами(рубриками) статей
 * Date: 09.06.15
 * Time: 15:02
 */

class Mod_topic extends Mod_base {
    protected $msg ;                    // объект для вывода сообщений
    protected $db = false ;             // объект класса для связи с БД
    protected $dbClass = 'Db_article' ; //  имя класса для работы с БД
    protected $parameters = [];         // параметры, принимаемые от контроллера
    //------------------------------//
    private $permission ;              // класс Mod_permissions - разрешение на действие
    private $topicList ;               // список доступных альбомов
    private $currentTopicId ;          // Id текущей галереи
    private $topicEditStat ;           // редактирование/просмотр
    private $topicStatName ;           // тоже, только имя
    //----------------------------------------//
   public function __construct() {
       parent::__construct() ;
       $this->permission = new Mod_permissions() ;
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
        if ($this->currentTopicId !== $this->parameters['currentTopicId'] ) {
            $this->currentTopicId = $this->parameters['currentTopicId'] ;
            $this->currentTopicStore() ;
        }

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

    /**
     * возможность редактировать статьи
     */
   public function getEditFlag() {
       if ($this->topicEditStat == TaskStore::TOPIC_STAT_SHOW) {
           return false ;
       }
       $objName = TaskStore::OBJ_ARTICLE;
       TaskStore::setParam('currentObj',$objName) ;
       $owner= true ;
       $orderPerm = $this->permission->getPermissions($owner) ;  // обычные права
       return  (in_array('edit', $orderPerm));
   }

    /**
     * Возможность добавления новых тем
     * @return bool
     */
   public function getAddTopicFlag() {
       $objName = TaskStore::OBJ_TOPIC ;
       TaskStore::setParam('currentObj',$objName) ;
       $orderPerm = $this->permission->getPermissions() ;  // обычные права
       return  (in_array('create', $orderPerm));
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