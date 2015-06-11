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

    private $msg;
    //--- тек атрибуты ---//
    private $curCnt = '';       // контроллерИмя
    private $curView = '';      // элементОтображения
    private $curLayOut = '';    // шаблонСтраницы
    private $curComponent = []; // компоненты страницы для вывода формы
    private $DIR_TOP;
    private $HTML_DIR_TOP;
    private $DIR_VIEW;
    private $DIR_LAYOUT;
    private $paramList;          // параметры, полученные от контроллера

    public function __construct($cntName)
    {
        $this->msg = TaskStore::getMessage();
        $this->DIR_TOP = TaskStore::$dirTop;
        $this->HTML_DIR_TOP = TaskStore::$htmlDirTop;
        $this->DIR_VIEW = TaskStore::$dirView;
        $this->DIR_LAYOUT = TaskStore::$dirLayout;

        $this->init();

        $this->curCnt = $cntName;
        $this->curView = $this->contView[$this->curCnt];
        $this->curLayOut = $this->viewLayout[$this->curView];
        $this->curComponent = $this->viewComponent[$this->curView];


//        $this->msg->addMessage('DEBUG:'.__METHOD__.':curCnt:'.$this->curCnt) ;
//        $this->msg->addMessage('DEBUG:'.__METHOD__.':curView:'.$this->curView) ;
//        $this->msg->addMessage('DEBUG:'.__METHOD__.':curLayOut:'.$this->curLayOut) ;
        //  подстановка curView
        foreach ($this->curComponent as $key => $value) {
            if (true === $value) {
                $this->curComponent[$key] = $this->curView;
            }
        }


    }

    /**
     * Вводит таблицы соответствий
     */
    private function init() {
        // соотвествие имяКотроллера -> элементОтображения
        $this->contView = [
            'cnt_user'      => 'vw_userLogin',
            'cnt_profile'   => 'vw_userProfile',
            'cnt_topic'     => 'vw_topic',
            'cnt_article'   => 'vw_articleEdit',
            'cnt_navigator' => 'vw_articleNav',
            'cnt_default'   => 'vw_default',
            'cnt_about'     => 'vw_about'];

       // элементОтображения -> шаблон страницы
        $this->viewLayout = [
            'vw_userLogin'   => 'lt_footer',
            'vw_userProfile' => 'lt_footerNo',
            'vw_topic'       => 'lt_footer',
            'vw_articleEdit' => 'lt_footerNo',
            'vw_articleNav'  => 'lt_footerHalf',
            'vw_default'     => 'lt_footerNo',
            'vw_about'       => 'lt_footerNo'];

        // элементОтображения -> компоненты вывода
        $this->viewComponent['vw_userLogin'] = [
            'content'  => false,
            'footer'   => true];
        $this->viewComponent['vw_userProfile'] = [
            'content'  => true,
            'footer'   => false];
        $this->viewComponent['vw_topic'] = [
            'content'  => false,
            'footer'   => true];
        $this->viewComponent['vw_articleEdit'] = [
            'content'  => true,
            'footer'   => false];
        $this->viewComponent['vw_articleNav'] = [  // формы в 3 частях страницы
            'content'  => 'vw_articleShow',
            'footer'   => 'vw_navigator',
            'rightPanel' => 'vw_articleList'];
        $this->viewComponent['vw_default'] = [  // форма отсутствует
            'content' => false,
            'footer'  => false];
        $this->viewComponent['vw_about'] = [
            'content' => true,
            'footer'  => false];
    }

    public function viewExec($paramList)
    {
        $this->paramList =  $paramList ;  // параметры из контроллера

        $partHeadPart = $this->headPart();   // <head>
        $partTopMenu = $this->topMenuPart(); // меню
        //----------------------------------------------------
        //---  partContent = {messageForm - вывод сообщений
        //                dataContent - содержимое }
        $partContent = $this->contentPart();
        $partFooter = $this->footerPart();
        $partRightPanel = $this->rightPanelPart() ;

        //-- основной шаблон ------//
        $layOutFile = $this->DIR_LAYOUT . '/' . $this->curLayOut . '.php';
        $layoutPar = [
            'partHeadPart'   => $partHeadPart,
            'partTopMenu'    => $partTopMenu,
            'partContent'    => $partContent,
            'partFooter'     => $partFooter,
            'partRightPanel' => $partRightPanel
        ];
        echo $this->template($layOutFile, $layoutPar);
    }

    /**
     * Формирование компоненты вывода
     */
    private function template($includeFile, $parList = false)
    {
        if (is_array($parList)) {    // параметры подстановки
            foreach ($parList as $parName => $parMean) {
                $$parName = $parMean;
            }
        }
        ob_start();
        include $includeFile;
        return ob_get_clean();
    }

    /**
     * <head> ... </head>
     */
    private function headPart()
    {
        $partFile = $this->DIR_VIEW . '/headPart.php';
        $params = [
            'htmlDirTop' => $this->HTML_DIR_TOP,
            'dirTop' => $this->DIR_TOP];
        return $this->template($partFile, $params);
    }

    /**
     * раздел меню-заголовок
     */
    private function topMenuPart()
    {
        $partFile = $this->DIR_VIEW . '/topMenu.php';
        $params = [
            'htmlDirTop' => $this->HTML_DIR_TOP,
            'dirTop' => $this->DIR_TOP];
        return $this->template($partFile, $params);
    }

    /**
     * раздел Content
     *---  content = {messageForm - вывод сообщений
     *                dataContent - содержимое }
     */
    private function contentPart()
    {

        $partMessageForm = $this->messagePart();
        $partDataContent = $this->contentDataPart();
        $partFile = $this->DIR_VIEW . '/contentPart.php';
        $params = [
            'partMessageForm' => $partMessageForm,
            'partDataContent' => $partDataContent];
        return $this->template($partFile, $params);
    }

    /**
     * Вывод сообщений
     */
    private function messagePart()
    {
        $partFile = $this->DIR_VIEW . '/messageForm.php';
        return $this->template($partFile);
    }

    /**
     * Данные центральной части
     */
    private function contentDataPart()
    {
        $content = $this->curComponent['content'];
        if (empty($content)) {
            return '';
        }
        $partFile = $this->DIR_VIEW .'/'. $content . '.php';
        $params = $this->paramList;   // параметры от контроллера
        if (!isset($params['dirTop'])) {
            $params['dirTop'] = $this->DIR_TOP;
        }
        if (!isset($params['htmlDirTop'])) {
            $params['htmlDirTop'] = $this->HTML_DIR_TOP;
        }

        return $this->template($partFile, $params);
    }

    /**
     * footer -  часть
     */
    private function footerPart()
    {
        $footer = $this->curComponent['footer'];
        if (empty($footer)) {
            return '';
        }
        $partFile = $this->DIR_VIEW .'/'. $footer . '.php';
        $params = $this->paramList;   // параметры от контроллера
        if (!isset($params['dirTop'])) {
            $params['dirTop'] = $this->DIR_TOP;
        }
        if (!isset($params['htmlDirTop'])) {
            $params['htmlDirTop'] = $this->HTML_DIR_TOP;
        }
        return $this->template($partFile, $params);
    }

    /**
     * Правая панель
     */
    private function rightPanelPart() {

        if (!isset($this->curComponent['rightPanel'])) {
            return '';
        }
        $rightPanel = $this->curComponent['rightPanel'];
        if (empty($rightPanel)) {
            return '' ;
        }
        $partFile = $this->DIR_VIEW .'/'. $rightPanel . '.php';
        $params = $this->paramList;   // параметры от контроллера
        if (!isset($params['dirTop'])) {
            $params['dirTop'] = $this->DIR_TOP;
        }
        if (!isset($params['htmlDirTop'])) {
            $params['htmlDirTop'] = $this->HTML_DIR_TOP;
        }
        return $this->template($partFile, $params);
    }

}
