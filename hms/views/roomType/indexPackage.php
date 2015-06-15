<?php

$buton = '';

if (landa()->checkAccess('RoomType', 'r'))
    $buton .= '{view}';

if (landa()->checkAccess('RoomType', 'u'))
    $buton .= '{update} ';

if (landa()->checkAccess('RoomType', 'd'))
    $buton .= '{delete}';
$this->widget('bootstrap.widgets.TbGridView', array(
    'id' => 'room-type-grid',
    'dataProvider' => $model2->search(),
    'type' => 'striped bordered condensed',
    'template' => '{summary}{pager}{items}{pager}',
    'columns' => array(
        array(
            'header'=>'Room Types',
            'name'=>'name',
            'type'=>'raw',
            'value'=>'"$data->shortDesc"',
            'htmlOptions'=>array('class'=>'span5'),
        ),
        array(
            'header' => 'Rate',
            'name' => 'rate',
            'type' => 'raw',
            'value' => '"$data->hasil"',
            'htmlOptions'=>array('class'=>'span6'),
           
        ),
        array(
            'class' => 'bootstrap.widgets.TbButtonColumn',
            'template' => $buton,
            'buttons' => array(
                'view' => array(
                    'label' => 'View',
                    'options' => array(
                        'class' => 'btn btn-small view'
                    )
                ),
                'update' => array(
                    'label' => 'Edit',
                    'options' => array(
                        'class' => 'btn btn-small update'
                    )
                ),
                'delete' => array(
                    'label' => 'Hapus',
                    'options' => array(
                        'class' => 'btn btn-small delete'
                    )
                )
            ),
            'htmlOptions' => array('style' => 'text-align:center;width: 125px'),
        )
    ),
));
?>