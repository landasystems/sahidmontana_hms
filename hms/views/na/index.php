<?php
$this->setPageTitle('Night Audit');


Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
    $('.search-form').slideToggle('fast');
    return false;
});
$('.search-form form').submit(function(){
    $.fn.yiiGridView.update('na-grid', {
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
$this->widget('bootstrap.widgets.TbGridView', array(
    'id' => 'na-grid',
    'dataProvider' => $model->search(),
    'type' => 'striped bordered condensed',
    'template' => '{summary}{pager}{items}{pager}',
    'columns' => array(
        array(
            'header' => 'Date NA',
            'name' => 'date_na',
            'type' => 'raw',
            'value' => 'date("D, d-M-Y",strtotime($data->date_na))',
        ),
        array(
            'header' => 'Net Sales',
            'name' => 'global_cash',
            'type' => 'raw',
            'value' => 'landa()->rp($data->global_cash,false)',
        ),
        array(
            'header' => 'Gross Sales',
            'name' => 'global_total',
            'type' => 'raw',
            'value' => 'landa()->rp($data->global_total,false)',
        ),
//        array(
//            'header' => 'Guest Ledger',
//            'name' => 'global_gl',
//            'type' => 'raw',
//            'value' => 'landa()->rp($data->global_gl)',
//        ),
//        array(
//            'header' => 'City Ledger',
//            'name' => 'global_cash',
//            'type' => 'raw',
//            'value' => 'landa()->rp($data->global_cl)',
//        ),
//        array(
//            'header' => 'Total',
//            'name' => 'global_total',
//            'type' => 'raw',
//            'value' => 'landa()->rp($data->global_total)',
//        ),
        array(
            'header' => 'Audit By',
            'name' => 'created_user_id',
            'type' => 'raw',
            'value' => '(isset($data->Cashier->name))?$data->Cashier->name:""',
        ),
        array(
            'header' => 'Prepared Time',
            'name' => 'created',
            'type' => 'raw',
            'value' => 'date("D, d-M-Y H:i",strtotime($data->created))',
        ),
        /*
          'created',
          'created_user_id',
          'modified',
         */
        array(
            'class' => 'bootstrap.widgets.TbButtonColumn',
            'template' => '{view}',
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
            'htmlOptions' => array('style' => 'width: 50px;text-align:center'),
        )
    ),
));
?>

