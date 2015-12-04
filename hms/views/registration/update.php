<?php

$this->setPageTitle('Registrations | ' . $model->code);
$this->beginWidget('zii.widgets.CPortlet', array(
    'htmlOptions' => array(
        'class' => ''
    )
));
$this->widget('bootstrap.widgets.TbMenu', array(
    'type' => 'pills',
    'items' => array(
        array('label' => 'Create', 'icon' => 'icon-plus', 'url' => Yii::app()->controller->createUrl('create')),
        array('label' => 'List Data', 'icon' => 'icon-th-list', 'url' => Yii::app()->controller->createUrl('index')),
        array('visible' => isset($_GET['v']), 'label' => 'Print', 'icon' => 'icon-print', 'url' => 'javascript:void(0);return false', 'linkOptions' => array('onclick' => 'printDiv("printableArea");return false;')),
    ),
));
$this->endWidget();
$myDetail = RegistrationDetail::model()->findByAttributes(array('registration_id' => $model->id));
?>

<?php echo $this->renderPartial('_form', array('model' => $model, 'mDetail' => $mDetail, 'myDetail' => $myDetail, 'modelDp' => $modelDp,)); ?>
