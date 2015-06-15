<?php
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id' => 'results',
    'enableAjaxValidation' => false,
    'method' => 'post',
    'type' => 'horizontal',
    'htmlOptions' => array(
        'enctype' => 'multipart/form-data'
    )
        ));
?>
<?php
$this->setPageTitle('Guest In House');
$this->breadcrumbs = array(
    'Guest In House',
);
?>
<script>
    function hide() {
        $(".well").hide();
        $(".form-horizontal").hide();
    }

</script>
<div class="well">

    <div class="row-fluid">
        <div class="span11">
            <?php // echo $form->dropDownListRow($mBuy, 'departement_id', CHtml::listData(Departement::model()->findAll(), 'id', 'name'), array('class' => 'span5', 'empty' => t('choose', 'global')));  ?>      

            <?php
            echo $form->dateRangeRow(
                    $mRes, 'date_from', array(
                'hint' => 'Click inside! An even a date range field!.',
                'prepend' => '<i class="icon-calendar"></i>',
                'options' => array('callback' => 'js:function(start, end){console.log(start.toString("MMMM d, yyyy") + " - " + end.toString("MMMM d, yyyy"));}')
                    )
            );
            ?>    
        </div>
        <div><?php if (isset($mRes->date_form)) { ?>
                <a onclick="hide()" class="btn btn-small view" title="Remove Form" rel="tooltip"><i class=" icon-remove-circle"></i></a>
            <?php } ?>
        </div>

    </div>
    <div class="form-actions">
        <?php
        $this->widget('bootstrap.widgets.TbButton', array(
            'buttonType' => 'submit',
            'type' => 'primary',
            'icon' => 'ok white',
            'label' => 'View Report',
        ));
        ?>
    </div>

    <?php $this->endWidget(); ?>
</div>

<?php
if (isset($mRes->date_from)) {
//    if (!empty($mRes->departement_id)){
//        $departement = Departement::model()->findByPk($mBuy->departement_id);
//        $departementId = ' AND departement_id="' . $departement->id .'"';
//        $departementName = $departement->name;        
//    }else{        
//        $departementId = '';
//        $departementName = 'All'; 
//    }

    $sDate = $mRes->date_from;
    $date = explode('-', $sDate);
    $date_from = date("Y-m-d", strtotime($date[0]));
    $date_to = date("Y-m-d", strtotime($date[1])) . " 23:59:59";

//    $a = explode('-', $mRes->created);
//    $b = explode('-', $mRes->created);
//    $start = date('Y/m/d', strtotime($a[0]));
//    $end = date('Y/m/d', strtotime($a[1])) . " 23:59:59";

    $roomBill = RoomBill::model()->with('Na')->findAll(array('condition' => 'Na.date_na >= "' . $date_from . '" and Na.date_na <= "' . $date_to . '"', 'order' => 'Na.date_na ASC'));


//    $roomReport =  Room::model()->findAll(array('condition'=>'id='.$reservationDetailReport->room_id));
//    $registrationReport = Registration::model()->findAll(array('condition'=>'(created >="' . $start . '" AND created <="' . $end . '" AND reservation_id="'.$reservstionReport->id.'") '));
    $this->renderPartial('_guesthouseResult', array('roomBill' => $roomBill, 'created' => $mRes->date_from));
}
?>