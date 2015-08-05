<?php

class GuestGroupController extends Controller {

    
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
            ),
            array('allow', // r
                'actions' => array('index', 'view'),
            ),
            array('allow', // u
                'actions' => array('update'),
            ),
            array('allow', // d
                'actions' => array('delete'),
            )
        );
    }

    public function actionView($id) {
        cs()->registerScript('read', '
            $("form input, form textarea, form select").each(function(){
                $(this).prop("disabled", true);
            });');
        $_GET['v'] = true;
        $this->render('update', array(
            'model' => $this->loadModel($id),
        ));
    }

    public function actionCreate() {
        $model = new Roles;
        $siteConfig = SiteConfig::model()->findByPk(1);

        if (isset($_POST['Roles'])) {
            $model->attributes = $_POST['Roles'];
            $model->is_allow_login = 0;
            if ($model->save()) {
                $this->saveRolesAuth($model->id);
                $this->redirect(array('view', 'id' => $model->id));
            }
        }

        $this->render('create', array(
            'model' => $model,
        ));
    }

    public function actionUpdate($id) {
        $model = $this->loadModel($id);

        if (isset($_POST['Roles'])) {
            $model->attributes = $_POST['Roles'];
            $model->is_allow_login = 0;
            if ($model->save()) {
                //delete roles auth
                RolesAuth::model()->deleteAll(array('condition' => 'roles_id=' . $model->id));
                $this->saveRolesAuth($model->id);

                $this->redirect(array('view', 'id' => $model->id));
            }
        }

        $this->render('update', array(
            'model' => $model,
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

    public function actionDelete($id) {

        if (Yii::app()->request->isPostRequest) {
            $this->loadModel($id)->delete();
            RolesAuth::model()->deleteAll(array('condition' => 'roles_id=' . $id));

            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if (!isset($_GET['ajax']))
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));

        } else
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    }

    /**
     * Lists all models.
     */
    public function actionIndex() {
        $model = new Roles('search');
        $model->unsetAttributes();
        $model->is_allow_login = 0;

        if (isset($_GET['Roles'])) {
            $model->attributes = $_GET['Roles'];
        }

        $this->render('index', array(
            'model' => $model,
        ));
    }

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
