<?php

class SiteConfigController extends Controller {

    public $breadcrumbs;

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
                'actions' => array('index', 'create'),
                'expression' => 'app()->controller->isValidAccess(1,"c")'
            ),
            array('allow', // r
                'actions' => array('index', 'view'),
                'expression' => 'app()->controller->isValidAccess(1,"r")'
            ),
            array('allow', // u
                'actions' => array('index', 'update'),
                'expression' => 'app()->controller->isValidAccess(1,"u")'
            ),
            array('allow', // d
                'actions' => array('index', 'delete'),
                'expression' => 'app()->controller->isValidAccess(1,"d")'
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

    public function actionAddRow() {
        $model = ChargeAdditional::model()->findByPk((int) $_POST['additional_id']);
        if (count($model) > 0) {
            echo '                                                  
                    <tr class="items">
                        <input type="hidden" name="others_include[]" id="' . $model->id . '" value="' . $model->id . '"/>                                                                                                    
                        <td style="text-align:center"><button class="delRow"><i class="icon-remove-circle" style="cursor:all-scroll;"></i></button></td>
                        <td> &nbsp;&nbsp;&raquo; ' . $model->name . '</td>                        
                        <td style="text-align:right">' . landa()->rp($model->charge) . '</td>                                                        
                    </tr>                     
                    ';
        }
        echo '<tr id="addRow" style="display:none"></tr>';
    }

    public function actionUpdate($id) {
        $model = $this->loadModel($id);

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        cs()->registerScript('tab', '$("#myTab a").click(function(e) {
                                        e.preventDefault();
                                        $(this).tab("show");
                                    })');

        if (isset($_POST['SiteConfig'])) {
            $model->attributes = $_POST['SiteConfig'];
            $model->others_include = "";
            $model->acc_cash_id = $_POST['SiteConfig']['acc_cash_id'];
            $model->acc_city_ledger_id = $_POST['SiteConfig']['acc_city_ledger_id'];
            $model->acc_service_charge_id = $_POST['SiteConfig']['acc_service_charge_id'];
            $model->acc_tax_id = $_POST['SiteConfig']['acc_tax_id'];
            $model->acc_clearance_id = $_POST['SiteConfig']['acc_clearance_id'];

            if (isset($_POST['others_include'])) {
                $model->others_include = json_encode($_POST['others_include']);
            }


            if (!empty($_POST['SiteConfig']['roles_guest']))
                $model->roles_guest = json_encode($_POST['SiteConfig']['roles_guest']);

            $file = CUploadedFile::getInstance($model, 'client_logo');
            if (is_object($file)) {
                $model->client_logo = Yii::app()->landa->urlParsing($model->client_name) . '.' . $file->extensionName;
            } else {
                unset($model->client_logo);
            }

            $settings = array();

            $settings['fb_charge'] = $_POST['fnb'];
            $settings['extrabed_charge'] = $_POST['extrabed'];
            $settings['fb_account'] = $_POST['breakfastAccount'];
            $settings['room_account'] = $_POST['roomAccount'];
            $settings['rate'] = $_POST['rate'];
//            $settings['tax'] = $_POST['tax'];

            $model->settings = json_encode($settings);

            if ($model->save()) {
                if (is_object($file)) {
                    $file->saveAs('images/site/' . $model->client_logo);
                    app()->landa->createImg('site/', $model->client_logo, $model->id, false);
                }
                //clear session site
                unset(Yii::app()->session['site']);

                $this->redirect(array('view', 'id' => $model->id));
            }
        }
        if (!empty($model->roles_guest))
            $model->roles_guest = json_decode($model->roles_guest);
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
        $criteria = new CDbCriteria();

        $model = new SiteConfig('search');
        $model->unsetAttributes();  // clear any default values

        if (isset($_GET['SiteConfig'])) {
            $model->attributes = $_GET['SiteConfig'];



            if (!empty($model->id))
                $criteria->addCondition('id = "' . $model->id . '"');


            if (!empty($model->client_name))
                $criteria->addCondition('client_name = "' . $model->client_name . '"');


            if (!empty($model->client_logo))
                $criteria->addCondition('client_logo = "' . $model->client_logo . '"');


            if (!empty($model->city_id))
                $criteria->addCondition('city_id = "' . $model->city_id . '"');


            if (!empty($model->address))
                $criteria->addCondition('address = "' . $model->address . '"');


            if (!empty($model->phone))
                $criteria->addCondition('phone = "' . $model->phone . '"');


            if (!empty($model->email))
                $criteria->addCondition('email = "' . $model->email . '"');
        }



        $this->render('index', array(
            'model' => $model,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new SiteConfig('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['SiteConfig']))
            $model->attributes = $_GET['SiteConfig'];

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
        $model = SiteConfig::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'site-config-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
