<?php
/**
 * Created by PhpStorm.
 * User: michael
 * Date: 24.05.15
 * Time: 16:23
 */

class Db_article extends Db_base {
    public function __construct() {
        parent::__construct() ;
    }

    /**
     * Выбрать статьи по заданной теме
     * если тема = пусто, то все
     * @param $topicid
     * @return array|bool
     */
    public function getArticlesByTopic($topicid) {
        $pdo = $this->pdo ;
        $articles = [];
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

        try {
            $smt = $pdo->prepare($sql);
            if (false !== $topicid ) {
                $smt->execute(['topicid' => $topicid ]);
            }else {
                $smt->execute([]);
            }
        } catch (PDOException  $e) {
            $this->msg->addMessage('ERROR:'. __METHOD__ .':' . $e->getMessage() ) ;
            return false;
        }
        if ( 0 == $smt->rowCount() ){
            return false ;
        }
        foreach ($smt as $row) {
            $articleid = $row['articleid'] ;
            $topics = $this->getArticleTopics($articleid) ;  // рубрики статьи
            $articles[] = [
                'articleid'  => $articleid ,
                'userid'     => $row['userid'],
                'title'      => $row['title'] ,
                'annotation' => $row['annotation'] ,
                'file'       => $row['file'] ,
                'topics'     => $topics             //  список тем здесь не нужен
            ] ;

        }
        return $articles ;
    }

    /**
     * выбирает атрибуты статей автора
     * @param $author  - это userLogin
     * @return array|bool
     */
    function getArticles($author) {
        $pdo = $this->pdo ;
        $articles = [];   // ['articleid' => ,'title' => ,'annotation'=>,
                             //   'file'=>,'topics'=>[ ['topicid'=>,'topicname'=>],.. ] ]
        $sql = 'SELECT articleid,
                        userid,
                        title,
                        file,
                        annotation
                        FROM articles ' ;
        $userid = false ;
        $whereid = '' ;
        if (!empty($author)) {
            $userid = $this->getUserid($author) ;
            if (!empty($userid)) {
                $where = 'WHERE userid = :userid ';
            }
        }
        $sql .= $where ;

        try {
            $smt = $pdo->prepare($sql);
            if (false !== $userid) {
                $smt->execute(['userid' => $userid]);
            }else {
                $smt->execute([]);
            }
        } catch (PDOException  $e) {
            $this->msg->addMessage('ERROR:'. __METHOD__ .':' . $e->getMessage() ) ;
            return false;
        }
        if ( 0 == $smt->rowCount() ){
            return false ;
        }
        foreach ($smt as $row) {
            $articleid = $row['articleid'] ;
            $topics = $this->getArticleTopics($articleid) ;  // рубрики статьи
            $articles[$articleid] = [
                'articleid'  => $articleid ,
                'userid'     => $row['userid'],
                'title'      => $row['title'] ,
                'annotation' => $row['annotation'] ,
                'file'       => $row['file'] ,
                'topics'     => $topics
            ] ;

        }
        return $articles ;

    }

