<?php

class ReportController extends Controller {

    public $breadcrumbs;
    public $layout = 'main';

    public function filters() {
        return array(
            'accessControl', // perform access control for CRUD operations
        );
    }

    public function accessRules() {
        return array(
            array('allow', // r
                'actions' => array('index', 'view'),
                'expression' => 'app()->controller->isValidAccess("Report_Arr/Dep","r")'
            ),
            array('allow', // r
                'actions' => array('index', 'view'),
                'expression' => 'app()->controller->isValidAccess("Report_GuestInHouse","r")'
            ),
            array('allow', // r
                'actions' => array('index', 'view'),
                'expression' => 'app()->controller->isValidAccess("Report_ExpectedGuest","r")'
            ),
            array('allow', // r
                'actions' => array('index', 'view'),
                'expression' => 'app()->controller->isValidAccess("Report_RoomSales","r")'
            ),
            array('allow', // r
                'actions' => array('index', 'view'),
                'expression' => 'app()->controller->isValidAccess("Report_GuestHistory","r")'
            ),
            array('allow', // r
                'actions' => array('productSold', 'view'),
                'expression' => 'app()->controller->isValidAccess("Report_ProductSold","r")'
            ),
        );
    }

    public function actionGuesthouse() {
        $this->layout = 'mainWide';
        $mRes = new Reservation();
//        $mRoom = new Room();
        if (!empty($_POST['Reservation']['date_from'])) {
            $mRes->attributes = $_POST['Reservation'];
        }

        $this->render('guesthouse', array('mRes' => $mRes));
    }

    public function actionExpected() {
        $mExpec = new Reservation();
        if (!empty($_POST['Reservation']['date_from'])) {
            $mExpec->attributes = $_POST['Reservation'];
        }

        $this->render('expected', array('mExpec' => $mExpec));
    }

    public function actionDaily() {
        $model = new BillCharge();
        if (!empty($_POST['BillCharge']['created'])) {
            $model->attributes = $_POST['BillCharge'];
        }
        $this->render('dailyFoodPercover', array('model' => $model));
    }

    public function actionProductSold() {
        $model = new BillCharge();
        if (!empty($_POST['BillCharge']['created'])) {
            $model->attributes = $_POST['BillCharge'];
        }
        $this->render('productSold', array('model' => $model));
    }

    public function actionArrivdepar() {
        $mArr = new Reservation();
        $mDepar = new Registration();
        if (!empty($_POST['Reservation']['date_from'])) {
            $mArr->attributes = $_POST['Reservation'];
        }
        if (!empty($_POST['Registration']['date_from'])) {
            $mDepar->attributes = $_POST['Registration'];
        }

        $this->render('arrivdepar', array('mArr' => $mArr, 'mDepar' => $mDepar));
    }

    //**********************MODULE ACCOUNTING************************************
    public function actionGenerateExcelSalary() {
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
//        $mProcess = new WorkorderProcess();
//        if (isset($_POST['WorkorderProcess']['time_end'])) {
//            $mProcess->attributes = $_POST['WorkorderProcess'];
//        }
        $mCoaDet = new AccCoaDet();
        $this->render('generalLedger', array('mCoaDet' => $mCoaDet));
    }

    public function actionNeraca() {
//        $mProcess = new WorkorderProcess();
//        if (isset($_POST['WorkorderProcess']['time_end'])) {
//            $mProcess->attributes = $_POST['WorkorderProcess'];
//        }
        $mCoaDet = new AccCoaDet();
        $this->render('neraca', array('mCoaDet' => $mCoaDet));
    }

    public function actionNeracaSaldo() {
//        $mProcess = new WorkorderProcess();
//        if (isset($_POST['WorkorderProcess']['time_end'])) {
//            $mProcess->attributes = $_POST['WorkorderProcess'];
//        }
        $mCoaDet = new AccCoaDet();
        $this->render('neracaSaldo', array('mCoaDet' => $mCoaDet));
    }

    public function actionlabaRugi() {
        $mCoa = new AccCoa();
        if (isset($_POST['AccCoa']['created'])) {
            $mCoa->attributes = $_POST['AccCoa'];
        }
        $this->render('labaRugi', array('mCoa' => $mCoa));
    }

