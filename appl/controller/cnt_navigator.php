<?php

/**
 * класс навигатор управляет страницами просмотра картинок
 * Date: 29.05.15
 * Time: 10:54
 */
class cnt_navigator extends cnt_base
{
    protected $msg;    // сообщения класса - объект Message
    protected $parListGet = [];  // параметры класса
    protected $parListPost = [];  // параметры класса
    protected $msgTitle = '';
    protected $msgName = '';
    protected $modelName = 'mod_navigator';
    protected $mod;
    protected $parForView = [];   // параметры для передачи view
    protected $nameForView = 'cnt_navigator';  // имя для передачи в ViewDriver
    protected $nameForStore = 'cnt_navigatorStore'; // имя строки параметров в TaskStore
    protected $ownStore = [];     // собственные сохраняемые параметры
    protected $forwardCntName = false; // контроллер, которому передается управление
    //--------------------------------//
    private $URL_TO_NAVIGATOR;          //  ссылка для формы

    public function __construct($getArray, $postArray)
    {
        parent::__construct($getArray, $postArray);
    }

    protected function prepare() {

        $this->URL_TO_NAVIGATOR = TaskStore::$htmlDirTop . '/index.php?cnt=cnt_navigator';
        if (isset($this->ownStore[$this->currentTopicId])) {
            $currentNavStore = $this->ownStore[$this->currentTopicId];
            $this->parListPost['currentNavStore'] = $currentNavStore ;// в параметры
        }
        if (isset($this->parListGet['page'])) {
            $this->parListPost['page'] = $this->parListGet['page'];// в параметры
        }
        if (isset($this->parListGet['articleid'])) {   // прямая ссылка на статью
            $this->parListPost['articleid'] = $this->parListGet['articleid'];// в параметры
        }
        $this->mod->setParameters($this->parListPost) ; // параметры в модель
        if (isset($this->parListPost['topicSelect'])) {    // смена темы
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

    protected function saveOwnStore()
    {
        parent::saveOwnStore();
    }

    /**
     * выдает имя контроллера для передачи управления
     * альтернатива viewGo
     * Через  $pListGet , $pListPost можно передать новые параметры
     */
    public function getForwardCntName(&$plistGet, &$pListPost)
    {
        parent::getForwardCntName($plistGet, $pListPost);
    }

    public function viewGo()
    {
        $this->parForView = [              // параметры формы
            'topicList'      => $this->mod->getTopicList() ,
            'currentTopicId' => $this->mod->getCurrentTopicId() ,
            'artPerPage'     => $this->mod->getArtPerPage(), // картинок на странице
            'currentPage'    => $this->mod->getCurrentPage(),// № тек страницы
            'navPageMin'     => $this->mod->getNavPageMin(), // min N страницы в указателе навигатора
            'navPageMax'     => $this->mod->getNavPageMax(), // max N ---------""-------------------
            'artMin'         => $this->mod->getArtMin(),     // №№ картинок для тек страницы
            'artMax'         => $this->mod->getArtMax(),
            'articles'       => $this->mod->getArticles(),   // полный списк файлов-картинок
            'urlNavigator'   => $this->URL_TO_NAVIGATOR,     // адрес для передачи в контроллер
            'dirArticle'     => TaskStore::$dirArticleHeap,  // директорий статей
            'dirImg'         => TaskStore::$htmlDirTop.'/images'
        ];
        parent::viewGo();
    }
}