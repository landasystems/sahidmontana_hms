<?php

class ReportController extends Controller {

    public $breadcrumbs;
    public $layout = 'main';

//    public function filters() {
//        return array(
//            'rights', 
//        );
//    }
    public function filters() {
        return array(
            'accessControl', // perform access control for CRUD operations
        );
    }

    public function accessRules() {
        return array(
            array('allow', // r
                'actions' => array('stockCard'),
                'expression' => 'app()->controller->isValidAccess("Report_StockCard","r")'
            ),
            array('allow', // r
                'actions' => array('buy'),
                'expression' => 'app()->controller->isValidAccess("Report_Buy","r")'
            ),
            array('allow', // r
                'actions' => array('buyRetur'),
                'expression' => 'app()->controller->isValidAccess("Report_BuyRetur","r")'
            ),
            array('allow', // r
                'actions' => array('sell'),
                'expression' => 'app()->controller->isValidAccess("Report_Sell","r")'
            ),
            array('allow', // r
                'actions' => array('sellRetur'),
                'expression' => 'app()->controller->isValidAccess("Report_SellRetur","r")'
            ),
            array('allow', // r
                'actions' => array('stockItem'),
                'expression' => 'app()->controller->isValidAccess("Report_StockItem","r")'
            ),
            array('allow', // r
                'actions' => array('salaryisPaid'),
                'expression' => 'app()->controller->isValidAccess("Report_Salary","r")'
            ),
            array('allow', // r
                'actions' => array('salaryUnpaid'),
                'expression' => 'app()->controller->isValidAccess("Report_SalaryUnpaid","r")'
            ),
            array('allow', // r
                'actions' => array('productionLoss'),
                'expression' => 'app()->controller->isValidAccess("Report_ProductionLoss","r")'
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

    public function actionStockCard() {
//        $modelClassroom = new Classroom;
//        $modelExam = new Exam;
//        $this->render('examReport', array('modelClassroom'=>$modelClassroom,'modelExam'=>$modelExam));
        $mProductStockCard = new ProductStockCard();
        if (isset($_POST['ProductStockCard'])) {
            $mProductStockCard->attributes = $_POST['ProductStockCard'];
        }

        $this->render('stockCard', array('mProductStockCard' => $mProductStockCard));
    }

    public function actionBuy() {
        $mBuy = new Buy();
        if (!empty($_POST['Buy']['created'])) {
            $mBuy->attributes = $_POST['Buy'];
        }

        $this->render('buy', array('mBuy' => $mBuy));
    }

    public function actionBuyRetur() {
        $mBuy = new BuyRetur();
        if (!empty($_POST['BuyRetur']['created'])) {
            $mBuy->attributes = $_POST['BuyRetur'];
        }

        $this->render('buyretur', array('mBuy' => $mBuy));
    }

    public function actionSell() {
        $mBuy = new Sell();
        if (!empty($_POST['Sell']['created'])) {
            $mBuy->attributes = $_POST['Sell'];
        }

        $this->render('sell', array('mBuy' => $mBuy));
    }

    public function actionSellRetur() {
        $mBuy = new SellRetur();
        if (!empty($_POST['SellRetur']['created'])) {
            $mBuy->attributes = $_POST['SellRetur'];
        }

        $this->render('sellretur', array('mBuy' => $mBuy));
    }

    public function actionStockItem() {
        $mProductStockItem = new Product();
        if (isset($_POST['Product'])) {
            $mProductStockItem->attributes = $_POST['Product'];
        }

        $this->render('stockItem', array('mProductStockItem' => $mProductStockItem));
    }

    public function actionProductionLoss() {
        $mProductionLoss = new WorkorderProcess();
        if (isset($_POST['WorkorderProcess']['time_end'])) {
            $mProductionLoss->attributes = $_POST['WorkorderProcess'];
        }
        $this->render('productionLoss', array('mProductionLoss' => $mProductionLoss));
    }

    public function actionSalaryUnpaid() {
        $mProcess = new WorkorderProcess();
        if (isset($_POST['WorkorderProcess']['time_end'])) {
            $mProcess->attributes = $_POST['WorkorderProcess'];
        }
        $this->render('salaryUnpaid', array('mProcess' => $mProcess));
    }

    public function actionSalaryisPaid() {
        $mSalary = new Salary();
        if (isset($_POST['Salary']['created'])) {
            $mSalary->attributes = $_POST['Salary'];
        }
        $this->render('salaryisPaid', array('mSalary' => $mSalary));
    }

    public function actionGenerateExcelSalary() {
        $_GET['xls'] = true;
        $a = explode('-', str_replace(".html", "", $_GET['created']));
        $start = date('Y/m/d', strtotime($a[0]));
        $end = date('Y/m/d', strtotime($a[1])) . " 23:59:59";
        logs($start);
        $salary = Salary::model()->findAll(array('condition' => 'created >= "' . $start . '" AND created <="' . $end . '"', 'order' => 'id'));


        Yii::app()->request->sendFile('salary-' . date('dmY') . '.xls', $this->renderPartial('excelReportSalary', array(
                    'salary' => $salary,
                    'start' => $start,
                    'end' => $end,
                        ), true)
        );
    }

    public function actionGenerateExcelProductionLoss() {
        $_GET['xls'] = true;
        $a = explode('-', str_replace(".html", "", $_GET['created']));
        $start = date('Y/m/d', strtotime($a[0]));
        $end = date('Y/m/d', strtotime($a[1])) . " 23:59:59";
        logs($start);
        $lossReport = WorkorderProcess::model()->findAll(array('condition' => '(time_end >="' . $start . '" AND time_end <="' . $end . '")AND loss_qty is not null AND loss_qty !=""', 'order' => 'id'));


        Yii::app()->request->sendFile('productionLoss-' . date('dmY') . '.xls', $this->renderPartial('excelProductionLoss', array(
                    'lossReport' => $lossReport,
                    'start' => $start,
                    'end' => $end,
                        ), true)
        );
    }

    public function actionGeneralLedger() {
        cs()->registerCss('', '
            @page { margin: 0.4cm;} 
                .tbPrint {width:100%}
                table.tbPrint td, table.tbPrint th{
                    border: solid #000 2px;
            }');
//        $mProcess = new WorkorderProcess();
//        if (isset($_POST['WorkorderProcess']['time_end'])) {
//            $mProcess->attributes = $_POST['WorkorderProcess'];
//        }
        $mCoaDet = new AccCoaDet();
        $this->render('generalLedger', array('mCoaDet' => $mCoaDet));
    }

    public function actionNeraca() {
        cs()->registerCss('', '
            @page { margin: 0.4cm;} 
                .tbPrint {width:100%}
                table.tbPrint td, table.tbPrint th{
                    border: solid #000 2px;
            }');
//        $mProcess = new WorkorderProcess();
//        if (isset($_POST['WorkorderProcess']['time_end'])) {
//            $mProcess->attributes = $_POST['WorkorderProcess'];
//        }
        $mCoaDet = new AccCoaDet();
        $this->render('neraca', array('mCoaDet' => $mCoaDet));
    }

    public function actionNeracaSaldo() {
        cs()->registerCss('', '
            @page { margin: 0.4cm;} 
                .tbPrint {width:100%}
                table.tbPrint td, table.tbPrint th{
                    border: solid #000 2px;
            }');
//        $mProcess = new WorkorderProcess();
//        if (isset($_POST['WorkorderProcess']['time_end'])) {
//            $mProcess->attributes = $_POST['WorkorderProcess'];
//        }
        $mCoaDet = new AccCoaDet();
        $this->render('neracaSaldo', array('mCoaDet' => $mCoaDet));
    }

    public function actionlabaRugi() {
        cs()->registerCss('', '
            @page { margin: 0.4cm;} 
                .tbPrint {width:100%}
                table.tbPrint td, table.tbPrint th{
                    border: solid #000 2px;
            }');
        $mCoa = new AccCoa();
        if (isset($_POST['AccCoa']['created'])) {
            $mCoa->attributes = $_POST['AccCoa'];
        }
        $this->render('labaRugi', array('mCoa' => $mCoa));
    }

    public function actionGenerateExcelLabaRugi() {
        $_GET['xls'] = true;
        $a = explode('-', str_replace(".html", "", $_GET['created']));
        $start = date('Y/m/d', strtotime($a[0]));
        $end = date('Y/m/d', strtotime($a[1])) . " 23:59:59";
        logs($start);
        $viewType = $_GET['viewType'];
        $model = AccCoaDet::model()->findAll(array('condition' => '((created <="' . $start . '" and created >="' . $end . '")AND created_user_id is not null AND created_user_id != "" ) '));
        $accCoa = AccCoa::model()->findAll(array('order' => 'code', 'condition' => '`group` = "receivable" OR `group`= "cost"'));

        Yii::app()->request->sendFile('labarugi-' . date('dmY') . '.xls', $this->renderPartial('_labaRugiResult', array(
                    'model' => $model,
                    'start' => $start,
                    'end' => $end,
                    'accCoa' => $accCoa,
                    'a' => $a,
                    'viewType' => $viewType
                        ), true)
        );
    }

    public function actionGenerateExcelNeracaSaldo() {
        $_GET['xls'] = true;
        $a = explode('-', str_replace(".html", "", $_GET['created']));
        $start = date('Y/m/d', strtotime($a[0]));
        $end = date('Y/m/d', strtotime($a[1])) . " 23:59:59";
        logs($start);
        $accCoa = AccCoa::model()->findAll(array('condition' => 'type="detail"', 'order' => 'code'));
        Yii::app()->request->sendFile('NeracaSaldo-' . date('dmY') . '.xls', $this->renderPartial('_neracaSaldoResult', array('a' => $a, 'accCoa' => $accCoa, 'start' => $start, 'end' => $end), true)
        );
    }

    public function actionGenerateExcelGeneralLedger() {
        $_GET['xls'] = true;
        $a = explode('-', $_GET['created']);
        $start = date('Y/m/d', strtotime($a[0]));
        $end = date('Y/m/d', strtotime($a[1])) . " 23:59:59";
        $id = str_replace(".html", "", $_GET['id']);
        $checked = true;

        $acc = AccCoa::model()->findByPk($id);
        if ($acc->type == "detail") {
            $accCoaDet = AccCoaDet::model()->findAll(array('order' => 'date_coa , id', 'condition' => '(date_coa >="' . $start . '" and date_coa <="' . $end . '") and acc_coa_id =' . $id));
            $beginingBalance = AccCoaDet::model()->beginingBalance(date('Y-m-d', strtotime('-1 day', strtotime($start))), $id);
            Yii::app()->request->sendFile('GeneralLedger-' . date('dmY') . '.xls', $this->renderPartial('_generalLedgerDetail', array('acc' => $acc, 'beginingBalance' => $beginingBalance, 'accCoaDet' => $accCoaDet, 'start' => $start, 'end' => $end,'checked' => $checked),true));
        } else {
            $children = $acc->descendants()->findAll();
            foreach ($children as $val) {
                $accCoaDet = AccCoaDet::model()->findAll(array('order' => 'date_coa , id', 'condition' => '(date_coa >="' . $start . '" and date_coa <="' . $end . '") and acc_coa_id =' . $val->id));
                $beginingBalance = AccCoaDet::model()->beginingBalance(date('Y-m-d', strtotime('-1 day', strtotime($start))), $id);
                Yii::app()->request->sendFile('GeneralLedger-' . date('dmY') . '.xls', $this->renderPartial('_generalLedgerDetail', array('acc' => $val, 'beginingBalance' => $beginingBalance, 'accCoaDet' => $accCoaDet, 'start' => $start, 'end' => $end,'checked' => $checked),true));
            }
        }
    }

    public function actionJurnalUmum() {
        $this->cssTable();
        cs()->registerCss('', '
            @page { margin: 0.4cm;} 
                .tbPrint {width:100%}
                table.tbPrint td, table.tbPrint th{
                    border: solid #000 2px;
            }');
        $mCoaDet = new AccCoaDet();
        $this->render('jurnalUmum', array('mCoaDet' => $mCoaDet));
    }

    public function actionGenerateExcelJurnalUmum() {
        $_GET['xls'] = true;
        $a = explode('-', str_replace(".html", "", $_GET['created']));
        $start = date('Y/m/d', strtotime($a[0]));
        $end = date('Y/m/d', strtotime($a[1])) . " 23:59:59";
        logs($start);
        $accCoaDet = AccCoaDet::model()->findAll(array('condition' => '(date_coa>="' . $start . '" and date_coa<="' . $end . '")', 'order' => 'id,date_coa'));
        Yii::app()->request->sendFile('JurnalUmum-' . date('dmY') . '.xls', $this->renderPartial('_jurnalUmumResult', array(
                    'start' => $start,
                    'end' => $end,
                    'accCoaDet' => $accCoaDet,
                    'a' => $a,
                        ), true)
        );
    }

    public function actionkartuPiutang() {
        cs()->registerCss('', '
            @page { margin: 0.4cm;} 
                .tbPrint {width:100%}
                table.tbPrint td, table.tbPrint th{
                    border: solid #000 2px;
            }');
        $mCoaDet = new AccCoaDet();
        $this->render('kartuPiutang', array('mCoaDet' => $mCoaDet));
    }

    public function actionkartuHutang() {
        cs()->registerCss('', '
            @page { margin: 0.4cm;} 
                .tbPrint {width:100%}
                table.tbPrint td, table.tbPrint th{
                    border: solid #000 2px;
            }');
        $mCoaDet = new AccCoaDet();
        $this->render('kartuHutang', array('mCoaDet' => $mCoaDet));
    }

    public function actionkartuStock() {
        cs()->registerCss('', '
            @page { margin: 0.4cm;} 
                .tbPrint {width:100%}
                table.tbPrint td, table.tbPrint th{
                    border: solid #000 2px;
            }');
        $mCoaDet = new AccCoaDet();
        $this->render('kartuStock', array('mCoaDet' => $mCoaDet));
    }

    public function actionGenerateExcelKartuHutang() {
        $_GET['xls'] = true;
        $a = explode('-', $_GET['created']);
        $start = date('Y/m/d', strtotime($a[0]));
        $end = date('Y/m/d', strtotime($a[1]));
        $ap_id = $_GET['ap_id'];
        $type = str_replace(".html", "", $_GET['type']);
        logs($start);
        $accCoaSub = AccCoaSub::model()->findAll(array('order' => 'date_coa, id', 'condition' => '(date_coa >="' . date('Y-m-d', strtotime($start)) . '" and date_coa <="' . date('Y-m-d', strtotime($end)) . '") and ap_id =' . $ap_id));
        Yii::app()->request->sendFile('KartuHutang-' . date('dmY') . '.xls', $this->renderPartial('_kartuHutangResult', array(
                    'start' => $start,
                    'end' => $end,
                    'accCoaSub' => $accCoaSub,
                    'a' => $a,
                    'ap_id' => $ap_id,
                    'type' => $type
                        ), true)
        );
    }

    public function actionGenerateExcelKartuPiutang() {
        $_GET['xls'] = true;
        $a = explode('-', $_GET['created']);
        $start = date('Y/m/d', strtotime($a[0]));
        $end = date('Y/m/d', strtotime($a[1]));
        $ar_id = $_GET['ar_id'];
        $type = str_replace(".html", "", $_GET['type']);
        logs($start);
        $accCoaSub = AccCoaSub::model()->findAll(array('order' => 'date_coa, id', 'condition' => '(date_coa >="' . date('Y-m-d', strtotime($start)) . '" and date_coa <="' . date('Y-m-d', strtotime($end)) . '") and ar_id =' . $ar_id));
        Yii::app()->request->sendFile('KartuPiutang-' . date('dmY') . '.xls', $this->renderPartial('_kartuPiutangResult', array(
                    'start' => $start,
                    'end' => $end,
                    'accCoaSub' => $accCoaSub,
                    'a' => $a,
                    'ar_id' => $ar_id,
                    'type' => $type
                        ), true)
        );
    }

    public function actionGenerateExcelKartuStock() {
        $_GET['xls'] = true;
        $a = explode('-', $_GET['created']);
        $start = date('Y/m/d', strtotime($a[0]));
        $end = date('Y/m/d', strtotime($a[1]));
        $as_id = $_GET['as_id'];
        $type = str_replace(".html", "", $_GET['type']);
        logs($start);
        $accCoaSub = AccCoaSub::model()->findAll(array('order' => 'date_coa, id', 'condition' => '(date_coa >="' . date('Y-m-d', strtotime($start)) . '" and date_coa <="' . date('Y-m-d', strtotime($end)) . '") and as_id =' . $as_id));
        Yii::app()->request->sendFile('KartuStock-' . date('dmY') . '.xls', $this->renderPartial('_kartuStockResult', array(
                    'start' => $start,
                    'end' => $end,
                    'accCoaSub' => $accCoaSub,
                    'a' => $a,
                    'as_id' => $as_id,
                    'type' => $type
                        ), true)
        );
    }

    public function actionkasHarian() {
        cs()->registerCss('', '
            @page { margin: 0.4cm;} 
                .tbPrint {width:100%}
                table.tbPrint td, table.tbPrint th{
                    border: solid #000 2px;
            }');
        $mCoaDet = new AccCoaDet();
        $this->render('kasHarian', array('mCoaDet' => $mCoaDet));
    }

    public function actionGenerateExcelKasHarian() {
        $_GET['xls'] = true;
        $a = $_GET['created'];
        $akhir = date('Y-m-d', strtotime($a));
//        logs($_GET);
        $cash = str_replace(".html", "", $_GET['cash']);

        $cashout = AccCashOut::model()->findAll(array('condition' => 'date_posting ="' . $akhir . '" AND acc_coa_id="' . $cash . '" AND acc_approval_id is not NULL','order'=>'code_acc'));
        $cashin = AccCashIn::model()->findAll(array('condition' => 'date_posting ="' . $akhir . '" AND acc_coa_id="' . $cash . '" AND acc_approval_id is not NULL','order'=>'code_acc'));

        $idk = array();
        foreach ($cashout as $ua) {
            $idk[] = $ua->id;
        }
        $idk = (empty($idk)) ? array(0 => 0) : $idk;
        $cashoutdet = AccCashOutDet::model()->findAll(array('condition' => 'acc_cash_out_id IN (' . implode(',', $idk) . ')', 'with' => 'AccCashOut', 'order' => 'AccCashOut.code_acc'));

        $idd = array();
        foreach ($cashin as $ub) {
            $idd[] = $ub->id;
        }
        $idd = (empty($idd)) ? array(0 => 0) : $idd;
        $cashindet = AccCashInDet::model()->findAll(array('condition' => 'acc_cash_in_id IN (' . implode(',', $idd) . ')', 'with' => 'AccCashIn', 'order' => 'AccCashIn.code_acc'));
        app()->request->sendFile('KasHarian-' . date('dmY') . '.xls', $this->renderPartial('_kasHarianDetail', array('prefix'=>'-','idd' => $idd, 'idk' => $idk, 'cashout' => $cashout, 'cashin' => $cashin, 'a' => $a, 'akhir' => $akhir, 'cash' => $cash, 'cashindet' => $cashindet, 'cashoutdet' => $cashoutdet),true));
    }

    public function actionGenerateExcelNeraca() {
        $_GET['xls'] = true;
        $a = $_GET['created'];
        $viewType = $_GET['viewType'];
        $last = date('Y/m/d', strtotime($a)) . " 23:59:59";
        logs($last);
        $accCoa = AccCoa::model()->findAll(array('condition' => '`group`="aktiva" OR `group`="pasiva"'));
        Yii::app()->request->sendFile('Neraca-' . date('dmY') . '.xls', $this->renderPartial('_neracaResult', array(
                    'a' => $a,
                    'last' => $last,
                    'accCoa' => $accCoa,
                    'viewType' => $viewType
                        ), true)
        );
    }

    public function actionRekapPiutang() {
        $this->cssTable();
        cs()->registerCss('', '
            @page { margin: 0.4cm;} 
                .tbPrint {width:100%}
                table.tbPrint td, table.tbPrint th{
                    border: solid #000 2px;
            }');
        $mCoaSub = new AccCoaSub();
        $this->render('rekapPiutang', array('mCoaSub' => $mCoaSub));
    }

    public function actionRekapHutang() {
        $this->cssTable();
        cs()->registerCss('', '
            @page { margin: 0.4cm;} 
                .tbPrint {width:100%}
                table.tbPrint td, table.tbPrint th{
                    border: solid #000 2px;
            }');
        $mCoaSub = new AccCoaSub();
        $this->render('rekapHutang', array('mCoaSub' => $mCoaSub));
    }

    public function actionRekapStock() {
        cs()->registerCss('', '
            @page { margin: 0.4cm;} 
                .tbPrint {width:100%}
                table.tbPrint td, table.tbPrint th{
                    border: solid #000 2px;
            }');
        $mCoaSub = new AccCoaSub();
        $this->render('rekapStock', array('mCoaSub' => $mCoaSub));
    }

    public function actionExcelRekapPiutang() {
        $_GET['xls'] = true;
        $a = explode('-', $_GET['created']);
        $start = date('Y-m-d', strtotime($a[0]));
        $end = date('Y-m-d', strtotime($a[1]));
        $supplier = User::model()->listUsers('customer');
        $type = $_GET['type'];

        Yii::app()->request->sendFile('RekapPiutang-' . date('dmY') . '.xls', $this->renderPartial('_rekapPiutangResult', array(
                    'a' => $a,
                    'supplier' => $supplier,
                    'start' => $start,
                    'end' => $end,
                    'type' => $type
                        ), true)
        );
    }
    
    public function cssTable() {
        cs()->registerCss('','
            thead{display:table-header-group;}
                ');
    }

}