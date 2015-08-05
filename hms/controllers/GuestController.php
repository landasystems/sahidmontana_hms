<?php

class GuestController extends Controller {

    

    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = 'main';

    public function filters() {
        return array(
            'accessControl', // perform access control for CRUD operations
        );
    }

    public function accessRules() {
        return array(
            array('allow', // c
                'actions' => array('create'),
                'expression' => 'app()->controller->isValidAccess("Guest","c")'
            ),
            array('allow', // r
                'actions' => array('index', 'view'),
                'expression' => 'app()->controller->isValidAccess("Guest","r")'
            ),
            array('allow', // u
                'actions' => array('update'),
                'expression' => 'app()->controller->isValidAccess("Guest","u")'
            ),
            array('allow', // d
                'actions' => array('delete'),
                'expression' => 'app()->controller->isValidAccess("Guest","d")'
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

    public function actionRemovephoto($id) {
        User::model()->updateByPk($id, array('avatar_img' => NULL));
    }

    public function actionCreate() {
        $model = new User;
        cs()->registerScript('tab', '$("#myTab a").click(function(e) {
                                        e.preventDefault();
                                        $(this).tab("show");
                                    })');

        $model->scenario = 'notAllow';

        if (isset($_POST['User'])) {
            $model->attributes = $_POST['User'];
            $model->birth = (!empty($_POST['User']['birth'])) ? date('Y/m/d', strtotime($_POST['User']['birth'])) : '';

            if ($model->save()) {
                $this->redirect(array('view', 'id' => $model->id));
            }
        }

        $this->render('create', array(
            'model' => $model,
        ));
    }

    public function actionUpdate($id) {
        $model = $this->loadModel($id);
        $model->scenario == 'notAllow';

        // $this->performAjaxValidation($model);
        cs()->registerScript('tab', '$("#myTab a").click(function(e) {
                                        e.preventDefault();
                                        $(this).tab("show");
                                    })
                                    ',0);

        if (isset($_POST['User'])) {
            $model->attributes = $_POST['User'];
            $model->birth = (!empty($_POST['User']['birth'])) ? date('Y/m/d', strtotime($_POST['User']['birth'])) : '';
            if ($model->save()) {
                $this->redirect(array('view', 'id' => $model->id));
            }
        }

        $this->render('update', array(
            'model' => $model,
        ));
    }

    
    public function actionDelete($id) {
        if (Yii::app()->request->isPostRequest) {
            // we only allow deletion via POST request
            $this->loadModel($id)->delete();

            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if (!isset($_GET['ajax']))
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
        } else
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    }

   

    public function actionOption() {
        if (isset($_POST['delete']) && isset($_POST['ceckbox'])) {
            foreach ($_POST['ceckbox'] as $data) {
                $this->loadModel($data)->delete();
            }
        }
        if (isset($_POST['disabled']) && isset($_POST['ceckbox'])) {
            foreach ($_POST['ceckbox'] as $data) {
                $model = $this->loadModel($data);
                $model->enabled = 0;
                $model->save();
            }
        }
        if (isset($_POST['enabled']) && isset($_POST['ceckbox'])) {
            foreach ($_POST['ceckbox'] as $data) {
                $model = $this->loadModel($data);
                $model->enabled = 1;
                $model->save();
            }
        }
        $this->redirect(array('user/index'));
    }

    public function actionIndex() {
        $model = new User('search');
        $model->unsetAttributes();  // clear any default values
        $roles = "";
        if (isset($_GET['User'])) {
            $model->attributes = $_GET['User'];
            $roles = (isset($_GET['User']['roles'])) ? $_GET['User']['roles'] : '';
        }



        $this->render('index', array(
            'model' => $model,
            'roles' => $roles,
        ));
    }

   
    public function loadModel($id) {
        $model = User::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'User-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }


}
