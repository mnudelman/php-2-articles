<?php
/**
 * Дополнение класса навигатор страниц
 *
 */

class cnt_vw_navigator extends Cnt_vw_base {
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
        $parameters = [
            'dirArticle' => $this->DIR_ARTICLE,
            'article'    => $this->mod->getCurrentArticle(),
            'dirImages'  => $this->DIR_IMAGE ];
        $components = false;
        $dir = $this->DIR_VIEW;
        $file = 'vw_articleShow';
        $this->vwDriver->addView($name, $parameters, $components, $dir, $file);
    }
    protected function partFooterDef() {
        $name = 'partFooter';                           // подвал страницы
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
        $name = 'partRightPanel';                           // правая панель
        $parameters = [
            'articles'    => $this->mod->getArticles() ,
            'htmlDirTop'  => $this->HTML_DIR_TOP ] ;
        $components = false;
        $dir = $this->DIR_VIEW;
        $file = 'vw_articleList';
        $this->vwDriver->addView($name, $parameters, $components, $dir, $file);
    }
}