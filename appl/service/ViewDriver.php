<?php

/**
 *
 * Управление выводом
 * Date: 23.05.15
 */
class ViewDriver
{
    private $contView = [];       // таблица имяКонтроллера => формаОтображения
    private $viewLayout = [];     // таблица форма => шаблонСтраницы
    private $viewComponent = [];  // таблица форма => компонентСтраницы для вывода

    private $msg ;
    //--- тек атрибуты ---//
    private $curCnt = '';       // контроллерИмя
    private $curView = '';      // формаОтображения
    private $curLayOut = '';    // шаблонСтраницы
    private $curParams = [];    // парараметрыПодстановки в форму
    private $curComponent = [] ; // компонент страницы для вывода формы

    public function __construct($cntName) {
        $this->init();
        $this->curCnt = $cntName ;
        $this->curView = $this->contView[$this->curCnt] ;
        $this->curLayOut= $this->viewLayout[$this->curView] ;
        $this->curComponent = $this->viewComponent[$this->curView] ;
        $this->msg = TaskStore::getMessage() ;

//        $this->msg->addMessage('DEBUG:'.__METHOD__.':curCnt:'.$this->curCnt) ;
//        $this->msg->addMessage('DEBUG:'.__METHOD__.':curView:'.$this->curView) ;
//        $this->msg->addMessage('DEBUG:'.__METHOD__.':curLayOut:'.$this->curLayOut) ;

        //  подстановка curView
        foreach ($this->curComponent as $key=>$value) {
            if (true === $value) {
                $this->curComponent[$key] = $this->curView;
            }
        }


    }

    /**
     * Вводит таблицы соответствий
     */
    private function init() {
        $this->contView = [
            'cnt_user' => 'vw_userLogin',
            'cnt_profile' => 'vw_userProfile',
            'cnt_topic' => 'vw_topic',
            'cnt_article' => 'vw_articleEdit',
            'cnt_pictureShow' => 'vw_pictureNav',
            'cnt_navigator' => 'vw_pictureNav',
            'cnt_default' => 'vw_default',
            'cnt_about' => 'vw_about' ];

        $this->viewLayout = [
            'vw_userLogin' => 'lt_footer',
            'vw_userProfile' => 'lt_footerNo',
            'vw_topic' => 'lt_footer',
            'vw_articleEdit' => 'lt_footerNo',
            'vw_pictureShow' => 'lt_footerNo',
            'vw_pictureNav' => 'lt_footerHalf',
            'vw_default' => 'lt_footerNo',
            'vw_about' => 'lt_footerNo'];


        $this->viewComponent['vw_userLogin'] = [
            'content' => false,
            'footer' => true];
        $this->viewComponent['vw_userProfile'] = [
            'content' => true,
            'footer' => false];
        $this->viewComponent['vw_topic'] = [
            'content' => false,
            'footer' => true];
        $this->viewComponent['vw_articleEdit'] = [
            'content' => true,
            'footer' => false];
        $this->viewComponent['vw_pictureShow'] = [
            'content' => true,
            'footer' => false];
        $this->viewComponent['vw_pictureShow'] = [  // формы в 2 частях страницы
            'content' => true,
            'footer' => false];

        $this->viewComponent['vw_pictureNav'] = [  // формы в 2 частях страницы
            'content' => 'vw_articleShow',
            'footer' => 'vw_navigator'];
        $this->viewComponent['vw_default'] = [  // форма отсутствует
            'content' => false,
            'footer' => false];
        $this->viewComponent['vw_about'] = [  // форма отсутствует
            'content' => true,
            'footer' => false];
    }
    public function viewExec($paramList) {
        $dir = TaskStore::$dirView ;
        $footer = $this->curComponent['footer'] ;
        $content = $this->curComponent['content'] ;
        if (false !== $footer) {
            $footer = $dir.'/'.$footer.'.php' ;
        }
        if (false !== $content) {
            $content = $dir.'/'.$content.'.php' ;
        }
        //----  подстановка параметров ---- //
        if (is_array($paramList)) {
            foreach ($paramList as $parName => $parMean) {
                $$parName = $parMean;
            }
        }
         $dir = TaskStore::$dirLayout ;

        include_once $dir.'/'.$this->curLayOut.'.php' ;
    }

}