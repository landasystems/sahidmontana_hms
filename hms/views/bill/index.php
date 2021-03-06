<?php
$this->setPageTitle('Bills');

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
    $('.search-form').slideToggle('fast');
    return false;
});
$('.search-form form').submit(function(){
    $.fn.yiiGridView.update('bill-grid', {
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
        array('label' => 'Create', 'icon' => 'icon-plus', 'url' => Yii::app()->controller->createUrl('create'), 'linkOptions' => array()),
        array('label' => 'List Data', 'icon' => 'icon-th-list', 'url' => Yii::app()->controller->createUrl('index'), 'active' => true, 'linkOptions' => array()),
        array('label' => 'Search', 'icon' => 'icon-search', 'url' => '#', 'linkOptions' => array('class' => 'search-button')),
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
$this->widget('bootstrap.widgets.TbGridView', array(
    'id' => 'bill-grid',
    'dataProvider' => $model->search(),
    'type' => 'striped condensed',
    'template' => '{summary}{pager}{items}{pager}',
    'columns' => array(
        'code',
//        array(
//            'header' => 'Guest',
//            'name' => 'guest_user_id',
//            'type' => 'raw',
//            'value' => '$data->Guest->guestName',
//        ),
        array(
            'header' => 'Guest',
            'name' => 'pax_name',
            'type' => 'raw',
            'value' => '$data->pax_name',
        ),
        array(
            'header' => 'Room',
            'name' => 'id',
            'type' => 'raw',
            'htmlOptions' => array('class'=>'span3'),
            'value' => '$data->roomNumber',
        ),
        array(
            'header' => 'Arrival',
            'name' => 'arrival_time',
            'type' => 'raw',
            'value' => 'date("d M",strtotime($data->arrival_time))',
        ),
        array(
            'header' => 'Depart',
            'name' => 'departure_time',
            'type' => 'raw',
            'value' => 'date("d M",strtotime($data->departure_time))',
        ),
        array(
            'header' => 'Total',
            'name' => 'total',
            'type' => 'raw',
            'value' => 'landa()->rp($data->total)',
        ),
        array(
            'header' => 'Created',
            'name' => ' created',
            'type' => 'raw',
            'value' => 'date("d M Y, H:i    ",strtotime($data->created))',
        ),
        /*
          'ca_user_id',
          'refund',
          'total',
         */
        array(
            'class' => 'bootstrap.widgets.TbButtonColumn',
            'template' => '{view} {update}',
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
                    'label' => 'Delete',
                    'options' => array(
                        'class' => 'btn btn-small delete'
                    )
                )
            ),
            'htmlOptions' => array('style' => 'width: 85px;text-align:center;'),
        )
    ),
));
?>

