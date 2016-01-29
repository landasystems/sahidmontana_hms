<?php
$category = ChargeAdditionalCategory::model()->findAll(array('condition' => 'level=1', 'index' => 'id'));
$account = Account::model()->findAll();
$category_all = ChargeAdditionalCategory::model()->findAll(array('index' => 'id'));
$additional = ChargeAdditional::model()->findAll(array('index' => 'id'));
$room = Room::model()->findAll(array('index' => 'id'));
$siteConfig = SiteConfig::model()->findByPk(1);
$roomBills = RoomBill::model()->findAll(array('order' => 't.id', 'condition' => '(is_checkedout=0 and t.is_na=0  and date_bill<="' . $siteConfig->date_system . '") or (date_format(Bill.created,"%Y-%m-%d")="' . $siteConfig->date_system . '")', 'order' => 'registration_id', 'index' => 'id', 'with' => array('BillDet', 'BillDet.Bill')));
$roomBills_By_Registration = RoomBill::model()->findAll(array('with' => array('Registration', 'Registration.Guest'), 'order' => 'Guest.id', 'condition' => '(is_checkedout=0 and t.is_na=0 and date_bill<="' . $siteConfig->date_system . '")', 'group' => 'registration_id', 'index' => 'id'));
$billCharge = BillCharge::model()->findAll(array('order' => 't.id', 'condition' => 'is_na=0 and is_temp=0', 'index' => 'id'));
$roomType = RoomType::model()->findAll(array('index' => 'id'));
$marketSegment = MarketSegment::model()->findAll(array('index' => 'id'));
$deposite = Deposite::model()->findAll(array('condition' => 'is_na=0', 'index' => 'id'));
$deposite_unapplied = Deposite::model()->findAll(array('condition' => 'is_used=0', 'index' => 'id'));
$deposite_unused = Deposite::model()->findAll(array('condition' => 'is_applied=0', 'index' => 'id'));
$bill = Bill::model()->findAll(array('condition' => 'is_na=0', 'index' => 'id'));
$expectedArrival = Reservation::model()->findAll(array('condition' => 'date_from="' . date("Y-m-d", strtotime('+1 days', strtotime($siteConfig->date_system))) . '"'));
$expectedDeparture = RoomBill::model()->findAll(array('select' => '*,max(date_bill)', 'group' => 'room_id', 'condition' => 'is_checkedout=0', 'order' => 'registration_id', 'index' => 'id', 'having' => 'max(date_bill)="' . date("Y-m-d", strtotime($siteConfig->date_system)) . '"'));
$forecast = Forecast::model()->findByAttributes(array('tahun' => date("Y", strtotime($siteConfig->date_system))));
$initialForecast = InitialForecast::model()->findByPk(1);
$roomTodayStatus = Room::model()->todayStatus();
//mencari numberofguest;
$numberOfGuest = 0;
foreach ($room as $val) {
    if ($val->status_housekeeping == 'occupied')
        $numberOfGuest += $val->pax;
}
$array = array(
    'forecast' => $forecast,
    'initialForecast' => $initialForecast,
    'room' => $room,
    'category' => $category,
    'account' => $account,
    'siteConfig' => $siteConfig,
    'marketSegment' => $marketSegment,
    'roomBills' => $roomBills,
    'billCharge' => $billCharge,
//    'billChargeAll' => $billChargeAll,
    'roomType' => $roomType,
    'category_all' => $category_all,
    'deposite' => $deposite,
    'deposite_unapplied' => $deposite_unapplied,
    'deposite_unused' => $deposite_unused,
//    'deposite_used' => $deposite_used,
    'bill' => $bill,
//    'billDet' => $billDet,
    'additional' => $additional,
//    'billChargeDet' => $billChargeDet,
    'roomBills_By_Registration' => $roomBills_By_Registration,
//    'roomBills_All' => $roomBills_All,
    'expectedArrival' => $expectedArrival,
    'expectedDeparture' => $expectedDeparture,
    'numberOfGuest' => $numberOfGuest,
    'roomTodayStatus' => $roomTodayStatus
);

function getRootName($id, $additional, $category, $category_all) {
    return $category[$category_all[$additional[$id]['charge_additional_category_id']]['root']]['name'];
}

function getRootId($id, $additional, $category, $category_all) {
    return $category_all[$additional[$id]['charge_additional_category_id']]['root'];
}
?>

