<?php
/**
 * Created by PhpStorm.
 * User: michael
 * Date: 08.06.15
 * Time: 20:24
 */

class Mod_profile extends Mod_base {
    protected $msg ;                         // объект для вывода сообщений
    protected $db = false ;                  // объект класса для связи с БД
    protected $dbClass = 'Db_user' ;             //  имя класса для работы с БД
    protected $parameters = [];              // параметры, принимаемые от контроллера
    //-----------------------------//
    private $profile = [] ;
    private $login ;
    private $password ;
    private $profileEditFlag = false ;      // true -> редактировать существующий профиль
    private $successfulSave = false ;       // удачное сохранение профиля
    private $profileError = false ;         // ошибка формирования профиля
    private $FIELDS_LIST =                   // список полей профиля
            'firstname,middlename,lastname,fileFoto,tel,email,sex,birthday' ;
    private $URL_DEFAULT ;                   // адрес начальной страницы
    //------------------------------//
    public function __construct() {
        parent::__construct() ;
    }
  /**
     *  определение собственных свойств из параметров
     */
    protected function init() {
        $this->setUser() ;
        if (isset($this->parameters['edit']) ) {
            $this->profileEditFlag = $this->parameters['edit'] ; // редакт существующий
        }
        if (! $this->profileEditFlag) {
            $this->login = $this->parameters['login'] ;
            $this->password = $this->parameters['password'] ;
        }
        $this->URL_DEFAULT = $this->parameters['urlDefault'] ;
    }

    /**
     * пользователь определяется из параметров профиля или атрибутовЗадачи
     */
    private function setUser() {
        if (isset($this->parameters['login'])) {
            $this->login = $this->parameters['login'] ;
            $this->password = $this->parameters['password'] ;
        }else {
            $this->login = TaskStore::getParam('userLogin') ;
            $this->password = TaskStore::getParam('password') ;
        }
    }
    /**
     * сохраняет профиль при первичной регистрации или изменениях
     */
    public function saveProfile() {
        $this->profile =  $this->profileIni();   // массив - список полей
        if (!$this->profileEditFlag) {
            if ((empty($this->login) || empty($this->password)) ) { // при редактировании не учитывается
                $this->profileError = true;
                $this->msg->addMessage('ERROR: Поля "login", "password" обязательны для заполнения! ');
                return false;
            }
        }
        $user_passw = $this->db->getUser($this->login);  // возвращает пару  [login,password]
        if (false === $user_passw && !$this->profileEditFlag) {    // новый пользователь - это хорошо
              $this->storeUser() ;  // пользователя занести в БД
        }
        if (false === $user_passw &&  $this->profileEditFlag )    {
            $this->profileError =true ;
            $this->msg->addMessage(
                'ERROR: Введенный "login" зарегистрирован ранее. Измените "login" !') ;
            return false;
        }
        $this->profileToDataBase() ;    // сохранить профиль
    }
    /**
     * Формирует список полей profile
     * @return array
     */
    private function profileIni() {
        $varList = $this->FIELDS_LIST ;   // список полей
        $arrName = explode(',', $varList);
        $fields = [] ;      // массив полей с их значением
        foreach ($arrName as $fieldName ) {
            $fields[$fieldName] = '' ;
        }
        return $fields ;
    }
    private function storeUser() {
        $userStatUser = TaskStore::USER_STAT_USER ;
        $userStatAdmin = TaskStore::USER_STAT_ADMIN ;
        $login = $this->login ;
        $password = $this->password ;
        $this->db->putUser($login, md5($password));    // пользователя занести в БД
        TaskStore::setParam('userLogin',$login);
        TaskStore::setParam('userName',$login) ;
        TaskStore::setParam('userPassword',$password) ;
        TaskStore::setParam('enterSuccessful',true) ;
        TaskStore::setParam('userStatus',$userStatUser) ;
        if ($login == 'admin' ) {
            TaskStore::setParam('userStatus',$userStatAdmin) ;
        }
    }
    private function profileToDataBase() {
        $this->profile['firstname'] = $this->parameters['firstname'];
        $this->profile['middlename'] = $this->parameters['middlename'];
        $this->profile['lastname']   = $this->parameters['lastname'];
        $this->profile['email']      = $this->parameters['email'];
        $this->profile['sex']        = $this->parameters['sex'];
        $year = $this->parameters['birthday_year'];
        $month = $this->parameters['birthday_month'];
        $day = $this->parameters['birthday_day'];
        $tm = mktime(0, 0, 0, $month, $day, $year);
        $this->profile['birthday']   = date('c', $tm);       // это хранится в БД

        $this->successfulSave = $this->db->putProfile($this->login, $this->profile);   // profile БД
        // эти поля не заносятся
        $this->profile['birthday_year']  = $this->parameters['birthday_year'];
        $this->profile['birthday_month'] = $this->parameters['birthday_month'];
        $this->profile['birthday_day']    = $this->parameters['birthday_day'];

    }

    /**
     *   Читать существующий профиль
     * @return bool
     */
    public function getProfile() {
        if ( empty(TaskStore::getParam('enterSuccessful')) ) {
            return false ;
        } else {      // читаем существующий профиль
            return  $this->db->getProfile($this->login);
        }
    }

    //--- возврат атрибутов в контроллер
    public function getLogin() {
        return $this->login ;

    }
    public function getPassword() {
        return $this->password ;
    }
    public function getEditFlag() {
        return $this->profileEditFlag ;
    }
    public function getSuccessful() {
        return $this->successfulSave ;
    }

    public  function getError() {
        return $this->profileError ;
    }
    public function getUrlDefault() {
        return $this->URL_DEFAULT ;
    }
}