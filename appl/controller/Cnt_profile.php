<?php
/**
 * контроллер - редактирование профиля
 * Date: 25.05.15
 */

class Cnt_profile extends Cnt_base {
    protected $msg ;    // сообщения класса - объект Message
    protected $parameters = [] ;  // параметры класса
    protected $msgTitle = '' ;
    protected $msgName = '' ;
    protected $modelName = 'Mod_profile' ;
    protected $mod ;
    protected $parForView = [] ; //  параметры для передачи view
    protected $classForView = 'Cnt_vw_profile' ; // класс для связи с представлением
    protected $nameForStore = 'cnt_profileStore'; // имя строки параметров в TaskStore
    protected $ownStore = [] ;     // собственные сохраняемые параметры
    protected $forwardCntName = false ; // контроллер, которому передается управление
    protected $URL_OWN ;               // адрес перехода на  cnt_profile
    //-----------------------------------//
    private $CNT_HOME = 'Cnt_default' ;
    private $URL_DEFAULT ;               // адрес перехода на  cnt_default
    private $profileEditFlag = false ;      // признак редактирования профиля - сохраняемый
    //-----------------------------------//
    public function __construct() {
     parent::__construct() ;
    }
    protected function prepare() {
        $this->URL_OWN = TaskStore::$htmlDirTop.'/Cnt_profile' ;
        $this->URL_DEFAULT = TaskStore::$htmlDirTop.'/Cnt_default' ;

        $this->mod->setUrlDefault($this->URL_DEFAULT) ;

        if (isset($this->parameters['edit'])) {    // вход для редактирования
            $this->profileEditFlag = $this->parameters['edit'] ;
        }elseif (isset($this->ownStore['profileEditFlag'])) {
            $this->profileEditFlag = $this->ownStore['profileEditFlag'] ;
            $this->taskParms->setParameter('edit',$this->profileEditFlag) ;
            $this->parameters = $this->taskParms->getParameters() ;
        }



        $this->taskParms->setParameter('urlDefault',$this->URL_DEFAULT) ;

       if (isset($this->parameters['exit'])) {    // выйти
            $this->forwardCntName = $this->CNT_HOME ;
        }

        if (isset($this->parameters['save'])) {    // создать / обновить профиль пользователя
            $this->mod->saveProfile() ;
        }
        parent::prepare() ;
    }
    /**
     *  построить массив $ownStore - собственные параметры
     */
    protected function buildOwnStore() {
        $this->ownStore = [                  // сохраняемые параметры
            'profileEditFlag' => $this->profileEditFlag  ];
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
}