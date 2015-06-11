<?php
/**
 * Базовый класс для работы с БД
 * Time: 16:16
 */

abstract class db_base {
    protected $pdo;   // объект - подключение к БД
    protected $msg ;  // объект - вывод сообщений
    public function __construct() {
        $this->pdo = TaskStore::getDbConnect() ;
        $this->msg = TaskStore::getParam('message') ;
    }

}