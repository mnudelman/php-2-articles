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
        $begTime = mktime(date('c')) ;
        $userLogin = TaskStore::getParam('userLogin') ;
        $password =  TaskStore::getParam('userPassword') ;
        $passSave =  TaskStore::getParam('passwSave') ;
        if (false === ($sessionId = addSession($userLogin,$begTime,$passSave) ) ) {
            // сбросить в гость и сообщение
        }

        TaskStore::setParam('sessionId',$sessionId) ;
        if ($passSave) {
            $this->putCookies() ;
        }
    }
    private function  putCookies() {

    }

    /**
     * новое время активности
     */
    public function setTime() {
        $newTime = mktime(date('c')) ;
        $sessionId = TaskStore::getParam('sessionId') ;
        $deltaTime = TaskStore::SESSION_TIME ;
        $passSave =  TaskStore::getParam('passwSave') ;
        $currentSession  = $this->db->getCurrentSession($sessionId ) ;
        $currentBegTime = $currentSession['begTime'] ;
        if (($newTime - $currentBegTime) <= $deltaTime ) {
            $this->db->setNewTime($sessionId,$newTime ) ;
            return true ;
        }
        if (($newTime - $currentBegTime) <= 2*$deltaTime && $passSave) {
            if ($this->isCookiesCorrect() ) {
                $this->db->setNewTime($sessionId,$newTime ) ;
                return true ;
            }
        }
        $this->sessionClear() ;
        return false ;

    }
    private function isCookiesCorrect() {
        return true ;
    }
    private function sessionClear() {

    }
}