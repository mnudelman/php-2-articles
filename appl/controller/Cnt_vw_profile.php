<?php
/**
 * вывод редактирование профиля
 */

class Cnt_vw_profile extends Cnt_vw_base {
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
    /**
     * свой раздел центральной части
     */
    public function partDataContentDef() {
       return [
        'name' => 'partDataContent' ,                           // центральный вывод
        'parameters' => [
            'urlToProfile'    => $this->URL_OWN,
            'urlToDefault'    => $this->mod->getUrlDefault(),
            'login'           => $this->mod->getLogin(),
            'password'        => $this->mod->getPassword() ,
            'profileEditFlag' => $this->mod->getEditFlag(),
            'successfulSave'  => $this->mod->getSuccessful(),
            'profile'         => $this->mod->getProfile(),
            'profileError'    => $this->mod->getError(),
            'monthList'       => $this->mod->getMonthName() ] ,
        'components' => false ,
        'dir' => $this->DIR_VIEW ,
        'file' => 'vw_userProfile'
        ] ;
    }
}