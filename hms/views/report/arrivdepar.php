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
$this->setPageTitle('Arrival / Departure');
$this->breadcrumbs = array(
    'Arrival / Departure',
);

?>
<script>
function hide(){
            $(".well").hide();
            $(".form-horizontal").hide();
}

</script>
<div class="well">

    <div class="row-fluid">
        <div class="span11">
            <?php // echo $form->dropDownListRow($mBuy, 'departement_id', CHtml::listData(Departement::model()->findAll(), 'id', 'name'), array('class' => 'span5', 'empty' => t('choose', 'global'))); ?>      

            <?php
            echo $form->dateRangeRow(
                    $mArr, 'date_from', array(
                'hint' => 'Click inside! An even a date range field!.',
                'prepend' => '<i class="icon-calendar"></i>',
                'options' => array('callback' => 'js:function(start, end){console.log(start.toString("MMMM d, yyyy") + " - " + end.toString("MMMM d, yyyy"));}')
                    )
            );
            ?>    
        </div>
        <div>
            <a onclick="hide()" class="btn btn-small view" title="Remove Form" rel="tooltip"><i class=" icon-remove-circle"></i></a>
        
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
if (isset($mArr->date_from)) {

        $sDate = $mArr->date_from;
        $date = explode('-', $sDate);
        $date_from = date("Y/m/d", strtotime($date[0]));
        $date_to = date("Y/m/d", strtotime($date[1])). " 23:59:59";
        $reservationReport = Reservation::model()->findAll(array('condition' => '(date_from >="' . $date_from . '" AND date_to <="' . $date_to . '") ', 'order' => 'id'));
        $registrationReport = Registration::model()->findAll(array('condition' => '(date_from >="' . $date_from . '" AND date_to <="' . $date_to . '") ', 'order' => 'id'));
//      $roomReport =  Room::model()->findAll(array('condition'=>'id='.$reservationDetailReport->room_id));
//      $registrationReport = Registration::model()->findAll(array('condition'=>'(created >="' . $start . '" AND created <="' . $end . '" AND reservation_id="'.$reservstionReport->id.'") '));
        $this->renderPartial('_arrivdeparResult', array('reservation' => $reservationReport,'registration'=>$registrationReport, 'date_from' => $mArr->date_from));
}
?>