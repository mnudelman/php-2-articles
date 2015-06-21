<?php
/**
* Тестирование класса Db_base - базовый класс для работы с БД
*/
session_start();
ini_set('display_errors', 1);
//error_reporting(E_ALL) ;
error_reporting(E_ALL ^ E_NOTICE);
header('Content-type: text/html; charset=utf-8');
include_once __DIR__ . '/local.php';
///////////////////////////////////////////////////////////////////
class Db_example extends Db_base {
    public function select_0() {    //  без подстановки
        echo 'SELECT без подстановки'.TaskStore::LINE_FEED ;

        $sql = 'SELECT * FROM users' ;
        $subst = [] ;
        $res = $this->sqlExecute($sql,$subst,'test') ;

        if (!(false === $res) ) {
            foreach ($res as $row) {
                print_r($row) ;
                echo TaskStore::LINE_FEED ;
            }
            echo 'число строк:'.$this->getRowCount().TaskStore::LINE_FEED ;
        }
        $messages = $this->msg->getMessages() ;
        if (is_array($messages)) {
            foreach ($messages as $text) {
                echo $text.TaskStore::LINE_FEED ;
            }
        }
    }

    public function select_0_1() {    //  без подстановки
        echo 'SELECT -  построчно'.TaskStore::LINE_FEED ;

        $sql = 'SELECT * FROM users' ;
        $subst = [] ;
        $this->sqlExecute($sql,$subst,'test') ;
        $rows = $this->getResult() ;
        var_dump($rows) ;
            foreach ($rows as $row) {
                print_r($row) ;
                echo TaskStore::LINE_FEED ;
            }
        echo 'ПОВТОР:'.TaskStore::LINE_FEED ;
        $rows = $this->getResult() ;
        var_dump($rows) ;
        foreach ($rows as $row) {
            print_r($row) ;
            echo TaskStore::LINE_FEED ;
        }

        echo 'число строк:'.$this->getRowCount().TaskStore::LINE_FEED ;
        $messages = $this->msg->getMessages() ;
        if (is_array($messages)) {
            foreach ($messages as $text) {
                echo $text.TaskStore::LINE_FEED ;
            }
        }
    }




    public function select_1() {    // подстановка
        echo 'SELECT с подстановкой'.TaskStore::LINE_FEED ;

        $sql = 'SELECT * FROM users where login = :login' ;
        $subst = ['login' => 'mnudelman'] ;
        $res = $this->sqlExecute($sql,$subst,'test') ;

        if (!(false === $res) ) {
            foreach ($res as $row) {
                print_r($row) ;
                echo TaskStore::LINE_FEED ;
            }
            echo 'число строк:'.$res->rowcount().TaskStore::LINE_FEED ;
        }
        $messages = $this->msg->getMessages() ;
        if (is_array($messages)) {
            foreach ($messages as $text) {
                print_r($text) ;
                echo TaskStore::LINE_FEED ;
            }
        }
    }
    public function select_2() {    // отсутствие данных
        echo 'SELECT с подстановкой'.TaskStore::LINE_FEED ;

        $sql = 'SELECT * FROM users where login = :login' ;
        $subst = ['login' => 'mnudelman__'] ;
        $res = $this->sqlExecute($sql,$subst,'test') ;

        if (!(false === $res) ) {
            foreach ($res as $row) {
                print_r($row) ;
                echo TaskStore::LINE_FEED ;
            }
            echo 'число строк:'.$res->rowcount().TaskStore::LINE_FEED ;
        }
        $messages = $this->msg->getMessages() ;
        if (is_array($messages)) {
            foreach ($messages as $text) {
                print_r($text) ;
                echo TaskStore::LINE_FEED ;
            }
        }
    }
    public function select_3() {    // ошибка оператора
        echo 'Ошибка оператора SELECT__ '.TaskStore::LINE_FEED ;

        $sql = 'SELECT * FROM users__ where login = :login' ;
        $subst = ['login' => 'mnudelman__'] ;
        $res = $this->sqlExecute($sql,$subst,'test') ;

        if (!(false === $res) ) {
            foreach ($res as $row) {
                print_r($row) ;
                echo TaskStore::LINE_FEED ;
            }
            echo 'число строк:'.$res->rowcount().TaskStore::LINE_FEED ;
        }
        $messages = $this->msg->getMessages() ;
        if (is_array($messages)) {
            foreach ($messages as $text) {
                print_r($text) ;
                echo TaskStore::LINE_FEED ;
            }
        }
    }

    //////////////////////////////////////////////////////
    public function insert_0() {    //
        echo 'оператора INSERT '.TaskStore::LINE_FEED ;

        $sql = 'INSERT INTO users (login,password) VALUES (:login , :password)' ;

        $subst = [
            'login' => 'basil',
            'password' => '12345' ] ;
        $res = $this->sqlExecute($sql,$subst,'test') ;

        if (!(false === $res) ) {
            echo 'последний  Id:'.$res.TaskStore::LINE_FEED ;
        //    echo 'число строк:'.$res->rowcount().TaskStore::LINE_FEED ; - не катит
            }


        $messages = $this->msg->getMessages() ;
        if (is_array($messages)) {
            foreach ($messages as $text) {
                print_r($text) ;
                echo TaskStore::LINE_FEED ;
            }
        }
    }
////////////////////////////////////////////////////////////////////////////////
    public function update_0() {    //
        echo 'оператор UPDATE '.TaskStore::LINE_FEED ;

        $sql = 'UPDATE users SET password = :password WHERE login = :login ' ;

        $subst = [
            'login' => 'basil',
            'password' => '12345' ] ;
        $res = $this->sqlExecute($sql,$subst,'test') ;

        if (!(false === $res) ) {
            echo 'число обновлений:'.$res.TaskStore::LINE_FEED ;
        }


        $messages = $this->msg->getMessages() ;
        if (is_array($messages)) {
            foreach ($messages as $text) {
                print_r($text) ;
                echo TaskStore::LINE_FEED ;
            }
        }
    }
////////////////////////////////////////////////////////////////////////////////
    public function delete_0() {    //
        echo 'оператор DELETE '.TaskStore::LINE_FEED ;

        $sql = 'DELETE FROM  users WHERE login = :login ' ;

        $subst = [
            'login' => 'basil'] ;
        $res = $this->sqlExecute($sql,$subst,'test') ;

        if (!(false === $res) ) {
            echo 'число удалений:'.$res.TaskStore::LINE_FEED ;
        }


        $messages = $this->msg->getMessages() ;
        if (is_array($messages)) {
            foreach ($messages as $text) {
                print_r($text) ;
                echo TaskStore::LINE_FEED ;
            }
        }
    }

}
$dbBase = new Db_example() ;
$dbBase->select_0_1() ;
$dbBase->select_0() ;
