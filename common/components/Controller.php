<?php

/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
//class Controller extends RController
class Controller extends CController {

    /**
     * @var string the default layout for the controller view. Defaults to '//layouts/column1',
     * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
     */
    public $layout = '//layouts/column1';

    /**
     * @var array context menu items. This property will be assigned to {@link CMenu::items}.
     */
    public $menu = array();

    /**
     * @var array the breadcrumbs of the current page. The value of this property will
     * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
     * for more details on how to specify this property.
     */
    public $breadcrumbs = array();

//    public function init() {
////        User::model()->listUser(); //get cache all user
//    }
    
//    public function filterAccessControl($filterChain)
//    {   
//        //user()->loginRequired();
//    }
    
    public function isValidAccess($auth_id='', $action='') {
        if (isset(user()->isSuperUser) && user()->isSuperUser) {
            return true;
        } else {
            if (isset(user()->roles) && isset(user()->roles[$auth_id])) {
                $arrCrud = json_decode(user()->roles[$auth_id]->crud, true);
                if (isset($arrCrud[$action]) && $arrCrud[$action] == 1) {
                    return true;
                } else {
                    $this->accessDenied();
                }
            } else {
                $this->accessDenied();
            }
        }
//        return true;
    }

    public function accessDenied($message = null) {
        if ($message === null)
            $message = 'You are not authorized to perform this action.';
        if (!isset(user()->id))
            user()->loginRequired();
        else
            throw new CHttpException(403, $message);
    }

}