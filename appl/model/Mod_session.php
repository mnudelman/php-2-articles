<?php
/**
 * сессии пользователей
 */

class Db_sessions extends Db_base {
    private static $instance = null ;
    private $msg ;
    private $db ;
    //--------------------------------//
    private function __construct() {
        $this->msg = Message::getInstace() ;
        $this->db = DbConnector::getConnect() ;
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
        $userId = $this->getUserId($userLogin) ;
        $sql = 'INSERT INTO sessions (userid,begtime,passwordsave) VALUES
                  (:userId , :begTime, :passwordsave)' ;
        $subst  = [
            'userId' => $userId,
            'begTime' => $begTime,
            'passwordsave' => $passSave ] ;
        $sessionId = $this->sqlExecute($sql,$subst,__METHOD__) ;
        TaskStore::setParam('sessionId',$sessionId) ;

    }
}