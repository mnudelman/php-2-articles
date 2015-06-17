<?php
/**
 *  вывод описания
 */

class Cnt_vw_about extends Cnt_vw_base {
    /**
     * Корневой шаблон
     */
    public function partMainDef() {
        return [
        'name' => 'main',                               // корень дереваПредставлений
        'parameters' => false ,
        'components' =>
            ['partHeadPart','partTopMenu','partContent','partFooter','partRightPanel'],
        'dir' => $this->DIR_LAYOUT,
        'file' => 'lt_footerNo'                         // файл - шаблон
        ] ;
    }
    /**
     * свой раздел центральной части
     */
    public function partDataContentDef() {
        return [
        'name' => 'partDataContent',
        'parameters' => false,
        'components' => false,
        'dir' => $this->DIR_VIEW,
        'file' => 'vw_about'
        ] ;
    }
}