    public function actionGenerateExcelLabaRugi() {
        $a = explode('-', str_replace(".html", "", $_GET['created']));
        $start = date('Y/m/d', strtotime($a[0]));
        $end = date('Y/m/d', strtotime($a[1])) . " 23:59:59";
        logs($start);
        $model = AccCoaDet::model()->findAll(array('condition' => '((created <="' . $start . '" and created >="' . $end . '")AND created_user_id is not null AND created_user_id != "" ) '));
        $accCoare = AccCoa::model()->findAll(array('order' => 'code', 'condition' => '`group` = "receivable"'));
        $accCoaco = AccCoa::model()->findAll(array('order' => 'code', 'condition' => '`group` = "cost"'));

        Yii::app()->request->sendFile('labarugi-' . date('dmY') . '.xls', $this->renderPartial('_excelLabaRugi', array(
                    'model' => $model,
                    'start' => $start,
                    'end' => $end,
                    'accCoare' => $accCoare,
                    'accCoaco' => $accCoaco,
                    'a' => $a
                        ), true)
        );
    }

    public function actionGenerateExcelNeracaSaldo() {
        $a = str_replace(".html", "", $_GET['created']);
        $last = date('Y/m/d', strtotime($a)) . " 23:59:59";
        logs($last);
        $accCoa = AccCoa::model()->findAll(array('order' => 'code', 'condition' => 'type = "detail"'));
        Yii::app()->request->sendFile('NeracaSaldo-' . date('dmY') . '.xls', $this->renderPartial('_excelNeracaSaldo', array(
                    'last' => $last,
                    'accCoa' => $accCoa
                        ), true)
        );
    }

    public function actionGenerateExcelGeneralLedger() {
        $a = explode('-', $_GET['created']);
        $start = date('Y/m/d', strtotime($a[0]));
        $end = date('Y/m/d', strtotime($a[1])) . " 23:59:59";
        $id = str_replace(".html", "", $_GET['id']);
        logs($start);
        $accCoaDet = AccCoaDet::model()->findAll(array('order' => 'date_coa , id', 'condition' => '(date_coa >="' . $start . '" and date_coa <="' . $end . '")and acc_coa_id=' . $id));
        Yii::app()->request->sendFile('GeneralLedger-' . date('dmY') . '.xls', $this->renderPartial('_excelGeneralLedger', array(
                    'start' => $start,
                    'end' => $end,
                    'accCoaDet' => $accCoaDet
                        ), true)
        );
    }

    public function actionJurnalUmum() {

        $mCoaDet = new AccCoaDet();
        $this->render('jurnalUmum', array('mCoaDet' => $mCoaDet));
    }

    public function actionGenerateExcelJurnalUmum() {
        $a = explode('-', str_replace(".html", "", $_GET['created']));
        $start = date('Y/m/d', strtotime($a[0]));
        $end = date('Y/m/d', strtotime($a[1])) . " 23:59:59";
        logs($start);
        $accCoaDet = AccCoaDet::model()->findAll(array('condition' => '(date_coa>="' . $start . '" and date_coa<="' . $end . '")', 'order' => 'id,date_coa'));
        Yii::app()->request->sendFile('JurnalUmum-' . date('dmY') . '.xls', $this->renderPartial('_excelJurnalUmum', array(
                    'start' => $start,
                    'end' => $end,
                    'accCoaDet' => $accCoaDet,
                    'a' => $a
                        ), true)
        );
    }

    public function actionkartuPiutang() {
        $mCoaSub = new AccCoaSub();
        $this->render('kartuPiutang', array('mCoaSub' => $mCoaSub));
    }

    public function actionkartuHutang() {
        $mCoaDet = new AccCoaSub();
        $this->render('kartuHutang', array('mCoaDet' => $mCoaDet));
    }

    public function actionkartuStock() {
        $mCoaSub = new AccCoaSub();
        $this->render('kartuStock', array('mCoaSub' => $mCoaSub));
    }

    public function actionGenerateExcelKartuHutang() {
        $a = explode('-', $_GET['created']);
        $start = date('Y/m/d', strtotime($a[0]));
        $end = date('Y/m/d', strtotime($a[1])) . " 23:59:59";
        $ap_id = str_replace(".html", "", $_GET['ap_id']);
        logs($start);
        $accCoaSub = AccCoaSub::model()->findAll(array('order' => 'date_coa , id', 'condition' => '(date_coa >="' . $start . '" and date_coa <="' . $end . '") and ap_id =' . $ap_id));
        Yii::app()->request->sendFile('KartuHutang-' . date('dmY') . '.xls', $this->renderPartial('_excelKartuHutang', array(
                    'start' => $start,
                    'end' => $end,
                    'accCoaSub' => $accCoaSub,
                    'a' => $a,
                    'ap_id' => $ap_id
                        ), true)
        );
    }

