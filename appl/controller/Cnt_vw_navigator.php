<?php
/**
 * Дополнение класса навигатор страниц
 *
 */

class cnt_vw_navigator extends Cnt_vw_base {

    public function partMainDef() {
        return [
        'name' => 'main' ,
        'parameters' => false ,
        'components' =>
        ['partHeadPart','partTopMenu','partContent','partFooter','partRightPanel'] ,
        'dir' => $this->DIR_LAYOUT ,
        'file'=> 'lt_footerHalf'
        ] ;
    }

    /**
     * Центральная часть
     */
    public function partContentDef() {
        return [
        'name' => 'partContent' ,
        'parameters' => false ,
        'components' => ['partMessage','partDataContent'] ,
        'dir' => $this->DIR_VIEW ,
        'file' => 'contentPart'
        ] ;
    }

    /**
     * Данные центальной части
     */
    public function partDataContentDef() {
        $article = $this->mod->getCurrentArticle() ;
        $errorMessage = (empty($article)) ? 'Статья отсутствует!' : '' ;
        $topics = (empty($article)) ? [] : $article['topics'] ;
        $title  = (empty($article)) ? '' : $article['title'] ;

        return [
        'name' => 'partDataContent' ,                           // центральный вывод
        'parameters' => [
            'topics'  => $topics,          // темы
            'title'   => $title,           // заголовок
            'errorMessage'  =>$errorMessage,
            'dirImages' => $this->DIR_IMAGE ] ,
        'components' => ['partArticleText'] ,     // тест статьи
        'dir' => $this->DIR_VIEW ,
        'file' => 'vw_articleShow'
        ] ;

    }
    public function partArticleTextDef() {
        $article = $this->mod->getCurrentArticle() ;
        return [
        'name' => 'partArticleText' ,                           // текст статьи
        'parameters' => false ,
        'components' => false ,
        'dir' => $this->DIR_ARTICLE ,
        'file' => $article['file']
        ] ;
    }
    public function partFooterDef() {
        return [
        'name' => 'partFooter' ,                           // подвал страницы - навигатор
        'parameters' => [
            'article'        => $this->mod->getCurrentArticle() ,
            'topicList'      => $this->mod->getTopicList() ,
            'currentTopicId' => $this->mod->getCurrentTopicId() ,
            'currentPage'    => $this->mod->getCurrentPage(),// № тек страницы
            'navPageMin'     => $this->mod->getNavPageMin(), // min N страницы в указателе навигатора
            'navPageMax'     => $this->mod->getNavPageMax(), // max N ---------""-------------------
            'urlNavigator'   => $this->URL_OWN,              // адрес для передачи в контроллер
            'dirImages'      => $this->DIR_IMAGE ] ,
        'components' => false ,
        'dir' => $this->DIR_VIEW ,
        'file' => 'vw_navigator'
        ] ;

    }
    public function partRightPanelDef() {
        return [
        'name' => 'partRightPanel' ,                           // правая панель-список статей
        'parameters' => [
            'articles'    => $this->mod->getArticles() ,
            'htmlDirTop'  => $this->HTML_DIR_TOP ] ,
        'components' => false ,
        'dir' => $this->DIR_VIEW ,
        'file' => 'vw_articleList'
        ] ;
    }
}