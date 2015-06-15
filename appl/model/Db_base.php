<?php
/**
 * Базовый класс для работы с БД
 * Time: 16:16
 */

abstract class Db_base {
    protected $pdo;   // объект - подключение к БД
    protected $msg ;  // объект - вывод сообщений
    public function __construct() {
        $this->pdo = DbConnector::getConnect() ;
        $this->msg = Message::getInstace() ;
    }

}