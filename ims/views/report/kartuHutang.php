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
$this->setPageTitle('Kartu Hutang');
$this->breadcrumbs = array(
    'Kartu Hutang',
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
            <div class="control-group ">
                <label class="control-label">Nama Supplier</label>
                <div class="controls">
                    <?php
                    $data = array(0 => 'Pilih') + CHtml::listData(User::model()->listUsers('supplier'), 'id', 'name');
                    $this->widget('bootstrap.widgets.TbSelect2', array(
                        'asDropDownList' => TRUE,
                        'data' => $data,
                        'value' => (isset($_POST['listUser']) ? $_POST['listUser'] : ''),
                        'name' => 'listUser',
                        'options' => array(
                            "placeholder" => 'Pilih',
                            "allowClear" => true,
                            'width' => '50%',
                        ),
                        'htmlOptions' => array(
                            'id' => 'listUser',
                        ),
                    ));
                    ?>
                </div>
            </div>
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
//        if (isset($_POST['AccCoaSub']['created'])) {
//            $this->widget(
//                    'bootstrap.widgets.TbButtonGroup', array(
//                'buttons' => array(
//                    array(
//                        'label' => 'Report',
//                        'icon' => 'print',
//                        'items' => array(
//                            array('label' => 'Export Ke Excel', 'url' => Yii::app()->controller->createUrl('report/GenerateExcelKartuHutang?created=' . str_replace("", "-", $_POST['AccCoaSub']['created'] . '&ap_id=' . $_POST['ap_id'] . '&type=ap'))),
//                            array('label' => 'Print', 'icon' => 'icon-print', 'url' => 'javascript:void(0);return false', 'linkOptions' => array('onclick' => 'printDiv();return false;')),
//                        )
//                    ),
//                ),
//                    )
//            );
//        } else {
//            echo '';
//        }
        ?>
    </div>

    <?php $this->endWidget(); ?>
</div>

<?php
if (isset($_POST['AccCoaDet']['created'])) {

    if (!empty($_POST['AccCoaDet']['created'])) {
        $a = explode('-', $_POST['AccCoaDet']['created']);
        $start = date('Y/m/d', strtotime($a[0]));
        $end = date('Y/m/d', strtotime($a[1]));

        $accCoaDet = AccCoaDet::model()->findAll(array(
            'with' => 'InvoiceDet',
            'order' => 'date_coa',
            'condition' => 'InvoiceDet.user_id=' . $_POST['listUser'] . ' AND (date_coa>="' . date('Y-m-d', strtotime($start)) . '" AND date_coa<="' . date('Y-m-d', strtotime($end)) . '")'
        ));

        $this->renderPartial('_kartuHutangResult', array(
            'start' => $start,
            'end' => $end,
            'accCoaDet' => $accCoaDet,
            'id' => $_POST['listUser']
        ));
    }
}
?>

