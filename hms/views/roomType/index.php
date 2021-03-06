<?php
$this->setPageTitle('Room Type');

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
        array('label' => 'Create', 'icon' => 'icon-plus', 'url' => Yii::app()->controller->createUrl('create'), 'linkOptions' => array()),
        array('label' => 'List Data', 'icon' => 'icon-th-list', 'url' => Yii::app()->controller->createUrl('index'), 'active' => true, 'linkOptions' => array()),
        array('label' => 'Search & Export', 'icon' => 'icon-search', 'url' => '#', 'linkOptions' => array('class' => 'search-button')),
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
$buton = '{view}{update}{delete}';

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
            'type' => 'striped condensed',
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
                            'label' => 'Delete',
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
            'type' => 'striped condensed',
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
                            'label' => 'Delete',
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
