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
$this->setPageTitle('Laporan Laba/Rugi');
$this->breadcrumbs = array(
    'Laporan Laba/Rugi',
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
                    echo CHtml::dropDownList('viewType', array(
                        'value' => (isset($_POST['viewType'])) ? $_POST['viewType'] : ''
                            ), AccCoa::model()->maxLevel(), array('empty' => '(Select Type)', 'callback' => 'viewType'));
                    ?>
                </div>
            </div>
            <?php
            echo $form->dateRangeRow(
                    $mCoa, 'created', array(
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
        if (isset($_POST['AccCoa']['created'])) {
            $this->widget('bootstrap.widgets.TbButtonGroup', array(
                'buttons' => array(
                    array(
                        'label' => 'Report',
                        'icon' => 'print',
                        'items' => array(
                            array('label' => 'Export Ke Excel', 'url' => Yii::app()->controller->createUrl('report/GenerateExcelLabaRugi?created=' . str_replace("", "-", $_POST['AccCoa']['created'] . '&viewType=' . $_POST['viewType']))),
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
    $a = explode('-', $_POST['AccCoa']['created']);
    $start = date('y-m-d', strtotime($a[0]));
    $end = date('y-m-d', strtotime($a[1]));


    $accCoa = AccCoa::model()->findAll(array('order' => 'code', 'condition' => '`group` = "receivable" OR `group`= "cost" AND level >='.$_POST['viewType']));
    $this->renderPartial('_labaRugiResult', array('accCoa' => $accCoa, 'viewType' => $_POST['viewType'], 'start' => $start, 'end' => $end, 'a' => $a));
}
?>
