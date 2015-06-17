<?php
/**
 * вывод авиоризации
 */

class Cnt_vw_user extends Cnt_vw_base {
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
            'login'             => $this->mod->getLogin(),
            'password'          => $this->mod->getPassword() ,
            'profileIsPossible' => $this->mod->isGoProfile(),
            'urlToProfile'      => $this->mod->getUrlProfile(),
            'urlToUser'         => $this->URL_OWN ] ,
        'components' => false ,
        'dir' => $this->DIR_VIEW ,
        'file' => 'vw_userLogin'
        ] ;
    }
}