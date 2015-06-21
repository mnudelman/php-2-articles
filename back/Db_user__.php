<?php
/**
 * Модель обеспечивает взаимодействие с БД для подсистемы регистрацииПользователя
 * Date: 24.05.15
 */

class Db_user extends Db_base {
    public function __construct() {
        parent::__construct() ;
    }

    public function getUser($userLogin) {
        // $userPassw = [] ;  // ['login' => login, 'password' => $password ]
        $pdo = $this->pdo ;
        $sql = 'SELECT * FROM users WHERE login = :login' ;
        try {
            $smt = $pdo->prepare($sql) ;
            $smt->execute(['login'=>$userLogin]) ;
            $row = $smt->fetch(PDO::FETCH_ASSOC) ;
            if (!(false === $row)){
                return ['login   ' => $row['login'],
                    'password' => $row['password'] ] ;
            }else {
                return false ;
            }
        }catch (PDOException  $e){
            $this->msg->addMessage('ERROR:'. __METHOD__ .':' . $e->getMessage() ) ;
            return false ;
        }
    }

    /**
     * запоминает пользователя в БД
     */
    public function putUser($userLogin,$password) {
        $pdo = $this->pdo ;
        $logPassw = $this->getUser($userLogin) ;
        if (!(false === $logPassw)) {      // уже есть в БД
            return ($logPassw['password'] == $password) ;
        }
        $sql = 'INSERT INTO  users (login,password) VALUES (:login,:password)' ;
        try {
            $smt = $pdo->prepare($sql) ;
            $smt->execute(['login'=>$userLogin,
                'password'=>$password]) ;

        }catch (PDOException  $e){
            $this->msg->addMessage('ERROR:'. __METHOD__ .':' . $e->getMessage() ) ;
            return false ;
        }
        return true ;
    }
    public function updatePassword($userLogin,$newPassword){
        $pdo = $this->pdo ;
        $logPassw = $this->getUser($userLogin) ;
        if ( false === $logPassw ) {      // нет в БД - это ошибка !!
            return false ;
        }
        $sql = 'UPDATE  users set password = :password where login = :login' ;
        try {
            $smt = $pdo->prepare($sql) ;
            $smt->execute(['login'=>$userLogin,
                'password'=>$newPassword]) ;

        }catch (PDOException  $e){
            $this->msg->addMessage('ERROR:'. __METHOD__ .':' . $e->getMessage() ) ;
            return false ;
        }
        return true ;
    }

    /**
     * возвращает  profile пользователя
     */
    public function getProfile($userLogin) {
        $pdo = $this->pdo ;
        $profile = []; // ['fieldName' => fieldMean, .....]
        $sql = 'SELECT * FROM userprofile where userprofile.userid IN
                        (SELECT userid FROM users WHERE login = :login )';
        try {
            $smt = $pdo->prepare($sql);
            $smt->execute(['login' => $userLogin]);
            $row = $smt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException  $e) {
            $this->msg->addMessage('ERROR:'. __METHOD__ .':' . $e->getMessage() ) ;
            return false;
        }
        if (false === $row) {
            return false;
        }
        foreach ($row as $fldName => $fldMean) {
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
        $pdo = $this->pdo ;
// ['fieldName' => fieldMean, .....]
        $sqlQuery = 'UPDATE userprofile SET ' ;
        $setLine = '' ;
        foreach ($profile as $fldName => $fldMean) {
            $tp = gettype($fldMean) ;
            if ('string' == $tp) {
                $li = $fldName.'='.'"'.$fldMean.'"' ;
            }else {
                $li = $fldName.'='.$fldMean ;
            }
            $setLine .= (empty($setLine)) ? $li : ','.$li ;
        }
        $where = 'where userprofile.userid IN
                  (SELECT userid FROM users WHERE login = :login )' ;
        $sql = $sqlQuery.$setLine.$where ;
        try {
            $smt = $pdo->prepare($sql) ;
            $smt->execute(['login'=>$userLogin]) ;

        }catch (PDOException  $e){

            $this->msg->addMessage('ERROR:'. __METHOD__ .':' . $e->getMessage() ) ;
            return false ;
        }
        return true ;
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