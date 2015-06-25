<?php
/**
 * Хранение  параметров задачи
 * Date: 22.05.15
 */
class TaskStore {
    public static $dirTop = false ;             // корневой директорий
    public static $dirController = false ;      // контроллеры
    public static $dirModel = false ;           // модели
    public static $dirView = false ;            // представления
    public static $dirLayout = false ;          // шаблоны
    public static $htmlDirTop = false ;         // относительный адрес для html
    public static $dirService = false ;         // сервисные функции
    public static $dirArticleHeap = false ;     // расположение текстов статей
    //-----------------------------------//

    //-- параметры состояния --//
    private static $sessionId = false;          // ид текущей сессии
    private static $passwSave = false ;          // сохранение пароля (  cookies )
    private static $userLogin = false ;
    private static $userPassword = false ;
    private static $userName = false ;
    private  static $enterSuccessful = false ;  // успешный вход
    private static $userStatus = false ;        // определяет возможности пользователя
    private static $topicId = false ;           // текущая тема
    private static $topicName = false ;
    private static $userRole = false ;
    private static $addRole = false ;          // дополнительная роль (например, owner
    private static $currentObj = false ;
    //-----память контроллеров ---//
    private static $cnt_userStore = [];
    private static $cnt_profileStore = [];
    private static $cnt_topicStore = [];
    private static $cnt_articleStore = [];
    private static $cnt_navigatorStore = [];
    //-------Список сохраняемых параметров-------//
    private static $storedParams = [
        'sessionId',          // ид текущей сессии
        'passwSave',          // сохранение пароля (  cookies )
        'userName',           // Имя пользователя
        'userLogin',          // login
        'userPassword',       // пароль
        'userStatus',         // статус пользователя (определяет доступные операции)
        'topicId',            // id текущей темы
        'topicName',          //  имя темы
        'userRole',         // роль пользователя
        'addRole',          // дополнительная роль
        'currentObj',       // текущий объект обработки
        'enterSuccessful',    // успешный вход
        'cnt_userStore',      // память контроллера user
        'cnt_profileStore',   // память контроллера profile
        'cnt_topicStore',     // память контроллера topic
        'cnt_articleStore',   // память контроллера article
        'cnt_navigatorStore'  // память контроллера navigator
        ];

    //------ константы ------------//

    const LINE_FEED = '<br>';
    const LINE_END = '"\n"';
    const ADMIN_LOGIN = 'ADMIN';
    /** статус определяет функциональные возможности */
    const  USER_STAT_ADMIN = 99;  // создание новых разделов, групповое добавление картинок
    const  USER_STAT_USER = 10;        // добавление картинок по одной
    const  USER_STAT_GUEST = 5;      // только просмотр

    const  TOPIC_STAT_SHOW = 1;    // только просмотр
    const  TOPIC_STAT_EDIT = 2;    // редактирование

    const  STAT_SHOW_NAME = 'только просмотр';
    const  STAT_EDIT_NAME = 'редактирование';

    const  ARTICLE_STAT_SHOW = 1;    // только просмотр
    const  ARTICLE_STAT_EDIT = 2;    // редактирование

    const PROFILE_STAT_REGISTRATION = 1 ;    // переход в профиль - первичная регистрация
    const PROFILE_STAT_EDIT = 2 ;            // редактирования существующего профиля
    // -- роли  --------------
    const ROLE_ADMIN = 'admin' ;
    const ROLE_USER = 'user' ;
    const ROLE_GUEST = 'guest' ;
    const ROLE_OWNER = 'owner' ;
    // -- действия  --------------
    const DO_READ = 'read' ;
    const DO_CREATE = 'create' ;
    const DO_EDIT = 'edit' ;
    const DO_DEL = 'delete' ;
    // -- объекты  --------------
    const OBJ_TOPIC = 'topic' ;
    const OBJ_ARTICLE = 'article' ;
    const OBJ_COMMENT = 'comment' ;

    //-- сессия  ---------
    const SESSION_TIME = 1200 ;  // сек
    const COOKIES_TIME = 1728000 ;
    const COOKIES_WORD = 'обучение в школе php' ;
    public static function init($dirTop, $htmlDirTop) {
        self::$dirTop = $dirTop;
        self::$htmlDirTop = $htmlDirTop;

        self::$dirController = self::$dirTop . '/appl/controller';
        self::$dirModel = self::$dirTop . '/appl/model';
        self::$dirView = self::$dirTop . '/appl/view';
        self::$dirLayout = self::$dirView .'/layouts' ;
        self::$dirService = self::$dirTop . '/appl/service';
        self::$dirArticleHeap = self::$dirTop . '/articleHeap';
        // восстановить параметры //
        $params = self::$storedParams ;
        foreach($params as $parName) {
            self::$$parName = self::restoreParam($parName) ;
        }
        if (empty(self::$userLogin)) {
            self::userClear() ;
        }
    }
    public static function userClear() {
        self::$userLogin = 'guest' ;
        self::$userName = 'Гость' ;
        self::$enterSuccessful = false ;
        self::$userStatus = self::USER_STAT_GUEST ;
        self::$userRole = self::ROLE_GUEST ;
    }
    /**
     * Сохранить параметр
     * @param $paramName
     * @param $paramMean
     */
    private static function storeParam($paramName,$paramMean) {
        $_SESSION[$paramName] = $paramMean ;
    }

    /**
     * Восстановить параметр
     * @param $paramName
     * @return bool
     */
    private static function restoreParam($paramName) {
        if (isset($_SESSION[$paramName])) {
            return $_SESSION[$paramName] ;
        } else {
            return false ;
        }
    }
    /**
     * @return array -  список директорий для поиска классов по __autoload
     */
    public static function getClassDirs()
    {
        return [self::$dirController,
            self::$dirModel,
            self::$dirService];
    }

    /**
     * получить параметр
     * @param $paramName
     * @return если (парамметрЕсть) ? ЗначениеПараметра : null
     */
    public static function getParam($paramName) {
        if (isset(self::$$paramName)) {
            return self::$$paramName ;
        }else {    // error:
            return null ;
        }
    }

    /**
     * Установить значение пераметра
     * @param $paramName
     * @param $paramMean
     * @return bool
     */
    public static function setParam($paramName,$paramMean) {
        if (isset(self::$$paramName)) {
            self::$$paramName = $paramMean ;
            self::storeParam($paramName,$paramMean) ;
            return true ;
        }else { // error:
            return false ;
        }
    }

}