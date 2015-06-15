<?php
/**
 * вывод авиоризации
 */

class Cnt_vw_user extends Cnt_vw_base {
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
            'login'             => $this->mod->getLogin(),
            'password'          => $this->mod->getPassword() ,
            'profileIsPossible' => $this->mod->isGoProfile(),
            'urlToProfile'      => $this->mod->getUrlProfile(),
            'urlToUser'         => $this->URL_OWN ] ;
        $components = false;
        $dir = $this->DIR_VIEW;
        $file = 'vw_userLogin';
        $this->vwDriver->addView($name, $parameters, $components, $dir, $file);
    }
}