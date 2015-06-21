<?php
/**
 * Created by PhpStorm.
 * User: michael
 * Date: 20.06.15
 * Time: 12:19
 */

class Db_article extends Db_base {

    /**
     * Выбрать статьи по заданной теме
     * если тема = пусто, то все
     */
    public function getArticlesByTopic($topicid) {
        $sql = 'SELECT articleid,
                        userid,
                        title,
                        file,
                        annotation
                        FROM articles  ' ;

        $where = '' ;
        if (!empty($topicid)) {
            $where = 'WHERE articleid IN (
              SELECT articleid FROM topicarticle
                     WHERE topicid = :topicid )' ;
        }
        $sql .= $where ;
        $subst = ( !empty($topicid) ) ? ['topicid' => $topicid] :[] ;

        $rows = $this->sqlExecute($sql,$subst,__METHOD__) ;

        if ( 0 == $this->getRowCount() ){
            return false ;
        }
        return $this->articlesFromRows($rows) ;
    }

    /**
     *
     */
    private function articlesFromRows($rows,$withKey=false) {
        $articles = [] ;
        foreach ($rows as $row) {
            $articleid = $row['articleid'] ;
            $topics = $this->getArticleTopics($articleid) ;  // рубрики статьи
            $article = [
                'articleid'  => $articleid ,
                'userid'     => $row['userid'],
                'title'      => $row['title'] ,
                'annotation' => $row['annotation'] ,
                'file'       => $row['file'] ,
                'topics'     => $topics             //  список тем здесь не нужен
            ] ;
            if ($withKey) {
                $articles[$articleid] = $article ;
            }else {
                $articles[] = $article ;
            }
        }
        return $articles ;
    }
    /**
     * выбирает атрибуты статей автора
     */
    public function getArticles($author) {
        $sql = 'SELECT articleid,
                        userid,
                        title,
                        file,
                        annotation
                        FROM articles ' ;
        $userid = false ;
        $where = [] ;
        if (!empty($author)) {
            $userid = $this->getUserid($author) ;
            if (!empty($userid)) {
                $where = 'WHERE userid = :userid ';
            }
        }
        $sql .= $where ;
        $subst = (!empty($userid) ) ? ['userid' => $userid] :[] ;
        if (false === ($rows = $this->sqlExecute($sql,$subst,__METHOD__) ) ) {
            return false ;
        }
        if ( 0 == $this->getRowCount() ){
            return false ;
        }
        $withKey = true ;
        return $this->articlesFromRows($rows,$withKey) ;
    }

    /**
     * Выбрать темы(рубрики) статьи
     */
    private function getArticleTopics($articleid) {
        $sql = 'SELECT topicarticle.topicid,
                       topics.topicname
                       FROM topicarticle,topics
                       WHERE topicarticle.articleid = :articleid AND
                             topics.topicid = topicarticle.topicid ' ;
        $subst = ['articleid' => $articleid] ;
        if (false === ($rows =  $this->sqlExecute($sql,$subst,__METHOD__)) ) {
            return false ;
        }

        if ( 0 == $this->getRowCount() ) {
            return false;
        }
        return $this->topicFromRows($rows) ;
    }

    private function topicFromRows($rows) {
        $articleTopics = [] ;
        foreach ($rows as $row) {
            $tid = $row['topicid'] ;
            $articleTopics[$tid] = [
                'topicid'   => $tid ,
                'topicname' => $row['topicname']
            ] ;
        }
        return $articleTopics ;
    }

    /**
     * добавить новые темы для статьи
     */
    private function addNewTopics($articleid,$addTopics) {
        $sql = 'INSERT INTO topicarticle (articleid,topicid) VALUES
                            (:articleid, :topicid)' ;

        foreach ($addTopics as $addT) {
            $subst = [
                'articleid' => $articleid ,
                'topicid'   => $addT['topicid']
            ] ;
            $smt = $this->sqlExecute($sql,$subst,__METHOD__) ;
        }
        return true ;
    }

    /**
     * убрать несуществующие темы для статьи
     */
    private function delOldTopics($articleid,$delTopics) {
        $sql = 'DELETE FROM topicarticle
                       WHERE articleid = :articleid AND
                             topicid = :topicid' ;
        foreach ($delTopics as $delT) {

            $subst = [
                'articleid'=> $articleid,
                'topicid' => $delT['topicid'] ] ;
            $this->sqlExecute($sql,$subst,__METHOD__) ;
        }
         return true ;
    }

    function findArticleFile($fileArticle) {
        $sql = 'SELECT * FROM articles
                WHERE file = :fileArticle ' ;
        $subst = ['fileArticle' => $fileArticle] ;
        if (false === ($this->sqlExecute($sql,$subst,__METHOD__))) {
            return false ;
        }

        return  ( 0 < $this->getRowCount() )  ;
    }
    /**
     * Помещает в БД списокФайлов-статей и
     * в отдельную таблицу topicarticle и authorarticle
     * @param $author - это login добавляющего статью
     * @param articles
     */
    function putArticles($author,$articles) {
        $n = 0 ;
        $userid = $this->getUserid($author) ;
        foreach($articles as $article) {
            $aid = $article['articleid'] ;
            $uid = $article['userid'] ;
            $title = $article['title'] ;
            $annotation = $article['annotation'] ;
            $file = $article['file'] ;
            $topics = $article['topics'] ;
            if (false === $aid) {     // новая запись
                if ($this->findArticleFile($file)) {
                    $this->msg->addMessage(
                        'INFO:Статья, содержащаяся в файле: ' . $file . ' есть в БД.');
                    continue;
                }
                $this->articleInsert($userid,$title,$annotation,$file) ;
            }else {                      // существующая запись
                $this->articleUpdate($aid,$title,$annotation) ;
            }

            $this->putArticleTopics($aid, $topics);
            $this->putAuthorArticle($author, $aid);
            $n ++ ;
        }
        return $n ;
    }
    private function articleInsert($userid,$title,$annotation,$file) {
        $sql = 'INSERT INTO articles (userid,title  ,annotation  ,file) VALUES
                                           (:userid,:title ,:annotation ,:file )';
        $subst = [
            'userid' => $userid,
            'title' => $title,
            'annotation' => $annotation,
            'file' => $file] ;
        $aid = $this->sqlExecute($sql,$subst,__METHOD__) ;  // добавленный  id
        return $aid ;
    }
    private function articleUpdate($aid,$title,$annotation)
    {
        $sql = 'UPDATE articles
                        SET title = :title,
                            annotation = :annotation
                        WHERE articleid = :articleid ';
        $subst = [
            'articleid' => $aid,
            'title' => $title,
            'annotation' => $annotation];
        $n = $this->sqlExecute($sql, $subst, __METHOD__);
        return $n;
    }
    /**
     * сохранить темы
     */
    private function putArticleTopics($articleid,$articleTopics) {
        $addTopics = false ;
        $delTopics = false ;     // удалить лишние
        $sql = 'SELECT id,topicid,articleid
                      FROM topicarticle
                      WHERE articleid = :articleid ' ;
        $subst = ['articleid' => $articleid] ;
        $this->sqlExecute($sql,$subst,__METHOD__) ;

        $sep = $this->topicsSeparate($articleTopics) ; // разделить на добавить/убрать
        $addTopics = $sep[0] ;        // добавить строки
        $delTopics = $sep[1] ;        // удалить лишние
        if(is_array($addTopics)) {
            $this->addNewTopics($articleid,$addTopics);      // добавить недостающие
        }
        if(is_array($delTopics)) {
            $this->delOldTopics($articleid,$delTopics);     // убрать лишние
        }
    }

    /**
     * Разделить список на ДобавитьНовые и УдалитьЛишние
     */
    private function topicsSeparate($articleTopics) {
        $addTopics = [] ;    // новыеСтроки
        $delTopics = [] ;    // удалитьЛишние
        $smt = $this->getResult() ;    // результат запроса
        if ( 0 == $this->getRowCount() ) {
            $addTopics = $articleTopics ;  // все в добавление
        }else {
            $rowSelect = [] ;
            foreach($smt as $row) {
                $tid = $row['topicid'] ;
                $rowSelect[$tid] = $row ;
                if ( !isset($articleTopics[$tid]) ) {   // лишние строки
                    $delTopics[] = $row;
                }
            }
            foreach($articleTopics as $tid=>$at) {
                if ( !isset($rowSelect[$tid]) ) {   // лишние строки
                    $addTopics[] = $at;
                }
            }
        }
        return [$addTopics,$delTopics] ;
    }



    /**
     * Добавляет связь автор - статья
     */
    private function putAuthorArticle($author, $articleid) {
        return true ;
    }
    /**
     * Удалить из БД списокСтатей
     */
    function delarticles($articles) {
        $n = 0 ;
        $sql = 'DELETE FROM articles WHERE articleid = :articleid' ;
        foreach ($articles as $article) {
            $subst = ['articleid' => $article['articleid']] ;
            $this->sqlExecute($sql,$subst,__METHOD__) ;
            $n++ ;
        }
        return $n ;
    }
    /**
     * возвращает список тем
     */
    function getTopic() {
        $topicList = [] ;
        $sql = 'SELECT topicid,
                       topicname
                 FROM topics
                 ORDER BY topicname' ;
       if (false === ( $rows = $this->sqlExecute($sql,[],__METHOD__) )) {
           return false ;
       }

        $topicList = $this->topicFromRows($rows) ;
        // добавим пустой элемент ;
        $topicList[0] = [
            'topicid' => false ,
            'topicname' => 'все темы' ] ;

        return $topicList ;
    }

    /**
     * наличие темы с заданным именем
     */
    function findTopic($topicName){
        $sql = 'SELECT topicid from topics where topicname = :topicName ' ;
        $subst = ['topicName' => $topicName] ;
        $rows= $this->sqlExecute($sql,$subst,__METHOD__) ;
        if (false === $rows){
            return false ;
        }
        $row = $rows[0] ;
        return  (empty($row) ) ? false : $row['topicid']  ;
    }

    /**
     * опрелить userid по  login
     */
    function getUserid($login) {
        $sql = 'SELECT * FROM users where login = :login' ;
        $subst = ['login' => $login] ;
        $rows= $this->sqlExecute($sql,$subst,__METHOD__) ;
        if (false === $rows){
            return false ;
        }
        $row = $rows[0] ;
        return  (empty($row) ) ? false : $row['userid']  ;
    }


    /**
     * Добавить новую тему
     */
    function putTopic ($topicName) {
        $topicId = $this->findTopic($topicName) ;
        if (false !== $topicId) {
            return true;
        }
        $sql = 'INSERT INTO topics (topicname) VALUES (:topicName)';
        $subst = ['topicName'=> $topicName] ;
        $topicId = $this->sqlExecute($sql,$subst,__METHOD__) ;
        return  $topicId ;
    }

    /**
     * удалить тему
     */
    function delTopic ($topicName) {
        return true ;
    }
    public function addComment($commentText,$userLogin,$articleId,$date) {
        $sql = 'INSERT INTO commentarticle (articleid,authorid,comment,date) VALUES
                (:articleId , :authorId , :text , :date)' ;
        $userId = $this->getUserid($userLogin) ;
        $subst = [
            'articleId' => $articleId,
            'authorId'  => $userId,
            'text'      => $commentText,
            'date'      => $date   ] ;
        return $this->sqlExecute($sql,$subst,__METHOD__) ;
    }
    public function getComments($articleId) {
        $sql = 'SELECT users.login,
                       commentarticle.comment,
                       commentarticle.date
                       FROM commentarticle,users
                       WHERE commentarticle.articleid = :articleId AND
                       commentarticle.authorid = users.userid
                       ORDER BY commentarticle.id DESC ' ;
        $subst = ['articleId' => $articleId] ;
        if (false === ($rows = $this->sqlExecute($sql,$subst,__METHOD__))) {
            return false ;
        }
        return $this->makeCommentsFromRows($rows) ;
    }
    private function makeCommentsFromRows($rows) {
        $comments= [] ;
        foreach ($rows as $row) {
            $comments[] = [
               'author' => $row['login'],
                'text'  => $row['comment'],
                'date' =>  $row['date']
             ] ;
        }
        return $comments ;
    }
    /**
     * преобразует  $_FILES в нормальную форму
     */
    public function filesTransform($topName)
    {
        /** переведем $_FILES в нормальную форму */
        $filesNorm = [];
        $names = $_FILES[$topName]['name'];
        $n = count($names);      // количество файлов
        for ($i = 0; $i < $n; $i++) {
            $fName = $_FILES[$topName]['name'][$i];
            $fType = $_FILES[$topName]['type'][$i];
            $fTmpName = $_FILES[$topName]['tmp_name'][$i];
            $fError = $_FILES[$topName]['error'][$i];
            $fSize = $_FILES[$topName]['size'][$i];
            $filesNorm[] = [
                'name' => $fName,
                'type' => $fType,
                'tmp_name' => $fTmpName,
                'error' => $fError,
                'size' => $fSize

            ];
        }
        return $filesNorm;
    }

    public function doubleLoad($dirName,$fName) {     // повторная загрузка
        return (file_exists($dirName.'/'.$fName)) ;
    }

}