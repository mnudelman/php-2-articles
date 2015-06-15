<?php
/**
 *  вывод описания
 */

class Cnt_vw_about extends Cnt_vw_base {
    /**
     * Корневой шаблон
     */
    protected function partMainDef() {
        $name = 'main' ;                               // корень дереваПредставлений
        $parameters = false ;
        $components = ['partHeadPart','partTopMenu','partContent','partFooter','partRightPanel'] ;
        $dir = $this->DIR_LAYOUT ;
        $file = 'lt_footerNo' ;                        // файл - шаблон
        $this->vwDriver->addView($name,$parameters,$components,$dir,$file) ;
    }

    /**
     * формирует тег <head>... </head>
     */
    protected function partHeadPartDef() {
        parent::partHeadPartDef() ;
    }
    /**
     * свой раздел центральной части
     */
    protected function partDataContentDef() {
        $name = 'partDataContent';                           // центральный вывод
        $parameters = false;
        $components = false;
        $dir = $this->DIR_VIEW;
        $file = 'vw_about';
        $this->vwDriver->addView($name, $parameters, $components, $dir, $file);

    }



}