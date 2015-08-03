<?php
//$na = Na::model()->findAll(array('condition' => 'year(date_na) = "2015"', 'limit' => '5', 'order' => 'id asc'));
//$na = Na::model()->findAll(array('order' => 'id asc'));
//foreach ($na as $val) {
//    $report = ReportGeographical::model()->insertGeoGraphical($val->id, $val->date_na);
//}
?>
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
$this->setPageTitle('Geographical Guest');

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
            <div class="control-group">
                <label class="control-label">Date : </label>
                <div class="controls">
                    <?php
//                    $geo = ReportGeographical::model()->findByPk(4);
//                    $rep = json_decode($geo->today_rno, TRUE);
//                    print_r($rep);
//                    echo '-'.$rep['city']['73'].'-';
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
                            'id' => 'created',
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
    echo CHtml::ajaxSubmitButton('View Report', Yii::app()->createUrl('Report/ViewResult'), array(
        'type' => 'POST',
        'success' => 'function(data){
                        $("#viewData").html(data);
                        }'
            ), array('class' => 'btn btn-primary')
    );
    ?>
    <?php
//    if (isset($_POST['User']['created'])) {
//        $this->widget(
//                'bootstrap.widgets.TbButtonGroup', array(
//            'buttons' => array(
//                array(
//                    'label' => 'Report',
//                    'icon' => 'print',
//                    'items' => array(
//                        array('label' => 'Export Ke Excel', 'url' => Yii::app()->controller->createUrl('report/GenerateExcelJurnalUmum?created=' . str_replace("", "-", $_POST['User']['created']))),
//                        array('label'=>'Print', 'icon'=>'icon-print', 'url'=>'javascript:void(0);return false', 'linkOptions'=>array('onclick'=>'printDiv();return false;')),
//                    )
//                ),
//            ),
//                )
//        );
//    } else {
//        echo'';
//    }
    ?>
</div>

<?php $this->endWidget(); ?>
<DIV ID="viewData"></DIV>
