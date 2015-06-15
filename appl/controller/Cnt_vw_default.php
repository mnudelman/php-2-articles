<?php
/**
 *  вывод заставки
 */

class Cnt_vw_default extends Cnt_vw_base {
    protected $vwDriver ;                // объект класса ViewDriver -
    //---------------------------------//
   /**
     * Корневой шаблон
     */
    protected function partMainDef() {
        $name = 'main' ;
        $parameters = false ;
        $components = ['partHeadPart','partTopMenu','partContent','partFooter','partRightPanel'] ;
        $dir = $this->DIR_LAYOUT ;
        $file = 'lt_footerNo' ;
        $this->vwDriver->addView($name,$parameters,$components,$dir,$file) ;
    }
   /**
     * свой раздел центральной части
     */
    protected function partDataContentDef() {
        $name = 'partDataContent';                           // центральный вывод
        $parameters = false;
        $components = false;
        $dir = $this->DIR_VIEW;
        $file = '';
        $this->vwDriver->addView($name, $parameters, $components, $dir, $file);

    }
}