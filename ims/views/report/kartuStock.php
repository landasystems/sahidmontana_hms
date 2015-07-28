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
$this->setPageTitle('Kartu Stock');
$this->breadcrumbs = array(
    'Kartu Stock',
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
                <label class="control-label">Nama Barang</label>
                <div class="controls">
                    <?php
                                $data = array(0 => t('choose', 'global')) + CHtml::listData(Product::model()->findAll(array('condition' => 'type="inv"')), 'id', 'name');
                                $this->widget('bootstrap.widgets.TbSelect2', array(
                                    'asDropDownList' => TRUE,
                                    'data' => $data,
                                    'name' => 'as_id',
                                    'value' => (isset($_POST['as_id'])) ? $_POST['as_id'] : '',
                                    'options' => array(
                                        "placeholder" => t('choose', 'global'),
                                        "allowClear" => true,
                                    ),
                                    'htmlOptions' => array(
                                        'style' => 'width:250px;'
                                    ),
                                ));
                               ?>

                </div>
            </div>
            <?php
            echo $form->dateRangeRow(
                    $mCoaSub, 'created', array(
                'prepend' => '<i class="icon-calendar"></i>',
                'options' => array('callback' => 'js:function(start, end){console.log(start.toString("MMMM d, yyyy") + " - " + end.toString("MMMM d, yyyy"));}'),
                'value' => (isset($_POST['AccCoaSub']['created'])) ? $_POST['AccCoaSub']['created'] :''
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
        if(isset($_POST['AccCoaSub']['created'])){
            $this->widget(
                'bootstrap.widgets.TbButtonGroup', array(
            'buttons' => array(
                array(
                    'label' => 'Report',
                    'icon' => 'print',
                    'items' => array(
                        array('label' => 'Export Ke Excel', 'url' => Yii::app()->controller->createUrl('report/GenerateExcelKartuStock?created=' .str_replace("","-",$_POST['AccCoaSub']['created'].'&as_id='.$_POST['as_id'].'&type=as'))),
                        array('label'=>'Print', 'icon'=>'icon-print', 'url'=>'javascript:void(0);return false', 'linkOptions'=>array('onclick'=>'printDiv();return false;')),
                    )
                ),
            ),
                )
        );
        }else{
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
        $start = date('Y/m/d', strtotime($a[0]));
        $end = date('Y/m/d', strtotime($a[1]));


        $accCoaSub = AccCoaSub::model()->findAll(array('order' => 'date_coa , id', 'condition' => '(date_coa >="' . $start . '" and date_coa <="' . $end . '") and as_id =' . $_POST['as_id']));
     
        $this->renderPartial('_kartuStockResult', array('start' => $start, 'end' => $end, 'as_id' => $_POST['as_id'],'accCoaSub'=>$accCoaSub,'type'=>'as'));
    }
}
?>

