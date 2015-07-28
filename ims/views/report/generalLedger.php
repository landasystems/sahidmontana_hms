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
$this->setPageTitle('General Ledger Report');
$this->breadcrumbs = array(
    'General Ledger Report',
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
            <?php // echo $form->dropDownListRow($accCoa, 'id', CHtml::listData(Acc_coa::model()->findAll(), 'id', 'name'), array('class' => 'span5', 'empty' => t('choose', 'global')));  ?>      
            <div class="control-group ">
                <label class="control-label">Nama Akun</label>
                <div class="controls">
                    <?php
                    $data = array(0 => t('choose', 'global')) + CHtml::listData(AccCoa::model()->findAll(array('order' => 'root, lft')), 'id', 'nestedname');
                    $this->widget('bootstrap.widgets.TbSelect2', array(
                        'asDropDownList' => TRUE,
                        'data' => $data,
                        'value' => (isset($_POST['accacoa']) ? $_POST['accacoa'] : ''),
                        'name' => 'accacoa',
                        'options' => array(
                            "placeholder" => t('choose', 'global'),
                            "allowClear" => true,
                            'width' => '50%',
                        ),
                        'htmlOptions' => array(
                            'id' => 'accacoa',
                        ),
                    ));
                    ?>
                </div>
            </div>
            <div class="control-group ">
                <label class="control-label">Pada</label>
                <div class="controls">
                    <?php
//                    $data2 = array(0 => t('choose', 'global')) + CHtml::listData(AccCoa::model()->findAll(array('order' => 'root, lft')), 'id', 'nestedname');
                    $this->widget('bootstrap.widgets.TbSelect2', array(
                        'asDropDownList' => TRUE,
                        'data' => $data,
                        'value' => (isset($_POST['pada']) ? $_POST['pada'] : ''),
                        'name' => 'pada',
                        'options' => array(
                            "placeholder" => t('choose', 'global'),
                            "allowClear" => true,
                            'width' => '50%',
                        ),
                        'htmlOptions' => array(
                            'id' => 'pada',
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
        Di gabung : <input type="checkbox" <?php echo (isset($_POST['isCompared'])) ? "checked" : ''; ?> name="isCompared" id="isCompared">
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
            $this->widget('bootstrap.widgets.TbButtonGroup', array(
                'htmlOptions' => array(
                    'class' => 'btn-mini'
                ),
                'type' => 'primary',
                'buttons' => array(
                    array(
                        'label' => 'Report',
                        'icon' => 'print',
                        'items' => array(
                            array('label' => 'Export Ke Excel', 'url' => Yii::app()->controller->createUrl('report/GenerateExcelGeneralLedger?created=' . str_replace("", "-", $_POST['AccCoaDet']['created'] . '&id=' . $_POST['accacoa']))),
                            array('label' => 'Print', 'icon' => 'icon-print', 'url' => 'javascript:void(0);return false', 'linkOptions' => array('onclick' => 'printDiv("printableArea");return false;')),
                        ),
                    ),
                ),
//                'htmlOptions' => array(
//                    'style' => 'width:12px'
//                )
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
    if (!empty($_POST['AccCoaDet']['created'])) {
        $a = explode('-', $_POST['AccCoaDet']['created']);
        $start = date('Y-m-d', strtotime($a[0]));
        $end = date('Y-m-d', strtotime($a[1]));
        $checked = (isset($_POST['isCompared']));
        $pada = $_POST['pada'];
        

//        $acc_id = AccCoa::model()->findAll(array('condition' => 'parent_id=' . $_POST['accacoa']));
        $this->renderPartial('_generalLedgerResult', array(
            'start' => $start,
            'end' => $end,
            'id' => $_POST['accacoa'], 
            'checked' => $checked,
            'pada' => $pada
                 ));
    }
}
?>

