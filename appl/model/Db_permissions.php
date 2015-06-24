<?php
/**
 * Created by PhpStorm.
 * User: michael
 * Date: 24.06.15
 * Time: 12:42
 */

class Db_permissions extends Db_base {
    public function getTotalRang($objName,$roleName) {
        $sql = 'SELECT totalrang
                       FROM permissions
                       WHERE permissions.objectid IN
                             (SELECT objectid FROM taskobjects WHERE objectname = :objectName) AND
                             permissions.roleid IN
                             (SELECT roleid FROM taskroles WHERE rolename = :roleName) ' ;
        $subst = [
            'objectName' => $objName,
            'roleName'   => $roleName ] ;
        if (false === ($rows = $this->sqlExecute($sql,$subst,__METHOD__))) {
            return false ;
        }
        $row = $rows[0] ;
        return $row['totalrang'] ;
    }
    public function getDoings() {
        $sql = 'SELECT doingname,rang FROM taskdoings order by rang' ;
        if (false === ($rows = $this->sqlExecute($sql,[],__METHOD__))) {
            return false ;
        }
        return $rows ;
    }

}