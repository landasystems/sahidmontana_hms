<?php

class AccCashOutController extends Controller {

    public $breadcrumbs;

    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = 'main';

    /*     * g
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
                'expression' => 'app()->controller->isValidAccess("AccCashOut","c")'
            ),
            array('allow', // r
                'actions' => array('index', 'view'),
                'expression' => 'app()->controller->isValidAccess("AccCashOut","r")'
            ),
            array('allow', // u
                'actions' => array('update'),
                'expression' => 'app()->controller->isValidAccess("AccCashOut","u")'
            ),
            array('allow', // d
                'actions' => array('delete'),
                'expression' => 'app()->controller->isValidAccess("AccCashOut","d")'
            ),
        );
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id) {
//        AccCoa::model()->checkAccess();

        cs()->registerCss('', '@page { size:22cm 14cm;margin: 0.4cm;}');

        $cashOutDetail = AccCashOutDet::model()->findAll(array(
            'condition' => 'acc_cash_out_id= ' . $id,
            'order' => 'id DESC'
        ));
        $approveDetail = AccApproval::model()->findAll(array(
            'condition' => 'acc_cash_out_id= ' . $id,
            'order' => 'id DESC'
        ));
        $this->render('view', array(
            'model' => $this->loadModel($id),
            'cashOutDet' => $cashOutDetail,
            'approveDetail' => $approveDetail,
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {
        $this->cssJs();
        $model = new AccCashOut;
        $model->code = SiteConfig::model()->formatting('cashout', TRUE);
        $model->total = 0;
        $model->date_trans = date("Y-m-d");
        $berhasil = true;

        if (isset($_POST['AccCashOut'])) {
            if ($_POST['totalKredit'] == $_POST['AccCashOut']['total']) {
                $model->attributes = $_POST['AccCashOut'];
                $model->code = SiteConfig::model()->formatting('cashout', FALSE);
                $model->date_trans = $_POST['AccCashOut']['date_trans'];
                $model->acc_coa_id = $_POST['AccCashOut']['accCoa'];

                if (isset($_POST['AccCashOutDet'])) {
                    if ($model->save()) {
                        $debet = array();
                        for ($i = 0; $i < count($_POST['AccCashOutDet']['acc_coa_id']); $i++) {

                            if (isset($_POST['nameAccount'][$i])) {
                                $ar = 0;
                                $as = 0;
                                $ap = 0;
                                $ledger = AccCoa::model()->findByPk($_POST['AccCashOutDet']['acc_coa_id'][$i]);

                                if ($ledger->type_sub_ledger == "ap")
                                    $ap = $_POST['nameAccount'][$i];

                                if ($ledger->type_sub_ledger == "as")
                                    $as = $_POST['nameAccount'][$i];

                                if ($ledger->type_sub_ledger == "ar")
                                    $ar = $_POST['nameAccount'][$i];
                            }

                            $cashOutDet = new AccCashOutDet;
                            $cashOutDet->acc_cash_out_id = $model->id;
                            $cashOutDet->acc_coa_id = $_POST['AccCashOutDet']['acc_coa_id'][$i];
                            $cashOutDet->amount = $_POST['AccCashOutDet']['amount'][$i];
                            $cashOutDet->description = $_POST['AccCashOutDet']['description'][$i];
                            $cashOutDet->invoice_det_id = $_POST['inVoiceDet'][$i];
                            $cashOutDet->ap_id = $ap;
                            $cashOutDet->as_id = $as;
                            $cashOutDet->ar_id = $ar;
                            $cashOutDet->save();

//                            if (isset($_POST['inVoiceDet'][$i]) && $_POST['inVoiceDet'][$i] != 0) {
//                                $invoiceDet = InvoiceDet::model()->findByPk($_POST['inVoiceDet'][$i]);
//                                if ($invoiceDet->type == 'supplier') {
//                                    $invoiceDet->payment = $invoiceDet->payment - $_POST['AccCashOutDet']['amount'][$i];
//                                } else {
//                                    $invoiceDet->payment = $invoiceDet->payment + $_POST['AccCashOutDet']['amount'][$i];
//                                }
//                                $invoiceDet->charge = $invoiceDet->charge + $_POST['AccCashOutDet']['amount'][$i];
//                                $invoiceDet->save();
//                            }
//                            $valSub[] = (object) array("id" => $model->id, "acc_coa_id" => $cashOutDet->acc_coa_id, "date_trans" => $model->date_trans, "description" => $cashOutDet->description, "debet" => $cashOutDet->amount, "code" => $model->code, "reff_type" => "cash_out", "ar" => $ar, "as" => $as, "ap" => $ap);
                            if ($cashOutDet->amount < 0)
                                $credit[] = (object) array("id" => $model->id, "acc_coa_id" => $cashOutDet->acc_coa_id, "date_trans" => $model->date_trans, "description" => $cashOutDet->description, "total" => $cashOutDet->total, "code" => $model->code, "reff_type" => "cash_out");
                            else
                                $debet[] = (object) array("id" => $model->id, "acc_coa_id" => $cashOutDet->acc_coa_id, "date_trans" => $model->date_trans, "description" => $cashOutDet->description, "total" => $cashOutDet->amount, "code" => $model->code, "reff_type" => "cash_out");
                        }

                        $credit[] = (object) array("id" => $model->id, "acc_coa_id" => $model->acc_coa_id, "date_trans" => $model->date_trans, "description" => $model->description, "total" => $model->total, "code" => $model->code, "reff_type" => "cash_out");

                        $siteConfig = SiteConfig::model()->findByPk(param('id'));
                        if ($siteConfig->is_approval == "no" or ( isset($data) and $siteConfig->is_approval == "manual")) {
                            AccCoa::model()->trans($debet, $credit);
//                            AccCoa::model()->transLedger($valSub, array());
                        } else {
                            $status = new AccApproval;
                            $status->status = "open";
                            $status->acc_cash_out_id = $model->id;
                            $status->save();
                        }
                        $berhasil = true;
                        $this->redirect(array('view', 'id' => $model->id, 'berhasil' => $berhasil));
                    }
                } else {
                    Yii::app()->user->setFlash('error', '<strong>Error! </strong>No account added.');
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
//        $this->cssJs();
        $accCoaSub = array();
        $cashout = $this->loadModel($id);
        $cashOutDetail = AccCashOutDet::model()->findAll(array('condition' => 'acc_cash_out_id= ' . $cashout->id));

        // load model approve
        $act = (isset($_GET['act'])) ? $_GET['act'] : '';
        $approveDetail = AccApproval::model()->findAll(array('condition' => 'acc_cash_out_id= ' . $id));
        $admin = array();
        $manager = array();
        $getModel = array();
//        $siteConfig = SiteConfig::model()->findByPk(param('id'));
        $cekApprove = AccCoaDet::model()->find(array('condition' => 'reff_type="cash_out" and reff_id=' . $id));

        //cek apakah data sudah diapprove
        $isAfterApprove = (isset($cashout->AccManager->status)) ? $cashout->AccManager->status : '';
        if (isset($isAfterApprove) and $isAfterApprove == "confirm") {
            $afterApprove = 1;
        } else {
            $afterApprove = 0;
        }

        //pengecekan jika sudah di approve, tidak boleh di approve lagi
        if ($act == 'approve' && $afterApprove == 1)
            throw new CHttpException(500, 'Data sudah disetujui, system tidak dapat melanjutkan proses.');

        //---------------------proses update----------------------------------------------
        if (isset($_POST['AccCashOut'])) {
            if (isset($_POST['AccCashOutDet'])) {
                if ($_POST['totalKredit'] == $_POST['AccCashOut']['total']) {
                    if (empty($_POST['AccCashOut']['accCoa']) && $act == 'approve') {
                        Yii::app()->user->setFlash('error', '<strong>Error! </strong>Kode Rekening belum terpilih');
                    } else {
                        //update cash out
                        $cashout->attributes = $_POST['AccCashOut'];
                        $cashout->acc_coa_id = $_POST['AccCashOut']['accCoa'];
                        if (isset($_POST['date_post']))
                            $cashout->date_posting = $_POST['date_post'];
                        if (!empty($_POST['codeAcc']))
                            $cashout->code_acc = $_POST['codeAcc'];

                        if ($act == 'approve' && empty($_POST['codeAcc'])) { //hanya waktu action approve generate code acc
//                            $format = json_decode($siteConfig->autonumber);
                            if ($cashout->AccCoa->type_sub_ledger == 'ks') {
                                DateConfig::model()->addYear($_POST['date_post'], 'cash_out');
                                $cashout->code_acc = SiteConfig::model()->formatting('cashoutks_acc', False, '', '', $_POST['date_post']);
                            } elseif ($cashout->AccCoa->type_sub_ledger == 'bk') {
                                DateConfig::model()->addYear($_POST['date_post'], 'bk_out');
                                $cashout->code_acc = SiteConfig::model()->formatting('cashoutbk_acc', False, '', '', $_POST['date_post']);
                            }
                            //tambah autonumber
//                            $siteConfig->autonumber = json_encode($format);
//                            $siteConfig->save();
                        }

                        $cashout->save();

                        //update cashout detail
                        AccCashOutDet::model()->deleteAll(array('condition' => 'acc_cash_out_id = ' . $cashout->id));
                        $debet = array();
                        $sDesc = '';
                        $valSub = array();
                        for ($i = 0; $i < count($_POST['AccCashOutDet']['acc_coa_id']); $i++) {
                            if (isset($_POST['nameAccount'][$i])) {
                                $ar = 0;
                                $as = 0;
                                $ap = 0;
                                $ledger = AccCoa::model()->findByPk($_POST['AccCashOutDet']['acc_coa_id'][$i]);

                                if ($ledger->type_sub_ledger == "ap")
                                    $ap = $_POST['nameAccount'][$i];

                                if ($ledger->type_sub_ledger == "as")
                                    $as = $_POST['nameAccount'][$i];

                                if ($ledger->type_sub_ledger == "ar")
                                    $ar = $_POST['nameAccount'][$i];
                            }

                            $cashOutDet = new AccCashOutDet;
                            $cashOutDet->acc_cash_out_id = $cashout->id;
                            $cashOutDet->acc_coa_id = $_POST['AccCashOutDet']['acc_coa_id'][$i];
                            $cashOutDet->amount = $_POST['AccCashOutDet']['amount'][$i];
                            $cashOutDet->description = $_POST['AccCashOutDet']['description'][$i];
                            $cashOutDet->invoice_det_id = (isset($_POST['inVoiceDet'][$i]) && $_POST['inVoiceDet'][$i] != 0) ? $_POST['inVoiceDet'][$i] : NULL;
                            $cashOutDet->ap_id = $ap;
                            $cashOutDet->as_id = $as;
                            $cashOutDet->ar_id = $ar;
                            $cashOutDet->save();

//                            if (isset($_POST['inVoiceDet']) && $_POST['inVoiceDet'][$i] != 0) {
//                                $invoiceDet = InvoiceDet::model()->findByPk($_POST['inVoiceDet'][$i]);
//                                if ($invoiceDet->type == 'supplier') {
//                                    $invoiceDet->payment = $invoiceDet->payment - $_POST['AccCashOutDet']['amount'][$i];
//                                } else {
//                                    $invoiceDet->payment = $invoiceDet->payment + $_POST['AccCashOutDet']['amount'][$i];
//                                }
//                                $invoiceDet->charge = $invoiceDet->charge + $_POST['AccCashOutDet']['amount'][$i];
//                                $invoiceDet->save();
//                            }

                            $sDesc .= '<br/>' . $cashOutDet->description;

                            //**********edit*****************
//                            if (isset($_POST['nameAccount'][$i])) {
//                                $valSub[] = (object) array("id" => $cashout->id, "acc_coa_id" => $cashOutDet->acc_coa_id, "date_trans" => (isset($_POST['date_post'])) ? $_POST['date_post'] : '', "description" => $cashOutDet->description, "debet" => $cashOutDet->amount, "code" => $cashout->code_acc, "reff_type" => "cash_out", "ar" => $ar, "as" => $as, "ap" => $ap);
//                            }

                            if ($cashOutDet->amount < 0)
                                $credit[] = (object) array("id" => $cashout->id, "acc_coa_id" => $cashOutDet->acc_coa_id, "date_trans" => (isset($_POST['date_post'])) ? $_POST['date_post'] : '', "description" => $cashOutDet->description, "total" => $cashOutDet->amount * -1, "code" => $cashout->code_acc, "reff_type" => "cash_out", "invoice_det_id" => (isset($_POST['inVoiceDet'][$i])) ? $_POST['inVoiceDet'][$i] : null);
                            else
                                $debet[] = (object) array("id" => $cashout->id, "acc_coa_id" => $cashOutDet->acc_coa_id, "date_trans" => (isset($_POST['date_post'])) ? $_POST['date_post'] : '', "description" => $cashOutDet->description, "total" => $cashOutDet->amount, "code" => $cashout->code_acc, "reff_type" => "cash_out", "invoice_det_id" => (isset($_POST['inVoiceDet'][$i])) ? $_POST['inVoiceDet'][$i] : null);
                        }
//                        if (isset($_POST['subid'])) {
//                            for ($j = 0; $j < count($_POST['subid']); $j++) {
//                                if (!empty($_POST['subid'][$j])) {
//                                    $subLedger = InvoiceDet::model()->findByPk($_POST['subid'][$j]);
//                                    if ($subLedger->type = "supplier") {
//                                        $subLedger->payment = $subLedger->payment + $_POST['valsub'][$j];
//                                        $subLedger->charge = $subLedger->charge - $_POST['valsub'][$j];
//                                    } else {
//                                        $subLedger->payment = $subLedger->payment - $_POST['valsub'][$j];
//                                        $subLedger->charge = $subLedger->charge + $_POST['valsub'][$j];
//                                    }
//                                    $subLedger->save();
//                                }
//                            }
//                        }
                        //jika description kosong, br di hilangkan
                        if (empty($cashout->description))
                            $sDesc = substr($sDesc, 5);
                        else
                            $sDesc = $cashout->description . $sDesc;

                        $credit[] = (object) array("id" => $cashout->id, "acc_coa_id" => $cashout->acc_coa_id, "date_trans" => (isset($_POST['date_post'])) ? $_POST['date_post'] : '', "description" => $sDesc, "total" => $cashout->total, "code" => $cashout->code_acc, "reff_type" => "cash_out", "invoice_det_id" => NULL);

                        // --------------------------Function Approve----------------------------

                        if ($act == 'approve' || ($act != 'approve' && $afterApprove == 1)) { //approve dijalankan ketika act approve / edit dan sudah di approve
                            AccCoaDet::model()->deleteAll(array('condition' => 'reff_type = "cash_out" and reff_id=' . $cashout->id));
//                            AccCoaSub::model()->deleteAll(array('condition' => 'reff_type="cash_out" and reff_id = ' . $cashout->id));


                            AccCoa::model()->trans($debet, $credit);
//                            AccCoa::model()->transLedger($valSub, array());

                            $manager = (object) array("status" => 'confirm', "description" => 'approve');
                            if ($afterApprove == 1) {
                                $manager = (object) array("status" => 'confirm', "description" => 'edit');
                            }
                            $getModel = (object) array('table' => 'AccCashOut', 'field' => 'acc_cash_out_id', 'id' => $id);
                            AccApproval::model()->saveApproval($admin, $manager, $getModel);
                        }
                        //----------------------------------END APPROVE---------------------------------------------------

                        $berhasil = true;
                        $this->redirect(array('view', 'id' => $cashout->id, 'berhasil' => $berhasil));
                    }
                } else {
                    Yii::app()->user->setFlash('error', '<strong>Error! </strong>Total Kredit dan Total Debit harus sama.');
                }
            } else {
                Yii::app()->user->setFlash('error', '<strong>Error! </strong>Detail dana masih kosong');
            }
        }

        $this->render('update', array(
            'model' => $cashout,
            'cashOutDetail' => $cashOutDetail,
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
// we only allow deletion via POST request
            if (!empty($model->code_acc)) {
                $this->actionDeleteApproved();
            } else {
                $this->loadModel($id)->delete();
                AccCashOutDet::model()->deleteAll(array('condition' => 'acc_cash_out_id = ' . $id));
            }


//            }
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
        $model = new AccCashOut('search');
        $model->unsetAttributes();  // clear any default values
        if (isset(user()->roles['accesskb'])) {
            $idData = user()->roles['accesskb']->crud;
            $sWhere = json_decode($idData);
//            echo $sWhere;
        } else {
            $sWhere = '';
        }
        if (isset($_GET['AccCashOut'])) {
            $model->attributes = $_GET['AccCashOut'];
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
        $model = AccCashOut::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(
            404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'acc-cash-out-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    public function cssJs() {
        cs()->registerScript('', '
                        
                        
                        
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

                if ($acca->type_sub_ledger == "ar")
                    $account = User::model()->findByPk($_POST['accountName']);

                else if ($acca->type_sub_ledger == "ap")
                    $account = User::model()->findByPk($_POST['accountName']);

                else if ($acca->type_sub_ledger == "as")
                    $account = Product::model()->findByPk($_POST['accountName']);

                $name = $account->name;
                $id = $account->id;
            } else {
                $name = "-";
                $id = "";
            }
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
                        <input type="hidden" class="nameAccount" name="nameAccount[]" id="nameAccount[]" value="' . $id . '"/>
                        <input type="hidden" class="inVoiceDet" name="inVoiceDet[]" class="inVoiceDet" id="inVoiceDet[]" value="' . $subId . '"/>
                        <span class="btn"><i class="delRow icon-remove-circle" style="cursor:all-scroll;"></i></span> 
                    </td>';
            echo '<td><select class="selectDua subLedger" style="width:100%" name="AccCashOutDet[acc_coa_id][]" id="AccCashOutDet[acc_coa_id][]">';
            foreach ($data as $key => $val) {
                $value = ($key == $_POST['account']) ? 'selected="selected"' : '';
                echo '<option ' . $value . ' value="' . $key . '">' . $val . '</option>';
            }
            echo '<option value="0">Pilih</option>';
            echo '</select></td>
                    <td style="text-align:center" class="subLedgerField">'.$invoiceName. '<a style="display:none" class="btn showModal">Select Sub-Ledger</a></td>
                    <td><input type="text" name="AccCashOutDet[description][]" id="AccCashOutDet[description][]"  value="' . $_POST['costDescription'] . '" style="width:95%;"/></td>
                    <td><div class="input-prepend"> <span class="add-on">Rp.</span><input type="text" name="AccCashOutDet[amount][]" id="AccCashOutDet[amount][]" class="angka totalDet" value="' . $_POST['debit'] . '"/></div></td>
                </tr>';
        }
    }

    public function actionDeleteApproved() {
        $idCash = $_GET['id'];
        AccCashOut::model()->deleteByPk($idCash);
        AccCashOutDet::model()->deleteAll(array('condition' => 'acc_cash_out_id = ' . $idCash));
        AccCoaDet::model()->deleteAll(array('condition' => 'reff_type="cash_out" AND reff_id=' . $idCash));
//        AccCoaSub::model()->deleteAll(array('condition' => 'reff_type="cash_out" AND reff_id=' . $idCash));
//        echo 'data berhasil dihapus';
    }

    public function actionFixNumber() {
        $cashout = AccCashOut::model()->findAll(array('condition' => 'date_posting>="2015-01-01" AND code_acc LIKE "BKK%"', 'order' => 'code_acc'));
        $det = AccCoaDet::model()->findAll(array('condition' => 'date_coa>="2015-01-01" AND code LIKE "BKK%"', 'order' => 'code'));
        $angka = 0;
        foreach ($cashout as $arr) {
            $angka++;
            $arr->code_acc = "BKK" . substr("000000" . $angka, -5);
            $arr->save();

            $mCoaDet = AccCoaDet::model()->findAll(array('condition' => 'reff_type="cash_out" and reff_id=' . $arr->id));
            foreach ($mCoaDet as $arrCoaDet) {
                $arrCoaDet->code = $arr->code_acc;
                $arrCoaDet->save();
            }
        }
    }

    public function actionGenerateExcel() {
        $model = new AccCashOut;
        $model->attributes = $_GET['AccCashOut'];
        $data = $model->search(true);
        $a = explode('-', $model->date_posting);
        return Yii::app()->request->sendFile('excelReport.xls', $this->renderPartial('excelReport', array(
                            'model' => $data,
                            'start' => $a[0],
                            'end' => $a[1],
                                ), true)
        );
    }

}
