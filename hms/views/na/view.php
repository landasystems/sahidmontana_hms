<style>
    .judul{border-bottom:solid #000 2px !important;border-top:solid #000 2px !important}
    .isi{border-bottom:none !important;border-top:none !important}
    
</style>
<?php
$this->setPageTitle('View Night Audit | Date : ' . $model->date_na);

?>

<?php
$this->beginWidget('zii.widgets.CPortlet', array(
    'htmlOptions' => array(
        'class' => ''
    )
));
$this->widget('bootstrap.widgets.TbMenu', array(
    'type' => 'pills',
    'items' => array(
        array('label' => 'Create', 'icon' => 'icon-plus', 'url' => Yii::app()->controller->createUrl('create'), 'linkOptions' => array()),
        array('label' => 'List Data', 'icon' => 'icon-th-list', 'url' => Yii::app()->controller->createUrl('index'), 'linkOptions' => array()),
//        array('label' => 'Edit', 'icon' => 'icon-edit', 'url' => Yii::app()->controller->createUrl('update', array('id' => $model->id)), 'linkOptions' => array()),
    //array('label'=>'Pencarian', 'icon'=>'icon-search', 'url'=>'#', 'linkOptions'=>array('class'=>'search-button')),
//        array('label' => 'Print', 'icon' => 'icon-print', 'url' => 'javascript:void(0);return false', 'linkOptions' => array('onclick' => 'printDiv();return false;')),
)));
$this->endWidget();
?>

<?php
$category = ChargeAdditionalCategory::model()->findAll(array('condition' => 'level=1', 'index' => 'id'));
$account = Account::model()->findAll();
$category_all = ChargeAdditionalCategory::model()->findAll(array('index' => 'id'));
$additional = ChargeAdditional::model()->findAll(array('index' => 'id'));
$siteConfig = SiteConfig::model()->findByPk(1);
$roomType = RoomType::model()->findAll(array('index' => 'id'));
$naDet = NaDet::model()->findAll(array('condition' => 'na_id=' . $model->id, 'index' => 'id'));
$naDetGroupRegistration = NaDet::model()->findAll(array('condition' => 'room_bill_id !=0 and na_id=' . $model->id, 'group' => 'registration_id'));
$naDsr = NaDsr::model()->find(array('condition' => 'na_id=' . $model->id, 'index' => 'id'));
$naStatistic = NaStatistic::model()->find(array('condition' => 'na_id=' . $model->id, 'index' => 'id'));
$naFoodAnalys = NaFoodAnalys::model()->find(array('condition' => 'na_id=' . $model->id, 'index' => 'id'));
$naFoodpercoverAnalys = NaFoodpercoverAnalys::model()->find(array('condition' => 'na_id=' . $model->id, 'index' => 'id'));
$naBeverageAnalys = NaBeverageAnalys::model()->find(array('condition' => 'na_id=' . $model->id, 'index' => 'id'));
$naDpUnused = NaDpNotApplied::model()->findAll(array('condition' => 'na_id=' . $model->id, 'index' => 'id'));
$naDpUsed = NaDpApplied::model()->findAll(array('condition' => 'na_id=' . $model->id, 'index' => 'id'));
$naGl = NaGl::model()->findAll(array('condition' => 'na_id=' . $model->id, 'index' => 'id'));
$naProductSold = NaProductSold::model()->find(array('condition' => 'na_id=' . $model->id, 'index' => 'id'));
//$billChargeAll = BillCharge::model()->findAll(array('index' => 'id'));
//$billChargeDet = BillChargeDet::model()->findAll(array('index' => 'id'));
//$roomBills_All = RoomBill::model()->findAll(array('index' => 'id'));
//$bill = Bill::model()->findAll(array('index' => 'id'));
//$billDet = BillDet::model()->findAll(array('index' => 'id'));
$expectedArrival = NaExpectedArrival::model()->findAll(array('condition' => 'na_id=' . $model->id, 'index' => 'id'));
$expectedDeparture = NaExpectedDeparture::model()->findAll(array('condition' => 'na_id=' . $model->id, 'index' => 'id'));
$array = array(
    'category' => $category,
    'naProductSold' => $naProductSold,
    'account' => $account,
    'naDsr' => $naDsr,
    'naStatistic' => $naStatistic,
    'naFoodAnalys' => $naFoodAnalys,
    'naFoodpercoverAnalys' => $naFoodpercoverAnalys,
    'naBeverageAnalys' => $naBeverageAnalys,
    'siteConfig' => $siteConfig,
    'roomType' => $roomType,
    'naDetGroupRegistration' => $naDetGroupRegistration,
    'category_all' => $category_all,
    'additional' => $additional,
    'naDet' => $naDet,
    'naDpUnused' => $naDpUnused,
    'naDpUsed' => $naDpUsed,
    'naGl' => $naGl,
//    'billChargeAll' => $billChargeAll,
//    'billChargeDet' => $billChargeDet,
//    'roomBills_All' => $roomBills_All,
//    'billDet' => $billDet,
//    'bill' => $bill,
    'model' => $model,
    'expectedArrival' => $expectedArrival,
    'expectedDeparture' => $expectedDeparture,
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
        <li><a href="#analys">F&B Analys</a></li>                                                    
        <li><a href="#cashier">Cashier Sheet</a></li>                                                    
        <!--        <li><a href="#transaction">Global NA</a></li>                                                    
                <li><a href="#departement">Detail NA</a></li>         -->
        <li><a href="#acc">Trans. By Account</a></li>         
        <li><a href="#departement">Trans. By Outlet</a></li> 
        <li><a href="#guestLedger">Guest Ledger Balance</a></li>                    
        <li><a href="#guestInHouse">Guest In House</a></li>   
        <li><a href="#guestExpected">Expected Guest</a></li>  
        <!--<li><a href="#cl">City Ledger</a></li>-->                
        <!--<li><a href="#checkedout">Checkedout</a></li>-->  
<!--        <li><a href="#dp">Deposite</a></li>  
        <li><a href="#dpna">DP Not Applied</a></li>  -->
        <!--<li><a href="#product_sold">Product Sold</a></li>-->  

        <!--<li><a href="#guestInHouse">Sources Of Business</a></li>-->                    
    </ul>

    <div class="tab-content">
        <div class="tab-pane active" id="dsr">
            <?php echo $this->renderPartial('_viewDsr', $array); ?>
        </div>      
        <div class="tab-pane" id="analys">
            <?php echo $this->renderPartial('_viewAnalysis', $array); ?>
        </div>      
        <div class="tab-pane" id="cashier">
            <?php echo $this->renderPartial('_viewCashier', $array); ?>
        </div>      
        <div class="tab-pane" id="transaction">
            <?php // echo $this->renderPartial('_viewTransaction', $array); ?>
        </div>      
        <div class="tab-pane" id="acc">
            <?php echo $this->renderPartial('_viewTransactionByAccount', $array); ?>
        </div>
        <div class="tab-pane" id="departement">
            <?php echo $this->renderPartial('_viewTransactionByDepartement', $array); ?>
        </div>
        <div class="tab-pane" id="guestLedger">
            <?php echo $this->renderPartial('_viewGuestLedgerByRoom', $array); ?>
        </div>
        <div class="tab-pane" id="guestInHouse">
            <?php echo $this->renderPartial('_viewGuestInHouse', $array); ?>
        </div>
        <div class="tab-pane" id="guestExpected">
            <?php echo $this->renderPartial('_viewExpectedGuest', $array); ?>
        </div>
        <div class="tab-pane" id="cl">
            <?php // echo $this->renderPartial('_viewCL', $array); ?>
        </div>
        <div class="tab-pane" id="dp">
            <?php // echo $this->renderPartial('_viewDp', $array); ?>
        </div>
        <div class="tab-pane" id="checkedout">
            <?php // echo $this->renderPartial('_viewCheckedout', $array); ?>
        </div>
        <div class="tab-pane" id="dpna">
            <?php // echo $this->renderPartial('_viewDPNA', $array); ?>
        </div>
        <div class="tab-pane" id="product_sold">
            <?php // echo $this->renderPartial('_viewProductSold', $array); ?>
        </div>
    </div> 


    <?php $this->endWidget(); ?>

    <script type="text/javascript">
        function printDiv(divName)
        {
            var printContents = document.getElementById(divName).innerHTML;
            var originalContents = document.body.innerHTML;
            document.body.innerHTML = printContents;
            window.print();

            document.body.innerHTML = originalContents;
            $("#myTab a").click(function(e) {
                e.preventDefault();
                $(this).tab("show");
            });
        }
    </script>