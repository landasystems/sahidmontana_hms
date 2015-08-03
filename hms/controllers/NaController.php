<?php

class NaController extends Controller {

    

    /**
     * @var string the default layoutac for the views. Defaults to '//layouts/column2', meaning
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
                'expression' => 'app()->controller->isValidAccess("NightAudit","c")'
            ),
            array('allow', // r
                'actions' => array('index', 'view'),
                'expression' => 'app()->controller->isValidAccess("NightAudit","r")'
            ),
            array('allow', // u
                'actions' => array('update'),
                'expression' => 'app()->controller->isValidAccess("NightAudit","u")'
            ),
            array('allow', // d
                'actions' => array('delete'),
                'expression' => 'app()->controller->isValidAccess("NightAudit","d")'
            )
        );
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id) {
        cs()->registerScript('', '$("#myTab a").click(function(e) {
                    e.preventDefault();
                    $(this).tab("show");
                });  '
        );
        $this->layout = 'mainWide';
        $this->render('view', array(
            'model' => $this->loadModel($id),
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {
        $siteConfig = SiteConfig::model()->findByPk(1);
        $cekForecast = Forecast::model()->find(array('condition' => 'tahun="' . date("Y") . '"'));
        $room_number = '';
        $no = 0;
        $warning = '';
        $roomUser = Room::model()->findAll(array('condition' => 'status="occupied" or status="house use" or status="compliment"', 'order' => 'number'));
        foreach ($roomUser as $val) {
            $max = RoomBill::model()->find(array('order' => 'date_bill DESC', 'condition' => 'room_id=' . $val->id . ' and registration_id=' . $val->registration_id));
            if (!empty($max)) {
                if (date("Y-m-d", strtotime('+1 day', strtotime($max->date_bill))) == $siteConfig->date_system) {
                    $no++;
                    $room_number .= $no . '. ' . $val->Registration->Guest->name . ' (' . $val->number . ')<br>';
                }
            } else {
                logs('room_id=' . $val->id . ' and registration_id=' . $val->registration_id);
            }
        }

        if (empty($cekForecast)) {
            $this->layout = 'mainWide';
            $this->render('create', array(
                'warning' => '<div class="alert alert-danger fade in">   
                <button type="button" class="close" data-dismiss="alert">×</button>
                <strong>Important! </strong> &nbsp;&nbsp; Please insert <b>Forecast</b> before <b>Night Audit</b>.    
            </div>',
            ));
        } else {
            $emptyAccount = Account::model()->findAll(array('condition' => ' acc_coa_id is NULL or acc_coa_id = 0 '));
            if (!empty($emptyAccount)) {
                $warning .= '<div class="alert alert-danger fade in">   
                            <button type="button" class="close" data-dismiss="alert">×</button>';
                $warning .= 'Please select account for : <br>';
                foreach ($emptyAccount as $account) {
                    $warning .= '<b>' . $account->name . '</b><br>';
                }
                $warning .= 'In menu <b>Account</b></div>';
            }

            if ($siteConfig->acc_cash_id == NULL or $siteConfig->acc_cash_id == 0) {
                $warning .= '<div class="alert alert-danger fade in">   
                            <button type="button" class="close" data-dismiss="alert">×</button>';
                $warning .= 'Please select account for <b>Cash</b> in menu <b>Settings</b> -> <b>Site Config</b> -> <b>Accounting</b></div>';
            }

            if ($siteConfig->acc_city_ledger_id == NULL or $siteConfig->acc_city_ledger_id == 0) {
                $warning .= '<div class="alert alert-danger fade in">   
                            <button type="button" class="close" data-dismiss="alert">×</button>';
                $warning .= 'Please select account for <b>City Ledger</b> in menu <b>Settings</b> -> <b>Site Config</b> -> <b>Accounting</b></div>';
            }

            if ($siteConfig->acc_service_charge_id == NULL or $siteConfig->acc_service_charge_id == 0) {
                $warning .= '<div class="alert alert-danger fade in">   
                            <button type="button" class="close" data-dismiss="alert">×</button>';
                $warning .= 'Please select account for <b>Service Charge</b> in menu <b>Settings</b> -> <b>Site Config</b> -> <b>Accounting</b></div>';
            }

            if ($siteConfig->acc_tax_id == NULL or $siteConfig->acc_tax_id == 0) {
                $warning .= '<div class="alert alert-danger fade in">   
                            <button type="button" class="close" data-dismiss="alert">×</button>';
                $warning .= 'Please select account for <b>Tax</b> in menu <b>Settings</b> -> <b>Site Config</b> -> <b>Accounting</b></div>';
            }

            if ($siteConfig->acc_clearance_id == NULL or $siteConfig->acc_clearance_id == 0) {
                $warning .= '<div class="alert alert-danger fade in">   
                            <button type="button" class="close" data-dismiss="alert">×</button>';
                $warning .= 'Please select account for <b>Clearence</b> in menu <b>Settings</b> -> <b>Site Config</b> -> <b>Accounting</b></div>';
            }

            if (!empty($room_number)) {
                $warning .= '<div class="alert alert-danger fade in">   
                            <button type="button" class="close" data-dismiss="alert">×</button>';
                $warning .= 'Please <b>checkout</b> or <b>extend</b> for :<br><b>' . $room_number . '</b>';
                $warning .= '</div>';
            }
            $model = new Na;
            cs()->registerScript('', '$("#myTab a").click(function(e) {
                    e.preventDefault();
                    $(this).tab("show");
                });  '
            );

            if (isset($_POST['Na'])) {
                $siteConfig = SiteConfig::model()->findByPk(1);
                $settings = json_decode($siteConfig->settings, true);
                $rateDolar = (!empty($settings['rate'])) ? $settings['rate'] : 0;
                $model->attributes = $_POST['Na'];
                $model->weather = $_POST['weather'];
                $model->event = $_POST['event'];
                $model->rate_dollar = $rateDolar;
                $model->date_na = $siteConfig->date_system;
                if ($model->save()) {

                    $roomBills = RoomBill::model()->findAll(array('condition' => '(date_bill<="' . $siteConfig->date_system . '" and is_checkedout=0 and t.is_na=0) or (date_format(Bill.created,"%Y-%m-%d")="' . $siteConfig->date_system . '")', 'order' => 'registration_id', 'index' => 'id', 'with' => array('BillDet', 'BillDet.Bill')));
                    $bill = Bill::model()->findAll(array('condition' => 'is_na=0', 'index' => 'id'));
                    $billCharge = BillCharge::model()->findAll(array('condition' => 'is_na=0 and is_temp=0', 'index' => 'id'));
                    $deposite = Deposite::model()->findAll(array('condition' => 'is_na=0', 'index' => 'id'));
                    $deposite_unapplied = Deposite::model()->findAll(array('condition' => 'is_used=0', 'index' => 'id'));
                    $deposite_unused = Deposite::model()->findAll(array('condition' => 'is_applied=0', 'index' => 'id'));
                    $expectedArrival = Reservation::model()->findAll(array('condition' => 'date_from="' . date("Y-m-d", strtotime('+1 days', strtotime($siteConfig->date_system))) . '"'));
                    $expectedDeparture = RoomBill::model()->findAll(array('select' => '*,max(date_bill)', 'group' => 'room_id', 'condition' => 'is_checkedout=0', 'order' => 'registration_id', 'index' => 'id', 'having' => 'max(date_bill)="' . date("Y-m-d", strtotime($siteConfig->date_system)) . '"'));

                    //mencari kamar yang rusak / out of order
                    $outOfOrder = Room::model()->findAll(array('condition' => 'status="out of order"'));
//                    $account = Account::model()->findAll();
                    $newCityLedger = array();
                    
//                    //simpan ke jurnal
//                    $cash = $_POST['cash'];
//                    $cityLedger = $_POST['grossSales'] - $_POST['cash'];
//                    $total = $_POST['grossSales'] + $_POST['cash'];
//
//                    $jurnal = new AccJurnal;
//                    $jurnal->code = 'NA' . date('m-d-y', strtotime($siteConfig->date_system)); //SiteConfig::model()->formatting('jurnal', FALSE);
//                    $jurnal->code_acc = 'NA' . date('m-d-y', strtotime($siteConfig->date_system));
//                    //SiteConfig::model()->formatting('jurnal_acc', False, '', '', $siteConfig->date_system);
//                    $jurnal->date_trans = $siteConfig->date_system;
//                    $jurnal->date_posting = $siteConfig->date_system;
//                    $jurnal->description = 'NA pada tanggal ' . $siteConfig->date_system;
//                    $jurnal->total_debet = $total;
//                    $jurnal->total_credit = $total;
//                    $jurnal->save();
//
//                    //simpan debet cash ke jurnal
//                    $jurnalDet = new AccJurnalDet;
//                    $jurnalDet->acc_jurnal_id = $jurnal->id;
//                    $jurnalDet->acc_coa_id = $siteConfig->acc_cash_id;
//                    $jurnalDet->debet = $cash;
//                    $jurnalDet->description = 'NA pada tanggal ' . $siteConfig->date_system;
//                    $jurnalDet->save();
//
//                    //simpan debet city ledger ke jurnal
//                    $jurnalDet = new AccJurnalDet;
//                    $jurnalDet->acc_jurnal_id = $jurnal->id;
//                    $jurnalDet->acc_coa_id = $siteConfig->acc_city_ledger_id;
//                    $jurnalDet->debet = $cityLedger;
//                    $jurnalDet->description = 'NA pada tanggal ' . $siteConfig->date_system;
//                    $jurnalDet->save();
//
//                    //simpan kredit account ke jurnal
//                    foreach ($account as $acc) {
//                        if ($_POST['todayNet'][$acc->id] > 0) {
//                            $jurnalDet = new AccJurnalDet;
//                            $jurnalDet->acc_jurnal_id = $jurnal->id;
//                            $jurnalDet->acc_coa_id = $acc->acc_coa_id;
//                            $jurnalDet->credit = $_POST['todayNet'][$acc->id];
//                            $jurnalDet->description = 'NA pada tanggal ' . $siteConfig->date_system;
//                            $jurnalDet->save();
//
//                            $credit[] = (object) array("id" => $jurnal->id, "acc_coa_id" => $acc->id, "date_trans" => $siteConfig->date_system, "description" => "NA pada tanggal '.$siteConfig->date_system.'", "total" => $_POST['todayNet'][$acc->id], "code" => $jurnal->code, "reff_type" => "jurnal");
//                        }
//                    }
//
//                    //simpan kredit tax ke jurnal
//                    $jurnalDet = new AccJurnalDet;
//                    $jurnalDet->acc_jurnal_id = $jurnal->id;
//                    $jurnalDet->acc_coa_id = $siteConfig->acc_tax_id;
//                    $jurnalDet->credit = $_POST['tax'];
//                    $jurnalDet->description = 'NA pada tanggal ' . $siteConfig->date_system;
//                    $jurnalDet->save();
//
//                    //simpan kredit service charge ke jurnal
//                    $jurnalDet = new AccJurnalDet;
//                    $jurnalDet->acc_jurnal_id = $jurnal->id;
//                    $jurnalDet->acc_coa_id = $siteConfig->acc_service_charge_id;
//                    $jurnalDet->credit = $_POST['servisDay'];
//                    $jurnalDet->description = 'NA pada tanggal ' . $siteConfig->date_system;
//                    $jurnalDet->save();
//
//                    //simpan debet clearance ke jurnal
//                    $jurnalDet = new AccJurnalDet;
//                    $jurnalDet->acc_jurnal_id = $jurnal->id;
//                    $jurnalDet->acc_coa_id = $siteConfig->acc_clearance_id;
//                    $jurnalDet->debet = $cash;
//                    $jurnalDet->description = 'NA pada tanggal ' . $siteConfig->date_system;
//                    $jurnalDet->save();
//
//                    //simpan kredit city ledger ke jurnal
//                    $jurnalDet = new AccJurnalDet;
//                    $jurnalDet->acc_jurnal_id = $jurnal->id;
//                    $jurnalDet->acc_coa_id = $siteConfig->acc_city_ledger_id;
//                    $jurnalDet->credit = $cash;
//                    $jurnalDet->description = 'NA pada tanggal ' . $siteConfig->date_system;
//                    $jurnalDet->save();
//
//                    //simpan ke acc det
//                    // credit servis
//                    $credit[] = (object) array("id" => $jurnal->id, "acc_coa_id" => $siteConfig->acc_service_charge_id, "date_trans" => $siteConfig->date_system, "description" => "NA pada tanggal '.$siteConfig->date_system.'", "total" => $_POST['servisDay'], "code" => $jurnal->code, "reff_type" => "jurnal");
//                    // credit tax
//                    $credit[] = (object) array("id" => $jurnal->id, "acc_coa_id" => $siteConfig->acc_tax_id, "date_trans" => $siteConfig->date_system, "description" => "NA pada tanggal '.$siteConfig->date_system.'", "total" => $_POST['tax'], "code" => $jurnal->code, "reff_type" => "jurnal");
//
//                    // debet cash
//                    $debet[] = (object) array("id" => $jurnal->id, "acc_coa_id" => $siteConfig->acc_cash_id, "date_trans" => $siteConfig->date_system, "description" => "NA pada tanggal '.$siteConfig->date_system.'", "total" => $_POST['cash'], "code" => $jurnal->code, "reff_type" => "jurnal");
//                    // debet cityledger
//                    $debet[] = (object) array("id" => $jurnal->id, "acc_coa_id" => $siteConfig->acc_city_ledger_id, "date_trans" => $siteConfig->date_system, "description" => "NA pada tanggal '.$siteConfig->date_system.'", "total" => ($_POST['grossSales'] - $_POST['cash']), "code" => $jurnal->code, "reff_type" => "jurnal");
//
//                    AccCoa::model()->trans($debet, $credit);
//
//                    unset($credit);
//                    unset($debet);
//
//                    // credit city ledger
//                    $credit[] = (object) array("id" => $jurnal->id, "acc_coa_id" => $siteConfig->acc_city_ledger_id, "date_trans" => $siteConfig->date_system, "description" => "NA pada tanggal '.$siteConfig->date_system.'", "total" => $_POST['cash'], "code" => $jurnal->code, "reff_type" => "jurnal");
//                    // debet clearance
//                    $debet[] = (object) array("id" => $jurnal->id, "acc_coa_id" => $siteConfig->acc_clearance_id, "date_trans" => $siteConfig->date_system, "description" => "NA pada tanggal '.$siteConfig->date_system.'", "total" => $_POST['cash'], "code" => $jurnal->code, "reff_type" => "jurnal");
//
//                    AccCoa::model()->trans($debet, $credit);


                    //simpan out of order di room schedule
                    foreach ($outOfOrder as $a) {
                        $roomSchedule = new RoomSchedule;
                        $roomSchedule->room_id = $a->id;
                        $roomSchedule->date_schedule = $siteConfig->date_system;
                        $roomSchedule->status = "out of order";
                        $roomSchedule->save();
                    }
                    //save detail
                    foreach ($roomBills as $data) {
                        $naDet = new NaDet;
                        $naDet->na_id = $model->id;
                        $naDet->room_bill_id = $data->id;
                        $naDet->is_checkedout = $data->is_checkedout;
                        $naDet->registration_id = $data->registration_id;
                        $naDet->save();
                    }
                    foreach ($bill as $data) {
                        $naDet = new NaDet;
                        $naDet->na_id = $model->id;
                        $naDet->bill_id = $data->id;
                        $naDet->save();
                        if ($data->ca_user_id != NULL and $data->ca_user_id > 0) {
                            $newCityLedger[] = (object) array('payment' => $data->ca_charge, 'user_id' => $data->ca_user_id, 'date' => $model->date_na);
                        }
                    }

                    foreach ($billCharge as $data) {
                        $naDet = new NaDet;
                        $naDet->na_id = $model->id;
                        $naDet->bill_charge_id = $data->id;
                        $naDet->save();
                        if ($data->ca_user_id != NULL and $data->ca_user_id > 0) {
                            $newCityLedger[] = (object) array('payment' => $data->ca_charge, 'user_id' => $data->ca_user_id, 'date' => $model->date_na);
                        }
                    }

                    foreach ($deposite as $data) {
                        $naDet = new NaDet;
                        $naDet->na_id = $model->id;
                        $naDet->deposite_id = $data->id;
                        $naDet->save();
                    }
                    //save deposite not applied
                    foreach ($deposite_unapplied as $data) {
                        $naDet = new NaDpNotApplied();
                        $naDet->na_id = $model->id;
                        $naDet->deposite_id = $data->id;
                        $naDet->save();
                    }

                    //save accounting city ledger
//                    InvoiceDet::model()->saveCityLedger($newCityLedger);

                    //save deposite applied
//                foreach ($deposite_used as $data) {
//                    $naDet = new NaDpApplied();
//                    $naDet->na_id = $model->id;
//                    $naDet->deposite_id = $data->id;
//                    $naDet->save();
//                }
                    //save guest ledger balance

                    if (isset($_POST['NaGl'])) {
                        for ($i = 0; $i < count($_POST['NaGl']['registration_id']); $i++) {
                            $NaGl = new NaGl();
                            $NaGl->na_id = $model->id;
                            $NaGl->registration_id = $_POST['NaGl']['registration_id'][$i];
                            $NaGl->bill_id = $_POST['NaGl']['bill_id'][$i];
                            $NaGl->room_number = $_POST['NaGl']['room_number'][$i];
                            $NaGl->guest_user_id = $_POST['NaGl']['guest_user_id'][$i];
                            $NaGl->prev = $_POST['NaGl']['prev'][$i];
                            $NaGl->charge = $_POST['NaGl']['charge'][$i];
                            $NaGl->deposite = $_POST['NaGl']['deposite'][$i];
                            $NaGl->tunai = $_POST['NaGl']['tunai'][$i];
                            $NaGl->creditcard = $_POST['NaGl']['creditcard'][$i];
                            $NaGl->cityledger = $_POST['NaGl']['cityledger'][$i];
                            $NaGl->refund = $_POST['NaGl']['refund'][$i];
                            $NaGl->balance = $_POST['NaGl']['balance'][$i];
                            $NaGl->save();
                        }
                    }

                    //save expected arrival
                    foreach ($expectedArrival as $arrival) {
                        $mArrival = new NaExpectedArrival();
                        $mArrival->na_id = $model->id;
                        $mArrival->reservation_id = $arrival->id;
                        $mArrival->save();
                    }

                    //save expected departure
                    foreach ($expectedDeparture as $departure) {
                        $mDeparture = new NaExpectedDeparture();
                        $mDeparture->na_id = $model->id;
                        $mDeparture->room_bill_id = $departure->id;
                        $mDeparture->save();
                    }

                    //save na_dsr
                    $account = Account::model()->findAll();
                    $taxService = array();
                    foreach ($account as $acc) {
                        $taxService[$acc->id] = array('tax' => $acc->tax, 'service' => $acc->service);
                    }

                    $na_dsr = new NaDsr();
                    $na_dsr->na_id = $model->id;
                    $na_dsr->today = json_encode($_POST['today']);
                    $na_dsr->mtd_actual = json_encode($_POST['mtd_actual']);
                    $na_dsr->mtd_forecast = json_encode($_POST['mtd_forecast']);
                    $na_dsr->mtd_last_month = json_encode($_POST['mtd_last_month']);
                    $na_dsr->ytd_actual = json_encode($_POST['ytd_actual']);
                    $na_dsr->ytd_forecast = json_encode($_POST['ytd_forecast']);
                    $na_dsr->tax_service = json_encode($taxService);
                    $na_dsr->save();

                    //save dsr statistic
                    $na_dsr = new NaStatistic();
                    $na_dsr->na_id = $model->id;
                    $na_dsr->today = json_encode($_POST['statistic_today']);
                    $na_dsr->mtd_actual = json_encode($_POST['statistic_mtd_actual']);
                    $na_dsr->mtd_forecast = json_encode($_POST['statistic_mtd_forecast']);
                    $na_dsr->mtd_last_month = json_encode($_POST['statistic_mtd_last_month']);
                    $na_dsr->ytd_actual = json_encode($_POST['statistic_ytd_actual']);
                    $na_dsr->ytd_forecast = json_encode($_POST['statistic_ytd_forecast']);
                    $na_dsr->save();

                    //save dsr food analys
                    $na_dsr = new NaFoodAnalys();
                    $na_dsr->na_id = $model->id;
                    $na_dsr->today = json_encode($_POST['food_analys_today']);
                    $na_dsr->mtd_actual = json_encode($_POST['food_analys_mtd_actual']);
                    $na_dsr->mtd_forecast = json_encode($_POST['food_analys_mtd_forecast']);
                    $na_dsr->mtd_last_month = json_encode($_POST['food_analys_mtd_last_month']);
                    $na_dsr->ytd_actual = json_encode($_POST['food_analys_ytd_actual']);
                    $na_dsr->ytd_forecast = json_encode($_POST['food_analys_ytd_forecast']);
                    $na_dsr->save();

                    //save dsr food percover
                    $na_dsr = new NaFoodpercoverAnalys();
                    $na_dsr->na_id = $model->id;
                    $na_dsr->today = json_encode($_POST['food_percover_today']);
                    $na_dsr->mtd_actual = json_encode($_POST['food_percover_mtd_actual']);
                    $na_dsr->mtd_forecast = json_encode($_POST['food_percover_mtd_forecast']);
                    $na_dsr->mtd_last_month = json_encode($_POST['food_percover_mtd_last_month']);
                    $na_dsr->ytd_actual = json_encode($_POST['food_percover_ytd_actual']);
                    $na_dsr->ytd_forecast = json_encode($_POST['food_percover_ytd_forecast']);
                    $na_dsr->save();

                    //save dsr beverage analys
                    $na_dsr = new NaBeverageAnalys();
                    $na_dsr->na_id = $model->id;
                    $na_dsr->today = json_encode($_POST['beverage_analys_today']);
                    $na_dsr->mtd_actual = json_encode($_POST['beverage_analys_mtd_actual']);
                    $na_dsr->mtd_forecast = json_encode($_POST['beverage_analys_mtd_forecast']);
                    $na_dsr->mtd_last_month = json_encode($_POST['beverage_analys_mtd_last_month']);
                    $na_dsr->ytd_actual = json_encode($_POST['beverage_analys_ytd_actual']);
                    $na_dsr->ytd_forecast = json_encode($_POST['beverage_analys_ytd_forecast']);
                    $na_dsr->save();

                    //save product sold
                    $sold = (isset($_POST['productSold'])) ? json_encode($_POST['productSold']) : json_encode(array());
                    $na_product_sold = new NaProductSold;
                    $na_product_sold->na_id = $model->id;
                    $na_product_sold->product_sold = $sold;
                    $na_product_sold->save();

                    //----------menyimpan otomatis cashier sheet setiap user yang ada transaksinya
                    $bills_arr = Bill::model()->findAll(array('condition' => 'is_cashier=0'));
                    $deposit_arr = Deposite::model()->findAll(array('condition' => 'is_cashier=0'));
                    $transaction_arr = BillCharge::model()->findAll(array('condition' => 'is_cashier=0 AND is_temp=0'));
                    $tempBillCashier = array();
                    foreach ($bills_arr as $bill) {
                        if (!isset($tempBillCashier[$bill->Cashier->id])) {
                            $mCashier = new BillCashier;
                            $mCashier->created = $siteConfig->date_system;
                            $mCashier->created_user_id = $bill->Cashier->id;
                            $mCashier->save();
                            $tempBillCashier[$bill->Cashier->id] = $mCashier->id;
                        }

                        $cashierDet = new BillCashierDet;
                        $cashierDet->bill_cashier_id = $tempBillCashier[$bill->Cashier->id];
                        $cashierDet->bill_id = $bill->id;
                        $cashierDet->save();
                    }

                    foreach ($deposit_arr as $dp) {
                        if (!isset($tempBillCashier[$dp->Cashier->id])) {
                            $mCashier = new BillCashier;
                            $mCashier->created = $siteConfig->date_system;
                            $mCashier->created_user_id = $dp->Cashier->id;
                            $mCashier->save();

                            $tempBillCashier[$dp->Cashier->id] = $mCashier->id;
                        }

                        $cashierDet = new BillCashierDet;
                        $cashierDet->bill_cashier_id = $tempBillCashier[$dp->Cashier->id];
                        $cashierDet->deposite_id = $dp->id;
                        $cashierDet->save();
                    }

                    foreach ($transaction_arr as $t) {
                        if (!isset($tempBillCashier[$t->Cashier->id])) {
                            $mCashier = new BillCashier;
                            $mCashier->created = $siteConfig->date_system;
                            $mCashier->created_user_id = $t->Cashier->id;
                            $mCashier->save();

                            $tempBillCashier[$t->Cashier->id] = $mCashier->id;
                        }

                        $cashierDet = new BillCashierDet;
                        $cashierDet->bill_cashier_id = $tempBillCashier[$t->Cashier->id];
                        $cashierDet->bill_charge_id = $t->id;
                        $cashierDet->save();
                    }
                    //-------------END AUTO CASHIER----------------------
                    //update is_na and processed
                    RoomBill::model()->updateAll(array('is_na' => 1, 'processed' => 1, 'na_id' => $model->id), 'date_bill="' . $siteConfig->date_system . '" and is_checkedout=0');
                    BillCharge::model()->updateAll(array('is_cashier' => 1, 'is_na' => 1, 'na_id' => $model->id), 'is_temp = 0 and is_na=0');
                    Bill::model()->updateAll(array('is_cashier' => 1, 'is_na' => 1, 'na_id' => $model->id), 'is_na=0');
                    Deposite::model()->updateAll(array('is_cashier' => 1, 'is_na' => 1, 'na_id' => $model->id), 'is_na=0');
                    Registration::model()->updateAll(array('is_na' => 1, 'na_id' => $model->id), 'is_na=0');

                    //update date_system + 1
                    $siteConfig->date_system = date("Y-m-d", strtotime('+1 day', strtotime($siteConfig->date_system)));
                    $siteConfig->save();

                    //simpan report geographical
                    $lastNa = Na::model()->find(array('order' => 'id DESC'));
                    $reportGeo = ReportGeographical::model()->insertGeoGraphical($lastNa->id, $lastNa->date_na);

                    //update processed roombill where date_bill = date_system siteconfig
                    //RoomBill::model()->updateAll(array('processed' => 1), 'date_bill="' . date("Y-m-d", strtotime($siteConfig->date_system)) . '"');
                    $this->redirect(array('view', 'id' => $model->id));
                }
            }
            $this->layout = 'mainWide';
            $this->render('create', array(
                'model' => $model,
                'warning' => $warning,
            ));
        }
    }

    public function actionGetTransactionBalance() {
        $return = array();
        $return['transaction'] = $this->renderPartial('_viewTransaction', array('date' => $_POST['date'],), true);
        $return['departement'] = $this->renderPartial('_viewTransactionByDepartement', array('date' => $_POST['date'],), true);
        $return['dp'] = $this->renderPartial('_viewDp', array('date' => $_POST['date'],), true);
        $return['chekcout'] = $this->renderPartial('_viewGP', array('date' => $_POST['date'],), true);
        $return['cl'] = $this->renderPartial('_viewCL', array('date' => $_POST['date'],), true);
        $return['guestLedger'] = $this->renderPartial('_viewGuestLedgerByRoom', array('date' => $_POST['date'],), true);
        echo json_encode($return);
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

        if (isset($_POST['Na'])) {
            $model->attributes = $_POST['Na'];
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
        $criteria = new CDbCriteria();

        $model = new Na('search');
        $model->unsetAttributes();  // clear any default values

        if (isset($_GET['Na'])) {
            $model->attributes = $_GET['Na'];



            if (!empty($model->id))
                $criteria->addCondition('id = "' . $model->id . '"');


            if (!empty($model->date_na))
                $criteria->addCondition('date_na = "' . $model->date_na . '"');


            if (!empty($model->global_by_cash))
                $criteria->addCondition('global_by_cash = "' . $model->global_by_cash . '"');


            if (!empty($model->global_by_cc))
                $criteria->addCondition('global_by_cc = "' . $model->global_by_cc . '"');


            if (!empty($model->global_by_gl))
                $criteria->addCondition('global_by_gl = "' . $model->global_by_gl . '"');


            if (!empty($model->global_by_cl))
                $criteria->addCondition('global_by_cl = "' . $model->global_by_cl . '"');


            if (!empty($model->created))
                $criteria->addCondition('created = "' . $model->created . '"');


            if (!empty($model->created_user_id))
                $criteria->addCondition('created_user_id = "' . $model->created_user_id . '"');


            if (!empty($model->modified))
                $criteria->addCondition('modified = "' . $model->modified . '"');
        }

        $this->render('index', array(
            'model' => $model,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new Na('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Na']))
            $model->attributes = $_GET['Na'];

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
        $model = Na::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'na-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
