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
$this->setPageTitle('Neraca Saldo');
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
            <?php
            echo $form->dateRangeRow(
                    $mCoaDet, 'created', array(
                'prepend' => '<i class="icon-calendar"></i>',
                'options' => array('callback' => 'js:function(start, end){console.log(start.toString("MMMM d, yyyy") + " - " + end.toString("MMMM d, yyyy"));}'),
                'value' => (isset($_POST['AccCoaDet']['created'])) ? $_POST['AccCoaDet']['created'] : ''
                    )
            );
            ?>    
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
        <?php
        if (isset($_POST['AccCoaDet']['created'])) {
            $this->widget(
                'bootstrap.widgets.TbButtonGroup', array(
            'buttons' => array(
                array(
                    'label' => 'Report',
                    'icon' => 'print',
                    'items' => array(
                        array('label' => 'Export Ke Excel', 'url' => Yii::app()->controller->createUrl('report/GenerateExcelNeracaSaldo?created=' . str_replace("", "-", $_POST['AccCoaDet']['created']))),
                        array('label'=>'Print', 'icon'=>'icon-print', 'url'=>'javascript:void(0);return false', 'linkOptions'=>array('onclick'=>'printDiv("printableArea");return false;')),
                    )
                ),
            ),
                )
        );
        }
        ?>
    </div>

    <?php $this->endWidget(); ?>
</div>
<?php
if (isset($_POST['AccCoaDet']['created'])) {
        $a = explode('-', $_POST['AccCoaDet']['created']);
        $start = date('Y-m-d', strtotime($a[0]));
        $end = date('Y-m-d', strtotime($a[1]));
        $accCoa = AccCoa::model()->findAll(array('condition'=>'type="detail"','order'=>'code'));
        
        $this->renderPartial('_neracaSaldoResult', array('a' => $a,'accCoa'=> $accCoa,'start'=>$start,'end'=>$end));
    
}
?>

