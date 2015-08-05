<?php

class AccountController extends Controller {

    

    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = 'main';

    /**
     * @return array action filters
     */
    public function filters() {
        return array(
            'accessControl', // perform access control for CRUD operations
        );
    }

    public function accessRules() {
        return array(
            array('allow', // c
                'actions' => array('create'),
                'expression' => 'app()->controller->isValidAccess("Account","c")'
            ),
            array('allow', // r
                'actions' => array('index', 'view'),
                'expression' => 'app()->controller->isValidAccess("Account","r")'
            ),
            array('allow', // u
                'actions' => array('update'),
                'expression' => 'app()->controller->isValidAccess("Account","u")'
            ),
            array('allow', // d
                'actions' => array('delete'),
                'expression' => 'app()->controller->isValidAccess("Account","d")'
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

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {
        $model = new Account;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Account'])) {
            $model->attributes = $_POST['Account'];
            if ($model->save()) {
                $siteConfig = SiteConfig::model()->findByPk(1);

                $newForecast = array();
                $foreCast = Forecast::model()->findAll(array('condition' => 'tahun>=' . date("Y", strtotime($siteConfig->date_system))));
                foreach ($foreCast as $valForecast) {
                    $forDsr = json_decode($valForecast->forecast, TRUE);
                    $newForecast = $forDsr;
                    $monthFor = array();
                    for ($i = 1; $i <= 12; $i++) {
                        $monthFor[$i] = 0;
                    }
                    $newForecast[$model->id] = $monthFor;
                    $forecastBaru = Forecast::model()->findByPk($valForecast->id);
                    $forecastBaru->forecast = json_encode($newForecast);
                    $forecastBaru->save();
                }

                $newDsr = array();
                $initialForecast = InitialForecast::model()->findByPk(1);
                $dsr = json_decode($initialForecast->dsr, TRUE);
                $newDsr = $dsr;
                $newDsr[$model->id]['monthToDate'] = 0;
                $newDsr[$model->id]['yearToDate'] = 0;

                for ($i = 1; $i <= 31; $i++) {
                    $lastmonth[$i] = 0;
                }
                $newDsr[$model->id]['lastMonth'] = $lastmonth;
                $initialForecast->dsr = json_encode($newDsr);
                $initialForecast->save();

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

        if (isset($_POST['Account'])) {
            $model->attributes = $_POST['Account'];
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
        $model = new Account('search');
        $model->unsetAttributes();  // clear any default values

        if (isset($_GET['Account'])) {
            $model->attributes = $_GET['Account'];
        }



        $this->render('index', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id) {
        $model = Account::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'account-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    public function actionBck() {
        //belum additional package
        //belum na
        $db = 'landa_hms_sahidmontana';
        $prefix = 'acca_';

        $modelBck = cmd('SELECT * FROM ' . $db . '.acca_na WHERE id NOT IN (SELECT id FROM acca_na) ORDER BY date_na')->query();
        if (isset($_POST['p'])) {
            $id = $_POST['id'];
            $pc = $_POST['p'];

            $master = array(
                'charge_additional',
                'charge_additional_category',
                'account',
                'forecast',
                'market_segment',
                'roles',
                'roles_auth',
                'room',
                'room_type',
                'site_config',
                'user',
            );

            $trans = array(
                'bill',
                'bill_cashier',
                'bill_cashier_det',
                'bill_charge',
                'bill_charge_det',
                'bill_det',
                'deposite',
                'na',
                'na_beverage_analys',
                'na_det',
                'na_dp_applied',
                'na_dp_not_applied',
                'na_dsr',
                'na_expected_arrival',
                'na_expected_departure',
                'na_food_analys',
                'na_gl',
                'na_product_sold',
                'na_statistic',
                'registration',
                'registration_detail',
                'reservation',
                'reservation_detail',
                'room_bill',
                'room_schedule',
            );

            /*
             * bill
             * bill_charge
             * bill_charge_det
             * registration
             * registration_detail
             * room_bill
             */

            foreach ($master as $val) {
                $tb = $prefix . $val;
                $tbFrom = $db . '.' . $prefix . $val;
                cmd('DELETE FROM ' . $tb)->execute();
                cmd('INSERT INTO ' . $tb . '(SELECT * FROM ' . $tbFrom . ')')->execute();
            }
            foreach ($trans as $val) {
                $tb = $prefix . $val;
                $tbFrom = $db . '.' . $prefix . $val;
//                cmd('DELETE FROM ' . $tb . '')->execute();
                cmd('INSERT INTO ' . $tb . ' (SELECT * FROM ' . $tbFrom . ' WHERE id NOT IN (SELECT id FROM ' . $tb . '))')->execute();
            }

            foreach ($id as $key => $na_id) {
                $p = $pc[$key] / 100;
                //-----------------------------------------------------------------------------
                $model = Bill::model()->findAll(array('condition' => 'na_id=' . $na_id));
                $bill = array();
                foreach ($model as $val) {
                    $bill[] = $val->id;
                }

                $sWhere = (empty($bill)) ? '' : ' WHERE id IN (' . implode(',', $bill) . ')';
                cmd('UPDATE acca_bill SET cash=cash*' . $p . ',cc_charge=cc_charge*' . $p . ',ca_charge=ca_charge*' . $p . ',refund=refund*' . $p . ',total=total*' . $p . $sWhere)->execute();

                $sWhere = (empty($bill)) ? '' : ' WHERE bill_id IN (' . implode(',', $bill) . ')';
                cmd('UPDATE acca_bill_det SET deposite_amount=deposite_amount*' . $p . $sWhere)->execute();

                $sWhere = (empty($bill)) ? '' : ' WHERE id IN (SELECT room_bill_id from acca_bill_det WHERE bill_id IN (' . implode(',', $bill) . '))';
                cmd('UPDATE acca_room_bill SET charge=charge*' . $p . ',extrabed_price=extrabed_price*' . $p . ',room_price=room_price*' . $p . ',fnb_price=fnb_price*' . $p . $sWhere)->execute();
//
                $sWhere = (empty($bill)) ? '' : ' WHERE gl_room_bill_id IN (' . implode(',', $bill) . ')';
                cmd('UPDATE acca_bill_charge SET cash=cash*' . $p . ',cc_charge=cc_charge*' . $p . ',ca_charge=ca_charge*' . $p . ',refund=refund*' . $p . ',total=total*' . $p . $sWhere)->execute();
//
                $sWhere = (empty($bill)) ? '' : ' WHERE bill_charge_id IN (SELECT id FROM acca_bill_charge WHERE gl_room_bill_id IN (' . implode(',', $bill) . '))';
                cmd('UPDATE acca_bill_charge_det SET charge=charge*' . $p . $sWhere)->execute();
            }
            $this->redirect(array('viewBck', array()));
        }

        $this->render('bck', array(
            'na' => $modelBck,
        ));
    }

    public
            function actionViewBck() {
        $this->render('viewBck', array(
        ));
    }

}
