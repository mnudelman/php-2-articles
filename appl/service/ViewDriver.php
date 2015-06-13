<?php

/**
 *
 * Управление выводом
 * Date: 23.05.15
 */
class ViewDriver
{
    private $msg;
    //--- тек атрибуты ---//
    private $parameters;               // параметры, полученные от контроллера
    private $allowedViews = [] ;       // представления с разрешенными компонентами
    private $notAllowedViews = [] ;    // все представления в начальный момент
    private $endedViews = [] ;         // исполненные представления
    private $allowSuccessful = false ; // успешное разрешение всех компонент
    private $MAX_ALLOW_STEPS = 5 ;   // max число проходов по разрешению ссылок
    public function __construct() {
        $this->msg = TaskStore::getMessage() ;
    }

    /**
     * Добавить представление
     * @param $name
     * @param $parameters - при добавлении параметры могут быть  false
     *                      доопределить можно в setParameters
     * @param $components
     * @param $dir
     * @param $viewFile
     */
    public function addView( $name,$parameters,$components,$dir,$viewFile) {
        $this->notAllowedViews[] =
         [
            'name'       => $name,        // имя компоненты
            'parameters' => $parameters , // параметры подстановки
            'components' => $components,  // вложенные компоненты
             'dir'       => $dir,         // директорий
            'file'       => $viewFile     // файл представления
        ] ;
    }

    /**
     * Разрешить ссылки на компоненты
     * представления с разрешенными компонентами переносятся
     * в $this->allowedViews
     */
    public function allowViews() {
        $kStep = 0 ;
        $allowSuccessful = false ;
        while ( !$allowSuccessful &&
                            $kStep++ <= $this->MAX_ALLOW_STEPS) {
            foreach ($this->notAllowedViews as $key => $viewElem) {
                if( !$this->isAllowViewElem( $viewElem['components']) ) {
                    continue ;
                }
                    $this->allowedViews[$viewElem['name']] = $viewElem ;
                    unset($this->notAllowedViews[$key]) ;
            }
        }
        $this->allowSuccessful = (0 == count($this->notAllowedViews) ) ;
    }

    /**
     * проверить разрешение компонент по представлению
     * @param $components
     * @return bool
     */
    private function isAllowViewElem($components) {
        $allowFlag = true ;
        if (is_array($components) ) {
            foreach ($components as $comp) {
                if (!isset($this->allowedViews[ $comp ])) {
                    $allowFlag = false ;
                    break ;
                }
            }
        }
        return $allowFlag ;
    }

    public function getAllowSuccessful() {
        return $this->allowSuccessful ;
    }

    public function getNotAllowedViews() {
        return $this->notAllowedViews ;
    }

    /**
     * добавление параметров. В  addView параметры могут быть пустыми
     * @param $name
     * @param $parameters
     */
    public function setParameters($name,$parameterName,$parameters) {
        if (isset($this->allowedViews[$name])) {
            $this->allowedViews[$name][$parameterName] = $parameters ;

        }
    }

  public function viewExec()
    {
        $this->endedViews = [] ;   // исполненные представления
       foreach ($this->allowedViews as $viewName => $view ) {
           $components = $view['components'];
           $compParameters = false;
           if (is_array($components)) {
               $compParameters = [];
               foreach ($components as $cmpName) {
                   $compParameters[$cmpName] = $this->endedViews[$cmpName];
               }

           }
           if (empty($view['file'])) {
              // $this->executeViews[$viewName] = 'INFO:'.__METHOD__.':'.$viewName.' IS EMPTY !' ;
               continue ;
           }
           $includeFile = $view['dir'] .'/'. $view['file'].'.php' ;
           $pars = $view['parameters'];
           $this->endedViews[$viewName] =
               $this->template($includeFile, $pars, $compParameters);
       }
       echo $this->endedViews['main'] ;
    }
    /**
     * Формирование компоненты вывода
     */
    private function template($includeFile, $parameters,$components) {
        if (is_array($parameters)) {    // параметры подстановки
            foreach ($parameters as $name => $mean) {
                $$name = $mean;
            }
        }
        if (is_array($components)) {    // компоненты
            foreach ($components as $name => $mean) {
                $$name = $mean;
            }
        }
        ob_start();
        include $includeFile;
        return ob_get_clean();
    }

}
