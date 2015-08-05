<?php
$this->setPageTitle('Registration Dps');


Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
    $('.search-form').slideToggle('fast');
    return false;
});
$('.search-form form').submit(function(){
    $.fn.yiiGridView.update('registration-dp-grid', {
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
    'id' => 'registration-dp-grid',
    'dataProvider' => $model->search(),
    'type' => 'striped condensed',
    'template' => '{summary}{pager}{items}{pager}',
    'columns' => array(
        array(
            'header' => 'Guest Name',
            'name' => 'registration_id',
            'type' => 'raw',
            'value' => '$data->Registration->Guest->name',
            'htmlOptions' => array('class' => 'span3', 'style' => 'text-align:left'),
            'headerHtmlOptions' => array('style' => 'text-align:center'),
        ),
        array(
            'header' => 'Amount',
            'name' => 'amount',
            'type' => 'raw',
            'value' => 'landa()->rp($data->amount)',
            'htmlOptions' => array('class' => 'span2', 'style' => 'text-align:right'),
            'headerHtmlOptions' => array('style' => 'text-align:center'),
        ),
        array(
            'header' => 'Date Deposit',
            'name' => 'created',
            'type' => 'raw',
            'value' => '$data->created',
            'htmlOptions' => array('class' => 'span2', 'style' => 'text-align:left'),
            'headerHtmlOptions' => array('style' => 'text-align:center'),
        ),
        array(
            'header' => 'Cashier',
            'name' => 'created_user_id',
            'type' => 'raw',
            'value' => '$data->Cashier->name',
            'htmlOptions' => array('class' => 'span2', 'style' => 'text-align:left'),
            'headerHtmlOptions' => array('style' => 'text-align:center'),
        ),
        /*
          'created_user_id',
          'modified',
         */
        array(
            'class' => 'bootstrap.widgets.TbButtonColumn',
            'template' => '{view} {update} {delete}',
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

