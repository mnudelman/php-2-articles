<?php
/**
 *  вывод заставки
 */

class Cnt_vw_default extends Cnt_vw_base {
   /**
     * Корневой шаблон
     */
    public function partMainDef() {
        return [
        'name' => 'main' ,
        'parameters' => false ,
        'components' =>
        ['partHeadPart','partTopMenu','partContent'] ,
        'dir' => $this->DIR_LAYOUT ,
        'file' => 'lt_footerNo'
        ] ;
    }
    public function partDataContentDef() {
        return [
            'name' => 'partDataContent' ,                           // центральный вывод
            'parameters' => [] ,
            'components' => [] ,     // тест статьи
            'dir' => $this->DIR_VIEW ,
            'file' => ''
        ] ;

    }
}