    /**
     * Выбрать темы(рубрики) статьи
     * @param $articleid
     */
    private function getArticleTopics($articleid) {
        $pdo = $this->pdo ;
        $articleTopics = [];   // [ ['topicid'=>,'topicname'=>],.. ] ]
        $sql = 'SELECT topicarticle.topicid,
                       topics.topicname
                       FROM topicarticle,topics
                       WHERE topicarticle.articleid = :articleid AND
                             topics.topicid = topicarticle.topicid ' ;
        try {
            $smt = $pdo->prepare($sql) ;
            $smt->execute(['articleid' => $articleid]) ;
        }catch (PDOException  $e){
            $this->msg->addMessage('ERROR:'. __METHOD__ .':' . $e->getMessage() ) ;
            return false ;
        }
        if ( 0 == $smt->rowCount() ) {
            return false;
        }
        foreach ($smt as $row) {
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
     * @param $articleid
     * @param $addTopics
     */
    private function addNewTopics($articleid,$addTopics) {
        $pdo = $this->pdo ;
        $sql = 'INSERT INTO topicarticle (articleid,topicid) VALUES
                            (:articleid, :topicid)' ;
        try {
            $smt = $pdo->prepare($sql) ;
            foreach ($addTopics as $addT) {
                $smt->execute([
                    'articleid' => $articleid ,
                    'topicid'   => $addT['topicid']
                ]) ;
            }
        }catch (PDOException  $e){
            $this->msg->addMessage('ERROR:'. __METHOD__ .':' . $e->getMessage() ) ;
            return false ;
        }
    }

    /**
     * убрать несуществующие темы для статьи
     * @param $articleid
     * @param $delTopics
     */
    private function delOldTopics($articleid,$delTopics) {
        $pdo = $this->pdo ;
        $sql = 'DELETE FROM topicarticle
                       WHERE id = :tid' ;
        try {
            $smt = $pdo->prepare($sql) ;
            foreach ($delTopics as $delT) {
                $smt->execute([
                    'id' => $delT['id']]) ;
            }
        }catch (PDOException  $e){
            $this->msg->addMessage('ERROR:'. __METHOD__ .':' . $e->getMessage() ) ;
            return false ;
        }

    }

    function findArticleFile($fileArticle) {
        $pdo = $this->pdo ;
        $sql = 'SELECT * FROM articles
                WHERE file = :fileArticle ' ;
        try {
            $smt = $pdo->prepare($sql) ;
            $smt->execute(['fileArticle' => $fileArticle]) ;
            $row = $smt->fetch(PDO::FETCH_ASSOC) ;
        }catch (PDOException  $e){
            $this->msg->addMessage('ERROR:'. __METHOD__ .':' . $e->getMessage() ) ;
            return false ;
        }
        return ( false === $row) ? false : true ;
    }
    /**
     * Помещает в БД списокФайлов-статей и
     * в отдельную таблицу topicarticle и authorarticle
     * @param $author - это login добавляющего статью
     * @param $articles
     * @return int
     */
    function putArticles($author,$articles) {
        $pdo = $this->pdo ;
        $n = 0 ;
        $userid = $this->getUserid($author) ;
        $sqlInsert = 'INSERT INTO articles (userid,title  ,annotation  ,file) VALUES
                                           (:userid,:title ,:annotation ,:file )';
        $sqlUpdate = 'UPDATE articles
                        SET title = :title,
                            annotation = :annotation
                        WHERE articleid = :articleid ' ;
        try{
            $smtInsert = $pdo->prepare($sqlInsert) ;
            $smtUpdate = $pdo->prepare($sqlUpdate) ;
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
                    $smtInsert->execute([
                        'userid' => $userid,
                        'title' => $title,
                        'annotation' => $annotation,
                        'file' => $file]);
                    $aid = $pdo->lastInsertId();
                }else {                      // существующая запись

                    $smtUpdate->execute([
                        'articleid' => $aid,
                        'title' => $title,
                        'annotation' => $annotation]);
                }

                    $this->putArticleTopics($aid, $topics);
                    $this->putAuthorArticle($author, $aid);
                $n ++ ;
            }
        }catch (PDOException $e){
            $this->msg->addMessage('ERROR:'. __METHOD__ .':' . $e->getMessage() ) ;
            return false ;
        }
        return $n ;
    }

