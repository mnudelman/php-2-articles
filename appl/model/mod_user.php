<?php
/**
 * класс поддержки регистрации
 * Date: 08.06.15
 */

class mod_user extends mod_base {
    private $dbUser ;     // объект класса db_user - связь с БД
    private $login ;
    private $password ;
    private $userStatus ;
    //----------------------------------------------------//
    public function __construct() {
        parent::__construct() ;
        $this->dbUser = new db_user() ;
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
            $userPassw = $this->dbUser->getUser($login);
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