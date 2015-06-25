<?php
/**
 * Created by PhpStorm.
 * User: michael
 * Date: 24.06.15
 * Time: 21:14
 */

class Db_session extends Db_base {
    private $dbArticle = false ;     // класс Db_user
    public function __construct() {
        parent::__construct() ;
        $this->dbArticle = new Db_article() ;
    }

    public function addSession($userLogin,$passSave) {
    $sql = 'INSERT INTO sessions (userid,begtime,passwordsave) VALUES
                  (:userId , :begTime, :passwordsave)' ;
    $userId = $this->dbArticle->getUserId($userLogin) ;
    $subst  = [
        'userId' => $userId,
        'begTime' => date('c'),
        'passwordsave' => $passSave ] ;

        return $this->sqlExecute($sql,$subst,__METHOD__) ;

}
    public function getSession($sessionId) {
        $sql = 'SELECT * FROM sessions WHERE id = :sessionId' ;
        $subst = ['sessionId' => $sessionId] ;
        $rows = $this->sqlExecute($sql,$subst,__METHOD__) ;
        $row = $rows[0] ;
        return [
           'sessionId' => $row['sessionid'],
            'begTime'  => $row['begtime'],
            'endTime'  => $row['endtime'],
            'passwordSave' => $row['passwordsave']
        ] ;

    }
    public function setNewTime($sessionId) {
        $sql = 'UPDATE sessions SET begtime = :begTime WHERE id = :sessionId' ;
        $subst = [
            'sessionId' => $sessionId,
            'begTime' => date('c') ] ;
        $n = $this->sqlExecute($sql,$subst,__METHOD__) ;
        return (1 == $n ) ;
    }
}