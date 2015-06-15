<?php

$this->widget(
        'bootstrap.widgets.TbSelect2', array(
    'name' => 'linkedRoom',
    'data' => $data,
    'value' => $value,
    'options' => array('width' => '50%','height' => '30px',),
    'htmlOptions' => array(
        'multiple' => 'multiple',
        'id' => 'linkedRoom',
    ),
        )
);
?>

