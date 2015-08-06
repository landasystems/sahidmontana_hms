<?php

$this->setPageTitle('Reservations | '. $model->id);

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
        array('label' => 'Edit', 'icon' => 'icon-edit', 'url' => Yii::app()->controller->createUrl('update', array('id' => $model->id)), 'active' => true, 'linkOptions' => array()),
        array('label' => 'Change Status [' . ucwords($model->status) . ']', 'icon' => 'icon-refresh', 'url' => '#', 'linkOptions' => array('data-target' => '#myModal', 'data-toggle' => 'modal')),
    ),
));
$this->endWidget();
$myDetail = ReservationDetail::model()->findByAttributes(array('reservation_id' => $model->id));
?>

<?php echo $this->renderPartial('_form', array('model' => $model, 'mDetail' => $mDetail, 'myDetail' => $myDetail, 'modelDp' => $modelDp,)); ?>
