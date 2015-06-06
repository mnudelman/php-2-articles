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
    protected $modelName = 'mod_article';
    protected $mod;
    protected $parForView = [];   // параметры для передачи view
    protected $nameForView = 'cnt_navigator';  // имя для передачи в ViewDriver
    protected $nameForStore = 'cnt_navigatorStore'; // имя строки параметров в TaskStore
    protected $ownStore = [];     // собственные сохраняемые параметры
    protected $forwardCntName = false; // контроллер, которому передается управление
    //--------------------------------//
    private $topicList = [] ;   // список альбомов
    private $articles = [];       // список статей
    private $artPerPage = 1;    // статей на странице
    private $NAV_PAGE_NUMBER = 10; // число ссылок на страницы навигатора
    private $realPageNumber;
    private $currentPage;         // тек страница
    private $newPage;             // новая страница
    private $maxPage;            // мах № страницы
    private $navPageMin;             // начальная стр навигатора
    private $navPageMax;              // мах страница навигатора
    private $artMin;          // нач картинки на тек странице
    private $artMax;          // конечный № картинки на тек странице
    private $pagesList = [];      // список всех страниц с интервалами №№ картинок
    private $currentNavStore = [];       // список сохраняемых параметров по альбомам
    private $currentTopicId;           // Id текущей темы
    private $URL_TO_NAVIGATOR;          //  ссылка для формы

    public function __construct($getArray, $postArray)
    {
        parent::__construct($getArray, $postArray);
    }

    protected function prepare()
    {
        $this->URL_TO_NAVIGATOR = TaskStore::$htmlDirTop . '/index.php?cnt=cnt_navigator';
        $this->currentTopicId = TaskStore::getParam('topicId');
        $this->topicList = $this->mod->getTopic() ;

        if (isset($this->parListPost['topicSelect'])) {    // смена темы
            $this->currentTopicId = $this->parListPost['currentTopicId'] ;
            $this->currentTopicSave() ;
            $this->navClear() ;
        }
        $this->articles = $this->mod->getArticlesByTopic($this->currentTopicId); // список статей
        $this->navRestore();
        if ( isset($this->parListPost['enter']) ) {
        }
        $this->pagesListClc();       // разбиение картинок по страницам
        $this->navInit();
        $this->newPageClc(); // вычислить новую страницу
        $this->navParClc(); // вычислить параметры навигатора

        parent::prepare();
    }
    /**
     * Сохранить атрибуты тек темы
     * @param $curId - Id тек альбома
     */
    private function currentTopicSave() {
        $tId = $this->currentTopicId   ;
        $curTopic = $this->topicList[$tId] ;
        $tName = $curTopic['topicname'] ;
        TaskStore::setParam('topicId',$tId) ;
        TaskStore::setParam('topicName',$tName) ;

    }
    /**
     * разнести статьи по страницам
     */
    private function pagesListClc()
    {
        $kPage = 1;
        $iMax = -1;
        $artNumbers = count($this->articles);
        while ($iMax < $artNumbers - 1) {
            $iMin = $iMax + 1;
            $iMax = $iMin + $this->artPerPage - 1;
            $iMax = min($iMax, $artNumbers - 1);

            $this->pagesList[$kPage++] = ['min' => $iMin,
                'max' => $iMax];

        }
        $this->realPageNumber = min($this->NAV_PAGE_NUMBER, count($this->pagesList));

    }

    /**
     * Восстановить параметры навигатора
     */
    private function navRestore()
    {
        if (isset($this->ownStore[$this->currentTopicId])) {
            $this->currentNavStore = $this->ownStore[$this->currentTopicId];
            if (isset($this->currentNavStore['artPerPage'])) {
                $this->artPerPage = $this->currentNavStore['artPerPage'];
            }
            if (isset($this->currentNavStore['currentPage'])) {
                $this->currentPage = $this->currentNavStore['currentPage'];
            }
            if (isset($this->currentNavStore['navPageMin'])) {
                $this->navPageMin = $this->currentNavStore['navPageMin'];
            }
            if (isset($this->currentNavStore['navPageMax'])) {
                $this->navPageMax = $this->currentNavStore['navPageMax'];
            }
        }
    }

    private function navClear()
    {
        $this->currentPage = 0;
        $this->navPageMin = 0;
        $this->navPageMax = 0;
    }

    /**
     * инициализация параметров навигатора
     */
    private function navInit()
    {
        if (empty($this->artPerPage)) {
            $this->artPerPage = 1;
        }
        if (empty($this->navPageMin) || empty($this->navPageMax) ||
            empty($this->currentPage)
        ) {
            $this->currentPage = 1;
            $this->navPageMin = 1;
            $this->navPageMax = min($this->NAV_PAGE_NUMBER, count($this->pagesList));
        }
    }

    /**
     * Вычислить новую страницу
     */
    private function newPageClc()
    {
        $this->newPage = $this->currentPage;
        $nPages = count($this->pagesList);
        if (isset($this->parListGet['page'])) {  // указатель для перехода через параметр
            //   page={first,prev,<i>,next,last}
            $nextPageCursor = $this->parListGet['page'];
            switch ($nextPageCursor) {
                case 'first' :
                    $this->newPage = 1;
                    break;
                case 'prev' :
                    $this->newPage = max(1, $this->currentPage - 1);
                    break;
                case 'next' :
                    $this->newPage = min($nPages, $this->currentPage + 1);
                    break;
                case 'last' :
                    $this->newPage = $nPages;
                    break;
                default :
                    $this->newPage = (int)$nextPageCursor;
            }
        }
        $this->currentPage = $this->newPage;
    }


    /**
     * // расчет текущих параметров
     */
    private function navParClc()
    {
        if ($this->newPage < $this->navPageMin) {
            $this->navPageMin = $this->newPage;
            $this->navPageMax = $this->navPageMin + $this->realPageNumber - 1;
        } elseif ($this->newPage > $this->navPageMax) {
            $this->navPageMax = $this->newPage;
            $this->navPageMin = $this->navPageMax - $this->realPageNumber + 1;
        }
        $this->artMin = $this->pagesList[$this->currentPage]['min'];
        $this->artMax = $this->pagesList[$this->currentPage]['max'];
    }


    /**
     *  построить массив $ownStore - собственные параметры
     */
    protected function buildOwnStore()
    {
        $this->currentNavStore = [                  // сохраняемые параметры
            'artPerPage' => $this->artPerPage,
            'currentPage' => $this->currentPage,
            'navPageMin' => $this->navPageMin,
            'navPageMax' => $this->navPageMax,
        ];
        // настройки разных альбомов могут быть разными
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
            'topicList'    => $this->topicList ,
            'currentTopicId' => $this->currentTopicId ,
            'artPerPage' => $this->artPerPage,       // картинок на странице
            'currentPage' => $this->currentPage,       // № тек страницы
            'navPageMin' => $this->navPageMin,         // min N страницы в указателе навигатора
            'navPageMax' => $this->navPageMax,         // max N ---------""-------------------
            'artMin' => $this->artMin,               // №№ картинок для тек страницы
            'artMax' => $this->artMax,
            'urlNavigator' => $this->URL_TO_NAVIGATOR,  // адрес для передачи в контроллер
            'articles' => $this->articles,             // полный списк файлов-картинок
            'dirArticle' => TaskStore::$dirArticleHeap, // директорий статей
            'dirImg'    => TaskStore::$htmlDirTop.'/images'
        ];
//        $this->msg->addMessage('DEBUG:'.__METHOD__.':artmin'.$this->artMin) ;
//        $this->msg->addMessage('DEBUG:'.__METHOD__.':artmax'.$this->artMax) ;
//        $this->msg->addMessage($this->articles) ;
        parent::viewGo();
    }
}