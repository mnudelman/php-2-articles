<?php
/**
 * Выбирает компонент контроллера, которому надо передать управление
 * Контроллер должен погружаться по autoload
 *  rooter только запускает контроллер.
 * Всю остальную обработку выполняет контроллер.
 */
class Router {
    private $controllerName ;    // текущий котроллер
    private $DEFAULT_NAME =  'Cnt_default' ;
    private $msg ;          // объект-сообщение
    private $taskParms ;    // объект класса TaskParameters - параметры задачи
    //-----------------------------------//
    public function __construct($defautOnly = false) {
        $this->taskParms = TaskParameters::getInstance();
        $this->msg = Message::getInstace();
  //      $cntN = $this->taskParms->getParameter('cnt');
  //     $this->controllerName = (false === $cntN) ? $this->DEFAULT_NAME : $cntN;

        $this->controllerName = ($defautOnly) ? $this->DEFAULT_NAME :
                                         $this->taskParms->getParameter('controller') ;
    }

    public function controllerGo() {
        while (true) {
            $class = $this->controllerName ;
            $controller = new $class() ;
            $forwardController = $controller->getForwardCntName() ;  // возможная передача управления
            if (!empty($forwardController)  ) {      // возможна передача управления другому контроллеру
                $this->controllerName = $forwardController ;
                continue ;
            }
            break ;
        }
        $controller->viewGo() ;   // формировать представление
    }
}