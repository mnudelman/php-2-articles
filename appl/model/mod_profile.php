<?php
/**
 * Created by PhpStorm.
 * User: michael
 * Date: 08.06.15
 * Time: 20:24
 */

class mod_profile extends mod_base {
    private $dbUser ;     // объект класса db_user - связь с БД
    private $profile = [] ;
    private $login ;
    private $password ;
    private $profileEditFlag = false ;      // true -> редактировать существующий профиль
    private $successfulSave = false ;       // удачное сохранение профиля
    private $profileParms= [] ;             // массив параметров-полей профиля
    private $profileError = false ;         // ошибка формирования профиля
    private $FIELDS_LIST =                   // список полей профиля
            'firstname,middlename,lastname,fileFoto,tel,email,sex,birthday' ;
    //------------------------------//
    public function __construct() {
        parent::__construct() ;
        $this->dbUser = new db_user() ;
    }

    /**
     * это передача атрибутов пофиля из контроллера
     */
    public function setParameters($profileParms) {
        $this->profileParms = $profileParms ;
        $this->setUser() ;
        if (isset($this->profileParms['edit']) ) {
            $this->profileEditFlag = $this->profileParms['edit'] ; // редакт существующий
        }
        if (! $this->profileEditFlag) {
            $this->login = $this->profileParms['login'] ;
            $this->password = $this->profileParms['password'] ;
        }
    }

    /**
     * пользователь определяется из параметров профиля или атрибутовЗадачи
     */
    private function setUser() {
        if (isset($this->profileParms['login'])) {
            $this->login = $this->profileParms['login'] ;
            $this->password = $this->profileParms['password'] ;
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
        $user_passw = $this->dbUser->getUser($this->login);  // возвращает пару  [login,password]
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
        $this->dbUser->putUser($login, md5($password));    // пользователя занести в БД
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
        $this->profile['firstname'] = $this->profileParms['firstname'];
        $this->profile['middlename'] = $this->profileParms['middlename'];
        $this->profile['lastname']   = $this->profileParms['lastname'];
        $this->profile['email']      = $this->profileParms['email'];
        $this->profile['sex']        = $this->profileParms['sex'];
        $year = $this->profileParms['birthday_year'];
        $month = $this->profileParms['birthday_month'];
        $day = $this->profileParms['birthday_day'];
        $tm = mktime(0, 0, 0, $month, $day, $year);
        $this->profile['birthday']   = date('c', $tm);       // это хранится в БД

        $this->successfulSave = $this->dbUser->putProfile($this->login, $this->profile);   // profile БД
        // эти поля не заносятся
        $this->profile['birthday_year']  = $this->profileParms['birthday_year'];
        $this->profile['birthday_month'] = $this->profileParms['birthday_month'];
        $this->profile['birthday_day']    = $this->profileParms['birthday_day'];

    }

    /**
     *   Читать существующий профиль
     * @return bool
     */
    public function getProfile() {
        if ( empty(TaskStore::getParam('enterSuccessful')) ) {
            return false ;
        } else {      // читаем существующий профиль
            return  $this->dbUser->getProfile($this->login);
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
}