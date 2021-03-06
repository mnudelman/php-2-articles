<?php
/**
 * контроллер - темы статей
 */

class Cnt_topic extends Cnt_base {
    protected $msg ;    // сообщения класса - объект Message
    protected $parListGet = [] ;  // параметры класса
    protected $parameters = [] ;  // параметры класса
    protected $msgTitle = '' ;
    protected $msgName = '' ;
    protected $modelName = 'Mod_topic' ;
    protected $mod ;
    protected $parForView = [] ;   // параметры для передачи view
    protected $classForView = 'Cnt_vw_topic' ;  // имя для передачи в ViewDriver
    protected $nameForStore = 'cnt_topicStore' ; // имя строки параметров в TaskStore
    protected $ownStore = [] ;     // собственные сохраняемые параметры
    protected $forwardCntName = false ; // контроллер, которому передается управление
    protected $URL_OWN = false ;     // адрес возврата в контроллер
    //-----------------------------------------------//
    //-------- url ------------------------------//
    private $CNT_HOME = 'Cnt_default' ;      // контроллер пустой
    private $CNT_ARTICLE = 'Cnt_article' ;   // контроллер статей
    //--------------------------------------------//
    private $articleStatEdit ;

    public function __construct() {
        parent::__construct() ;
    }
    protected function prepare() {

        $this->URL_OWN = TaskStore::$htmlDirTop.'/Cnt_topic' ;

        if (isset($this->parameters['exit'])) {      // выход (в "главный" index )
            $this->forwardCntName = $this->CNT_HOME ;
        }

        if (isset($this->parameters['changeStat'])) {       // сменить режим ( SHOW <-> EDIT )
            $this->mod->changeStat();
        }

        if (isset($this->parameters['goShow'])) {   //   в просмотр альбома
            $this->forwardCntName = $this->CNT_ARTICLE;
            $this->articleStatEdit = TaskStore::ARTICLE_STAT_SHOW;
        }
        if (isset($this->parameters['editArticle'])) {   //   редактировать
            $this->forwardCntName = $this->CNT_ARTICLE;
            $this->articleStatEdit = TaskStore::ARTICLE_STAT_EDIT ;
        }

        if (isset($this->parameters['addTopicExec'])) {   //   добавить в список новыйАльбом
            $this->mod->addTopic();
        }

        parent::prepare() ;
    }
    ////////////////////////////////////////////////////////////////////////////////
    /**
     * выдает имя контроллера для передачи управления
     * альтернатива viewGo
     * Через  $pListGet , $pListPost можно передать новые параметры
     */
    public function getForwardCntName() {
        $plistGet = [] ;
        if ($this->forwardCntName == $this->CNT_ARTICLE) {
            if ($this->articleStatEdit == TaskStore::ARTICLE_STAT_SHOW) {
                $plistGet = ['show' => true] ;
            }else {
                $plistGet = ['edit' => true] ;
            }
        }
        $this->taskParms->setParameters($plistGet) ;
        return $this->forwardCntName ;
    }
}