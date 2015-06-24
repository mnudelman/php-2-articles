<?php
/**
 * Регистрация - работа с БД
 */

class Db_user extends Db_base {
    public function getUser($userLogin) {
        // $userPassw = [] ;  // ['login' => login, 'password' => $password ]
        $sql = 'SELECT * FROM users WHERE login = :login' ;
        $subst = ['login'=>$userLogin] ;
        $rows = $this->sqlExecute($sql,$subst,__METHOD__) ;
        if (false === $rows) {
            return false ;
        }
        $row = $rows[0] ;
        if (!empty($row)){
            return ['login   ' => $row['login'],
                'password' => $row['password'] ] ;
        }else {
            return false ;
        }
    }

    /**
     * запоминает пользователя в БД
     */
    public function putUser($userLogin,$password) {
        $logPassw = $this->getUser($userLogin) ;
        if (!(false === $logPassw)) {      // уже есть в БД
            return ($logPassw['password'] == $password) ;
        }
        $sql = 'INSERT INTO  users (login,password) VALUES (:login,:password)' ;
        $subst = [
            'login'=>$userLogin,
            'password'=>$password] ;
        $this->sqlExecute($sql,$subst,__METHOD__) ;
        return true ;
    }
    public function updatePassword($userLogin,$newPassword){
        $logPassw = $this->getUser($userLogin) ;
        if ( false === $logPassw ) {      // нет в БД - это ошибка !!
            return false ;
        }
        $sql = 'UPDATE  users set password = :password where login = :login' ;
        $subst = ['login'=>$userLogin,
            'password'=>$newPassword] ;
        $this->sqlExecute($sql,$subst,__METHOD__) ;
        return true ;
    }

    /**
     * возвращает  profile пользователя
     */
    public function getProfile($userLogin) {
        $profile = []; // ['fieldName' => fieldMean, .....]
        $sql = 'SELECT * FROM userprofile where userprofile.userid IN
                        (SELECT userid FROM users WHERE login = :login )';
        $subst = ['login' => $userLogin] ;
        $rows = $this->sqlExecute($sql,$subst,__METHOD__) ;

        if (false === $rows) {
            return false;
        }
        $row = $rows[0] ;
        if (0 == $this->getRowCount()) {
            return $this->profileIni() ;
        }
        foreach ($row  as $fldName => $fldMean) {
            $profile[$fldName] = $fldMean;
        }
// birthday : YYYY-mm_ddT....
        $birthdayComponents = $this->getDateComponents($profile['birthday']) ;
        $profile['birthday_year']  = $birthdayComponents['year'];
        $profile['birthday_month'] = $birthdayComponents['month'];
        $profile['birthday_day']   = $birthdayComponents['day'];
        return $profile;
    }

    /**
     * разложение типа date на компоненты
     * date: YYYY-mm-ddT....
     */
    private function getDateComponents($date) {
        $arr = explode('-', $date);
        $dateComponents = [] ;
        $dateComponents['year'] = $arr[0];
        $dateComponents['month'] = $arr[1];
        $dT = explode('T', $arr[2]);
        $dateComponents['day'] = $dT[0] ;
        return $dateComponents ;
    }

    /**
     * сохраняет profile пользователя
     */
    public function putProfile($userLogin,$profile) {
        $sqlQuery = 'UPDATE userprofile SET ' ;
        $setLine = '' ;
        foreach ($profile as $fldName => $fldMean) {
          // $tp = gettype($fldMean) ;
          //  if ('string' == $tp) {
                $li = $fldName.'='.'"'.$fldMean.'"' ;
           // }else {
           //     $li = $fldName.'='.$fldMean ;
           // }
            $setLine .= (empty($setLine)) ? $li : ','.$li ;
        }
        $where = ' WHERE userprofile.userid IN
                  (SELECT userid FROM users WHERE login = :login )' ;
        $sql = $sqlQuery.$setLine.$where ;
        $subst = ['login'=>$userLogin] ;
        $this->sqlExecute($sql,$subst,__METHOD__) ;

        return true ;
    }
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
    /**
     * Формирует список полей profile
     * @return array
     */
    public function profileIni() {
        $varList = 'firstname,middlename,lastname,fileFoto,tel,email,sex,birthday' ;   // список полей
        $arrName = explode(',', $varList);
        $fields = [] ;      // массив полей с их значением
        foreach ($arrName as $fieldName ) {
            $fields[$fieldName] = '' ;
        }
        return $fields ;
    }
}
