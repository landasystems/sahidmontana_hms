<?php

class ChargeAdditionalCategoryController extends Controller {

    

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
          
            array('allow', // r
                'actions' => array('delete','update','create','index', 'view'),
                'expression' => 'app()->controller->isValidAccess("ChargeAdditionalCategory","r")'
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


    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {
        $model = new ChargeAdditionalCategory;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['ChargeAdditionalCategory'])) {

            if ($_POST['ChargeAdditionalCategory']['parent_id']) {
                $child = new ChargeAdditionalCategory;
                $child->attributes = $_POST['ChargeAdditionalCategory'];

                $root = $model->findByPk($_POST['ChargeAdditionalCategory']['parent_id']);
                if ($child->appendTo($root))
                    $this->redirect(array('view', 'id' => $child->id));
            }else {
                $model->attributes = $_POST['ChargeAdditionalCategory'];
                if ($model->saveNode()){
                    user()->setFlash('success',"Saved successfully");
                    $this->redirect(array('view', 'id' => $model->id));
                }
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

        if (isset($_POST['ChargeAdditionalCategory'])) {
            if ($_POST['ChargeAdditionalCategory']['parent_id']) {
                $model->attributes = $_POST['ChargeAdditionalCategory'];
                if ($model->saveNode()) {
                    $root = $model->findByPk($_POST['ChargeAdditionalCategory']['parent_id']);
                    $model->moveAsFirst($root);
                    $this->redirect(array('view', 'id' => $model->id));
                }
            } else {
                $model->attributes = $_POST['ChargeAdditionalCategory'];
                $model->saveNode();
                if (!($model->isRoot()))
                    $model->moveAsRoot();
                
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

            $model = $this->loadModel($id);

            //delete download where have child
            $descendants = $model->children()->findAll();
            $sWhere[] = $id;
            foreach ($descendants as $o) {
                $sWhere[] = $o->id;
            }
           // Article::model()->deleteAll(array('condition'=>'article_category_id'.$sWhere));
           // landa()->deleteDir('images/' . $model->path);
           ChargeAdditional::model()->deleteAll(array('condition'=>'charge_additional_category_id IN (' . implode(',', $sWhere) . ')'));
//            cmd('DELETE FROM acca_article WHERE )->query();

            // we only allow deletion via POST request
            $model->deleteNode();

            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if (!isset($_GET['ajax']))
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
        }
        else
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    }

    /**
     * Lists all models.
     */
    public function actionIndex() {

        $model = new ChargeAdditionalCategory('search');
        $model->unsetAttributes();  // clear any default values

        if (isset($_GET['ChargeAdditionalCategory'])) {
            $model->attributes = $_GET['ChargeAdditionalCategory'];
        }

        $this->render('index', array(
            'model' => $model,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new ChargeAdditionalCategory('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['ChargeAdditionalCategory']))
            $model->attributes = $_GET['ChargeAdditionalCategory'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    public function actionSettingTransaction() {
        $model = new ChargeAdditionalCategory;
        $this->render('settingTransaction', array(
            'model' => $model,
        ));
    }
    
    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id) {
        $model = ChargeAdditionalCategory::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'charge-additional-category-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    

}
