<?php

class AccCoaController extends Controller {

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
                'expression' => 'app()->controller->isValidAccess("AccCoa","c")'
            ),
            array('allow', // r
                'actions' => array('index', 'view'),
                'expression' => 'app()->controller->isValidAccess("AccCoa","r")'
            ),
            array('allow', // u
                'actions' => array('update'),
                'expression' => 'app()->controller->isValidAccess("AccCoa","u")'
            ),
            array('allow', // d
                'actions' => array('delete'),
                'expression' => 'app()->controller->isValidAccess("AccCoa","d")'
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
        //$this->cssJs();
        $model = new AccCoa;
        $accCoaDet = new AccCoaDet();

        if (isset($_POST['AccCoa'])) {
            if ($_POST['AccCoa']['parent_id'] != 0) {
                $root = $model->findByPk($_POST['AccCoa']['parent_id']);


                $child = new AccCoa;
                $child->attributes = $_POST['AccCoa'];
                $child->code = $_POST['AccCoa']['code'];
                $child->type = $_POST['AccCoa']['type'];
                $child->type_sub_ledger = $_POST['AccCoa']['type_sub_ledger'];
                $child->group = $_POST['AccCoa']['group'];

                if ($child->appendTo($root)) {
                    /* $accCoaDet = new AccCoaDet;
                      $accCoaDet->acc_coa_id = $child->id;
                      $accCoaDet->code = $child->code;
                      $accCoaDet->date_coa = $_POST['AccCoaDet']['date_coa'];
                      $accCoaDet->description = $_POST['AccCoaDet']['description'];
                      //                  $accCoaDet->debet = $_POST['AccCoaDet']['debet'];
                      $accCoaDet->credit = 0;
                      $accCoaDet->balance = $_POST['AccCoaDet']['debet'];
                      $accCoaDet->reff_type = "balance";
                      $accCoaDet->reff_id = $child->id;
                      $accCoaDet->save();
                     */
//                    if (!isset($_POST['AccCoa']['filee'])) //jika bukan dari import excel
                    $this->redirect(array('view', 'id' => $child->id));
                }
//                if (!empty($child->id)) {
//                    $this->redirect(array('view', 'id' => $child->id,'hgaha' => 'hahahaha'));
//                }
            } else {
                $model->attributes = $_POST['AccCoa'];
                $model->type = $_POST['AccCoa']['type'];
                $model->type_sub_ledger = $_POST['AccCoa']['type_sub_ledger'];
                $model->group = $_POST['AccCoa']['group'];

                $model->saveNode();
                if (!empty($model->id))
                    $this->redirect(array('view', 'id' => $model->id));
            }
        }

        if (!isset($_POST['AccCoa']['filee'])) { //jika bukan dari import excel
            $this->render('create', array(
                'model' => $model,
                'accCoaDet' => $accCoaDet,
            ));
        }
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id) {
        $model = $this->loadModel($id);

        if (isset($_POST['AccCoa'])) {
            if ($_POST['AccCoa']['parent_id']) {
                $root = $model->findByPk($_POST['AccCoa']['parent_id']);

                $model->attributes = $_POST['AccCoa'];
                $model->code = $_POST['AccCoa']['code'];
                $model->type = $_POST['AccCoa']['type'];
                $model->type_sub_ledger = $_POST['AccCoa']['type_sub_ledger'];
                $model->group = $_POST['AccCoa']['group'];

                if ($model->saveNode()) {
                    $model->moveAsFirst($root);
                    $this->redirect(array('view', 'id' => $model->id));
                }
            } else {
                $model->attributes = $_POST['AccCoa'];
                $model->code = $_POST['AccCoa']['code'];
                $model->type = $_POST['AccCoa']['type'];
                $model->type_sub_ledger = $_POST['AccCoa']['type_sub_ledger'];
                $model->group = $_POST['AccCoa']['group'];
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
        $model1 = AccCoa::model()->findAll();
        $criteria = new CDbCriteria();

        $model = new AccCoa('search');
        $model->unsetAttributes();  // clear any default values

        if (isset($_GET['AccCoa'])) {
            $model->attributes = $_GET['AccCoa'];

            if (!empty($model->id))
                $criteria->addCondition('id = "' . $model->id . '"');

            if (!empty($model->code))
                $criteria->addCondition('code = "' . $model->code . '"');

            if (!empty($model->name))
                $criteria->addCondition('name = "' . $model->name . '"');

            if (!empty($model->description))
                $criteria->addCondition('description = "' . $model->description . '"');

            if (!empty($model->created_user_id))
                $criteria->addCondition('created_user_id = "' . $model->created_user_id . '"');

            if (!empty($model->modified))
                $criteria->addCondition('modified = "' . $model->modified . '"');

            if (!empty($model->created))
                $criteria->addCondition('created = "' . $model->created . '"');

            if (!empty($model->level))
                $criteria->addCondition('level = "' . $model->level . '"');

            if (!empty($model->lft))
                $criteria->addCondition('lft = "' . $model->lft . '"');

            if (!empty($model->rgt))
                $criteria->addCondition('rgt = "' . $model->rgt . '"');

            if (!empty($model->root))
                $criteria->addCondition('root = "' . $model->root . '"');

            if (!empty($model->parent_id))
                $criteria->addCondition('parent_id = "' . $model->parent_id . '"');
        }
        $this->render('index', array(
            'model' => $model,
            'model1' => $model1
        ));
    }

    public function actionBeginningBalance() {
        $this->cssJs();
        $model = AccCoa::model()->findAll(array('order' => 'root, lft', 'order' => 'id asc'));
        $tabHeader = AccCoa::model()->findAll(array('condition' => 'level = 1 '));
        $siteConfig = SiteConfig::model()->findByPk(1);
        $coaDet = array();

        if (isset($_POST['debet']) or isset($_POST['credit'])) {
            foreach ($_POST['id'] as $no => $id) {
//                if ($val->type == "detail") {
                $balance = AccCoaDet::model()->find(array('condition' => 'reff_type="balance" and acc_coa_id=' . $id));
                if (!empty($balance)) { //jika belum sudah ada balance, berarti tinggal editing saja
                    if (($_POST['debet'][$no] == $balance->debet) && ($_POST['credit'][$no] == $balance->credit) && ($balance->date_coa == $siteConfig->date_system)) {
                        //do nothing
                        //jika debet, kredit, tanggal, sama dengan yang ada di database
                    } else {
                        $balance->debet = $_POST['debet'][$no];
                        $balance->credit = $_POST['credit'][$no];
                        $balance->balance = $balance->debet - $balance->credit;
                        $balance->date_coa = date('Y-m-d', strtotime($siteConfig->date_system));
                        $balance->save();
//                        $coaDet[] = (object) array("balance" => $balance->balance, "date_trans" => $siteConfig->date_system, "acc_coa_id" => $balance->acc_coa_id);
//                        $coaSub[] = (object) array("balance" => $balance->balance, "date_trans" => $siteConfig->date_system, "acc_coa_id" => $balance->acc_coa_id);
//                        AccCoaDet::model()->updateAfterSubLedger($coaSub);
                    }
                } else {
                    //klo debet atau kredit tidak sama dengan 0 (ada isinya) , maka disimpan
//                    if ((isset($_POST['debet'][$no]) and $_POST['debet'][$no] != "0") or (isset($_POST['credit'][$no]) and $_POST['credit'][$no] != "0")) {
                    $balance = new AccCoaDet;
                    $balance->acc_coa_id = $id;
                    $balance->date_coa = date('Y-m-d', strtotime($siteConfig->date_system));
                    $balance->debet = $_POST['debet'][$no];
                    $balance->credit = $_POST['credit'][$no];
                    $balance->balance = $balance->debet - $balance->credit;
                    $balance->reff_type = "balance";
//                        $balance->reff_id = $id;
                    $balance->save();
//                        $coaDet[] = (object) array("balance" => $balance->balance, "date_trans" => $siteConfig->date_system, "acc_coa_id" => $balance->acc_coa_id);
//                        $coaSub[] = (object) array("balance" => $balance->balance, "date_trans" => $siteConfig->date_system, "acc_coa_id" => $balance->acc_coa_id);
//                        AccCoaDet::model()->updateAfterSubLedger($coaSub);
//                    }
                }
//                AccCoaDet::model()->updateAfter($coaDet);
//                }
            }
            Yii::app()->user->setFlash('success', '<strong>Berhasil! </strong>Data saldo awal berhasil tersimpan');
//            $this->redirect(array('beginningBalance/view'));
            //   } else {
            //       Yii::app()->user->setFlash('error', '<strong>Error! </strong>Total Kredit dan Total Debit harus sama.');
            //   }
        }

        $this->render('beginningBalance', array(
            'model' => $model,
            'tabHeader' => $tabHeader,
            'siteConfig' => $siteConfig,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new AccCoa('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['AccCoa']))
            $model->attributes = $_GET['AccCoa'];

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
        $model = AccCoa::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'acc-coa-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    public function actionRetAccount() {
        $isi = array();
        $model = AccCoa::model()->findByPk($_POST['ledger']);
        $ledger = $model->type_sub_ledger;
        $array = array();
        if ($ledger == "ar") {
            $array = User::model()->listUsers('customer');
        } else if ($ledger == "ap") {
            $array = User::model()->listUsers('supplier');
        } else if ($ledger == "as") {
            $array = Product::model()->findAll(array('condition' => 'type="inv"'));
        }

        $isi['tampil'] = ($ledger == 'ar' || $ledger == 'as' || $ledger == 'ap') ? true : false;
        $isi['render'] = $this->renderPartial('/invoiceDet/_searchInvoice', array(
            'array' => $array
                ), true);
        echo json_encode($isi);
    }

    public function actionImportExcel() {
        $berhasil = false;
        $model = new AccCoa;
        if (isset($_POST['AccCoa'])) {
            $file = CUploadedFile::getInstance($model, 'filee');
            if (is_object($file)) { //jika filenya valid
//                trace('aaa');
                $file->saveAs('images/file/' . $file->name);
                $data = new Spreadsheet_Excel_Reader('images/file/' . $file->name);

//                trace($data);
                $id = array();
                $nama = array();
                for ($j = 2; $j <= $data->sheets[0]['numRows']; $j++) {
                    $id[$j] = (isset($data->sheets[0]['cells'][$j][1])) ? $data->sheets[0]['cells'][$j][1] : '';
                    $code[$j] = (isset($data->sheets[0]['cells'][$j][2])) ? $data->sheets[0]['cells'][$j][2] : '';
                    $name[$j] = (isset($data->sheets[0]['cells'][$j][3])) ? $data->sheets[0]['cells'][$j][3] : '';
                    $description[$j] = (isset($data->sheets[0]['cells'][$j][4])) ? $data->sheets[0]['cells'][$j][4] : '';
                    $group[$j] = (isset($data->sheets[0]['cells'][$j][5])) ? $data->sheets[0]['cells'][$j][5] : '';
                    $type[$j] = (isset($data->sheets[0]['cells'][$j][6])) ? $data->sheets[0]['cells'][$j][6] : '';
//                $level[$j] = (isset($data->sheets[0]['cells'][$j][8])) ? $data->sheets[0]['cells'][$j][8] : '';
                    $parent_id[$j] = (isset($data->sheets[0]['cells'][$j][7])) ? $data->sheets[0]['cells'][$j][7] : '';
                    $tgl_saldo_awal[$j] = (isset($data->sheets[0]['cells'][$j][8])) ? $data->sheets[0]['cells'][$j][8] : '';
                    $saldo_awal[$j] = (isset($data->sheets[0]['cells'][$j][9])) ? $data->sheets[0]['cells'][$j][9] : '';

                    //script simpan
                    $_POST['AccCoa']['id'] = (int) $id[$j];
                    $_POST['AccCoa']['code'] = $code[$j];
                    $_POST['AccCoa']['name'] = $name[$j];
                    $_POST['AccCoa']['description'] = $description[$j];
                    $_POST['AccCoa']['group'] = $group[$j];
                    $_POST['AccCoa']['type'] = $type[$j];
                    $_POST['AccCoa']['parent_id'] = (int) $parent_id[$j];
                    $_POST['AccCoa']['type_sub_ledger'] = null;

                    $_POST['AccCoaDet']['description'] = 'Saldo Awal';
                    $_POST['AccCoaDet']['date_coa'] = $tgl_saldo_awal[$j];
                    $_POST['AccCoaDet']['debet'] = $saldo_awal[$j];

                    $this->actionCreate();
                    $berhasil = true;
                }
            }
        }
        $this->render('importExcel', array('model' => $model, 'berhasil' => $berhasil));
    }

    public function cssJs() {
        cs()->registerScript('', '
                $(".debet").on("keyup", function() {
                    var totalDebet = 0;
                    if(!$(this).val().length){
//                        $(this).val("0");
                    }
                    
                    $(".debet").each(function() {
                    
                        totalDebet += parseInt($(this).val());
                    });
                    $("#total_debet").val(totalDebet);
                });
                
                 $(".credit").on("keyup", function() {
                    var totalCredit = 0;
                    if(!$(this).val().length){
//                        $(this).val("0");
                    }
                    
                    $(".credit").each(function() {
                        totalCredit += parseInt($(this).val());
                    });
                    $("#total_credit").val(totalCredit);
                });
                  
                 $("#acc-beginning-balance-form").on("keyup",".debet",function(){
                    var debet = $(this).val();
                    if(debet.length){
                        $(this).parent().parent().parent().find(".credit").attr("readonly", true);
                    }else{
                        $(this).parent().parent().parent().find(".credit").attr("readonly", false);
                    }
                 });
                 $("#acc-beginning-balance-form").on("keyup",".credit",function(){
                    var credit = $(this).val();
                    if(credit.length){
                        $(this).parent().parent().parent().find(".debet").attr("readonly", true);
                    }else{
                        $(this).parent().parent().parent().find(".debet").attr("readonly", false);
                    }
                 });
                 
            ');
    }

    public function actionFixBalance() {
        $model = AccCoaDet::model()->findAll(array('order' => 'acc_coa_id, date_coa, id'));
        $acc_coa_id = '';
        $balance = 0;
        foreach ($model as $val) {
            if ($acc_coa_id != $val->acc_coa_id) {
                $balance = $val->balance;
                $acc_coa_id = $val->acc_coa_id;
            } else {
                $balance += $val->debet;
                $balance -= $val->credit;
                $val->balance = $balance;
                $val->save();
            }
        }
    }

//    public function actionBalanceKartu() {
//        $this->cssJs();
//        $Customer = User::model()->listUsers('customer');
//        $Supplier = User::model()->listUsers('supplier');
//        $Product = Product::model()->findAll();
//        $siteConfig = SiteConfig::model()->findByPk(1);
//
//        if (isset($_POST['debetc']) or isset($_POST['creditc'])) {
//            foreach ($_POST['idc'] as $cust => $id) {
//                $subAr = AccCoaSub::model()->find(array(
//                    'condition' => 'ar_id ="' . $id . '"',
//                    'order' => 'date_coa DESC'
//                ));
//                if (!empty($subAr)) {
//                    if (($_POST['debetc'] == $subAr->debet) && ($_POST['creditc'] == $subAr->credit) && ($siteConfig->date_system == $subAr->date_coa)) {
//                        //kosong
//                    } else {
//                        $subAr->debet = $_POST['debetc'][$cust];
//                        $subAr->credit = $_POST['creditc'][$cust];
//                        $subAr->reff_type = 'balance';
//                        $subAr->ar_id = $id;
//                        $subAr->date_coa = $siteConfig->date_system;
//                        $subAr->save();
//                    }
//                } else {
//                    $subAr = new AccCoaSub();
//                    $subAr->debet = $_POST['debetc'][$cust];
//                    $subAr->credit = $_POST['creditc'][$cust];
//                    $subAr->reff_type = 'balance';
//                    $subAr->ar_id = $id;
//                    $subAr->date_coa = $siteConfig->date_system;
//                    $subAr->save();
//                }
//            }
//        }
//        if (isset($_POST['debetp']) or isset($_POST['creditp'])) {
//            foreach ($_POST['idp'] as $cust => $id) {
//                $subAp = AccCoaSub::model()->find(array(
//                    'condition' => 'ap_id ="' . $id . '"',
//                    'order' => 'date_coa DESC'
//                ));
//                if (!empty($subAp)) {
//                    if (($_POST['debetp'] == $subAp->debet) && ($_POST['creditc'] == $subAp->credit) && ($siteConfig->date_system == $subAp->date_coa)) {
//                        //kosong
//                    } else {
//                        $subAp->debet = $_POST['debetp'][$cust];
//                        $subAp->credit = $_POST['creditp'][$cust];
//                        $subAp->reff_type = 'balance';
//                        $subAp->ap_id = $id;
//                        $subAp->date_coa = $siteConfig->date_system;
//                        $subAp->save();
//                    }
//                } else {
//                    $subAp = new AccCoaSub();
//                    $subAp->debet = $_POST['debetp'][$cust];
//                    $subAp->credit = $_POST['creditp'][$cust];
//                    $subAp->reff_type = 'balance';
//                    $subAp->ap_id = $id;
//                    $subAp->date_coa = $siteConfig->date_system;
//                    $subAp->save();
//                }
//            }
//        }
//        if (isset($_POST['debets']) or isset($_POST['credits'])) {
//            foreach ($_POST['ids'] as $cust => $id) {
//                $subAs = AccCoaSub::model()->find(array(
//                    'condition' => 'as_id ="' . $id . '"',
//                    'order' => 'date_coa DESC'
//                ));
//                if (!empty($subAs)) {
//                    if (($_POST['debets'] == $subAs->debet) && ($_POST['credits'] == $subAs->credit) && ($siteConfig->date_system == $subAs->date_coa)) {
//                        //kosong
//                    } else {
//                        $subAs->debet = $_POST['debets'][$cust];
//                        $subAs->credit = $_POST['credits'][$cust];
//                        $subAs->reff_type = 'balance';
//                        $subAs->as_id = $id;
//                        $subAs->date_coa = $siteConfig->date_system;
//                        $subAs->save();
//                    }
//                } else {
//                    $subAs = new AccCoaSub();
//                    $subAs->debet = $_POST['debets'][$cust];
//                    $subAs->credit = $_POST['credits'][$cust];
//                    $subAs->reff_type = 'balance';
//                    $subAs->as_id = $id;
//                    $subAs->date_coa = $siteConfig->date_system;
//                    $subAs->save();
//                }
//            }
//        }
//        $this->render('balanceKartu', array(
//            'Customer' => $Customer,
//            'Supplier' => $Supplier,
//            'Product' => $Product
//        ));
//    }

    public function actionDefaultExcel() {
        Yii::app()->request->sendFile('InputRekening' . '.xls', $this->renderPartial('defaultInput', array(
                        ), true)
        );
    }

    public function actionFixNumber() {
        $cashOut = AccCashOut::model()->findAll(array('condition' => 'date_posting>="2015-01-01" AND code_acc LIKE "BKK%"', 'order' => 'code_acc'));
        $angka = 0;
        foreach ($cashOut as $arr) {
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

    public function actionSelectInvoice() {
        if (isset($_POST['id'])) {
            $account = User::model()->findByPk($_POST['id']);
            $balance = InvoiceDet::model()->findAllByAttributes(array('user_id' => $_POST['id']));
            echo '<tr><th><input type="text" name="code_invoice" style="width:85%" id="code_invoice"></th>';
            echo '<th><input style="width:95%" type="text" name="invoice_description" id="invoice_description"></th>';
            echo '<th><input style="max-width:90% !important" type="text" class="angka" id="invoice_amount" value="0"></th>';
            echo '<th><input type="hidden" id="type_invoice" value="' . $account->Roles->type . '"></th>';
            echo '<td style="text-align:center"><a title="Tambah" rel="tooltip" href="#" class="addNewInvoice btn btn-mini">&nbsp;<i class=" brocco-icon-plus"></i></a></td></tr>';
            foreach ($balance as $b) {
                $charge = AccCoaDet::model()->balanceInvoice($b->id); //filter no date
                $payment = (!empty($b->payment) ? $b->payment : 0);
                echo '<tr>'
                . '<td style="width:10%">' . $b->code . '</td>'
                . '<td>' . $b->description . '</td>'
                . '<td style="text-align:right">' . landa()->rp($payment, false, 2) . '</td>'
                . '<td style="text-align:right">' . landa()->rp($charge, false, 2) . '</td>'
                . '<td style="width:20%; text-align:center">'
                . '<a class="btn btn-mini" title="Pilih" rel="tooltip">'
                . '<div class="ambil" nilai="'.$payment.'" account="' . $account->name . '" code="' . $b->code . '" det_id="' . $b->id . '" desc="' . $b->description . '">'
                . '&nbsp;<i class="minia-icon-checked"> </i>'
                . '</div>'
                . '</a> '
                . '<a class="btn btn-mini btn-danger" title="Hapus" rel="tooltip">'
                . '<div class="delInvoice" det_id="' . $b->id . '">'
                . '&nbsp;<i class="minia-icon-trashcan icon-white"> </i>'
                . '</div>'
                . '</a>'
                . '</td>'
                . '</tr>';
            }
        }
    }

    public function actionDeletedRow() {
        if (!empty($_POST['idSub'])) {
            echo '<tr id="deletedRow" style="display:none">
                            <td></td>
                            <td></td>
                  </tr>
             <tr style="display:none">'
            . '<td><input type="hidden" name="subid[]" value="' . $_POST['idSub'] . '"</td>'
            . '<td><input type="hidden" name="valsub[]" value="' . $_POST['totalDet'] . '"</td>'
            . '</tr>';
        }
    }

    public function actionNewInvoice() {

        if (!empty($_POST['code'] && !empty($_POST['user_id']))) {
            $model = new InvoiceDet();
            $model->code = $_POST['code'];
            $model->user_id = $_POST['user_id'];
            $model->description = $_POST['description'];
            $model->payment = $_POST['amount'];
            $model->type = $_POST['type'];
            $model->term_date = date('Y-m-d');
            $model->is_new_invoice = 1;
            if ($model->save()) {
                echo 1;
            }
        }
    }
}
