<?php
/**
 * вывод редактирование тем
 */

class Cnt_vw_topic extends Cnt_vw_base {
    /**
     * Корневой шаблон
     */
    public function partMainDef() {
        return [
        'name' => 'main' ,
        'parameters' => false ,
        'components' =>
        ['partHeadPart','partTopMenu','partContent','partFooter','partRightPanel'] ,
        'dir' => $this->DIR_LAYOUT ,
        'file' => 'lt_footer'
        ] ;
    }
     /**
     * подвал
     */
    public function partFooterDef() {
        return [
        'name' => 'partFooter' ,                           // подвал страницы
        'parameters' => [
            'topicList'       => $this->mod->getTopicList() ,
            'currentTopicId'  => $this->mod->getTopicId() ,
            'urlToTopic'      => $this->URL_OWN ,
            'topicEditStat'   => $this->mod->getEditStat(),
            'topicStatName'   => $this->mod->getStatName(),
            'editFlag'        => $this->mod->getEditFlag(),
            'addTopicFlag'    => $this->mod->getAddTopicFlag() ] ,
        'components' => false ,
        'dir' => $this->DIR_VIEW ,
        'file' => 'vw_topic'
        ] ;
    }

}