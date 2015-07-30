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

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
//    public function actionCreate() {
//        $model = new SiteConfig;
//
//        // Uncomment the following line if AJAX validation is needed
//        // $this->performAjaxValidation($model);
//
//        if (isset($_POST['SiteConfig'])) {
//            $model->attributes = $_POST['SiteConfig'];
//            
//            if (!empty($model->client_logo)) {
//                $file = CUploadedFile::getInstance($model, 'client_logo');
//                $oriFileName = Yii::app()->landa->urlParsing($model->client_name) . '.' . $file->extensionName;
//                $model->client_logo = $oriFileName;
//                $file->saveAs('images/site/' . $oriFileName);
//                Yii::app()->landa->createImg('site/', $oriFileName, $model->id);
//            }
//
//            if ($model->save())
//                $this->redirect(array('view', 'id' => $model->id));
//        }
//
//        $this->render('create', array(
//            'model' => $model,
//        ));
//    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
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
            if (!empty($_POST['SiteConfig']['roles_customer']))
                $model->roles_customer = json_encode($_POST['SiteConfig']['roles_customer']);
            if (!empty($_POST['SiteConfig']['roles_supplier']))
                $model->roles_supplier = json_encode($_POST['SiteConfig']['roles_supplier']);
            if (!empty($_POST['SiteConfig']['roles_employment']))
                $model->roles_employment = json_encode($_POST['SiteConfig']['roles_employment']);

            $file = CUploadedFile::getInstance($model, 'client_logo');
            if (is_object($file)) {
                $model->client_logo = Yii::app()->landa->urlParsing($model->client_name) . '.' . $file->extensionName;
            } else {
                unset($model->client_logo);
            }


            if ($model->save()) {
                if (is_object($file)) {
                    $file->saveAs('images/site/' . $model->client_logo);
                    app()->landa->createImg('site/', $model->client_logo, $model->id);
                }

                //clear session site
                unset(Yii::app()->session['site']);


                //$this->redirect(array('view', 'id' => $model->id));
            }
        }
        if (!empty($model->roles_customer))
            $model->roles_customer = json_decode($model->roles_customer);
        if (!empty($model->roles_supplier))
            $model->roles_supplier = json_decode($model->roles_supplier);
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
        $session = new CHttpSession;
        $session->open();
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
        $session['SiteConfig_records'] = SiteConfig::model()->findAll($criteria);


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

    public function actionGenerateExcel() {
        $session = new CHttpSession;
        $session->open();

        if (isset($session['SiteConfig_records'])) {
            $model = $session['SiteConfig_records'];
        } else
            $model = SiteConfig::model()->findAll();


        Yii::app()->request->sendFile(date('YmdHis') . '.xls', $this->renderPartial('excelReport', array(
                    'model' => $model
                        ), true)
        );
    }

    public function actionGeneratePdf() {

        $session = new CHttpSession;
        $session->open();
        Yii::import('application.modules.admin.extensions.giiplus.bootstrap.*');
        require_once(Yii::getPathOfAlias('common') . '/extensions/tcpdf/tcpdf.php');
        require_once(Yii::getPathOfAlias('common') . '/extensions/tcpdf/config/lang/eng.php');

        if (isset($session['SiteConfig_records'])) {
            $model = $session['SiteConfig_records'];
        } else
            $model = SiteConfig::model()->findAll();



        $html = $this->renderPartial('expenseGridtoReport', array(
            'model' => $model
                ), true);

        //die($html);

        $pdf = new TCPDF();
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor(Yii::app()->name);
        $pdf->SetTitle('Laporan SiteConfig');
        $pdf->SetSubject('Laporan SiteConfig Report');
        //$pdf->SetKeywords('example, text, report');
        $pdf->SetHeaderData('', 0, "Report", '');
        //$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, "Laporan" SiteConfig, "");
        $pdf->SetHeaderData("", "", "Laporan SiteConfig", "");
        $pdf->setHeaderFont(Array('helvetica', '', 8));
        $pdf->setFooterFont(Array('helvetica', '', 6));
        $pdf->SetMargins(15, 18, 15);
        $pdf->SetHeaderMargin(5);
        $pdf->SetFooterMargin(10);
        $pdf->SetAutoPageBreak(TRUE, 0);
        $pdf->SetFont('dejavusans', '', 7);
        $pdf->AddPage();
        $pdf->writeHTML($html, true, false, true, false, '');
        $pdf->LastPage();
        $pdf->Output("SiteConfig_002.pdf", "I");
    }

}
