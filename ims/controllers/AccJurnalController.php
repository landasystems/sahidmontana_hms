<?php

class AccJurnalController extends Controller {

    public $breadcrumbs;

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
                'expression' => 'app()->controller->isValidAccess("AccJurnal","c")'
            ),
            array('allow', // r
                'actions' => array('index', 'view'),
                'expression' => 'app()->controller->isValidAccess                ("AccJurnal","r")'
            ),
            array('allow', // u
                'actions' => array('update'),
                'expression' => 'app()->controller->isValidAccess("AccJurnal","u")'
            ),
            array('allow', // d
                'actions' => array('delete'),
                'expression' => 'app()->controller->isValidAccess("AccJurnal","d")'
            )
        );
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id) {
        cs()->registerCss('', '@page { size:22cm 14cm;margin: 0.4cm;}');

        $detailJurnal = AccJurnalDet::model()->findAll(array('order'=>'id','condition' => 'acc_jurnal_id=' . $id));
        $approveDetail = AccApproval::model()->findAll(array('condition' => 'acc_jurnal_id= ' . $id));
        $this->render('view', array(
            'model' => $this->loadModel($id),
            'detailJurnal' => $detailJurnal,
            'approveDetail' => $approveDetail,
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {
        $this->cssJs();
        $model = new AccJurnal;
        $model->code = SiteConfig::model()->formatting('jurnal', TRUE);
        $model->total_debet = 0;
        $model->total_credit = 0;
        $model->date_trans = date("Y-m-d");


        $this->performAjaxValidation($model);

        if (isset($_POST['AccJurnal'])) {
            if ($_POST['AccJurnal']['total_debet'] == $_POST['AccJurnal']['total_credit']) {
                $model->attributes = $_POST['AccJurnal'];
                $model->code = SiteConfig::model()->formatting('jurnal', FALSE);
                if ($model->save()) {
                    if (isset($_POST['acc_coa_id'])) {
                        for ($i = 0; $i < count($_POST['acc_coa_id']); $i++) {
                            if (isset($_POST['nameAccount'][$i])) {
                                $ar = 0;
                                $as = 0;
                                $ap = 0;
                                $ledger = AccCoa::model()->findByPk($_POST['acc_coa_id'][$i]);

                                if ($ledger->type_sub_ledger == "ap")
                                    $ap = $_POST['nameAccount'][$i];

                                if ($ledger->type_sub_ledger == "as")
                                    $as = $_POST['nameAccount'][$i];

                                if ($ledger->type_sub_ledger == "ar")
                                    $ar = $_POST['nameAccount'][$i];
                            }

                            $jurnalDet = new AccJurnalDet;
                            $jurnalDet->acc_jurnal_id = $model->id;
                            $jurnalDet->acc_coa_id = $_POST['acc_coa_id'][$i];
                            $jurnalDet->invoice_det_id = $_POST['inVoiceDet'][$i];
                            $jurnalDet->debet = $_POST['valdebet'][$i];
                            $jurnalDet->credit = $_POST['valcredit'][$i];
                            $jurnalDet->description = $_POST['description'][$i];
                            $jurnalDet->ap_id = $ap;
                            $jurnalDet->as_id = $as;
                            $jurnalDet->ar_id = $ar;
                            $jurnalDet->save();

//                            if (isset($_POST['inVoiceDet'][$i]) && $_POST['inVoiceDet'][$i] != 0) {
//                                $invoiceDet = InvoiceDet::model()->findByPk($_POST['inVoiceDet'][$i]);
//                                if ($invoiceDet->type == 'supplier') {
//                                    if ($_POST['valdebet'][$i] != 0) {
//                                        $invoiceDet->payment = $invoiceDet->payment - $_POST['valdebet'][$i];
//                                        $invoiceDet->charge = $invoiceDet->charge + $_POST['valdebet'][$i];
//                                    } else {
//                                        $invoiceDet->payment = $invoiceDet->payment + $_POST['valcredit'][$i];
//                                        $invoiceDet->charge = $invoiceDet->charge - $_POST['valcredit'][$i];
//                                    }
//                                } else {
//                                    if ($_POST['valdebet'][$i] != 0) {
//                                        $invoiceDet->payment = $invoiceDet->payment + $_POST['valdebet'][$i];
//                                        $invoiceDet->charge = $invoiceDet->charge - $_POST['valdebet'][$i];
//                                    } else {
//                                        $invoiceDet->payment = $invoiceDet->payment - $_POST['valcredit'][$i];
//                                        $invoiceDet->charge = $invoiceDet->charge + $_POST['valcredit'][$i];
//                                    }
//                                }
//                                $invoiceDet->save();
//                            }

                            if ($_POST['valdebet'][$i] != 0) {
                                $debet[] = (object) array("id" => $model->id, "acc_coa_id" => $jurnalDet->acc_coa_id, "date_trans" => $model->date_trans, "description" => $jurnalDet->description, "total" => $jurnalDet->debet, "code" => $model->code, "reff_type" => "jurnal",'invoice_det_id' => $jurnalDet->invoice_det_id);
//                                $subDebet[] = (object) array("id" => $model->id, "acc_coa_id" => $_POST['acc_coa_id'][$i], "date_trans" => $model->date_trans, "description" => $_POST['description'][$i], "debet" => $_POST['valdebet'][$i], "code" => $model->code, "reff_type" => "jurnal", "ar" => $ar, "as" => $as, "ap" => $ap);
                            } else {
                                $credit[] = (object) array("id" => $model->id, "acc_coa_id" => $jurnalDet->acc_coa_id, "date_trans" => $model->date_trans, "description" => $jurnalDet->description, "total" => $jurnalDet->credit, "code" => $model->code, "reff_type" => "jurnal",'invoice_det_id' => $jurnalDet->invoice_det_id);
//                                $subCrediCt[] = (object) array("id" => $model->id, "acc_coa_id" => $_POST['acc_coa_id'][$i], "date_trans" => $model->date_trans, "description" => $_POST['description'][$i], "credit" => $_POST['valcredit'][$i], "code" => $model->code, "reff_type" => "jurnal", "ar" => $ar, "as" => $as, "ap" => $ap);
                            }
                        }

                        $siteConfig = SiteConfig::model()->findByPk(param('id'));
//                        if ($siteConfig->is_approval == "no") {
//                            AccCoa::model()->trans($debet, $credit);
//                            AccCoa::model()->transLedger($subDebet, $subCredit);
//                        } else {
                            $status = new AccApproval;
                            $status->status = "open";
                            $status->acc_jurnal_id = $model->id;
                            $status->save();
//                        }
                        $this->redirect(array('view', 'id' => $model->id));
                    } else {
                        Yii::app()->user->setFlash('error', '<strong>Error! </strong>No account added.');
                    }
                }
            } else {
                Yii::app()->user->setFlash('error', '<strong>Error! </strong>Total Kredit dan Total Debit harus sama.');
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
        $this->cssJs();
        $accCoaSub = array();
        $jurnal = $this->loadModel($id);
        $detailJurnal = AccJurnalDet::model()->findAll(array('order'=>'id','condition' => 'acc_jurnal_id=' . $jurnal->id));

        // load model approve
        $act = (isset($_GET['act'])) ? $_GET['act'] : '';
        $jurnalDetail = AccJurnalDet::model()->findAll(array('condition' => 'acc_jurnal_id= ' . $id));
        $approveDetail = AccApproval::model()->findAll(array('condition' => 'acc_jurnal_id= ' . $id));
        $model = $this->loadModel($id);
        $admin = array();
        $manager = array();
        $getModel = array();
        $siteConfig = SiteConfig::model()->findByPk(param('id'));
        $cekApprove = AccCoaDet::model()->find(array('condition' => 'reff_type="jurnal" and reff_id=' . $id));

        //cek apakah data sudah diapprove
        $isAfterApprove = (isset($jurnal->AccManager->status)) ? $jurnal->AccManager->status : '';
        if (isset($isAfterApprove) and $isAfterApprove == "confirm") {
            $afterApprove = 1;
        } else {
            $afterApprove = 0;
        }

        //pengecekan jika sudah di approve, tidak boleh di approve lagi
        if ($act == 'approve' && $afterApprove == 1)
            throw new CHttpException(500, 'Data sudah disetujui, system tidak dapat melanjutkan proses.');

        //---------------------proses update----------------------------------------------
        if (isset($_POST['AccJurnal'])) {
            if (isset($_POST['acc_coa_id'])) {
                if ($_POST['AccJurnal']['total_debet'] == $_POST['AccJurnal']['total_credit']) {
                    if (empty($_POST['AccJurnal']['accCoa']) && $act == 'approve') {
                        Yii::app()->user->setFlash('error', '<strong>Error! </strong>Kode Rekening belum terpilih');
                    } else {
                        
                    }

                    //update jurnal 
                    $jurnal->attributes = $_POST['AccJurnal'];
                    if (isset($_POST['date_post']))
                        $jurnal->date_posting = $_POST['date_post'];
                    if (!empty($_POST['codeAcc']))
                        $jurnal->code_acc = $_POST['codeAcc'];

                    if ($act == 'approve' && empty($_POST['codeAcc'])) { //hanya waktu action approve generate code acc
//                        $format = json_decode($siteConfig->autonumber);
                        DateConfig::model()->addYear($_POST['date_post'], 'jurnal');
                        $jurnal->code_acc = SiteConfig::model()->formatting('jurnal_acc', False, '', '', $_POST['date_post']);
//                        $format->jurnal++;
                        //tambah autonumber
//                        $siteConfig->autonumber = json_encode($format);
//                        $siteConfig->save();
                    }
                    $jurnal->save();

                    //Delete

                    $thisDetail = AccCoaDet::model()->findAll(array('condition' => 'reff_id=' . $jurnal->id . ' and reff_type="jurnal"', 'order' => 'date_coa ASC, id ASC'));
                    foreach ($thisDetail as $val) {
                        $balance = AccCoaDet::model()->find(array('condition' => 'date_coa < "' . $val->date_coa . '" and acc_coa_id="' . $val->acc_coa_id . '"', 'order' => 'date_coa DESC, id DESC'));
                        if (empty($balance)) {
                            $valBalance = 0;
                            $date = '0000-00-00';
                        } else {
                            $valBalance = $balance->balance;
                            $date = $jurnal->date_posting;
                        }
                        $jurnalDet[] = (object) array("balance" => $valBalance, "date_trans" => $val->date_coa, "acc_coa_id" => $val->acc_coa_id);
                    }

//                    $thisLedger = AccCoaSub::model()->findAll(array('condition' => 'reff_id = ' . $jurnal->id . ' and reff_type="jurnal"', 'order' => 'id ASC'));
//                    foreach ($thisLedger as $val) {
//                        $balance = AccCoaSub::model()->find(array('condition' => 'date_coa < "' . $date . '" and acc_coa_id="' . $val->acc_coa_id . '"', 'order' => 'date_coa DESC, id DESC'));
//                        if (empty($balance)) {
//                            $valBalance = 0;
//                            $date = '0000-00-00';
//                        } else {
//                            $valBalance = $balance->balance;
//                            $date = $jurnal->date_posting;
//                        }
//                        $accCoaSub[] = (object) array("balance" => $valBalance, "date_trans" => $date, "acc_coa_id" => $val->acc_coa_id, "ar" => $val->ar_id, "as" => $val->as_id, "ap" => $val->ap_id);
//                    }

                    if ($afterApprove) { //sudah di approve, pengeditan pada acccoadet & acccoasub
                        AccCoaDet::model()->deleteAll(array('condition' => 'reff_id=' . $jurnal->id . ' and reff_type="jurnal"'));
//                        AccCoaSub::model()->deleteAll(array('condition' => 'reff_id = ' . $jurnal->id . ' and reff_type="jurnal"'));
                    }

                    $sDesc = '';
                    AccJurnalDet::model()->deleteAll(array('condition' => 'acc_jurnal_id=' . $id));
                    for ($i = 0; $i < count($_POST['acc_coa_id']); $i++) {

                        if (isset($_POST['nameAccount'][$i])) {
                            $ar = 0;
                            $as = 0;
                            $ap = 0;
                            $ledger = AccCoa::model()->findByPk($_POST['acc_coa_id'][$i]);

                            if ($ledger->type_sub_ledger == "ap")
                                $ap = $_POST['nameAccount'][$i];

                            if ($ledger->type_sub_ledger == "as")
                                $as = $_POST['nameAccount'][$i];

                            if ($ledger->type_sub_ledger == "ar")
                                $ar = $_POST['nameAccount'][$i];
                        }

                        $jurnalDet = new AccJurnalDet;
                        $jurnalDet->acc_jurnal_id = $jurnal->id;
                        $jurnalDet->acc_coa_id = $_POST['acc_coa_id'][$i];
                        $jurnalDet->invoice_det_id = (isset($_POST['inVoiceDet'][$i]) && $_POST['inVoiceDet'][$i] != 0) ? $_POST['inVoiceDet'][$i] : NULL;
                        $jurnalDet->debet = $_POST['valdebet'][$i];
                        $jurnalDet->credit = $_POST['valcredit'][$i];
                        $jurnalDet->description = (!empty($_POST['description'][$i])) ? $_POST['description'][$i] : "-";
                        $jurnalDet->ap_id = $ap;
                        $jurnalDet->as_id = $as;
                        $jurnalDet->ar_id = $ar;
                        $jurnalDet->save();

//                        if (isset($_POST['inVoiceDet'][$i]) && $_POST['inVoiceDet'][$i] != 0) {
//                            $invoiceDet = InvoiceDet::model()->findByPk($_POST['inVoiceDet'][$i]);
//                            if ($invoiceDet->type == 'supplier') {
//                                if ($_POST['valdebet'][$i] != 0) {
//                                    $invoiceDet->payment = $invoiceDet->payment - $_POST['valdebet'][$i];
//                                    $invoiceDet->charge = $invoiceDet->charge + $_POST['valdebet'][$i];
//                                } else {
//                                    $invoiceDet->payment = $invoiceDet->payment + $_POST['valcredit'][$i];
//                                    $invoiceDet->charge = $invoiceDet->charge - $_POST['valcredit'][$i];
//                                }
//                            } else {
//                                if ($_POST['valdebet'][$i] != 0) {
//                                    $invoiceDet->payment = $invoiceDet->payment + $_POST['valdebet'][$i];
//                                    $invoiceDet->charge = $invoiceDet->charge - $_POST['valdebet'][$i];
//                                } else {
//                                    $invoiceDet->payment = $invoiceDet->payment - $_POST['valcredit'][$i];
//                                    $invoiceDet->charge = $invoiceDet->charge + $_POST['valcredit'][$i];
//                                }
//                            }
//                            $invoiceDet->save();
//                        }
                        $sDesc = $jurnalDet->description;

                        if ($_POST['valdebet'][$i] != 0) {
                            $debet[] = (object) array("id" => $jurnal->id, "acc_coa_id" => $jurnalDet->acc_coa_id, "date_trans" => (isset($_POST['date_post'])) ? $_POST['date_post'] : '', "description" => $sDesc, "total" => $jurnalDet->debet, "code" => $jurnal->code_acc, "reff_type" => "jurnal", "invoice_det_id" => (isset($_POST['inVoiceDet'][$i])) ? $_POST['inVoiceDet'][$i] : null);
//                            $subDebet[] = (object) array("id" => $jurnal->id, "acc_coa_id" => $_POST['acc_coa_id'][$i], "date_trans" => (isset($_POST['date_post'])) ? $_POST['date_post'] : '', "description" => $sDesc, "debet" => $_POST['valdebet'][$i], "code" => $jurnal->code_acc, "reff_type" => "jurnal", "ar" => $ar, "as" => $as, "ap" => $ap);
                        } else {
                            $credit[] = (object) array("id" => $jurnal->id, "acc_coa_id" => $jurnalDet->acc_coa_id, "date_trans" => (isset($_POST['date_post'])) ? $_POST['date_post'] : '', "description" => $sDesc, "total" => $jurnalDet->credit, "code" => $jurnal->code_acc, "reff_type" => "jurnal", "invoice_det_id" => (isset($_POST['inVoiceDet'][$i])) ? $_POST['inVoiceDet'][$i] : null);
//                            $subCredit[] = (object) array("id" => $jurnal->id, "acc_coa_id" => $_POST['acc_coa_id'][$i], "date_trans" => (isset($_POST['date_post'])) ? $_POST['date_post'] : '', "description" => $sDesc, "credit" => $_POST['valcredit'][$i], "code" => $jurnal->code_acc, "reff_type" => "jurnal", "ar" => $ar, "as" => $as, "ap" => $ap);
                        }
                    }

                    if ($act == 'approve' || ($act != 'approve' && $afterApprove == 1)) { //approve dijalankan ketika act approve / edit dan sudah di approve
                        AccCoaDet::model()->deleteAll(array('condition' => 'reff_type = "jurnal" and reff_id=' . $jurnal->id));
//                        AccCoaSub::model()->deleteAll(array('condition' => 'reff_type="jurnal" and reff_id = ' . $jurnal->id));

                        AccCoa::model()->trans($debet, $credit);
//                        AccCoa::model()->transLedger($subDebet, $subCredit);

                        $manager = (object) array("status" => 'confirm', "description" => 'approve');
                        if ($afterApprove == 1) {
                            $manager = (object) array("status" => 'confirm', "description" => 'edit');
                        }
                        $getModel = (object) array('table' => 'AccJurnal', 'field' => 'acc_jurnal_id', 'id' => $id);
                        AccApproval::model()->saveApproval($admin, $manager, $getModel);
                    }
                    $berhasil = true;
                    $this->redirect(array('view', 'id' => $jurnal->id, 'berhasil' => $berhasil));
                } else {
                    Yii::app()->user->setFlash('error', '<strong>Error! </strong>Total Kredit dan Total Debit harus sama.');
                }
            } else {
                Yii::app()->user->setFlash('error', '<strong>Error! </strong>No account added.');
            }
        }

        $this->render('update', array(
            'model' => $jurnal,
            'detailJurnal' => $detailJurnal,
            'approveDetail' => $approveDetail,
        ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id) {
        $model = $this->loadModel($id);

        if (Yii::app()->request->isPostRequest) {
            if (!empty($model->code_acc)) {
                $this->actionDeleteApproved();
            } else {
                $this->loadModel($id)->delete();
                AccJurnalDet::model()->deleteAll(array('condition' => 'acc_jurnal_id=' . $id));
            }

            if (!isset($_GET['ajax']))
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] :
                                array('admin'));
        } else
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    }

    /**
     * Lists all models.
     */
    public function actionIndex() {
        $criteria = new CDbCriteria();

        $model = new AccJurnal('search');
        $model->unsetAttributes();  // clear any default values

        if (isset($_GET['AccJurnal'])) {
            $model->attributes = $_GET['AccJurnal'];




            if (!empty($model->id))
                $criteria->addCondition('id = "' . $model->id . '"');



            if (!empty($model->code))
                $criteria->addCondition('code = "' . $model->code . '"');



            if (!empty($model->date_trans))
                $criteria->addCondition('date_trans = "' . $model->date_trans . '"');



            if (!empty($model->description))
                $criteria->addCondition('description = "' . $model->description . '"');



            if (!empty($model->total_debet))
                $criteria->addCondition('total_debet = "' . $model->total_debet . '"');



            if (!empty($model->total_credit))
                $criteria->addCondition('total_credit = "' . $model->total_credit . '"');


            if (
                    !empty($model->acc_admin_user_id))
                $criteria->addCondition('acc_admin_user_id = "' . $model->acc_admin_user_id . '"');



            if (!empty($model->acc_user_id))
                $criteria->addCondition('acc_user_id = "' . $model->acc_user_id . '"');



            if (!empty($model->created))
                $criteria->addCondition('created = "' . $model->created . '"');


            if
            (!empty($model->created_user_id))
                $criteria->addCondition('created_user_id = "' . $model->created_user_id . '"');



            if (!empty($model->modified))
                $criteria->addCondition('modified = "' . $model->modified . '"');
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
        $model = AccJurnal::model()->
                findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'acc-jurnal-form') {
            echo CActiveForm::validate($model);

            Yii::app()->end();
        }
    }

    public function cssJs() {
        cs()->registerScript('', ' 
                 $("#acc-jurnal-form").on("keyup",".totalDeb",function(){
                    var valdebet = $(this).val();
                    if(valdebet.length){
                        $(this).parent().parent().parent().find(".totalCre").attr("readonly", true);
                    }else{
                        $(this).parent().parent().parent().find(".totalCre").attr("readonly", false);
                    }
                 });
                 
                $("#acc-jurnal-form").on("keyup",".totalCre",function(){
                    var valcredit = $(this).val();
                    if(valcredit.length){
                        $(this).parent().parent().parent().find(".totalDeb").attr("readonly", true);
                    }else{
                        $(this).parent().parent().parent().find(".totalDeb").attr("readonly", false);
                    }
                 });
                                             
                 $("#debit").on("keyup", function() {
                 var debit = $("#debit").val();
                 if(debit.length && debit!="0"){
                 $("#credit").attr("readonly", true);
                 }else{
                 $("#credit").attr("readonly", false);
                 }
                 });
                 
                 $("#credit").on("keyup", function() {
                 var credit = $("#credit").val();
                 if(credit.length && credit!="0"){
                 $("#debit").attr("readonly", true);
                 }else{
                 $("#debit").attr("readonly", false);
                 }
                 });
                  
               function calculate(){
                     var debet = $("#debit").val();
                        if(!debet.length){
                            debet = 0;
                        }
                        var credit = $("#credit").val();
                        if(!credit.length){
                            credit = 0;
                        }
                    var totalDebet = parseFloat(debet) + parseFloat($("#total_debet").val());
                    $("#total_debet").val(totalDebet);
                    
                    var totalCredit = parseFloat(credit) + parseFloat($("#total_credit").val());
                    $("#total_credit").val(totalCredit);
                }
                
                function calculateMin(){
                    var totalDebet=0;
                    $(".totalDeb").each(function() {
                   totalDebet += parseFloat($(this).val());
                    });
                   $("#total_debet").val(totalDebet);
                   
                    var totalCredit=0;
                    $(".totalCre").each(function() {
                   totalCredit += parseFloat($(this).val());
                    });
                   $("#total_credit").val(totalCredit);
                }
                
                function clear(){
                    $("#debit").val("0");
                    $("#credit").val("0");
                    $("#costDescription").val("");
                    $("#s2id_account").select2("data", "0");
                    $("#s2id_accountName").select2("data", "0");
                    $("#credit").attr("readonly", false);
                    $("#debit").attr("readonly", false);
                 }
                 
                  $(".totalDeb").on("keyup", function() {
                        var totalDebet=0;
                    $(".totalDeb").each(function() {
                   totalDebet += parseFloat($(this).val());
                    });
                   $("#total_debet").val(totalDebet);
                   
                    var totalCredit=0;
                    $(".totalCre").each(function() {
                   totalCredit += parseFloat($(this).val());
                    });
                   $("#total_credit").val(totalCredit);
                   });
                   
                   $(".totalCre").on("keyup", function() {
                                                    var totalDebet=0;
                                                $(".totalDeb").each(function() {
                                               totalDebet += parseFloat($(this).val());
                                                });
                                               $("#total_debet").val(totalDebet);

                                                var totalCredit=0;
                                                $(".totalCre").each(function() {
                                               totalCredit += parseFloat($(this).val());
                                                });
                                               $("#total_credit").val(totalCredit);
                                               });
                 
            $(".delRow").on("click", function() {
                 $(this).parent().parent().parent().remove();
                 calculateMin();
            });
            
            $("#costDescription,#debit,#credit").keypress(function (e) {
                if (e.which == 13) {
                  $("#btnAdd").trigger("click");
                  e.preventDefault();
                }
            });
            ');
    }

    public function actionAddRow() {
        $acca = AccCoa::model()->findByPk($_POST['account']);
        $data = array(0 => t('choose', 'global')) + CHtml::listData(AccCoa::model()->findAll(array('order' => 'root, lft')), 'id', 'nestedname');
        $subId = (isset($_POST['subledgerid'])) ? $_POST['subledgerid'] : 0;
        $mInvoce = InvoiceDet::model()->findByPk($subId);
        $invoiceName = (!empty($mInvoce->code) && !empty($mInvoce->User->name)) ? '<a class="btn btn-mini removeSub"><i class=" icon-remove-circle"></i></a>[' . $mInvoce->code . ']' . $mInvoce->User->name : '';
        if ($acca->type != "general") {
            if (isset($_POST['accountName']) and ! empty($_POST['accountName'])) {

                if (
                        $acca->type_sub_ledger == "ar")
                    $account = User::model()->findByPk($_POST['accountName']);

                if (
                        $acca->type_sub_ledger == "ap")
                    $account = User::model()->findByPk($_POST['accountName']);

                if (
                        $acca->type_sub_ledger == "as")
                    $account = Product::model()->findByPk($_POST['accountName']);

                $name = $account->name;
                $id = $account->id;
            } else {
                $name = "-";
                $id = "0";
            }

            $debetDisabled = ($_POST['debit'] == 0) ? 'readonly="readonly"' : '';
            $creditDisabled = ($_POST['credit'] == 0) ? 'readonly="readonly"' : '';
            echo '<tr id="addRow" style="display:none">
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr class="newRow">
                        <td style="text-align:center">
                            <input type="hidden" name="nameAccount[]" id="nameAccount[]" value="' . $id . '"/> 
                            <input type="hidden" name="inVoiceDet[]" id="inVoiceDet[]" class="inVoiceDet" value="' . $subId . '"/> 
                             <span class="btn"><i class="delRow icon-remove-circle" style="cursor:all-scroll;"></i></span> 
                        </td>';
            echo '<td><select class="selectDua subLedger" style="width:100%" name="acc_coa_id[]" id="acc_coa_id[]">';
            foreach ($data as $key => $val) {
                $value = ($key == $_POST['account']) ? 'selected="selected"' : '';
                echo '<option ' . $value . ' value="' . $key . '">' . $val . '</option>';
            }
            echo '<option value="0">Pilih</option>';
            echo '</select></td>
                        <td style="text-align:center" class="subLedgerField"> ' . $invoiceName . '<a style="display:none" class="btn showModal">Select Sub-Ledger</a></td>
                        <td><input type="text" class="span4" name="description[]" id="description[]" value="' . $_POST['costDescription'] . '"/> </td>
                        <td><div class="input-prepend"> <span class="add-on">Rp.</span><input type="text" name="valdebet[]" id="valdebet[]" onkeyup="calculateMin()" class="angka totalDeb" value="' . $_POST['debit'] . '" ' . $debetDisabled . '/></div></td>
                        <td><div class="input-prepend"> <span class="add-on">Rp.</span><input type="text" name="valcredit[]" id="valcredit[]" class="angka totalCre" value="' . $_POST['credit'] . '" ' . $creditDisabled . '/></div></td>
                    </tr>';
        }
    }

    public function actionDeleteApproved() {
        $idCash = $_GET['id'];
        AccJurnal::model()->deleteByPk($idCash);
        AccJurnalDet::model()->deleteAll(array('condition' => 'acc_jurnal_id = ' . $idCash));
        AccCoaDet::model()->deleteAll(array('condition' => 'reff_type="jurnal" AND reff_id=' . $idCash));
//        AccCoaSub::model()->deleteAll(array('condition' => 'reff_type="jurnal" AND reff_id=' . $idCash));
//        echo 'data berhasil dihapus';
    }

    public function actionFixNumber() {
        $cashOut = AccCashOut::model()->findAll(array('condition' => 'date_posting>="2015-01-01" AND code_acc LIKE "BKK%"', 'order' => 'code_acc'));
        $angka = 0;
        foreach ($cashOut as $arr) {
            $angka++;
            $arr->code_acc = "JMM" . substr("000000" . $angka, -5);
            $arr->save();

            $mCoaDet = AccCoaDet::model()->findAll(array('condition' => 'reff_type="cash_out" and reff_id=' . $arr->id));
            foreach ($mCoaDet as $arrCoaDet) {
                $arrCoaDet->code = $arr->code_acc;
                $arrCoaDet->save();
            }
        }
    }

    public function actionFixDelete() {
        $ids = array();
        $jurnal = AccJurnal::model()->findAll(array());
        foreach ($jurnal as $id) {
            $ids[] = $id->id;
        }
//        logs(implode(',', $ids));
        AccCoaDet::model()->deleteAll(array('condition' => 'reff_type="jurnal" AND reff_id NOT IN(' . implode(',', $ids) . ')'));
//        AccCoaSub::model()->deleteAll(array('condition' => 'reff_type="jurnal" AND reff_id NOT IN(' . implode(',', $ids) . ')'));
//        
    }

    public function actionGenerateExcel() {
        $jurnal = new AccJurnal;
        $jurnal->attributes = $_GET['AccJurnal'];

        $criteria = new CDbCriteria();
        $criteria->with = array('AccJurnal');
        $criteria->compare('AccJurnal.code', $jurnal->code, true);
        $criteria->compare('AccJurnal.code_acc', $jurnal->code_acc, true);
        $exDate = explode('-', $jurnal->date_posting);
        $criteria->condition = 'AccJurnal.date_posting >="' . date('Y-m-d', strtotime($exDate[0])) . '" AND AccJurnal.date_posting <="' . date('Y-m-d', strtotime($exDate[1])) . '"';

        $model = AccJurnalDet::model()->findAll($criteria);

        $a = explode('-',$jurnal->date_posting);

        return Yii::app()->request->sendFile('excelReport.xls', $this->renderPartial('excelReport', array(
                            'model' => $model,
                            'start' => $a[0],
                            'end' => $a[1],
                                ), true)
        );
    }

}
