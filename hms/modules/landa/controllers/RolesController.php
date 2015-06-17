<?php

class RolesController extends Controller {

    public $breadcrumbs;
    public $layout = '//layouts/main';

    public function filters() {
        return array(
            'accessControl', // perform access control for CRUD operations
        );
    }

    public function accessRules() {
        return array(
            array('allow', // c
                'actions' => array('create'),
                'expression' => 'app()->controller->isValidAccess("GroupUser","c")',
                'expression' => 'app()->controller->isValidAccess("GroupGuest","c")',
            ),
            array('allow', // r
                'actions' => array('index', 'view'),
                'expression' => 'app()->controller->isValidAccess("GroupUser","r")',
                'expression' => 'app()->controller->isValidAccess("GroupGuest","r")',
            ),
            array('allow', // u
                'actions' => array('update'),
                'expression' => 'app()->controller->isValidAccess("GroupUser","u")',
                'expression' => 'app()->controller->isValidAccess("GroupGuest","u")',
            ),
            array('allow', // d
                'actions' => array('delete'),
                'expression' => 'app()->controller->isValidAccess("GroupUser","d")',
                'expression' => 'app()->controller->isValidAccess("GroupGuest","d")',
            )
        );
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id) {
        $type = $_GET['type'];
        $this->render('view', array(
            'model' => $this->loadModel($id),
            'type' => $type,
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {
        $model = new Roles;
        $siteConfig = SiteConfig::model()->findByPk(param('id'));
//        $siteConfig = SiteConfig::model()->listSiteConfig();
        $type = $_GET['type'];
        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Roles'])) {
            $model->attributes = $_POST['Roles'];
            if ($type == 'user') {
                $model->attributes = $_POST['Roles'];
            }
            if ($model->save()) {
                $this->saveRolesAuth($model->id);

                //clear session roles
                unset(Yii::app()->session['listRoles']);

                if ($type == 'guest') {
                    $guest = json_decode($siteConfig->roles_guest, true);
                    if (isset($guest)) {
                        array_push($guest, $model->id);
                    } else {
                        $guest = array($model->id);
                    }
                    $siteConfig->roles_guest = json_encode($guest);
                }
                $siteConfig->save();

                unset(Yii::app()->session['site']);
                $this->redirect(array('view', 'id' => $model->id, 'type' => $type));
            }
        }

        $this->render('create', array(
            'model' => $model,
            'type' => $type,
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id) {
        $model = $this->loadModel($id);
        $type = $_GET['type'];
        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Roles'])) {
            $model->attributes = $_POST['Roles'];
            if ($model->save()) {
                //delete roles auth
                RolesAuth::model()->deleteAll(array('condition' => 'roles_id=' . $model->id));
                $this->saveRolesAuth($model->id);

                //clear session roles
                unset(Yii::app()->session['listRoles']);
                $this->redirect(array('view', 'id' => $model->id, 'type' => $type));
            }
            unset(Yii::app()->session['site']);
        }

        $this->render('update', array(
            'model' => $model,
            'type' => $type,
        ));
    }

    public function saveRolesAuth($roles_id) {
        if (isset($_POST['auth_id'])) {
            foreach ($_POST['auth_id'] as $arrAuth) {
                $crud = array();
                if (isset($_POST[$arrAuth]['c']))
                    $crud['c'] = 1;
                if (isset($_POST[$arrAuth]['r']))
                    $crud['r'] = 1;
                if (isset($_POST[$arrAuth]['u']))
                    $crud['u'] = 1;
                if (isset($_POST[$arrAuth]['d']))
                    $crud['d'] = 1;

                if (count($crud) > 0) {
                    $mRolesAuth = new RolesAuth();
                    $mRolesAuth->roles_id = $roles_id;
                    $mRolesAuth->auth_id = $arrAuth;
                    $mRolesAuth->crud = json_encode($crud);
                    $mRolesAuth->save();
                }
            }
        }
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id, $type = 'user') {
        $siteConfig = SiteConfig::model()->listSiteConfig();


        if (Yii::app()->request->isPostRequest) {

            $cek = User::model()->find(array('condition' => 'roles_id=' . $id));
            $siteConfig = SiteConfig::model()->findByPk(1);

            //delete roles auth

            if (count($cek) > 0 and $type == 'guest') {
              
                
            } else {
                
                if ($type == 'guest') {
                    $guest = json_decode($siteConfig->roles_guest, true);
                    $guest = array_diff($guest, array($id));
                    $siteConfig->roles_guest = json_encode($guest);
                }

                $siteConfig->save();
                $this->loadModel($id)->delete();
                RolesAuth::model()->deleteAll(array('condition' => 'roles_id=' . $id));
            }
            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if (!isset($_GET['ajax']))
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
                
            unset(Yii::app()->session['site']);
        } else
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    }

    /**
     * Lists all models.
     */
    public function actionIndex() {
        $criteria = new CDbCriteria();

        $model = new Roles('search');
        $model->unsetAttributes();  // clear any default values

        if (isset($_GET['Roles'])) {
            $model->attributes = $_GET['Roles'];



            if (!empty($model->id))
                $criteria->addCondition('id = "' . $model->id . '"');


            if (!empty($model->name))
                $criteria->addCondition('name = "' . $model->name . '"');


            if (!empty($model->is_allow_login))
                $criteria->addCondition('is_allow_login = "' . $model->is_allow_login . '"');
        }

        $this->render('index', array(
            'model' => $model,
            'type' => 'index'
        ));
    }

    public function actionUser() {
        $session = new CHttpSession;
        $session->open();
        $criteria = new CDbCriteria();

        $model = new Roles('search');
        $model->unsetAttributes();  // clear any default values

        if (isset($_GET['Roles'])) {
            $model->attributes = $_GET['Roles'];



            if (!empty($model->id))
                $criteria->addCondition('id = "' . $model->id . '"');


            if (!empty($model->name))
                $criteria->addCondition('name = "' . $model->name . '"');


            if (!empty($model->is_allow_login))
                $criteria->addCondition('is_allow_login = "' . $model->is_allow_login . '"');
        }
        $session['Roles_records'] = Roles::model()->findAll($criteria);


        $this->render('index', array(
            'model' => $model,
            'type' => 'user'
        ));
    }

    public function actionGuest() {
        $session = new CHttpSession;
        $session->open();
        $criteria = new CDbCriteria();

        $model = new Roles('search');
        $model->unsetAttributes();  // clear any default values
//        $siteConfig = SiteConfig::model()->listSiteConfig();
//        $rolescust = json_decode($siteConfig->roles_customer, true);
//        $customer="";
        if (isset($_GET['Roles'])) {
            $model->attributes = $_GET['Roles'];
//            $customer = $_GET['Roles']['$rolescust'];


            if (!empty($model->id))
                $criteria->addCondition('id = "' . $model->id . '"');


            if (!empty($model->name))
                $criteria->addCondition('name = "' . $model->name . '"');


            if (!empty($model->is_allow_login))
                $criteria->addCondition('is_allow_login = "' . $model->is_allow_login . '"');
        }
        $session['Roles_records'] = Roles::model()->findAll($criteria);


        $this->render('index', array(
            'model' => $model,
            'type' => 'guest',
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new Roles('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Roles']))
            $model->attributes = $_GET['Roles'];

        $this->render('admin', array(
            'model' => $model,
//            'customer' => $customer,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id) {
        $model = Roles::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'roles-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
