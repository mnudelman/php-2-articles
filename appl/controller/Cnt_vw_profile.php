<?php
/**
 * вывод редактирование профиля
 */

class Cnt_vw_profile extends Cnt_vw_base {
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
        $parameters = [
            'urlToProfile'    => $this->URL_OWN,
            'urlToDefault'    => $this->mod->getUrlDefault(),
            'login'           => $this->mod->getLogin(),
            'password'        => $this->mod->getPassword() ,
            'profileEditFlag' => $this->mod->getEditFlag(),
            'successfulSave'  => $this->mod->getSuccessful(),
            'profile'         => $this->mod->getProfile(),
            'profileError'    => $this->mod->getError() ] ;
        $components = false;
        $dir = $this->DIR_VIEW;
        $file = 'vw_userProfile';
        $this->vwDriver->addView($name, $parameters, $components, $dir, $file);
    }
}