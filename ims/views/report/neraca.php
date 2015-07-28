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
$this->setPageTitle('Neraca Report');
$this->breadcrumbs = array(
    'Neraca Report',
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
                <label class="control-label">Pengelompokan</label>
                <div class="controls">
                    <?php
                    echo CHtml::dropDownList('viewType',array(
                        'value'=>(isset($_POST['viewType'])) ? $_POST['viewType'] : ''
                    ), AccCoa::model()->maxLevel() , array('empty' => '(Select Type)'));
                    ?>
                </div>
            </div>
            <div class="control-group">
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
                        array('label' => 'Export Ke Excel', 'url' => Yii::app()->controller->createUrl('GenerateExcelNeraca',array('created'=>$_POST['created'],'viewType' => $_POST['viewType'])), 'linkOptions'=>array()),
                        array('label'=>'Print', 'icon'=>'icon-print', 'url'=>'javascript:void(0);return false', 'linkOptions'=>array('onclick'=>'printDiv();return false;')),
                    )
                ),
            ),
                )
        );
       } else {
           echo'';
       }
       ?>
</div>
        <?php $this->endWidget(); ?>
<?php
if (isset($_POST['created']) AND isset($_POST['viewType'])) {
    $a = date('Y-m-d', strtotime($_POST['created']));

    $accCoa = AccCoa::model()->findAll(array('condition' => '(`group`="aktiva" OR `group`="pasiva")'));
    $this->renderPartial('_neracaResult', array('a' => $a,'accCoa'=>$accCoa, 'viewType' => $_POST['viewType']));
}
?>
