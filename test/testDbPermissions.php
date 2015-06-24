<?php
/**
 * Начальное заполнение БД
 */
session_start();
ini_set('display_errors', 1);
//error_reporting(E_ALL) ;
error_reporting(E_ALL ^ E_NOTICE);
header('Content-type: text/html; charset=utf-8');
include_once __DIR__ . '/local.php';
///////////////////////////////////////////////////////////////////
class Db_permissions extends Db_base {
    public function fillTaskObjects() {
        $sql = 'INSERT INTO taskobjects (objectname) VALUES (:objectName)' ;
        $this->sqlExecute($sql,['objectName' => 'topic'],__METHOD__) ;
        $this->sqlExecute($sql,['objectName' => 'article'],__METHOD__) ;
        $this->sqlExecute($sql,['objectName' => 'comment'],__METHOD__) ;
    }
    public function fillTaskRoles() {
        $sql = 'INSERT INTO taskroles (rolename,facultative) VALUES (:roleName , :facultative)' ;
        $subst = [
            'roleName' => 'admin' ,
            'facultative' => 0 ] ;
        $this->sqlExecute($sql,$subst,__METHOD__) ;

        $subst = [
            'roleName' => 'user' ,
            'facultative' => 0 ] ;
        $this->sqlExecute($sql,$subst,__METHOD__) ;

        $subst = [
            'roleName' => 'guest' ,
            'facultative' => 0 ] ;
        $this->sqlExecute($sql,$subst,__METHOD__) ;

        $subst = [
            'roleName' => 'owner' ,
            'facultative' => 1 ] ;
        $this->sqlExecute($sql,$subst,__METHOD__) ;

    }
    public function fillTaskDoing() {
        $sql = 'INSERT INTO taskdoings (doingname,rang) VALUES (:doingName , :rang)' ;
        $subst = [
            'doingName' => 'read' ,
            'rang' => 1 ] ;
        $this->sqlExecute($sql,$subst,__METHOD__) ;

        $subst = [
            'doingName' => 'create' ,
            'rang' => 10 ] ;
        $this->sqlExecute($sql,$subst,__METHOD__) ;

        $subst = [
            'doingName' => 'edit' ,
            'rang' => 100 ] ;
        $this->sqlExecute($sql,$subst,__METHOD__) ;

        $subst = [
            'doingName' => 'delete' ,
            'rang' => 1000 ] ;
        $this->sqlExecute($sql,$subst,__METHOD__) ;
    }

    public function fillPermissions() {
        $sql1 = 'SELECT * FROM taskobjects' ;
        $objRows = $this->sqlExecute($sql1, [], __METHOD__);

        $sql2 = 'SELECT * FROM taskroles' ;
        $roleRows = $this->sqlExecute($sql2, [], __METHOD__);

        // загрузить пары objectid , roleid
        $sql = 'INSERT INTO permissions (objectid,roleid) VALUES (:objid , :roleid) ' ;
        foreach ($objRows as $obj) {
            $objid = $obj['objectid'] ;
            foreach ($roleRows as $role) {
                $roleid = $role['roleid'] ;
                $subst = [
                   'objid' => $objid,
                   'roleid'=> $roleid ] ;

                $this->sqlExecute($sql, $subst, __METHOD__);

            }
        }


    }
    public function updatePermissions() {
        $sql = 'UPDATE permissions SET totalrang = :totalrang
                WHERE permissions.roleid IN ( SELECT roleid FROM  taskroles
                                             WHERE taskroles.rolename = :rolename ) AND
                      permissions.objectid IN (SELECT objectid FROM taskobjects
                                              WHERE taskobjects.objectname = :objectname )' ;

        $subst = [
            'rolename' => 'admin',
            'objectname' => 'article',
            'totalrang' => 1001 ] ;
        $this->sqlExecute($sql, $subst, __METHOD__);

        $subst = [
            'rolename' => 'guest',
            'objectname' => 'article',
            'totalrang' => 1 ] ;
        $this->sqlExecute($sql, $subst, __METHOD__);


        $subst = [
            'rolename' => 'owner',
            'objectname' => 'article',
            'totalrang' => 100 ] ;
        $this->sqlExecute($sql, $subst, __METHOD__);

        $subst = [
            'rolename' => 'user',
            'objectname' => 'article',
            'totalrang' => 11 ] ;
        $this->sqlExecute($sql, $subst, __METHOD__);

//----------------------------------------------------------------------

        $subst = [
            'rolename' => 'admin',
            'objectname' => 'comment',
            'totalrang' => 1001 ] ;
        $this->sqlExecute($sql, $subst, __METHOD__);

        $subst = [
            'rolename' => 'guest',
            'objectname' => 'comment',
            'totalrang' => 1 ] ;
        $this->sqlExecute($sql, $subst, __METHOD__);


        $subst = [
            'rolename' => 'owner',
            'objectname' => 'comment',
            'totalrang' => 1100 ] ;
        $this->sqlExecute($sql, $subst, __METHOD__);

        $subst = [
            'rolename' => 'user',
            'objectname' => 'comment',
            'totalrang' => 11 ] ;
        $this->sqlExecute($sql, $subst, __METHOD__);

//----------------------------------------------------------


        $subst = [
            'rolename' => 'admin',
            'objectname' => 'topic',
            'totalrang' => 1011 ] ;
        $this->sqlExecute($sql, $subst, __METHOD__);

        $subst = [
            'rolename' => 'guest',
            'objectname' => 'topic',
            'totalrang' => 1 ] ;
        $this->sqlExecute($sql, $subst, __METHOD__);


        $subst = [
            'rolename' => 'owner',
            'objectname' => 'topic',
            'totalrang' => 0 ] ;
        $this->sqlExecute($sql, $subst, __METHOD__);

        $subst = [
            'rolename' => 'user',
            'objectname' => 'topic',
            'totalrang' => 1 ] ;
        $this->sqlExecute($sql, $subst, __METHOD__);




    }

}

$fill = new Db_permissions() ;
//$fill->fillTaskObjects() ;
//$fill->fillTaskRoles() ;

//$fill->fillTaskDoing() ;
//$fill->fillPermissions() ;
//$fill->selectPermissions() ;
$fill->updatePermissions() ;
$msg = Message::getInstace() ;
$messages = $msg->getMessages() ;
if (is_array($messages)) {
    foreach ($messages as $text) {
        print_r($text) ;
        echo TaskStore::LINE_FEED ;
    }
}