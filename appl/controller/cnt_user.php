<?php
/**
 * контроллер регистрации
 * Date: 23.05.15
 */

class cnt_user extends cnt_base {
    protected $msg ;    // сообщения класса - объект Message
    protected $parListPost = [] ;  // параметры класса
    protected $parListGet = [] ;  // параметры класса
    protected $subController = false ; // контроллер уровня +1
    protected $msgTitle = '' ;
    protected $modelName = 'mod_user' ;
    protected $mod ;             // объект - модель
    protected $parForView = [] ; //  параметры для передачи view
    protected $nameForView = 'cnt_user' ; // имя для передачи в ViewDriver
    protected $forwardCntName = false ; // контроллер, которому передается управление
    //--------------------------------//
    private $CNT_HOME = 'cnt_default' ;
    private $CNT_PROFILE = 'cnt_profile' ;
    private $URL_TO_PROFILE ;         // адрес для перехода по cnt_profile
    private $URL_TO_USER ;            // адрес для перехода по cnt_user
    private $profileStat ;            // статус перехода в профиль

    //------------------------------------//
    public function __construct($getArray,$postArray) {
        parent::__construct($getArray,$postArray) ;
        $this->URL_TO_PROFILE = TaskStore::$htmlDirTop.'/index.php?cnt=cnt_profile' ;
        $this->URL_TO_USER = TaskStore::$htmlDirTop.'/index.php?cnt=cnt_user' ;

    }
    protected function prepare() {
        if (isset($this->parListPost['exit'])) {              // выход - возврат на главную
            $this->forwardCntName = $this->CNT_HOME ;
        }elseif (isset($this->parListPost['profile']) ) {    //  переход в профиль
            if ($this->mod->isGoProfile()) {
                $this->forwardCntName = $this->CNT_PROFILE ;
                $this->profileStat = TaskStore::PROFILE_STAT_EDIT ;

            }
        }elseif (isset($this->parListPost['registration']))  {  // первичная регистрация
            $this->forwardCntName = $this->CNT_PROFILE ;
            $this->profileStat = TaskStore::PROFILE_STAT_REGISTRATION ;
        }

        elseif (isset($this->parListPost['enter'])  ) {     // ввод login,passworf
            $login = $this->parListPost['login'];
            $password = $this->parListPost['password'];
            $this->mod->setLogin($login) ;
            $this->mod->setPassword($password) ;
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
    public function getForwardCntName(&$plistGet,&$plistPost) {
        $plistGet = [] ;
        $plistPost = [] ;

        if ($this->forwardCntName == $this->CNT_PROFILE) { // редактирование существующего профиля
            if ($this->profileStat == TaskStore::PROFILE_STAT_EDIT) {
                $plistGet = ['edit' => true];
            }else {
                $plistGet = ['edit' => false];
            }
        }
        return $this->forwardCntName ;
      //  parent::getForwardCntName($plistGet,$plistPost) ;
    }
    /**
     * переход на собственную форму
     */
    public function viewGo() {
        $login = $this->mod->getLogin();
        $password = $this->mod->getPassword() ;
        $profileIsPossible = ($login === TaskStore::getParam('userLogin')) ?
            TaskStore::getParam('enterSuccessful') : false ;
        $this->parForView = [
            'login'    => $login,
            'password' => $password ,
            'profileIsPossible' => $profileIsPossible,
            'urlToProfile' => $this->URL_TO_PROFILE,
            'urlToUser' => $this->URL_TO_USER ] ;
       parent::viewGo() ;
    }
}