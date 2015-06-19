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
$this->setPageTitle('Rekap Kartu Stock Report');
$this->breadcrumbs = array(
    'Rekap Kartu Stock Report',
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
            <?php
            echo $form->dateRangeRow(
                    $mCoaSub, 'created', array(
                'prepend' => '<i class="icon-calendar"></i>',
                'options' => array('callback' => 'js:function(start, end){console.log(start.toString("MMMM d, yyyy") + " - " + end.toString("MMMM d, yyyy"));}'),
                'value' => (isset($_POST['AccCoaSub']['created'])) ? $_POST['AccCoaSub']['created'] : ''
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
        if (isset($_POST['AccCoaSub']['created'])) {
            $this->widget(
                    'bootstrap.widgets.TbButtonGroup', array(
                'buttons' => array(
                    array(
                        'label' => 'Report',
                        'icon' => 'print',
                        'items' => array(
                            array('label' => 'Export Ke Excel', 'url' => Yii::app()->controller->createUrl('report/GenerateExcelRekapStock?created=' . str_replace("", "-", $_POST['AccCoaSub']['created']))),
                            array('label' => 'Print', 'icon' => 'icon-print', 'url' => 'javascript:void(0);return false', 'linkOptions' => array('onclick' => 'printDiv();return false;')),
                        )
                    ),
                ),
                    )
            );
        } else {
            echo '';
        }
        ?>
    </div>

    <?php $this->endWidget(); ?>
</div>
<?php
if (isset($_POST['yt0'])) {

    if (!empty($_POST['AccCoaSub']['created'])) {
        $a = explode('-', $_POST['AccCoaSub']['created']);
        $start = date('Y-m-d', strtotime($a[0]));
        $end = date('Y-m-d', strtotime($a[1]));
        $supplier = User::model()->listUsers('inv');

        $this->renderPartial('_rekapStockResult', array(
            'a' => $a,
            'supplier' => $supplier,
            'start' => $start,
            'end' => $end,
            'type' => 'as'
        ));
    }
}
?>

