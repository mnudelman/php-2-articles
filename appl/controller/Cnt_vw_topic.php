<?php
/**
 * вывод редактирование тем
 */

class Cnt_vw_topic extends Cnt_vw_base {
    /**
     * Корневой шаблон
     */
    protected function partMainDef() {
        $name = 'main' ;
        $parameters = false ;
        $components = ['partHeadPart','partTopMenu','partContent','partFooter','partRightPanel'] ;
        $dir = $this->DIR_LAYOUT ;
        $file = 'lt_footer' ;
        $this->vwDriver->addView($name,$parameters,$components,$dir,$file) ;

    }
     /**
     * подвал
     */
    protected function partFooterDef() {
        $name = 'partFooter';                           // подвал страницы
        $parameters = [
            'topicList'       => $this->mod->getTopicList() ,
            'currentTopicId'  => $this->mod->getTopicId() ,
            'urlToTopic'      => $this->URL_OWN ,
            'topicEditStat'   => $this->mod->getEditStat(),
            'topicStatName'   => $this->mod->getStatName(),
            'editFlag'        => $this->mod->getEditFlag(),
            'addTopicFlag'    => $this->mod->getAddTopicFlag() ] ;

        $components = false;
        $dir = $this->DIR_VIEW;
        $file = 'vw_topic';
        $this->vwDriver->addView($name, $parameters, $components, $dir, $file);
    }

}