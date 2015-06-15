<?php
/**
 *  абстрактный класс контроллера
 * Date: 23.05.15
 */

abstract class Cnt_base
{
    protected $msg;              // сообщения  - объект Message
    protected $viewDiver ;       // объект класса ViewDriver
    protected $parListGet = [];  // параметры класса - аналог $_GET
    protected $parameters = []; // параметры класса - аналог $_POST,$_GET
    protected $taskParms ;      //  объект TaskParameters - параметры задачи
    protected $modelName = '';   // имя класса-модели
    protected $mod;              // объект -модель
    protected $classForView = false;  //  класс для формирования шаблона
    protected $nameForStore = ''; // имя строки параметров в TaskStore
    protected $ownStore = [];     // собственные сохраняемые параметры
    protected $forwardCntName = false; // контроллер, которому передается управление
    protected $URL_OWN = false;     // адрес возврата в текущий контроллер

    //--------------------------------------------------//
    public function __construct()
    {
        $this->msg = Message::getInstace() ;
        $this->viewDiver = new ViewDriver() ;
        $class = $this->modelName;
        if (!empty($class)) {
            $this->mod = new $class();
        }
        if (!empty($this->nameForStore)) {
            $this->ownStore = TaskStore::getParam($this->nameForStore); //  взять параметры из TaskStore
        }
        $this->taskParms = TaskParameters::getInstance() ;
        $this->parameters = $this->taskParms->getParameters() ;
        $this->prepare();
    }

    protected function prepare()
    {
        //------- работа   ------------//
        $this->buildOwnStore(); // построить массив параметров
        $this->saveOwnStore();  //  сохранить параметры
    }

    /**
     *  построить массив $ownStore - собственные параметры
     */
    protected function buildOwnStore()
    {

    }

    protected function saveOwnStore()
    {
        if (!empty($this->nameForStore)) {
            TaskStore::setParam($this->nameForStore, $this->ownStore); //  сохранить параметры из TaskStore
        }
    }

    /**
     * выдает имя контроллера для передачи управления
     * альтернатива viewGo
     * Через  $pListGet , $pListPost можно передать новые параметры
     */
    public function getForwardCntName()
    {
        $plistGet = [];
        $plistPost = [];
        $this->taskParms->setParameters($plistGet,$plistPost) ;
        return $this->forwardCntName;
    }

    /**
     * подготовка и вывод представления
     * подготовка выполняется через вспомогательный класс classForView
     */
    public function viewGo()
    {
        $viewDriver = $this->viewDiver ;
        $class = $this->classForView;      // вспомогательный класс для формирования представлений
        $forView = new $class();
        $forView->setViewDriver($viewDriver) ;
        $forView->setModel($this->mod);
        $forView->setViewDriver($viewDriver);
        $forView->setUrlOwn($this->URL_OWN);
        $forView->buildViewTree();       // в этом методе в  $viewDriver передаются компоненты
                                         // для построения дереваПредставлений
        $viewDriver->allowViews();       // разрешить ссылки на компоненты
        if (!$viewDriver->getAllowSuccessful()) {  // не все компоненты шаблона определены
            $notAllowView = $viewDriver->getNotAllowedViews();
            foreach ($notAllowView as $view) {
                $this->msg->addMessage(
                    'ERROR:' . __METHOD__ . ':неопределенные компоненты представления:' .
                                                                              $view['name']);
            }
            $this->messageParameterUpdate(); // обновить параметр для вывода сообщений
       }
        $viewDriver->viewExec();
    }

    /**
     * обновить параметр для вывода собщений на экран
     * TODO - сделать константами имена, связанные с выводом сообщений
     */
    protected function messageParameterUpdate()  {
        $viewName = 'partMessege';
        $parName = 'messeges';
        $parValue = $this->msg->getMessages();
        $this->viewDiver->setParameter($viewName, $parName, $parValue);
    }
}