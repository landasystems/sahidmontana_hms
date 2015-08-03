<?php
$this->setPageTitle('Bill Cashiers Approval');


Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
    $('.search-form').slideToggle('fast');
    return false;
});
$('.search-form form').submit(function(){
    $.fn.yiiGridView.update('bill-cashier-grid', {
        data: $(this).serialize()
    });
    return false;
});
");
?>

<?php
//$this->beginWidget('zii.widgets.CPortlet', array(
//    'htmlOptions' => array(
//        'class' => ''
//    )
//));
//$this->widget('bootstrap.widgets.TbMenu', array(
//    'type' => 'pills',
//    'items' => array(
//        array('label' => 'Create', 'icon' => 'icon-plus', 'url' => Yii::app()->controller->createUrl('create'), 'linkOptions' => array()),
//        array('label' => 'List Data', 'icon' => 'icon-th-list', 'url' => Yii::app()->controller->createUrl('index'), 'active' => true, 'linkOptions' => array()),
//        array('label' => 'Pencarian', 'icon' => 'icon-search', 'url' => '#', 'linkOptions' => array('class' => 'search-button')),
//        array('label' => 'Export ke PDF', 'icon' => 'icon-download', 'url' => Yii::app()->controller->createUrl('GeneratePdf'), 'linkOptions' => array('target' => '_blank'), 'visible' => true),
//        array('label' => 'Export ke Excel', 'icon' => 'icon-download', 'url' => Yii::app()->controller->createUrl('GenerateExcel'), 'linkOptions' => array('target' => '_blank'), 'visible' => true),
//    ),
//));
//$this->endWidget();
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
    'id' => 'bill-cashier-grid',
    'dataProvider' => $model->search(),
    'type' => 'striped bordered condensed',
    'template' => '{summary}{pager}{items}{pager}',
    'columns' => array(
        array(
            'header' => 'Date',
            'name' => 'created',
            'type' => 'raw',
            'value' => 'date("l Y-m-d H:i:s",strtotime($data->created))',
        ),
        array(
            'header' => 'Cashier',
            'name' => 'created_user_id',
            'type' => 'raw',
            'value' => '$data->Cashier->name',
        ),
        array(
            'header' => 'Approved',
            'name' => 'approved_user_id',
            'type' => 'raw',
            'value' => '(!empty($data->approved_user_id))?$data->Approved->name:""',
        ),
        array(
            'class' => 'bootstrap.widgets.TbButtonColumn',
            'template' => '{approve}',
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
                'approve' => array(
                    'label' => 'Approve',
                    'icon' => 'icon-check',
                    'url' => '"viewApproving/".$data->id',
                    'options' => array(
                        'class' => 'btn btn-small'
                    )
                ),
                'delete' => array(
                    'label' => 'Hapus',
                    'options' => array(
                        'class' => 'btn btn-small delete'
                    )
                )
            ),
            'htmlOptions' => array('style' => 'width: 25px;text-align:center'),
        )
    ),
));
?>

