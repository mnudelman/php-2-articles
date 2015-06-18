<?php

/**
 * класс навигатор управляет страницами просмотра
 * Date: 29.05.15
 * Time: 10:54
 */
class Cnt_navigator extends Cnt_base
{
    protected $viewDriver ;                   // объект класса ViewDriver -
    protected $msg;                           // сообщения  - объект Message
    protected $parListGet = [];               // параметры класса - аналог $_GET
    protected $parameters = [];              // параметры класса - аналог $_POST
    protected $modelName = 'Mod_navigator';   // имя класса-модели
    protected $mod;                           // объект-модель
    protected $parForView = [];               // параметры для передачи view
    protected $classForView = 'Cnt_vw_navigator';       // имя для передачи в ViewDriver
    protected $nameForStore = 'cnt_navigatorStore'; // имя строки параметров в TaskStore
    protected $ownStore = [];                       // собственные сохраняемые параметры
    protected $forwardCntName = false;              // контроллер, которому передается управление
    protected $URL_OWN;                             //  ссылка для формы
    //--------------------------------//
    private $DIR_TOP ;
    private $HTML_DIR_TOP ;
    private $DIR_VIEW ;
    private $DIR_LAYOUT ;

    public function __construct()
    {
        parent::__construct();
    }

    protected function prepare() {

        $this->DIR_TOP =TaskStore::$dirTop ;
        $this->HTML_DIR_TOP = TaskStore::$htmlDirTop ;
        $this->DIR_VIEW = TaskStore::$dirView ;
        $this->DIR_LAYOUT =TaskStore::$dirLayout ;
        $this->URL_OWN = TaskStore::$htmlDirTop . '/index.php?cnt=Cnt_navigator';


        if (isset($this->ownStore[$this->currentTopicId])) {
            $currentNavStore = $this->ownStore[$this->currentTopicId];
            $this->parameters['currentNavStore'] = $currentNavStore ;// в параметры
        }

        if (isset($this->parameters['topicSelect'])) {    // смена темы
            $this->mod->currentTopicSave() ;
        }
        $this->mod->navExecute() ;
        parent::prepare();
    }
    /**
     *  построить массив $ownStore - собственные параметры
     */
    protected function buildOwnStore()
    {
        $this->currentNavStore = [                  // сохраняемые параметры
            'artPerPage' => $this->mod->getArtPerPage(), // статей на странице
            'currentPage' => $this->mod->getCurrentPage(),// № тек страницы
            'navPageMin' => $this->mod->getNavPageMin(), // min N страницы в указателе навигатора
            'navPageMax' => $this->mod->getNavPageMax(), // max N ---------""-------------------
        ];
        // настройки разных тем могут быть разными
        $this->ownStore[$this->currentTopicId] = $this->currentNavStore;
    }
   /**
   * подготовка и вывод представления
   */
    public function viewGo($test=false) {
        parent::viewGo();
    }
}