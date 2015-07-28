<style>
    .contentwrapper {
        min-height: 200px;
    }
</style>
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
$this->setPageTitle('Jurnal Umum');
$this->breadcrumbs = array(
    'Jurnal Umum',
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
        <div class="span12">
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


</div>
<div class="form-actions">
    <?php
    $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType' => 'submit',
        'type' => 'primary',
        'icon' => 'ok white',
        'label' => 'Lihat',
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
                        array('label' => 'Export Ke Excel', 'url' => Yii::app()->controller->createUrl('report/GenerateExcelJurnalUmum?created=' . str_replace("", "-", $_POST['AccCoaDet']['created']))),
                        array('label' => 'Print', 'icon' => 'icon-print', 'url' => 'javascript:void(0);return false', 'linkOptions' => array('onclick' => 'printDiv("printableArea");return false;')),
                    )
                ),
            ),
                )
        );
    } else {
        echo'';
    }
    ?>
    <?php $this->endWidget(); ?>
</div>

<?php
if (isset($_POST['yt0'])) {
    $a = explode('-', $_POST['AccCoaDet']['created']);
    $start = date('Y/m/d', strtotime($a[0]));
    $end = date('Y/m/d', strtotime($a[1]));
    $accCoaDet = AccCoaDet::model()->findAll(array('condition' => 'reff_type<>"balance" AND (date_coa>="' . $start . '" and date_coa<="' . $end . '")', 'order' => 'date_coa,id'));

    $this->renderPartial('_jurnalUmumResult', array('a' => $a, 'accCoaDet' => $accCoaDet, 'start' => $start, 'end' => $end));
}
?>
