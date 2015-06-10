<?php
/**
 * класс поддерживает редактирование списка статей
 * Date: 09.06.15
 * Time: 17:41
 */

class mod_article extends mod_base {
    private $db ;                               // объект для обращения к БД
    private $parameters ;                       // параметры модели
    private $articleStatEdit;                   // режим (редакт - просмотр)
    private $topicList = [] ;                   // список всех тем
    private $articles = [] ;                    // список  статей
    private $dirArticle ;                       // директорий файлов
    private $currentTopicId ;                   // Id текущей темы
    private $userLogin ;
    private $userStat ;
    private $NEW_TITLE = 'new article!' ;        //  заголовок новой статьи
    //---------------------------------------------------------------//
    public function __construct() {
        $this->db = new db_article() ;
        parent::__construct() ;
    }
    /**
     * это передача атрибутов пофиля из контроллера
     */
    public function setParameters($parameters) {
        $this->parameters = $parameters ;
        $this->init() ;
    }
    private function init() {
        $this->articleStatEdit = (isset($this->parameters['edit'])) ?
            TaskStore::ARTICLE_STAT_EDIT : TaskStore::ARTICLE_STAT_SHOW ;
        $this->currentTopicId = TaskStore::getParam('topicId') ;
        $this->userLogin = TaskStore::getParam('userLogin') ;
        $this->userStat = TaskStore::getParam('userStatus') ;
        $this->topicList = $this->db->getTopic() ;   // все темы
        $this->articles = $this->db->getArticles($this->userLogin) ;
                                                          // статьи, загруженные userLogin
        $this->dirArticle = $this->parameters['dirArticle'] ;
     }
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
            $chkName = 'check#' . $aid ;
            if (isset($this->parameters[$chkName])) {       // элемент отмечен

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
        return $this->parameters[$titleName] ;
    }

    /**
     * @param $articleId
     * @return array  - списокТемСтатьи
     */
    private function getArticleTopics($articleId) {
        $currentTopics = [] ;
        foreach ($this->topicList as $tid=>$topic) {
            $tName = 'topic#'.$tid.'#'.$articleId ;
            if (isset($this->parameters[$tName])) {
                $currentTopics[$tid] = $topic ;

            }
        }
        return $currentTopics ;
    }
    /**
     * Сохранить отмеченные статьи
     */
    public function saveArticle() {
        $owner = $this->userLogin ;
        $articlesForSave = $this->getCheckedList();
        $this->db->putArticles($owner, $articlesForSave);
        $this->articles = $this->db->getArticles($this->userLogin) ;  // статьи, загруженные userLogin
    }


    public function addArticle() {
        $owner = $this->userLogin ;
        $addArticles = []; //  -  для загрузки в БД
        $filesNorm = $this->db->filesTransform('articleFile');  // преобразовать в нормальную форму
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
            if ($this->db->doubleLoad($this->dirArticle, $name)) {
                $this->msg->addMessage("INFO: Попытка повторной загрузки файла :" . $name) ;
            }
            $fileTo = $this->dirArticle . '/' . basename($name);
            if (is_uploaded_file($tmpName)) {
                $res = move_uploaded_file($tmpName, $fileTo);
                $nLoaded++;
            }

        }
        $newOnly = true;   // блокирует изменение имеющихся в БД
        $this->db->putArticles($owner, $addArticles, $newOnly);    // добавить в БД/обновить комментарий
        $this->articles = $this->db->getArticles($this->userLogin) ;  // статьи, загруженные userLogin
        $this->msg->addMessage('INFO:Загружено  файлов:' . $nLoaded) ;
    }
    public function delCheckedArticle() {
        $articlesForDelete = $this->getCheckedList();
        $this->db->delArticles($articlesForDelete);
        $this->articles = $this->db->getArticles($this->userLogin) ;  // статьи, загруженные userLogin
    }
    ///////////////////////////////////////////////////////////////////
   public function getTopicList() {
       return $this->topicList ;
   }
   public function getArticles() {
       return $this->articles ;
   }


}