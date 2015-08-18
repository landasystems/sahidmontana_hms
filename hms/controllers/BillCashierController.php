<?php

class BillCashierController extends Controller {

    

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
                'expression' => 'app()->controller->isValidAccess("BillChasier","c")'
            ),
            array('allow', // r
                'actions' => array('index', 'view'),
                'expression' => 'app()->controller->isValidAccess("BillChasier","r")'
            ),
            array('allow', // r
                'actions' => array('index', 'approving'),
                'expression' => 'app()->controller->isValidAccess("BillChasierApproving","r")'
            ),
            array('allow', // u
                'actions' => array('update'),
                'expression' => 'app()->controller->isValidAccess("BillChasier","u")'
            ),
            array('allow', // d
                'actions' => array('delete'),
                'expression' => 'app()->controller->isValidAccess("BillChasier","d")'
            )
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

    public function actionViewApproving($id) {
        if (isset($_POST['approve'])) {
            BillCashier::model()->updateAll(array('approved_user_id' => Yii::app()->user->id), 'id=' . $id);
            $this->redirect(array('approving'));
        }
        if (isset($_POST['reject'])) {
            BillCashier::model()->updateAll(array('approved_user_id' => ''), 'id=' . $id);
            $this->redirect(array('approving'));
        }

        $this->render('viewApproval', array(
            'model' => $this->loadModel($id),
        ));
    }

    public function actionApproving() {
        
        $model = new BillCashier('search');
        $model->unsetAttributes();  // clear any default values

        if (isset($_GET['BillCashier'])) {
            $model->attributes = $_GET['BillCashier'];
        }


        $this->render('approval', array(
            'model' => $model,
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {
        $model = new BillCashier;
        if (isset($_POST['is_post'])) {
            $bills = Bill::model()->findAll(array('condition' => 'created_user_id=' . Yii::app()->user->id . ' and is_cashier=0'));
            $deposit = Deposite::model()->findAll(array('condition' => 'created_user_id=' . Yii::app()->user->id . ' and is_cashier=0'));
            $transaction = BillCharge::model()->findAll(array('condition' => 'created_user_id=' . Yii::app()->user->id . ' and is_cashier=0 AND is_temp=0'));

            if (!empty($bills) || !empty($deposit) || !empty($transaction)) {
                $model->save();
                foreach ($bills as $bill) {
                    $cashierDet = new BillCashierDet;
                    $cashierDet->bill_cashier_id = $model->id;
                    $cashierDet->bill_id = $bill->id;
                    $cashierDet->save();
                }

                foreach ($deposit as $dp) {
                    $cashierDet = new BillCashierDet;
                    $cashierDet->bill_cashier_id = $model->id;
                    $cashierDet->deposite_id = $dp->id;
                    $cashierDet->save();
                }

                foreach ($transaction as $t) {
                    $cashierDet = new BillCashierDet;
                    $cashierDet->bill_cashier_id = $model->id;
                    $cashierDet->bill_charge_id = $t->id;
                    $cashierDet->save();
                }

                Bill::model()->updateAll(array('is_cashier' => 1), 'created_user_id=' . Yii::app()->user->id);
                BillCharge::model()->updateAll(array('is_cashier' => 1), 'created_user_id=' . Yii::app()->user->id);
                Deposite::model()->updateAll(array('is_cashier' => 1), 'created_user_id=' . Yii::app()->user->id);
                
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

        if (isset($_POST['BillCashier'])) {
            $model->attributes = $_POST['BillCashier'];
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
        }
        else
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    }

    /**
     * Lists all models.
     */
    public function actionIndex() {

        $model = new BillCashier('search');
        $model->unsetAttributes();  // clear any default values

        if (isset($_GET['BillCashier'])) {
            $model->attributes = $_GET['BillCashier'];
        }

        $this->render('index', array(
            'model' => $model,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new BillCashier('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['BillCashier']))
            $model->attributes = $_GET['BillCashier'];

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
        $model = BillCashier::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'bill-cashier-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    

    public function actionGenerateExcelCashierSheet() {
        $a =  str_replace(".html", "", $_GET['bill_cashier_id']);

        Yii::app()->request->sendFile('CahierSheet-' . date('dmY') . '.xls', $this->renderPartial('_excelReportCashierSheet', array(
                    'id' => $a,
                        ), true)
        );
    }

    

}
