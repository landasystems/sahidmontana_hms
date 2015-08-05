<?php
$this->setPageTitle('View Forecasts | ID : ' . $model->id);

?>

<?php
$this->beginWidget('zii.widgets.CPortlet', array(
    'htmlOptions' => array(
        'class' => ''
    )
));
$this->widget('bootstrap.widgets.TbMenu', array(
    'type' => 'pills',
    'items' => array(
        array('label' => 'Create', 'icon' => 'icon-plus', 'url' => Yii::app()->controller->createUrl('create'), 'linkOptions' => array()),
        array('label' => 'List Data', 'icon' => 'icon-th-list', 'url' => Yii::app()->controller->createUrl('index'), 'linkOptions' => array()),
        array('label' => 'Edit', 'icon' => 'icon-edit', 'url' => Yii::app()->controller->createUrl('update', array('id' => $model->id)), 'linkOptions' => array()),
        //array('label'=>'Search', 'icon'=>'icon-search', 'url'=>'#', 'linkOptions'=>array('class'=>'search-button')),
        array('label' => 'Print', 'icon' => 'icon-print', 'url' => 'javascript:void(0);return false', 'linkOptions' => array('onclick' => 'printDiv();return false;')),
)));
$this->endWidget();
?>
<div class='printableArea'>
    <?php echo $this->renderPartial('_view', array('model' => $model)); ?>
</div>
<style type="text/css" media="print">
    body {visibility:hidden;}
    .printableArea{visibility:visible;} 
    .printableArea{visibility:visible;} 
    .printableArea{width: 100%;top: 0px;left: 0px;position: absolute;} 
</style>
<script type="text/javascript">
    function printDiv()
    {

        window.print();

    }
</script>
