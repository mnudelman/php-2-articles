<?php
/**
 *
 * Date: 09.06.15
 * Time: 18:58
 */

class Mod_navigator extends  Mod_base
{
    protected $msg ;                     // объект для вывода сообщений
    protected $db = false ;              // объект класса для связи с БД
    protected $dbClass = 'Db_article' ;  //  имя класса для работы с БД
    protected $parameters = [];          // параметры, принимаемые от контроллера
    //--------------------------//
    private $topicList = [];   // список альбомов
    private $articles = [];            // список статей
    private $artPerPage = 1;           // статей на странице
    private $NAV_PAGE_NUMBER = 10;     // число ссылок на страницы навигатора
    private $realPageNumber;
    private $currentPage;              // тек страница
    private $newPage;                  // новая страница
    private $maxPage;                  // мах № страницы
    private $navPageMin;               // начальная стр навигатора
    private $navPageMax;               // мах страница навигатора
    private $artMin;                   // нач статья на тек странице
    private $artMax;                   // конечный № статьи на тек странице
    private $pagesList = [];           // список всех страниц с интервалами №№ картинок
    private $currentNavStore = [];     // список сохраняемых параметров по альбомам
    private $currentTopicId;           // Id текущей темы
    private $newTopicFlag = false ;    // признак установки новой темы
    private $currentArticleId = false ;//  прямая ссылка на статью
    //-----------------------------------------//
    public function __construct() {
        parent::__construct() ;
    }
   /**
     *  определение собственных свойств из параметров
     */
    protected function init() {
        $this->currentTopicId = TaskStore::getParam('topicId');
        $this->topicList = $this->db->getTopic();
        if (isset($this->parameters['articleid'])) {
            $this->currentArticleId = $this->parameters['articleid'] ;
        }
    }

    /**
     * Сохранить атрибуты тек темы
     * @param $curId - Id тек альбома
     */
    public function currentTopicSave() {
        $this->currentTopicId = $this->parameters['currentTopicId'];
        $tId = $this->currentTopicId;
        $curTopic = $this->topicList[$tId];
        $tName = $curTopic['topicname'];
        TaskStore::setParam('topicId', $tId);
        TaskStore::setParam('topicName', $tName);
        $this->newTopicFlag = true ;

    }

    /**
     * Выполнить работу навигатора
     */
    public function navExecute() {
        $this->parameters = $this->taskParms->getParameters() ;
        $this->articles = $this->db->getArticlesByTopic($this->currentTopicId); // список статей
        if ($this->newTopicFlag) {
            $this->navClear() ;
            $this->newTopicFlag = false ;
        }else {
            $this->navRestore();
        }
        $this->pagesListClc();       // разбиение статей по страницам
        $this->navInit();
        $this->newPageClc();         // вычислить новую страницу
        $this->navParClc();          // вычислить параметры навигатора
    }

    /**
     * разнести статьи по страницам
     */
    private function pagesListClc() {
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
    private function navRestore() {
        if (isset($this->parameters['currentNavStore'])) {
            $this->currentNavStore = $this->parameters['currentNavStore'];
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
    private function navInit() {
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
     * индексы списков  articles и pageList совпадают
     * т.к. на странице одна статья
     */
    private function newPageFromId() {
        $newPage = 0 ;
        foreach($this->articles as $index=>$article) {
            $aid = $article['articleid'] ;
            if ($aid == $this->currentArticleId) {
              $newPage = $index+1 ;
                break ;
            }
        }
        return $newPage ;
    }
    /**
     * Вычислить новую страницу
     */
    private function newPageClc() {
        if (!empty($this->currentArticleId)){
            $this->newPage= $this->newPageFromId() ;
        }else {
            $this->newPage = $this->currentPage;
        }
        $nPages = count($this->pagesList);
        if (isset($this->parameters['page'])) {  // указатель для перехода через параметр
            //   page={first,prev,<i>,next,last}
            $nextPageCursor = $this->parameters['page'];
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
    private function navParClc() {
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
     * Сохранить комментарий
     */
    public function commentSave($commentText) {
         if (!$this->isAddCommentFlag()) {
             return false ;
         }
        $userLogin = TaskStore::getParam('userLogin') ;
        $currentArticle = $this->getCurrentArticle() ;
        $articleId = $currentArticle['articleid'] ;
        $date = date('c') ;
        $this->db->addComment($commentText,$userLogin,$articleId,$date) ;
    }
//////////////////////////////////////////////////////////////////////////////////
    public function getTopicList()
    {
        return $this->topicList;
    }
    public function getCurrentTopicId() {
        return $this->currentTopicId;
    }
    public function getArtPerPage() {
        return $this->artPerPage;
    }
    public function getCurrentPage() {
        return $this->currentPage;
    }
    public function getNavPageMin() {
        return $this->navPageMin;
    }
    public function getNavPageMax() {
        return $this->navPageMax;
    }
    public function getartMin() {
        return $this->artMin;
    }
    public function getArtMax() {
        return $this->artMax;
    }
    public function getArticles() {
        return $this->articles;
    }
    public function getCurrentArticle() {
        return $this->articles[$this->artMin] ;
    }
    public function getComments() {
        $currentArticle = $this->getCurrentArticle() ;
        $articleID = $currentArticle['articleid'] ;
        return $this->db->getComments($articleID) ;

    }
    public function isAddCommentFlag() {
        return TaskStore::getParam('userStatus') >=
        TaskStore::USER_STAT_USER ;
    }
}