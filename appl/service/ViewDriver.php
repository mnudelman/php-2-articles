<?php

/**
 * Управление выводом
 * Все представления, участвующие в выводе собираются
 * в произвольном порядке в $notAllowedViews (метод addView).
 * в методе allowViews выполняется несколько проходов по $notAllowedViews
 * и элементы, у которых все компоненты разрешены переносятся в $allowedViews
 *   первые строки в $allowedViews элементы без компонентов, и т.д. - последним
 *  будет корень дерева представлений с именем "main".
 * в методе viewExec производится проход по  $allowedViews и вычисление элементов.
 * Последний вычисленный элемент будет корень 'main'. Он выводится на экран.
 */
class ViewDriver
{
    private $msg;                      // объект для вывода сообщений
    //--- тек атрибуты ---//
    private $allowedViews = [] ;       // представления с разрешенными компонентами
    private $notAllowedViews = [] ;    // все представления в начальный момент
    private $endedViews = [] ;         // исполненные представления
    private $allowSuccessful = false ; // успешное разрешение всех компонент
    private $MAX_ALLOW_STEPS = 5 ;     // max число проходов по разрешению ссылок
    public function __construct() {
        $this->msg = TaskStore::getMessage() ;
    }

    /**
     * Добавить представление
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
     * проверить разрешение компонент
     * компонента определена если находится
     * в allowedViews
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
     * установить параметр. Может понадобиться, например для
     * корректировки параметра вывода сообщений
     * @param $name   - имя представления
     * @param $parameterName - имя параметра
     * @param $value - значение параметра
     */
    public function setParameter($name,$parameterName,$value) {
        if (isset($this->allowedViews[$name])) {
            $this->allowedViews[$name][$parameterName] = $value ;

        }
    }

    /**
     * проход по списку представлений и вычисление их значений.
     * компоненты - это дополнительный параметр для вычисления.
     * allowedViews построен таким образом, что любой из компонентов
     * находится "выше" самого представления -> к моменту вычисления
     * все компоненты уже вычислены и находятся в endedViews поэтому
     * происходит их подстановка как обычных параметров.
     * Представление с именем "main" является корнем всего дерева.
     * Именно он ('main") выводится на экран.
     */
    public function viewExec()
    {
        $this->endedViews = [] ;   // исполненные представления
       foreach ($this->allowedViews as $viewName => $view ) {
           $components = $view['components'];
           $parameterComponents = false;
           if (is_array($components)) {
               $parameterComponents = [];
               foreach ($components as $cmpName) {
                   $parameterComponents[$cmpName] = $this->endedViews[$cmpName];
               }

           }
           if (empty($view['file'])) {
              // $this->executeViews[$viewName] = 'INFO:'.__METHOD__.':'.$viewName.' IS EMPTY !' ;
               continue ;
           }
           $includeFile = $view['dir'] .'/'. $view['file'].'.php' ;
           $pars = $view['parameters'];
           $this->endedViews[$viewName] =
               $this->template($includeFile, $pars, $parameterComponents);
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
