<?php
/**
 * Контроллер - редактирование статей автора
 * Редактируются заголовки и темы(рубрики) статей
 * Date: 25.05.15
 * Time: 23:21
 */

class cnt_article extends cnt_base {
    protected $msg ;    // сообщения класса - объект Message
    protected $parListGet = [] ;  // параметры класса
    protected $parListPost = [] ;  // параметры класса
    protected $msgTitle = '' ;
    protected $msgName = '' ;
    protected $modelName = 'mod_article' ;
    protected $mod ;
    protected $parForView = [] ;   // параметры для передачи view
    protected $nameForView = 'cnt_article' ;  // имя для передачи в ViewDriver
    protected $nameForStore = 'cnt_articleStore' ; // имя строки параметров в TaskStore
    protected $ownStore = false ;     // собственные сохраняемые параметры
    protected $forwardCntName = false ; // контроллер, которому передается управление
    //-----------------------------------------------------------//
    private $articleStatEdit;                        // режим (редакт - просмотр)
    private $topicList = [] ;                        // список всех тем
    private $articles = [] ;                         // список  статей
    private $dirArticle ;                               // директорий файлов
    private $filesBuffer = [] ;                      // список файлов в буфере
    private $currentTopicId ;                      // Id текущей галереи
    private $userLogin ;
    private $userStat ;
    private $statError = false ;                    //   Ошибка, связанная со статусом
    private $topicSelectError = false;            // ошибка выбора альбома
    private $FORWARD_CNT_NAVIGATOR = 'cnt_navigator' ; // имя для передачи управления
    private $NEW_TITLE = 'new article!' ;                 //  комментарий к новому изображению
    private $URL_TO_ARTICLE ;
    private $htmlDirTop ;
    //---------------------------------------------------------------//

    public function __construct($getArray,$postArray) {
        $this->articleStatEdit = (isset($this->parListGet['edit'])) ?
            TaskStore::ARTICLE_STAT_EDIT : TaskStore::ARTICLE_STAT_SHOW ;

        $this->URL_TO_ARTICLE = TaskStore::$htmlDirTop.'/index.php?cnt=cnt_article' ;
        $this->htmlDirTop = TaskStore::$htmlDirTop ;
        $this->dirArticle = TaskStore::$dirArticleHeap ;
        parent::__construct($getArray,$postArray) ;

    }
    protected function prepare() {
        //------- работа   ------------//
        $this->currentTopicId = TaskStore::getParam('topicId') ;
        $this->userLogin = TaskStore::getParam('userLogin') ;
        $userStat = TaskStore::getParam('userStatus') ;
        $this->topicList = $this->mod->getTopic() ;   // все темы
        $this->articles = $this->mod->getArticles($this->userLogin) ;  // статьи, загруженные userLogin
        if (isset($this->parListPost['show']) || isset($this->parListGet['show'])) {   // просмотр
           // $this->nameForView = $this->nameForViewShow ;

            $this->forwardCntName = $this->FORWARD_CNT_NAVIGATOR ;  // передача управления для

        }elseif ($userStat < TaskStore::USER_STAT_USER) {
            $this->statError = true;
        }
        if (empty($this->currentTopicId)) {
        //    $this->gallerySelectError = true ;
        }

        if (isset($this->parListPost['save'])) {   // сохранить и выйти
                                                 // сохраняем только отмеченные по  checkbox
            $this->saveArticle() ;
        }

        if (isset($this->parListPost['add'])) {   // добавить статьи
            $this->addArticle() ;
        }

        if (isset($this->parListPost['del'])) {   // удалить отмеченные
            $this->delCheckedArticle() ;
        }


        parent::prepare() ;
    }
////////////////////////////////////////////////////////////////////////////////////////
    /**
     * из общего списка файлов выбирает отмеченные в форме
     * @param $listFiles - общий список
     * @return array - списокОтмеченных
     */
    private function getCheckedList()
    {   // список отмеченных файлов
        $checkedArticles = [];     // отмеченные файлы
        $articles = $this->articles ;
        if (empty($articles)) {
            return $checkedArticles;
        }
        foreach ($articles as $aid=>$article) {  // оставим только отмеченные check-<file>
            $file = $article['file'] ;
            $chkName = 'check#' . $aid ;
            if (isset($this->parListPost[$chkName])) {       // элемент отмечен

                $checkedArticles[$aid] = $article ;
                $checkedArticles[$aid]['title'] = $this->getArticleTitle($aid) ;
                $checkedArticles[$aid]['topics'] = $this->getArticleTopics($aid) ;
           }
        }
        return $checkedArticles;
    }

