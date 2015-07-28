<?php
$this->setPageTitle('Lihat Acc Coas | ID : ' . $model->id);
$this->breadcrumbs = array(
    'Acc Coas' => array('index'),
    $model->name,
);
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
        array('visible' => landa()->checkAccess('AccCoa', 'c'), 'label' => 'Tambah', 'icon' => 'icon-plus', 'url' => Yii::app()->controller->createUrl('create'), 'linkOptions' => array()),
        array('visible' => landa()->checkAccess('AccCoa', 'r'), 'label' => 'Daftar', 'icon' => 'icon-th-list', 'url' => Yii::app()->controller->createUrl('index'), 'linkOptions' => array()),
        array('visible' => landa()->checkAccess('AccCoa', 'u'), 'label' => 'Edit', 'icon' => 'icon-edit', 'url' => Yii::app()->controller->createUrl('update', array('id' => $model->id)), 'linkOptions' => array()),
        //array('label'=>'Pencarian', 'icon'=>'icon-search', 'url'=>'#', 'linkOptions'=>array('class'=>'search-button')),
        array('label' => 'Print', 'icon' => 'icon-print', 'url' => 'javascript:void(0);return false', 'linkOptions' => array('onclick' => 'printDiv();return false;')),
)));
$this->endWidget();
?>
<div class='printableArea'>

    <?php
    $this->widget('bootstrap.widgets.TbDetailView', array(
        'data' => $model,
        'attributes' => array(
            'id',
            'name',
            'description',
            'created_user_id',
            'modified',
            'created',
            'level',
            'lft',
            'rgt',
            'root',
            'parent_id',
        ),
    ));
    ?>
</div>
<style type="text/css" media="print">
    body {visibility:hidden;}
    .printableArea{visibility:visible;} 
</style>
<script type="text/javascript">
    function printDiv()
    {

        window.print();

    }
</script>
