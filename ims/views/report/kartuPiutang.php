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
$this->setPageTitle('Kartu Piutang');
$this->breadcrumbs = array(
    'Kartu Piutang',
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
                <label class="control-label">Nama Customer</label>
                <div class="controls">
                    <?php
                    $data = array(0 => t('choose', 'global')) + CHtml::listData(User::model()->listUsers('customer'), 'id', 'name');
                    $this->widget('bootstrap.widgets.TbSelect2', array(
                        'asDropDownList' => TRUE,
                        'data' => $data,
                        'value' => (isset($_POST['listUser']) ? $_POST['listUser'] : ''),
                        'name' => 'listUser',
                        'options' => array(
                            "placeholder" => t('choose', 'global'),
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
                'value' => (isset($_POST['AccCoaDet']['created'])) ? $_POST['AccCoaDet']['created'] : '',
                'prepend' => '<i class="icon-calendar"></i>',
                'options' => array('callback' => 'js:function(start, end){console.log(start.toString("MMMM d, yyyy") + " - " + end.toString("MMMM d, yyyy"));}')
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
//        if (isset($_POST['AccCoaDet']['created'])) {
//            $this->widget(
//                    'bootstrap.widgets.TbButtonGroup', array(
//                'buttons' => array(
//                    array(
//                        'label' => 'Report',
//                        'icon' => 'print',
//                        'items' => array(
//                            array('label' => 'Export Ke Excel', 'url' => Yii::app()->controller->createUrl('report/GenerateExcelKartuPiutang?created=' . str_replace("", "-", $_POST['AccCoaSub']['created'] . '&ar_id=' . $_POST['ar_id'] . '&type=ar'))),
//                            array('label' => 'Print', 'icon' => 'icon-print', 'url' => 'javascript:void(0);return false', 'linkOptions' => array('onclick' => 'printDiv();return false;')),
//                        )
//                    ),
//                ),
//                    )
//            );
//        } else {
//            echo'';
//        }
        ?>
    </div>

    <?php $this->endWidget(); ?>
</div>

<?php
if (isset($_POST['yt0'])) {

    if (!empty($_POST['AccCoaDet']['created'])) {
        $a = explode('-', $_POST['AccCoaDet']['created']);
        $start = date('Y/m/d', strtotime($a[0]));
        $end = date('Y/m/d', strtotime($a[1]));

        $accCoaDet = AccCoaDet::model()->findAll(array(
            'with' => 'InvoiceDet',
            'order' => 'date_coa',
            'condition' => 'InvoiceDet.user_id=' . $_POST['listUser'] . ' AND (date_coa>="' . date('Y-m-d', strtotime($start)) . '" AND date_coa<="' . date('Y-m-d', strtotime($end)) . '")'
        ));

        $this->renderPartial('_kartuPiutangResult', array(
            'start' => $start,
            'end' => $end,
            'accCoaDet' => $accCoaDet,
            'id' => $_POST['listUser']
        ));
    }
}
?>

