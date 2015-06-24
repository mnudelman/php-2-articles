<?php
/**
 * Created by PhpStorm.
 * User: michael
 * Date: 24.06.15
 * Time: 21:14
 */

class Db_session extends Db_base {
public function addSession($userLogin,$begTime,$passSave) {
    $sessionId = $this->db->addSession($userLogin,$begTime,$passSave) ;

    $sql = 'INSERT INTO sessions (userid,begtime,passwordsave) VALUES
                  (:userId , :begTime, :passwordsave)' ;
    $userId = $this->getUserId($userLogin) ;
    $subst  = [
        'userId' => $userId,
        'begTime' => $begTime,
        'passwordsave' => $passSave ] ;
    return $this->sqlExecute($sql,$subst,__METHOD__) ;

}
}