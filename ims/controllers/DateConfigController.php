<?php

class DateConfigController extends Controller {

    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $breadcrumbs;
    public $layout = 'main';

    /**
     * @return array action filters
     */
    public function filters() {
        return array(
            'accessControl', // perform access control for CRUD operations
            'postOnly + delete', // we only allow deletion via POST request
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules() {
        return array(
            array('allow', // c
                'actions' => array('create'),
                'expression' => 'app()->controller->isValidAccess("DateConfig","c")'
            ),
            array('allow', // r
                'actions' => array('index', 'view'),
                'expression' => 'app()->controller->isValidAccess("DateConfig","r")'
            ),
            array('allow', // u
                'actions' => array('update'),
                'expression' => 'app()->controller->isValidAccess("DateConfig","u")'
            ),
            array('allow', // d
                'actions' => array('delete'),
                'expression' => 'app()->controller->isValidAccess("DateConfig","d")'
            ),
        );
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id) {
        $this->render('view', array(
            'model' => $this->loadModel($id),
        ));
    }

//        public function actionSearch(){
//            $criteria = new CDbCriteria();
//            $criteria->compare ('a');
//        }
    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {
        $model = new DateConfig;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['DateConfig'])) {
            $model->attributes = $_POST['DateConfig'];
            if ($model->save())
                $this->redirect(array('view', 'id' => $model->id));
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

        if (isset($_POST['DateConfig'])) {
            $model->attributes = $_POST['DateConfig'];
            if ($model->save())
                $this->redirect(array('view', 'id' => $model->id));
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
        $this->loadModel($id)->delete();

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
    }

    /**
     * Lists all models.
     */
    public function actionIndex() {
        $criteria = new CDbCriteria();
        $model = new DateConfig('search');
        $model->unsetAttributes();

        if (isset($_GET['DateConfig'])) {
            $model->attributes = $_GET['DateConfig'];

            if (!empty($model->id))
                $criteria->addCondition('id = "' . $model->id . '"');

            if (!empty($model->year))
                $criteria->addCondition('code = "' . $model->year . '"');

            if (!empty($model->cash_in))
                $criteria->addCondition('code = "' . $model->cash_in . '"');

            if (!empty($model->cash_out))
                $criteria->addCondition('code = "' . $model->cash_out . '"');

            if (!empty($model->bk_in))
                $criteria->addCondition('code = "' . $model->bk_in . '"');

            if (!empty($model->bk_out))
                $criteria->addCondition('code = "' . $model->bk_out . '"');

            if (!empty($model->jurnal))
                $criteria->addCondition('code = "' . $model->jurnal . '"');

            if (!empty($model->cash_in_jkt))
                $criteria->addCondition('code = "' . $model->cash_in_jkt . '"');

            if (!empty($model->cash_out_jkt))
                $criteria->addCondition('code = "' . $model->cash_out_jkt . '"');
        }
        $this->render('index', array(
            'model' => $model,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new DateConfig('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['DateConfig']))
            $model->attributes = $_GET['DateConfig'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return DateConfig the loaded model
     * @throws CHttpException
     */
    public function loadModel($id) {
        $model = DateConfig::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param DateConfig $model the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'date-config-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