    /**
     * возвращает заголовок статьи
     * @param $articleId
     * @return mixed
     */
    private function getArticleTitle($articleId) {
        $titleName = 'title#'.$articleId ;
        return $this->parListPost[$titleName] ;
    }

    /**
     * @param $articleId
     * @return array  - списокТемСтатьи
     */
    private function getArticleTopics($articleId) {
        $currentTopics = [] ;
        foreach ($this->topicList as $tid=>$topic) {
            $tName = 'topic#'.$tid.'#'.$articleId ;
            if (isset($this->parListPost[$tName])) {
                $currentTopics[$tid] = $topic ;

            }
        }
        return $currentTopics ;
    }
    /**
     * Сохранить отмеченные статьи
     */
    private function saveArticle() {
        $owner = $this->userLogin ;
        $articlesForSave = $this->getCheckedList();
        $this->mod->putArticles($owner, $articlesForSave);
        $this->articles = $this->mod->getArticles($this->userLogin) ;  // статьи, загруженные userLogin
    }


    private function addArticle() {
        $owner = $this->userLogin ;
        $addArticles = []; //  -  для загрузки в БД
        $filesNorm = $this->mod->filesTransform('articleFile');  // преобразовать в нормальную форму
        $nLoaded = 0;

        foreach ($filesNorm as $fdes) {
            $name = $fdes['name'];
            $tmpName = $fdes['tmp_name'];
            $error = $fdes['error'];

            if (!0 == $error) {
                $this->msg->addMessage("ERROR: Ошибка выбора файла:" . $name . " код ошибки: " . $error) ;
                continue;
            }
            $addArticles[] = [
                'articleid'  => false ,
                'userid'     => false,
                'title'      => $this->NEW_TITLE ,
                'annotation' => '' ,
                'file'       => $name ,
                'topics'     => []
            ] ;
            if ($this->mod->doubleLoad($this->dirArticle, $name)) {
                $this->msg->addMessage("INFO: Попытка повторной загрузки файла :" . $name) ;
            }
            $fileTo = $this->dirArticle . '/' . basename($name);
            if (is_uploaded_file($tmpName)) {
                $res = move_uploaded_file($tmpName, $fileTo);
                $nLoaded++;
            }

        }
        $newOnly = true;   // блокирует изменение имеющихся в БД
        $this->mod->putArticles($owner, $addArticles, $newOnly);    // добавить в БД/обновить комментарий
        $this->articles = $this->mod->getArticles($this->userLogin) ;  // статьи, загруженные userLogin
        $this->msg->addMessage('INFO:Загружено  файлов:' . $nLoaded) ;
    }
    private function delCheckedArticle() {
        $articlesForDelete = $this->getCheckedList();
        $this->mod->delArticles($articlesForDelete);
        $this->articles = $this->mod->getArticles($this->userLogin) ;  // статьи, загруженные userLogin
    }
    /////////////////////////////////////////////////////////////////////////////////

    /**
     *  построить массив $ownStore - собственные параметры
     */
    protected function buildOwnStore() {    // в памяти контроллера сохраняется список картинок
                                            // для копирования
       $this->ownStore = ['buffer' => $this->filesBuffer ] ;
    }
    protected function saveOwnStore() {
        parent::saveOwnStore() ;
    }
    /**
     * выдает имя контроллера для передачи управления
     * альтернатива viewGo
     * Через  $pListGet , $pListPost можно передать новые параметры
     */
    public function getForwardCntName(&$plistGet,&$plistPost) {
        $plistGet = [] ;
       $plistPost = [] ;
        return $this->forwardCntName ;


//        parent::getForwardCntName($plistGet,$plistPost) ;
    }
    public function viewGo() {
        $this->parForView = [
            'topicList'      => $this->topicList,
            'articles'       => $this->articles,
            'urlArticleEdit'    => $this->URL_TO_ARTICLE ,
            'dirArticle' => TaskStore::$dirTop.'/articleHeap' ,
            'htmlDirTop' => $this->htmlDirTop
            ] ;

        parent::viewGo() ;
    }

}