<?php
$this->setPageTitle('Room Types');
$this->breadcrumbs = array(
    'Room Types',
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
    $('.search-form').slideToggle('fast');
    return false;
});
$('.search-form form').submit(function(){
    $.fn.yiiGridView.update('room-type-grid', {
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
        array('label' => 'Create', 'icon' => 'icon-plus', 'url' => Yii::app()->controller->createUrl('create'), 'linkOptions' => array(), 'visible' => landa()->checkAccess('RoomType', 'c')),
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

if (landa()->checkAccess('RoomType', 'r'))
    $buton .= '{view}';

if (landa()->checkAccess('RoomType', 'u'))
    $buton .= '{update} ';

if (landa()->checkAccess('RoomType', 'd'))
    $buton .= '{delete}';
?>

<ul class="nav nav-tabs" id="myTab">
    <li class="active"><a href="#dsr">Room</a></li>                                                    
    <li><a href="#analysis">Package</a></li>

</ul>
<div class="tab-content">
    <div class="tab-pane active" id="dsr">
        <?php
        $this->widget('bootstrap.widgets.TbGridView', array(
            'id' => 'room-type-grid2',
            'dataProvider' => $model2->search(),
            'type' => 'striped bordered condensed',
            'template' => '{summary}{pager}{items}{pager}',
            'columns' => array(
                array(
                    'header' => 'Room Types',
                    'name' => 'name',
                    'type' => 'raw',
                    'value' => '"$data->shortDesc"',
                    'htmlOptions' => array('class' => 'span5'),
                ),
                array(
                    'header' => 'Rate',
                    'name' => 'rate',
                    'type' => 'raw',
                    'value' => '"$data->hasil"',
                    'htmlOptions' => array('class' => 'span6'),
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
    </div>
    <div class="tab-pane" id="analysis">
        <?php
        $this->widget('bootstrap.widgets.TbGridView', array(
            'id' => 'room-type-grid3',
            'dataProvider' => $model->search(),
            'type' => 'striped bordered condensed',
            'template' => '{summary}{pager}{items}{pager}',
            'columns' => array(
                array(
                    'header' => 'Room Types',
                    'name' => 'name',
                    'type' => 'raw',
                    'value' => '"$data->shortDesc"',
                    'htmlOptions' => array('class' => 'span5'),
                ),
                array(
                    'header' => 'Rate',
                    'name' => 'rate',
                    'type' => 'raw',
                    'value' => '"$data->hasil"',
                    'htmlOptions' => array('class' => 'span6'),
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
    </div>


</div> 
