<?php
/**
 * сессии пользователей
 */

class Mod_session {
    private static $instance = null ;
    private $msg ;
    private $db ;
    //--------------------------------//
    private function __construct() {
        $this->msg = Message::getInstace() ;
        $this->db = new Db_session()  ;
    }
    public static function getInstace() {
        if (is_null(self::$instance) ) {
            self::$instance = new self()  ;
        }

        return self::$instance ;
    }

    /**
     * запускается в момент регистрации
     */
    public function newSession() {
        $userLogin = TaskStore::getParam('userLogin') ;
        $password =  TaskStore::getParam('userPassword') ;
        $passSave =  TaskStore::getParam('passwSave') ;
        if (! TaskStore::getParam('enterSuccessful') ){
            $this->msg->addMessage('ERROR: Не выполнен вход на сайт.') ;
            return false ;
        }


        if (false === ($sessionId = $this->db->addSession($userLogin,$passSave) ) ) {
            $this->msg->addMessage('ERROR:Ошибка начала сессии.') ;
            TaskStore::userClear() ;
            return false ;
        }

        TaskStore::setParam('sessionId',$sessionId) ;
        if ($passSave) {
            $this->putCookies() ;
        }
    }
    private function  putCookies() {
        $fingerprint = $this->fingerPrintClc() ;
        $dTime = TaskStore::COOKIES_TIME ;
        setcookie('fingerprint',$fingerprint,time() + $dTime) ;

    }

    /**
     * новое время активности
     */
    public function setTime() {
        if (! TaskStore::getParam('enterSuccessful') ){
            return false ;
        }



        $newTime = mktime(date('c')) ;
        $sessionId = TaskStore::getParam('sessionId') ;
        $deltaTime = TaskStore::SESSION_TIME ;
        $passSave =  TaskStore::getParam('passwSave') ;
        $currentSession  = $this->db->getSession($sessionId ) ;
        $currentBegTime = mktime($currentSession['begTime'] ) ;
        if (($newTime - $currentBegTime) <= $deltaTime ) {

            $this->db->setNewTime($sessionId,$newTime ) ;
            return true ;
        }

        if (($newTime - $currentBegTime) <= 2*$deltaTime && $passSave) {
            if (( $flag = $this->isCookiesCorrect()) ) {
                $this->db->setNewTime($sessionId ) ;
                return true ;
            }
        }
        TaskStore::userClear() ;
        return false ;

    }
    private function isCookiesCorrect() {
        return ( $_COOKIE["fingerprint"] == $this->fingerPrintClc() ) ;
    }

    private function fingerPrintClc() {
        $login = TaskStore::getParam('userLogin') ;
        $password = TaskStore::getParam('userPassword') ;
        $cookiesWord = TaskStore::COOKIES_WORD ;
        return md5($cookiesWord.$login.$password) ;

    }
}