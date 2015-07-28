<?php

class TicketCategoryController extends Controller {

    public $breadcrumbs;
    public $layout = '//layouts/main';

    public function filters() {
        return array(
            'accessControl', // perform access control for CRUD operations
        );
    }

    public function accessRules() {
        return array(
            array('allow', 
                'actions' => array('index'),
                'expression' => 'app()->controller->isValidAccess()'
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
        $model = new TicketCategory;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);


        if (isset($_POST['TicketCategory'])) {
            if ($_POST['TicketCategory']['parent_id']) {
                $root = $model->findByPk($_POST['TicketCategory']['parent_id']);

                $child = new TicketCategory;
                $child->attributes = $_POST['TicketCategory'];
                //     $child->alias = $root->alias . '/' . Yii::app()->landa->urlParsing($child->name);

                if ($child->appendTo($root))
                    $this->redirect(array('view', 'id' => $child->id));
            }else {
                $model->attributes = $_POST['TicketCategory'];
                // $model->alias = Yii::app()->landa->urlParsing($model->name);
                if ($model->saveNode())
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

        if (isset($_POST['TicketCategory'])) {
            if ($_POST['TicketCategory']['parent_id']) {
                $root = $model->findByPk($_POST['TicketCategory']['parent_id']);

                $model->attributes = $_POST['TicketCategory'];

                if ($model->saveNode()) {
                    $model->moveAsFirst($root);
                    $this->redirect(array('view', 'id' => $model->id));
                }
            } else {
                $model->attributes = $_POST['TicketCategory'];
                $model->saveNode();
                if (!($model->isRoot()))
                    $model->moveAsRoot();
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
            $this->loadModel($id)->deleteNode();

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

        $model = new TicketCategory('search');
        $model->unsetAttributes();  // clear any default values

        if (isset($_GET['TicketCategory'])) {
            $model->attributes = $_GET['TicketCategory'];



            if (!empty($model->id))
                $criteria->addCondition('id = "' . $model->id . '"');


            if (!empty($model->name))
                $criteria->addCondition('name = "' . $model->name . '"');


            if (!empty($model->created))
                $criteria->addCondition('created = "' . $model->created . '"');


            if (!empty($model->modified))
                $criteria->addCondition('modified = "' . $model->modified . '"');


            if (!empty($model->level))
                $criteria->addCondition('level = "' . $model->level . '"');


            if (!empty($model->lft))
                $criteria->addCondition('lft = "' . $model->lft . '"');


            if (!empty($model->rgt))
                $criteria->addCondition('rgt = "' . $model->rgt . '"');


            if (!empty($model->root))
                $criteria->addCondition('root = "' . $model->root . '"');
        }
        $session['TicketCategory_records'] = TicketCategory::model()->findAll($criteria);


        $this->render('index', array(
            'model' => $model,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new TicketCategory('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['TicketCategory']))
            $model->attributes = $_GET['TicketCategory'];

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
        $model = TicketCategory::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'ticket-category-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    public function actionGenerateExcel() {
        $session = new CHttpSession;
        $session->open();

        if (isset($session['TicketCategory_records'])) {
            $model = $session['TicketCategory_records'];
        } else
            $model = TicketCategory::model()->findAll();


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

        if (isset($session['TicketCategory_records'])) {
            $model = $session['TicketCategory_records'];
        } else
            $model = TicketCategory::model()->findAll();



        $html = $this->renderPartial('expenseGridtoReport', array(
            'model' => $model
                ), true);

        //die($html);

        $pdf = new TCPDF();
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor(Yii::app()->name);
        $pdf->SetTitle('Laporan TicketCategory');
        $pdf->SetSubject('Laporan TicketCategory Report');
        //$pdf->SetKeywords('example, text, report');
        $pdf->SetHeaderData('', 0, "Report", '');
        //$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, "Laporan" TicketCategory, "");
        $pdf->SetHeaderData("", "", "Laporan TicketCategory", "");
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
        $pdf->Output("TicketCategory_002.pdf", "I");
    }

}