    /**
     * сохранить темы
     * @param $articleid      - статья
     * @param $articleTopics  - темы статьи
     */
    private function putArticleTopics($articleid,$articleTopics) {
        $pdo = $this->pdo ;
        $addTopics = false ;     // добавить строки
        $delTopics = false ;     // удалить лишние
        $sql = 'SELECT id,topicid,articleid
                      FROM topicarticle
                      WHERE articleid = :articleid ' ;
        try {
            $smt = $pdo->prepare($sql) ;
            $smt->execute(['articleid' => $articleid]) ;
        }catch (PDOException  $e){
            $this->msg->addMessage('ERROR:'. __METHOD__ .':' . $e->getMessage() ) ;
            return false ;
        }
        if ( 0 == $smt->rowCount() ) {
            $addTopics = $articleTopics ;
            $delTopics = [] ;
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
        if(is_array($addTopics)) {
            $this->addNewTopics($articleid,$addTopics);      // добавить недостающие
        }
        if(is_array($delTopics)) {
            $this->delOldTopics($articleid,$delTopics);     // убрать лишние
        }
    }




    /**
     * Добавляет связь автор - статья
     * @param $author
     * @param $articleid
     * @return bool
     */
    private function putAuthorArticle($author, $articleid) {
        $pdo = $this->pdo ;
        $authorid = $this->getUserid($author) ;
        $sql = 'INSERT INTO authorarticle (authorid ,articleid) VALUES
                                           (:authorid ,:articleid )';
        $sqlFind = 'SELECT * FROM authorarticle
                           WHERE authorid = :authorid AND
                                 articleid = :articleid ' ;
        try{
            $smtFind = $pdo->prepare($sqlFind) ;
            $smtFind->execute([
                'authorid' => $authorid ,
                'articleid'=> $articleid ]) ;
            $row = $smtFind->fetch(PDO::FETCH_ASSOC) ;
            if (false === $row) {    // нет записи ->  надо добавить
                $smt = $pdo->prepare($sql) ;
                $smt->execute([
                    'authorid' => $authorid ,
                    'articleid'=> $articleid]) ;
            }
        }catch (PDOException $e){
            $this->msg->addMessage('ERROR:'. __METHOD__ .':' . $e->getMessage() ) ;
            return false ;
        }
        return true ;
    }



    /**
     * Удалить из БД списокСтатей
     * @param $articles
     * @return int
     */
    function delarticles($articles) {
        $pdo = $this->pdo ;
        $n = 0 ;
        $sql = 'DELETE FROM articles WHERE articleid = :articleid' ;
        try {
            $smt = $pdo->prepare($sql);
            foreach ($articles as $articleid=>$article) {
                    $smt->execute(['articleid'=> $articleid] ) ;
                    $n++ ;
            }
        }catch (PDOException $e){
            $this->msg->addMessage('ERROR:'. __METHOD__ .':' . $e->getMessage() ) ;
            return false ;
        }
        return $n ;
    }
    /**
     * возвращает список тем
     * @return array
     */
    function getTopic() {
        $pdo = $this->pdo ;
        $topicList = [] ;
        $sql = 'SELECT topicid,
                       topicname
                 FROM topics
                 ORDER BY topicname' ;
        try{
            $smt = $pdo->prepare($sql) ;
            $smt->execute([]) ;
        }catch (PDOException $e){
            $this->msg->addMessage('ERROR:'. __METHOD__ .':' . $e->getMessage() ) ;
            return false ;
        }
        foreach ($smt as $row) {
            $topicId = $row['topicid'] ;
            $topicList[$topicId] = [
                'topicid' => $row['topicid'],
                'topicname' => $row['topicname']
            ] ;
        }
        // добавим пустой элемент
        $topicList[0] = [
            'topicid' => false ,
            'topicname' => 'все темы' ] ;

        return $topicList ;
    }

    /**
     * наличие темы с заданным именем
     * @param $topicname
     * @return bool
     */
    function findTopic($topicName){
        $pdo = $this->pdo ;
        $sql = 'SELECT topicid from topics where topicname = :topicName ' ;
        try{
            $smt = $pdo->prepare($sql) ;
            $smt->execute(['topicName' => $topicName]) ;
        }catch (PDOException $e){
            $this->msg->addMessage('ERROR:'.__METHOD__.':' . $e->getMessage() ) ;
            return false ;
        }
        $row = $smt->fetch(PDO::FETCH_ASSOC);
       return  (false === $row) ? false : $row['topicid']  ;
    }

    /**
     * опрелить userid по  login
     * @param $login
     * @return userid
     */
    function getUserid($login) {
        $pdo = $this->pdo ;
        $sql = 'SELECT * FROM users where login = :login' ;
        try{
            $smt = $pdo->prepare($sql) ;
            $smt->execute(['login'  => $login]) ;
        }catch (PDOException $e){
            $this->msg->addMessage('ERROR:'.__METHOD__.':' . $e->getMessage() ) ;
            return false ;
        }
        $row = $smt->fetch(PDO::FETCH_ASSOC);
        return  (false === $row) ? false : $row['userid']  ;
    }


    /**
     * Добавить новую тему
     * @param $topicName
     * @return bool
     */
    function putTopic ($topicName) {
        $pdo = $this->pdo ;
        $topicId = $this->findTopic($topicName) ;
        if (false !== $topicId) {
            return true;
        }
        $sql = 'INSERT INTO topics (topicname) VALUES (:topicName)';
        try {
            $smt = $pdo->prepare($sql);
            $smt->execute(['topicName'=> $topicName]);
        } catch (PDOException $e) {
            $this->msg->addMessage('ERROR:'.__METHOD__.':' . $e->getMessage() ) ;
            return false;
        }
        $topicId = $pdo->lastInsertId() ;
        return  $topicId ;
    }

    /**
     * удалить тему
     * @param $topicName
     * @return bool
     */
    function delTopic ($topicName) {
        $pdo = $this->pdo ;
        return true ;
    }

    /**
     * преобразует  $_FILES в нормальную форму
     * @param $topName
     * @return array
     */
    function filesTransform($topName)
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

    function doubleLoad($dirName,$fName) {     // повторная загрузка
        return (file_exists($dirName.'/'.$fName)) ;
    }

}