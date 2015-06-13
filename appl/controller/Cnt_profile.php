<?php
/**
 * класс - редактирование профиля
 * Date: 25.05.15
 */

class Cnt_profile extends Cnt_base {
    protected $msg ;    // сообщения класса - объект Message
    protected $parListGet = [] ;  // параметры класса
    protected $parListPost = [] ;  // параметры класса
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
    public function __construct($getArray,$postArray) {
     parent::__construct($getArray,$postArray) ;
    }
    protected function prepare() {
        $this->URL_OWN = TaskStore::$htmlDirTop.'/index.php?cnt=Cnt_profile' ;
        $this->URL_DEFAULT = TaskStore::$htmlDirTop.'/index.php?cnt=Cnt_default' ;

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
        $this->parListPost['urlDefault'] = $this->URL_DEFAULT ;
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
            'urlToProfile'    => $this->URL_OWN,
            'urlToDefault'    => $this->mod->getUrlDefault(),
            'login'           => $this->mod->getLogin(),
            'password'        => $this->mod->getPassword() ,
            'profileEditFlag' => $this->mod->getEditFlag(),
            'successfulSave'  => $this->mod->getSuccessful(),
            'profile'         => $this->mod->getProfile(),
            'profileError'    => $this->mod->getError() ] ;
        parent::viewGo() ;
    }
}