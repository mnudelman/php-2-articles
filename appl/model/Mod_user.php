<?php
/**
 * класс поддержки регистрации
 * Date: 08.06.15
 */

class mod_user extends Mod_base {
    protected $msg ;            // объект для вывода сообщений
    protected $db = false ;             // объект класса для связи с БД
    protected $dbClass = 'Db_user' ;        //  имя класса для работы с БД
    protected $parameters = []; // параметры, принимаемые от контроллера
    //----------------------------//
    private $login ;
    private $password ;
    private $userStatus ;
    private $URL_PROFILE ;
    //----------------------------------------------------//
    public function __construct() {
        parent::__construct() ;
    }
    /**
     * это передача атрибутов из контроллера
     */
    public function setParameters($parameters) {
        parent::setParameters($parameters) ;
    }

    /**
     * метод определения собственных свойств из параметров
     */
    protected function init() {
        $this->login = TaskStore::getParam('userLogin') ;
        $this->userStatus = TaskStore::getParam('userStatus') ;
    }

    /**
     * Возможность перехода в профиль
     */
    public function isGoProfile() {
       return( $this->userStatus >= TaskStore::USER_STAT_USER  &&
               !empty($this->login)) ;
    }
    public function getLogin() {
        return $this->login ;
    }
    public function getPassword() {
        return $this->password ;
    }
    public function setLogin($login) {
        $this->login = $login ;
    }
    public function setPassword($password) {
        $this->password = $password ;
    }

    /**
     * @param $url - адрес перехода на профильПользователя
     */
    public function setUrlProfile($url) {
        $this->URL_PROFILE = $url ;
    }
    public function getUrlProfile() {
        return $this->URL_PROFILE ;
    }
    /**
     * допустимость новых login, password
     * @param $login
     * @param $password
     * @return bool
     */
    public function isUserLoginSuccessful() {
        $isSuccessful = false;
        $login = $this->login   ;
        $password = $this->password   ;
        if (empty($login) || empty($password)) {
            $this->msg->addMessage('ERROR:Поля "Имя:" и "Пароль:" должны быть заполнены !');
        } else {
            $userPassw = $this->db->getUser($login);
            if (false === $userPassw) { // $login отсутствует в БД
                $this->msg->addMessage('ERROR: Недопустимое имя пользователя.Повторите ввод!');
            } else {  // проверяем пароль
                $fromDbPassw = $userPassw['password'];
                if ($fromDbPassw !== md5($password)) {
                    $this->msg->addMessage('ERROR: Неверный пароль.Повторите ввод !');
                } else {
                    $isSuccessful = true;
                    $this->storeUser();
                }
            }
        }
        return $isSuccessful;
    }
    private function storeUser() {
        TaskStore::setParam('userLogin', $this->login);
        TaskStore::setParam('userName', $this->login);
        TaskStore::setParam('userPassword', $this->password);
        TaskStore::setParam('enterSuccessful', true);
        TaskStore::setParam('userStatus', TaskStore::USER_STAT_USER);
        if ('admin' == $this->login) {
            TaskStore::setParam('userStatus', TaskStore::USER_STAT_ADMIN);
        }
    }

}