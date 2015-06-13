<?php
/**
 *  абстрактный класс контроллера
 * Date: 23.05.15
 */

abstract class Cnt_base {
    protected $msg ;              // сообщения  - объект Message
    protected $parListGet = [] ;  // параметры класса - аналог $_GET
    protected $parListPost = [] ; // параметры класса - аналог $_POST
    protected $modelName = '' ;   // имя класса-модели
    protected $mod ;              // объект класса-модели
    protected $parForView = [] ;   // параметры для передачи view
    protected $classForView = false ;  //  класс для формирования шаблона
    protected $nameForStore = '' ; // имя строки параметров в TaskStore
    protected $ownStore = [] ;     // собственные сохраняемые параметры
    protected $forwardCntName = false ; // контроллер, которому передается управление
    protected $URL_OWN = false ;     // адрес возврата в контроллер
    //--------------------------------------------------//
    public function __construct($getArray,$postArray) {
        $this->msg = TaskStore::getParam('message') ;
        $class = $this->modelName ;
        if (!empty($class)) {
            $this->mod = new $class();
        }
        if (!empty($this->nameForStore)) {
           $this->ownStore = TaskStore::getParam($this->nameForStore) ; //  взять параметры из TaskStore
        }
        $this->parListGet = $getArray ;
        $this->parListPost = $postArray ;
        $this->prepare() ;
    }
    protected function prepare() {
        //------- работа   ------------//
        $this->buildOwnStore() ; // построить массив параметров
        $this->saveOwnStore() ;  //  сохранить параметры
    }

    /**
     *  построить массив $ownStore - собственные параметры
     */
    protected function buildOwnStore() {

    }
    protected function saveOwnStore() {
        if (!empty($this->nameForStore)) {
            TaskStore::setParam($this->nameForStore,$this->ownStore) ; //  сохранить параметры из TaskStore
        }
    }
    /**
     * выдает имя контроллера для передачи управления
     * альтернатива viewGo
     * Через  $pListGet , $pListPost можно передать новые параметры
     */
    public function getForwardCntName(&$plistGet,&$pListPost) {
        $plistGet = [] ;
        $plistPost = [] ;
        return $this->forwardCntName ;
    }
    public function viewGo() {
        $vd = new ViewDriver() ;
        $class = $this->classForView ;
        $forView = new $class() ;

        $forView->setModel($this->mod) ;
        $forView->setViewDriver($vd) ;
        $forView->setUrlOwn($this->URL_OWN) ;
        $forView->buildViewTree() ;
        $vd->allowViews() ; // разрешить ссылки на компоненты
        if (!$vd->getAllowSuccessful()) {  // не всекомпоненты шаблона определены
            $notAllowView = $vd->getNotAllowedViews() ;
            foreach($notAllowView as $view) {
                $name = $view['name'] ;
                $this->msg->addMessage(
                'ERROR:'.__METHOD__.':неопределенные компоненты представления:'.$view['name']) ;
            }
        }
        $vd->viewExec() ;
    }
 }