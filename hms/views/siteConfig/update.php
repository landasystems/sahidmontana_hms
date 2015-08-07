<?php

$this->setPageTitle(' Site Config');

$this->beginWidget('zii.widgets.CPortlet', array(
    'htmlOptions' => array(
        'class' => ''
    )
));

$this->endWidget();

echo $this->renderPartial('_form', array('model' => $model));
?>