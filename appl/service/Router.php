<?php
/**
 * Выбирает компонент контроллера, которому надо передать управление
 * Контроллер должен погружаться по autoload
 *  rooter только запускает контроллер.
 * Все остальное(обработка $_GET, $_POST) выполняет контроллер.
 */
class Router {
    private $controllerName = 'Cnt_default' ;
    private $paramListGet = [] ;
    private $paramListPost = [] ;
    private $msg ;
    //-----------------------------------//
    public function __construct() {
        if (isset($_GET['cnt'])) {
            $this->controllerName = $_GET['cnt'] ;
        }elseif (isset($_POST['cnt'])) {
            $this->controllerName = $_POST['cnt'] ;
        }
        $this->paramListGet = $_GET ;
        $this->paramListPost = $_POST ;
        $this->msg = TaskStore::getMessage() ;
    }

    public function controllerGo() {
        while (true) {
            $class = $this->controllerName ;
            $pListGet = $this->paramListGet ;
            $pListPost = $this->paramListPost ;
            $controller = new $class($pListGet,$pListPost) ;
            $forwardController = $controller->getForwardCntName($pListGet,$pListPost) ;  // возможная передача управления
            if (!empty($forwardController)  ) {      // возможна передача управления другому контроллеру
                $this->controllerName = $forwardController ;
                $this->paramListGet = $pListGet ;
                $this->paramListPost = $pListPost ;
                continue ;
            }
            break ;
        }
        $controller->viewGo() ;   // вывод формы контроллера
    }
}