<?php
$this->setPageTitle('View Departement | '.$model->id);

?>

<?php
//$this->beginWidget('zii.widgets.CPortlet', array(
//	'htmlOptions'=>array(
//		'class'=>''
//	)
//));
$this->widget('bootstrap.widgets.TbMenu', array(
    'type' => 'pills',
    'items' => array(
        array('label' => 'Create', 'icon' => 'icon-plus', 'url' => Yii::app()->controller->createUrl('create'), 'linkOptions' => array()),
        array('label' => 'List Data', 'icon' => 'icon-th-list', 'url' => Yii::app()->controller->createUrl('index'), 'linkOptions' => array()),
        array('label' => 'Edit', 'icon' => 'icon-edit', 'url' => Yii::app()->controller->createUrl('update', array('id' => $model->id)), 'linkOptions' => array()),
    //array('label'=>'Search', 'icon'=>'icon-search', 'url'=>'#', 'linkOptions'=>array('class'=>'search-button')),
//		array('label'=>'Print', 'icon'=>'icon-print', 'url'=>'javascript:void(0);return false', 'linkOptions'=>array('onclick'=>'printDiv();return false;')),
)));
//$this->endWidget();
?>
<!--<div class='printableArea'>-->

<?php
// $this->widget('bootstrap.widgets.TbDetailView',array(
//	'data'=>$model,
//	'attributes'=>array(
//		'id',
//		'name',
//		'level',
//		'lft',
//		'rgt',
//		'root',
//	),
//)); 
?>
<!--</div>
<style type="text/css" media="print">
body {visibility:hidden;}
.printableArea{visibility:visible;} 
</style>
<script type="text/javascript">
function printDiv()
{

window.print();

}
</script>-->


<div class="form">
    <?php
    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id' => 'charge-additional-category-form',
        'enableAjaxValidation' => false,
        'method' => 'post',
        'type' => 'horizontal',
        'htmlOptions' => array(
            'enctype' => 'multipart/form-data'
        )
    ));
    ?>
    <fieldset>
        <hr>
        <!--        <legend>
                    <p class="note">Fields with <span class="required">*</span> is Required.</p>
                </legend>-->

        <?php echo $form->errorSummary($model, 'Opps!!!', null, array('class' => 'alert alert-error span12')); ?>                
        <div class="control-group ">
            <label class="control-label">Parent Category</label>
            <div class="controls">
                <?php echo CHtml::dropDownList('ChargeAdditionalCategory[parent_id]', $model->parent_id, CHtml::listData(ChargeAdditionalCategory::model()->findAll(array('order' => 'root, lft')), 'id', 'nestedname'), array('class' => 'span3', 'empty' => 'root', 'disabled' => true)); ?>
            </div>
        </div>
        <?php echo $form->textFieldRow($model, 'name', array('class' => 'span5', 'disabled' => true, 'maxlength' => 45)); ?>
        <?php echo $form->textFieldRow($model, 'code', array('class' => 'span5', 'disabled' => true, 'maxlength' => 45)); ?>

       

    </fieldset>
    <hr>

    <?php $this->endWidget(); ?>

</div>
