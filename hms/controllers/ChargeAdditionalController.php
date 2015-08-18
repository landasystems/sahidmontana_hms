<?php

class ChargeAdditionalController extends Controller {

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
                'expression' => 'app()->controller->isValidAccess("ChargeAdditional","c")'
            ),
            array('allow', // r
                'actions' => array('index', 'view'),
                'expression' => 'app()->controller->isValidAccess("ChargeAdditional","r")'
            ),
            array('allow', // u
                'actions' => array('update'),
                'expression' => 'app()->controller->isValidAccess("ChargeAdditional","u")'
            ),
            array('allow', // d
                'actions' => array('delete'),
                'expression' => 'app()->controller->isValidAccess("ChargeAdditional","d")'
            )
        );
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
//    public function actionView($id) {
//        $this->render('view', array(
//            'model' => $this->loadModel($id),
//        ));
//    }

    public function actionGenerateExcel() {
        $model = new ChargeAdditional;
        $data = $model->search(true);
        Yii::app()->request->sendFile(date('YmdHi') . 'AdditionalCharge.xls', $this->renderPartial('excelReport', array(
                    'model' => $data,
                        ), true));
    }

    public function actionView($id) {
        cs()->registerScript('read', '
            $("form input, form textarea, form select").each(function(){
                $(this).prop("disabled", true);
            });');
        $_GET['v'] = true;
        $this->actionUpdate($id);
    }

    public function actionGetChargeAdditional() {
        $name = $_GET["q"];
        $list = array();
        $data = ChargeAdditional::model()->findAll(array('condition' => 'is_publish=1 and name like "%' . $name . '%"'));
        if (empty($data)) {
            $list[] = array("id" => "0", "text" => "No Results Found..");
        } else {
            foreach ($data as $val) {
                $sCat = (isset($val->ChargeAdditionalCategory->code)) ? $val->ChargeAdditionalCategory->code : "";
                $list[] = array("id" => $val->id, "text" => $sCat . ' - ' . $val->name);
            }
        }
        echo json_encode($list);
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {
        $model = new ChargeAdditional;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['ChargeAdditional'])) {
            $model->attributes = $_POST['ChargeAdditional'];
            // $model->acc_coa_id = $_POST['accacoa'];
            if ($model->save()){
                user()->setFlash('success',"Saved successfully");
                $this->redirect(array('view', 'id' => $model->id));
            }
        }

        $this->render('create', array(
            'model' => $model,
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id) {
        $model = $this->loadModel($id);

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['ChargeAdditional'])) {
            $model->attributes = $_POST['ChargeAdditional'];
            if ($model->save()){
                user()->setFlash('success',"Saved successfully");
                $this->redirect(array('view', 'id' => $model->id));
            }
        }

        $this->render('update', array(
            'model' => $model,
        ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
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

    /**
     * Lists all models.
     */
    public function actionIndex() {
        if (isset($_POST['delete']) && isset($_POST['ceckbox'])) {
            foreach ($_POST['ceckbox'] as $data) {
                $this->loadModel($data)->delete();
            }
        }
        if (isset($_POST['buttonunpublish']) && isset($_POST['ceckbox'])) {
            foreach ($_POST['ceckbox'] as $data) {
                $model = $this->loadModel($data);
                $model->is_publish = 0;
                $model->save();
            }
        }
        if (isset($_POST['buttonpublish']) && isset($_POST['ceckbox'])) {
            foreach ($_POST['ceckbox'] as $data) {
                $model = $this->loadModel($data);
                $model->is_publish = 1;
                $model->save();
            }
        }

        $model = new ChargeAdditional('search');
        $model->unsetAttributes();  // clear any default values

        if (isset($_GET['ChargeAdditional'])) {
            $model->attributes = $_GET['ChargeAdditional'];
        }

        $this->render('index', array(
            'model' => $model,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new ChargeAdditional('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['ChargeAdditional']))
            $model->attributes = $_GET['ChargeAdditional'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id) {
        $model = ChargeAdditional::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'charge-additional-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
