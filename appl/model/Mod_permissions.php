<?php
/**
 * Разрешение на действия
 */

class Mod_permissions extends Mod_base{
    protected $msg ;                     // объект для вывода сообщений
    protected $db = false ;              // объект класса для связи с БД
    protected $dbClass = 'Db_permissions' ;  //  имя класса для работы с БД
    protected $parameters = [];          // параметры, принимаемые от контроллера
    //--------------------------//

    /**
     *  разрешение на конкретное действие
     */
    public function isPermission($doingName) {
        $permissions = $this->getPermissions() ;
        return (isset($permissions[$doingName])) ;
    }
    /**
     * все разрешенные действия
     */
    public function getPermissions($ownerFlag=false) {
        $permiss = [] ;
        $addPermiss = [] ;
        $objName = TaskStore::getParam('currentObj') ;
        $userRole = TaskStore::getParam('userRole') ;
        $totalRang = $this->db->getTotalRang($objName,$userRole) ;
        // возможна дополнительная роль собственника объекта
        if ($ownerFlag) {
            $addRole = TaskStore::ROLE_OWNER ;
            $addTotalRang = $this->db->getTotalRang($objName, $addRole);
        }
        if (!empty($addTotalRang) ) {
            $addPermiss = $this->totalRangParse($addTotalRang) ;
        }
        $permiss = $this->totalRangParse($totalRang) ;

        return array_merge($permiss,$addPermiss) ;

    }

    /**
     * разложить  $totalRang по степеням 10
     */
    private function totalRangParse($totalRang) {
        $doings = $this->db->getDoings() ;

        $doingPermissions = [] ;
        foreach ($doings as $doing) {
            $name = $doing['doingname'] ;
            $rang = $doing['rang'] ;
            if ($totalRang == $rang   ||
                ($totalRang >= $rang  && $totalRang % ($rang * 10) == $rang) ) {
                $doingPermissions[] = $name ;
                $totalRang -= $rang ;
            }
        }
        return $doingPermissions ;
    }


}