<?php
/**
 * класс - Данные представления является дополнением к соответствующему
 * классу-контроллеру.
 * Данные формируются в однотипных методах по числу компонент представления.
 * Каждый компонент может иметь собственные копоненты, т.е. дерево
 * может быть произвольного уровня. В дочерних классах отдельные компоненты могут
 * отсутствовать или добавлены произвольно новые.
 */

abstract class Cnt_vw_base {
    protected $msg;                      // сообщения  - объект Message
    protected $mod ;                     // объект-модель
    protected $URL_OWN;                  // ссылка для формы котроллера
    protected $DIR_TOP ;
    protected $HTML_DIR_TOP ;
    protected $DIR_VIEW ;
    protected $DIR_LAYOUT ;
    protected $DIR_ARTICLE ;
    protected $DIR_IMAGE ;
    public function __construct() {
        $this->msg = Message::getInstace() ;
        $this->DIR_TOP =TaskStore::$dirTop ;
        $this->HTML_DIR_TOP = TaskStore::$htmlDirTop ;
        $this->DIR_VIEW = TaskStore::$dirView ;
        $this->DIR_LAYOUT =TaskStore::$dirLayout ;
        $this->DIR_ARTICLE = TaskStore::$dirArticleHeap ;
        $this->DIR_IMAGE = TaskStore::$htmlDirTop .'/images' ;
    }
    public function setModel($model) {
        $this->mod = $model ;
    }
    /**
     * @param $url - адрес собственного контроллера
     */
    public function setUrlOwn($url) {
        $this->URL_OWN = $url ;
    }
    /**
     * Корневой шаблон
     */
    public function partMainDef() {
        return [
        'name' => 'main' ,                      // корень дереваПредставлений
        'parameters' => false ,
        'components' =>
        ['partHeadPart','partTopMenu','partContent'],
        'dir' => $this->DIR_LAYOUT,
        'file' => ''
        ] ;
    }

    /**
     * формирует тег <head>... </head>
     */
    public function partHeadPartDef() {
        return [
        'name' => 'partHeadPart' ,                              // тег <head></head>
        'parameters' => [ 'htmlDirTop' => $this->HTML_DIR_TOP ] ,
        'components' => false ,
        'dir' => $this->DIR_VIEW ,
        'file' => 'headPart'
        ] ;
    }

    /**
     * меню - начало страницы
     */
    public  function partTopMenuDef() {
        $topicStore = TaskStore::getParam('topicName') ;
        $topicName = ( empty($topicStore)) ? 'тема не выбрана' : $topicStore ;

        return [
        'name' => 'partTopMenu' ,
        'parameters' => [
            'htmlDirTop' => $this->HTML_DIR_TOP,
            'topicName' =>  $topicName,
            'userName'  => TaskStore::getParam('userName') ] ,
        'components' => false ,
        'dir' => $this->DIR_VIEW,
        'file' => 'topMenu'
        ] ;
    }

    /**
     * центральная часть страницы
     */
    public function partContentDef() {
        return [
        'name' => 'partContent' ,
        'parameters' => false ,
        'components' => ['partMessage','partDataContent'] ,
        'dir' => $this->DIR_VIEW ,
        'file' => 'contentPart'
        ] ;
    }

    /**
     * Вывод сообщений
     */
    public function partMessageDef() {
        return [
        'name' => 'partMessage' ,
        'parameters'  => ['messages' => $this->msg->getMessages() ] ,
        'components' => false ,
        'dir' => $this->DIR_VIEW ,
        'file' => 'messageForm'
        ] ;
    }

}