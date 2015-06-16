<?php
/**
 * Дополнение класса навигатор страниц
 *
 */

class cnt_vw_navigator extends Cnt_vw_base {
    /**
     * Формирует все компоненты шаблона
     */
    public function buildViewTree() {    //  дерево Представлений
        $this->partMainDef() ;          //
        $this->partHeadPartDef() ;      // <head> .. </head>
        $this->partTopMenuDef() ;       // меню-начало страницы
        $this->partContentDef() ;       // центральная часть
        $this->partMessageDef() ;       // сообщения
        $this->partDataContentDef() ;
        $this->partFooterDef() ;        // подвал
        $this->partRightPanelDef() ;    // правая панель
        parent::buildViewTree() ;
        $this->partArticleTextDef() ;    // текст статьи
    }

    protected function partMainDef() {
        $name = 'main' ;
        $parameters = false ;
        $components = ['partHeadPart','partTopMenu','partContent','partFooter','partRightPanel'] ;
        $dir = $this->DIR_LAYOUT ;
        $file = 'lt_footerHalf' ;
        $this->vwDriver->addView($name,$parameters,$components,$dir,$file) ;
    }
    protected function partContentDef() {
        $name = 'partContent' ;                              // центральный content
        $parameters = false ;
        $components = ['partMessage','partDataContent'] ;
        $dir = $this->DIR_VIEW ;
        $file = 'contentPart' ;
        $this->vwDriver->addView($name,$parameters,$components,$dir,$file) ;
    }
    protected function partDataContentDef() {
        $name = 'partDataContent';                           // центральный вывод
        $article = $this->mod->getCurrentArticle() ;

        $errorMessage = (empty($article)) ? 'Статья отсутствует!' : '' ;
        $topics = (empty($article)) ? [] : $article['topics'] ;
        $title  = (empty($article)) ? '' : $article['title'] ;


        $parameters = [
            'topics'  => $topics,          // темы
            'title'   => $title,           // заголовок
            'errorMessage'  =>$errorMessage,
            'dirImages' => $this->DIR_IMAGE ];
        $components = ['partArticleText'];     // тест статьи
        $dir = $this->DIR_VIEW;
        $file = 'vw_articleShow';
        $this->vwDriver->addView($name, $parameters, $components, $dir, $file);
    }
    protected function partArticleTextDef() {
        $name = 'partArticleText';                           // текст статьи
        $parameters = false ;
        $components = false ;
        $dir = $this->DIR_ARTICLE ;

        $article = $this->mod->getCurrentArticle() ;
        $file = $article['file'];
        $this->vwDriver->addView($name, $parameters, $components, $dir, $file);
    }
    protected function partFooterDef() {
        $name = 'partFooter';                           // подвал страницы - навигатор
        $parameters = [
            'article'        => $this->mod->getCurrentArticle() ,
            'topicList'      => $this->mod->getTopicList() ,
            'currentTopicId' => $this->mod->getCurrentTopicId() ,
            'currentPage'    => $this->mod->getCurrentPage(),// № тек страницы
            'navPageMin'     => $this->mod->getNavPageMin(), // min N страницы в указателе навигатора
            'navPageMax'     => $this->mod->getNavPageMax(), // max N ---------""-------------------
            'urlNavigator'   => $this->URL_OWN,              // адрес для передачи в контроллер
            'dirImages'      => $this->DIR_IMAGE ] ;
        $components = false;
        $dir = $this->DIR_VIEW;
        $file = 'vw_navigator';
        $this->vwDriver->addView($name, $parameters, $components, $dir, $file);

    }
    protected function partRightPanelDef() {
        $name = 'partRightPanel';                           // правая панель-список статей
        $parameters = [
            'articles'    => $this->mod->getArticles() ,
            'htmlDirTop'  => $this->HTML_DIR_TOP ] ;
        $components = false;
        $dir = $this->DIR_VIEW;
        $file = 'vw_articleList';
        $this->vwDriver->addView($name, $parameters, $components, $dir, $file);
    }
}