    public function actionGenerateExcelKartuPiutang() {
        $a = explode('-', $_GET['created']);
        $start = date('Y/m/d', strtotime($a[0]));
        $end = date('Y/m/d', strtotime($a[1])) . " 23:59:59";
        $ar_id = str_replace(".html", "", $_GET['ar_id']);
        logs($start);
        $accCoaSub = AccCoaSub::model()->findAll(array('order' => 'date_coa , id', 'condition' => '(date_coa >="' . $start . '" and date_coa <="' . $end . '") and ar_id =' . $ar_id));
        Yii::app()->request->sendFile('KartuHutang-' . date('dmY') . '.xls', $this->renderPartial('_excelKartuPiutang', array(
                    'start' => $start,
                    'end' => $end,
                    'accCoaSub' => $accCoaSub,
                    'a' => $a,
                    'ar_id' => $ar_id
                        ), true)
        );
    }

    public function actionGenerateExcelKartuStock() {
        $a = explode('-', $_GET['created']);
        $start = date('Y/m/d', strtotime($a[0]));
        $end = date('Y/m/d', strtotime($a[1])) . " 23:59:59";
        $as_id = str_replace(".html", "", $_GET['as_id']);
        logs($start);
        $accCoaSub = AccCoaSub::model()->findAll(array('order' => 'date_coa , id', 'condition' => '(date_coa >="' . $start . '" and date_coa <="' . $end . '") and as_id =' . $as_id));
        Yii::app()->request->sendFile('KartuStock-' . date('dmY') . '.xls', $this->renderPartial('_excelKartuStock', array(
                    'start' => $start,
                    'end' => $end,
                    'accCoaSub' => $accCoaSub,
                    'a' => $a,
                    'as_id' => $as_id
                        ), true)
        );
    }

    public function actionkasHarian() {

        $mCoaDet = new AccCoaDet();
        $this->render('kasHarian', array('mCoaDet' => $mCoaDet));
    }

    public function actionGenerateExcelKasHarian() {
        $a = $_GET['created'];
        $akhir = date('Y/m/d', strtotime($a));
        $cashIn = str_replace(".html", "", $_GET['acc_coa_id']);
        logs($akhir);
        $accCoaDet = AccCoaDet::model()->findAll(array('condition' => 'date_coa="' . $akhir . '" and acc_coa_id="' . $cashIn . '"'));
        Yii::app()->request->sendFile('KasHarian-' . date('dmY') . '.xls', $this->renderPartial('_excelKasHarian', array(
                    'accCoaDet' => $accCoaDet,
                    'a' => $a,
                    'akhir' => $akhir,
                    'cashIn' => $cashIn
                        )
                ), true);
    }

    //**********************END MODULE ACCOUNTING************************************

    public function actiongeographical() {
        $location = User::model()->find(array());
        $this->render('geographical', array('location' => $location));
    }

    public function actionsourceOfBusiness() {
        $this->layout = 'mainWide';
        $location = User::model()->find(array());
        $this->render('sourceOfBusiness', array('location' => $location));
    }

    public function actionTopProducers() {
        $this->layout = 'mainWide';
        $location = User::model()->find(array());

        $this->render('topProducers', array('location' => $location));
    }

    public function actionTopTen() {
        $this->layout = 'mainWide';
        $location = User::model()->find(array());
        $this->render('topTen', array('location' => $location));
    }

    public function actionViewResult() {
        $tanggal = date('Y-m-d', strtotime($_POST['created']));
        $this->renderPartial('_resultGeographical');
    }

    public function actionViewSob() {
        $month = array('Jan', 'Feb', 'March', 'April', 'May', 'June', 'July', 'Aug', 'Sept', 'Oct', 'Nov', 'Dec');

        echo '<h2 align="center">Source Of Business</h2>';
        echo '<h3 align="center">Month : ' . $month[$_POST['month'] - 1] . ' ' . $_POST['year'] . '</h3>';
        $this->renderPartial('_resultSourceOfBusiness');
    }

    public function actionViewTopProducers() {
        echo '<h2 align="center">TOP PRODUCERS ' . $_POST['year'] . '</h2>';
        $this->renderPartial('_resultTopProducers');
    }

    public function actionViewTopTen() {
        $month = array('Jan', 'Feb', 'March', 'April', 'May', 'June', 'July', 'Aug', 'Sept', 'Oct', 'Nov', 'Dec');
        echo '<h2 align="center">TOP TEN</h2>';
        echo '<h3 align="center">' . $month[$_POST['month'] - 1] . ' ' . $_POST['year'] . '</h3>';
        $this->renderPartial('_resultTopTen');
    }

}

?>
