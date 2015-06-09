<?php
/**
 * класс - редактирование профиля
 * Date: 25.05.15
 */

class cnt_profile extends cnt_base {
    protected $msg ;    // сообщения класса - объект Message
    protected $parListGet = [] ;  // параметры класса
    protected $parListPost = [] ;  // параметры класса
    protected $msgTitle = '' ;
    protected $msgName = '' ;
    protected $modelName = 'mod_profile' ;
    protected $mod ;
    protected $parForView = [] ; //  параметры для передачи view
    protected $nameForView = 'cnt_profile' ; // имя для передачи в ViewDriver
    protected $nameForStore = 'cnt_profileStore'; // имя строки параметров в TaskStore
    protected $ownStore = [] ;     // собственные сохраняемые параметры
    protected $forwardCntName = false ; // контроллер, которому передается управление
    //-----------------------------------//
    private $CNT_HOME = 'cnt_default' ;
    private $URL_TO_PROFILE ;               // адрес перехода на  cnt_profile
    private $URL_TO_DEFAULT ;               // адрес перехода на  cnt_default
    private $profileEditFlag = false ;      // признак редактирования профиля - сохраняемый
    //-----------------------------------//
    public function __construct($getArray,$postArray) {
     parent::__construct($getArray,$postArray) ;
    }
    protected function prepare() {
        $this->URL_TO_PROFILE = TaskStore::$htmlDirTop.'/index.php?cnt=cnt_profile' ;
        $this->URL_TO_DEFAULT = TaskStore::$htmlDirTop.'/index.php?cnt=cnt_default' ;

        if (isset($this->ownStore['profileEditFlag'])) {
            $this->profileEditFlag = $this->ownStore['profileEditFlag'] ;



            if ($this->profileEditFlag) {
                $this->parListPost['edit'] = true ;    // добавим в общий список параметров
            }else {
                $this->parListPost['edit'] = false ;    // добавим в общий список параметров
            }
        }

        if (isset($this->parListGet['edit'])) {    // вход для редактирования
            $pG = $this->parListGet['edit'] ;
            $this->parListPost['edit'] = $pG ;    // добавим в общий список параметров
            $this->profileEditFlag = $pG ;
        }

        $this->mod->setParameters($this->parListPost) ; // параметры-реквизиты в модель

       if (isset($this->parListPost['exit'])) {    // выйти
            $this->forwardCntName = $this->CNT_HOME ;
        }elseif (isset($this->parListPost['save'])) {    // создать / обновить профиль пользователя
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
     * сохранить массив параметров
     */
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
        return $this->forwardCntName ;
    }
    public function viewGo() {      // атрибуты для формы
        $this->parForView = [
            'urlToProfile'    => $this->URL_TO_PROFILE,
            'urlToDefault'    => $this->URL_TO_DEFAULT,
            'login'           => $this->mod->getLogin(),
            'password'        => $this->mod->getPassword() ,
            'profileEditFlag' => $this->mod->getEditFlag(),
            'successfulSave'  => $this->mod->getSuccessful(),
            'profile'         => $this->mod->getProfile(),
            'profileError'    => $this->mod->getError() ] ;
        parent::viewGo() ;
    }
}