<div class="form">
    <?php
    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id' => 'room-bill-form',
        'enableAjaxValidation' => false,
        'method' => 'post',
        'type' => 'horizontal',
        'htmlOptions' => array(
            'enctype' => 'multipart/form-data'
        )
    ));
    ?>
    <ul class="nav nav-tabs" id="myTab">
        <li class="active"><a href="#dsr">Daily Sales Report</a></li>                                                    
        <li><a href="#analysis">F&B Analysis</a></li>
        <li><a href="#cashier">Cashier Sheet</a></li>
        <!--<li><a href="#transaction">Global NA</a></li>-->              
        <li><a href="#acc">Trans. By Account</a></li>         
        <li><a href="#departement">Tran. By Outlet</a></li>                 
        <li><a href="#guestLedger">Guest Ledger Balance</a></li>                    
        <li><a href="#guestInHouse">Guest In House</a></li>   
        <!--<li><a href="#sob">Sources Of Business</a></li>-->   
        <li><a href="#guestExpected">Expected Guest</a></li>  
        <!--<li><a href="#checkedout">Checkedout</a></li>-->  
        <!--<li><a href="#cl">City Ledger</a></li>-->                
        <!--        <li><a href="#dp">Deposite</a></li>  
                <li><a href="#dpna">DP Not Applied</a></li>  -->
        <!--<li><a href="#sold">Product Sold</a></li>-->  

        <!--<li><a href="#guestInHouse">Sources Of Business</a></li>-->                    
    </ul>
    <div class="tab-content">
        <div class="tab-pane active" id="dsr">
            <?php echo empty($warning) ? $this->renderPartial('_naDsr', $array) : ''; ?>
        </div>
        <div class="tab-pane" id="analysis">
            <?php echo $this->renderPartial('_naAnalysis', $array); ?>
        </div>
        <div class="tab-pane" id="cashier">
            <?php echo $this->renderPartial('_naCashier', $array); ?>
        </div>
        <div class="tab-pane" id="transaction">
            <?php // echo $this->renderPartial('_naTransaction', $array);  ?>
        </div>      
        <div class="tab-pane" id="departement">
            <?php echo $this->renderPartial('_naTransactionByDepartement', $array); ?>
        </div>
        <div class="tab-pane" id="acc">
            <?php echo $this->renderPartial('_naTransactionByAccount', $array); ?>
        </div>
        <div class="tab-pane" id="guestLedger">
            <?php echo $this->renderPartial('_naGuestLedgerByRoom', $array); ?>
        </div>
        <div class="tab-pane" id="guestInHouse">
            <?php echo $this->renderPartial('_naGuestInHouse', $array); ?>
        </div>
        <div class="tab-pane" id="sob">
            <?php // echo $this->renderPartial('_naSOB', $array); ?>
        </div>
        <div class="tab-pane" id="guestExpected">
            <?php echo $this->renderPartial('_naExpectedGuest', $array); ?>
        </div>
        <div class="tab-pane" id="cl">
            <?php // echo $this->renderPartial('_naCL', $array);  ?>
        </div>
        <div class="tab-pane" id="checkedout">
            <?php // echo $this->renderPartial('_naCheckedout', $array); ?>
        </div>
        <div class="tab-pane" id="dp">
            <?php // echo $this->renderPartial('_naDP', $array); ?>
        </div>
        <div class="tab-pane" id="dpna">
            <?php // echo $this->renderPartial('_naDPNA', $array); ?>
        </div>
        <div class="tab-pane" id="sold">
            <?php // echo $this->renderPartial('_naProductSold', $array);  ?>
        </div>

    </div> 
    <br/>
    <div class="well">
        <ul>
            <li><span class="label label-info">Data must be Valid.</span> Gross Sales, Guest ledger balance, room charge, BF, other charge each Room must be valid and correct before NA Processed</li>
            <li><span class="label label-info">Cannot be Roolback.</span> Please, make sure again all data is correctly.</li>
        </ul>
        <?php
        if (empty($warning)) {
            echo '<button class="btn btn-primary" type="submit" name="approve"><i class="icon-ok icon-white"></i> Save & Process Night Audit</button>';
        } else {
            echo $warning;
        }
        ?>      

    </div>


    <?php $this->endWidget(); ?>
