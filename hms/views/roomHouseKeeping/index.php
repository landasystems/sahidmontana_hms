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
    'items'=> array(
        array('label' => 'Create', 'icon' => 'icon-plus', 'url' => Yii::app()->controller->createUrl('create'), 'linkOptions' => array()),
        array('label' => 'List Data', 'icon' => 'icon-th-list', 'url' => Yii::app()->controller->createUrl('index'), 'active' => true, 'linkOptions' => array()),
        array('label' => 'Pencarian', 'icon' => 'icon-search', 'url' => '#', 'linkOptions' => array('class' => 'search-button')),
        array('label' => 'Export ke PDF', 'icon' => 'icon-download', 'url' => Yii::app()->controller->createUrl('GeneratePdf'), 'linkOptions' => array('target' => '_blank'), 'visible' => true),
        array('label' => 'Export ke Excel', 'icon' => 'icon-download', 'url' => Yii::app()->controller->createUrl('GenerateExcel'), 'linkOptions' => array('target' => '_blank'), 'visible' => true),
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
    'id' => 'room-grid',
    'dataProvider' => $model->search(),
    'type' => 'striped condensed',
    'template' => '{summary}{pager}{items}{pager}',
    'columns' => array(
        'id',
        'number',
        'RoomType.name',
        'floor',
        'status',
        'bed',
        array(
            'name' => 'House Keeper',
        ),
        array(
            'class' => 'bootstrap.widgets.TbButtonColumn',
            'template' => '{update}',
            'buttons' => array(
                'update' => array(
                    'label' => 'Edit',
                    'options' => array(
                        'class' => 'btn btn-small update'
                    )
                ),
            ),
            'htmlOptions' => array('style' => 'width: 125px'),
        )
    ),
));
?>

