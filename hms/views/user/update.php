<?php

$this->setPageTitle('Users : ' . $model->name);
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
        array('label' => 'Create', 'icon' => 'icon-plus', 'url' => Yii::app()->controller->createUrl('create')),
        array('label' => 'List Data', 'icon' => 'icon-th-list', 'url' => Yii::app()->controller->createUrl('index')),
    ),
));
$this->endWidget();
?>

<?php echo $this->renderPartial('_form', array('model' => $model)); ?>
