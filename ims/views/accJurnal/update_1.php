<?php

$this->setPageTitle('Edit Acc Jurnals | ID : ' . $model->id);
$this->breadcrumbs = array(
    'Acc Jurnals' => array('index'),
    $model->id => array('view', 'id' => $model->id),
    'Update',
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
        array('visible' => landa()->checkAccess('AccJurnal', 'c'), 'label' => 'Tambah', 'icon' => 'icon-plus', 'url' => Yii::app()->controller->createUrl('create'), 'linkOptions' => array()),
        array('visible' => landa()->checkAccess('AccJurnal', 'r'), 'label' => 'Daftar', 'icon' => 'icon-th-list', 'url' => Yii::app()->controller->createUrl('index'), 'linkOptions' => array()),
        array('visible' => landa()->checkAccess('AccJurnal', 'u'), 'label' => 'Edit', 'icon' => 'icon-edit', 'url' => Yii::app()->controller->createUrl('update', array('id' => $model->id)), 'active' => true, 'linkOptions' => array()),
    ),
));
$this->endWidget();
?>

<?php echo $this->renderPartial('_form', array('model' => $model, 'detailJurnal' => $detailJurnal)); ?>