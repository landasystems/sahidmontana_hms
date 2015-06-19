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
$this->setPageTitle('Laporan Kas Harian');
$this->breadcrumbs = array(
    'Laporan Kas Harian',
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
            <div class="control-group">
                <label class="control-label">Nama Akun</label>
                <div class="controls">
                    <?php
                    $data = array(0 => t('choose', 'global')) + CHtml::listData(AccCoa::model()->findAll(array('condition' => 'type_sub_ledger="ks" OR type_sub_ledger="bk"', 'order' => 'root, lft')), 'id', 'nestedname');
                    $this->widget('bootstrap.widgets.TbSelect2', array(
                        'asDropDownList' => TRUE,
                        'data' => $data,
                        'name' => 'cash',
                        'value' => (isset($_POST['cash']) ? $_POST['cash'] : ''),
                        'options' => array(
                            "placeholder" => t('choose', 'global'),
                            "allowClear" => true,
                        ),
                        'htmlOptions' => array(
                            'id' => 'AccCashIn_account',
                            'style' => 'width:250px;'
                        ),
                    ));
                    ?> 
                </div>
            </div>
            <label class="control-label">Tanggal</label>
            <div class="controls">
                <?php
                $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                    'name' => 'created',
                    'value' => (isset($_POST['created'])) ? $_POST['created'] : '',
                    // additional javascript options for the date picker plugin
                    'options' => array(
                        'showAnim' => 'fold',
                        'changeMonth' => 'true',
                        'changeYear' => 'true',
                    ),
                    'htmlOptions' => array(
                        'style' => 'height:20px;',
                        'id' => 'acccoa'
                    ),
                ));
                ?>
            </div>

        </div>
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
    if (isset($_POST['created'])) {
        $this->widget(
                'bootstrap.widgets.TbButtonGroup', array(
            'buttons' => array(
                array(
                    'label' => 'Report',
                    'icon' => 'print',
                    'items' => array(
                        array('label' => 'Export Ke Excel', 'url' => Yii::app()->controller->createUrl('report/GenerateExcelKasHarian?created=' .str_replace("","-",$_POST['created'].'&cash='.$_POST['cash']))),
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
if (!empty($_POST['cash'])) {
    $a = date('Y-m-d', strtotime($_POST['created']));
    $akhir = date('Y/m/d', strtotime($a));

    $this->renderPartial('_kasHarianResult', array('a' => $a, 'akhir' => $akhir, 'cash' => $_POST['cash']));
}
?>

