<?php
/**
 * контроллер регистрации
 * Date: 23.05.15
 */

class Cnt_user extends Cnt_base {
    protected $msg ;    // сообщения класса - объект Message
    protected $parameters = [] ;  // параметры класса
    protected $parListGet = [] ;  // параметры класса
    protected $subController = false ; // контроллер уровня +1
    protected $msgTitle = '' ;
    protected $modelName = 'Mod_user' ;
    protected $mod ;             // объект - модель
    protected $parForView = [] ; //  параметры для передачи view
    protected $classForView = 'Cnt_vw_user' ; // класс параметров для представлений
    protected $forwardCntName = false ; // контроллер, которому передается управление
    protected  $URL_OWN ;                  // адрес для перехода в контроллер

    //--------------------------------//
    private $CNT_HOME = 'Cnt_default' ;
    private $CNT_PROFILE = 'Cnt_profile' ;
    private $URL_PROFILE ;         // адрес для перехода по cnt_profile
    private $profileStat ;            // статус перехода в профиль

    //------------------------------------//
    public function __construct() {
        parent::__construct() ;
    }
    protected function prepare() {
        $this->URL_PROFILE = TaskStore::$htmlDirTop.'/index.php?cnt=Cnt_profile' ;
        $this->URL_OWN = TaskStore::$htmlDirTop.'/index.php?cnt=Cnt_user' ;


        $this->mod->setUrlProfile($this->URL_PROFILE) ;

        if (isset($this->parameters['exit'])) {              // выход - возврат на главную
            $this->forwardCntName = $this->CNT_HOME ;
        }

        if (isset($this->parameters['profile']) ) {    //  переход в профиль
            if ($this->mod->isGoProfile()) {
                $this->forwardCntName = $this->CNT_PROFILE ;
                $this->profileStat = TaskStore::PROFILE_STAT_EDIT ;

            }
        }

        if (isset($this->parameters['registration']))  {  // первичная регистрация
            $this->forwardCntName = $this->CNT_PROFILE ;
            $this->profileStat = TaskStore::PROFILE_STAT_REGISTRATION ;
        }

        if (isset($this->parameters['enter'])  ) {     // ввод login,passworf
            $this->mod->setLogin($this->parameters['login']) ;
            $this->mod->setPassword($this->parameters['password']) ;
            if ($this->mod->isUserLoginSuccessful()) {           // вход выполнен
                $this->forwardCntName = $this->CNT_HOME;
            }
        }
    }
    /**
     * выдает имя контроллера для передачи управления
     * альтернатива viewGo
     * Через  $pListGet , $pListPost можно передать новые параметры
     */
    public function getForwardCntName() {
        $plistGet = [] ;
        if ($this->forwardCntName == $this->CNT_PROFILE) { // редактирование существующего профиля
            if ($this->profileStat == TaskStore::PROFILE_STAT_EDIT) {
                $plistGet = ['edit' => true];
            }else {
                $plistGet = ['edit' => false];
            }
        }
        $this->taskParms->setParameters($plistGet) ;
        return $this->forwardCntName ;
    }
}