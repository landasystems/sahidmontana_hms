<?php
$this->setPageTitle('Rooms');

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
    $('.search-form').slideToggle('fast');
    return false;
});
$('.search-form form').submit(function(){
    $.fn.yiiGridView.update('room-grid', {
        data: $(this).serialize()
    });
    return false;
});
");
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
        array('label' => 'Create', 'icon' => 'icon-plus', 'url' => Yii::app()->controller->createUrl('create'), 'linkOptions' => array(),'visible'=>landa()->checkAccess('Room','c')),
        array('label' => 'List Data', 'icon' => 'icon-th-list', 'url' => Yii::app()->controller->createUrl('index'), 'active' => true, 'linkOptions' => array()),
        array('label' => 'Pencarian', 'icon' => 'icon-search', 'url' => '#', 'linkOptions' => array('class' => 'search-button')),
//        array('label' => 'Export ke PDF', 'icon' => 'icon-download', 'url' => Yii::app()->controller->createUrl('GeneratePdf'), 'linkOptions' => array('target' => '_blank'), 'visible' => true),
//        array('label' => 'Export ke Excel', 'icon' => 'icon-download', 'url' => Yii::app()->controller->createUrl('GenerateExcel'), 'linkOptions' => array('target' => '_blank'), 'visible' => true),
    ),
));
$this->endWidget();
?>



<div class="search-form" style="display:none">
    <?php
    $this->renderPartial('_search', array(
        'model' => $model,
    ));
    ?>
</div><!-- search-form -->


<?php
$buton = '';

if (landa()->checkAccess('Room', 'r'))
    $buton .= '{view}';

if (landa()->checkAccess('Room', 'u'))
    $buton .= '{update} ';

if (landa()->checkAccess('Room', 'd'))
    $buton .= '{delete}';

$this->widget('bootstrap.widgets.TbGridView', array(
    'id' => 'room-grid',
    'dataProvider' => $model->search(),
    'type' => 'striped condensed',
    'template' => '{summary}{pager}{items}{pager}',
    'columns' => array(
//        'number',
//        'RoomType.room_type_id',
        array(
            'header' => 'Room Number',
            'name' => 'number',
            'type' => 'raw',
            'value' => '$data->number',
            'htmlOptions' => array('class'=>'span2','style' => 'text-align:center'),
            'headerHtmlOptions' => array('style' => 'text-align:center'),
        ),
        array(
            'header' => 'Room Type',
            'name' => 'room_type_id',
            'type' => 'raw',
            'value' => '$data->RoomType["name"]',
            'htmlOptions' => array('class'=>'span2','style' => 'text-align:center'),
            'headerHtmlOptions' => array('style' => 'text-align:center'),
            
        ),
        array(
            'header' => 'Floor',
            'name' => 'floor',
            'type' => 'raw',
            'value' => '$data->floor',
            'htmlOptions' => array('class'=>'span2','style' => 'text-align:center'),
            'headerHtmlOptions' => array('style' => 'text-align:center'),
        ),      
        array(
            'header' => 'Bed',
            'name' => 'bed',
            'type' => 'raw',
            'value' => '$data->bed',
            'htmlOptions' => array('class'=>'span2','style' => 'text-align:center'),
            'headerHtmlOptions' => array('style' => 'text-align:center'),
        ),      
        
        array(
            'header' => 'Status',
            'name' => 'status',
            'type' => 'raw',
            'value' => '(empty($data->status))?"Vacant":ucwords($data->status)',
        ),
        
        array(
            'header' => 'Pax',
            'name' => 'status',
            'type' => 'raw',
            'value' => '(!empty($data->pax))?$data->pax:""',
        ),
        
        array(
            'header' => 'Registered By',
            'name' => 'registration_id',
            'type' => 'raw',
            'value' => '(!empty($data->registration_id))?$data->Registration->Guest->name:""',
        ),
        
        array(
            'header' => 'Guest Name',
            'name' => 'registration_id',
            'type' => 'raw',
            'value' => '$data->guestName',
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
            'htmlOptions' => array('style' => 'width: 125px;text-align:center'),
        )
    ),
));
?>

