<?php
$this->setPageTitle('Deposites');


Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
    $('.search-form').slideToggle('fast');
    return false;
});
$('.search-form form').submit(function(){
    $.fn.yiiGridView.update('deposite-grid', {
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

function by($data) {
    $dp_by = array('cash' => 'Cash', 'cc' => 'Credit Card', 'debit' => 'Debit Card', 'ca' => 'City Ledger');
    return $dp_by[$data];
}

$this->widget('bootstrap.widgets.TbGridView', array(
    'id' => 'deposite-grid',
    'dataProvider' => $model->search(),
    'type' => 'striped condensed',
    'template' => '{summary}{pager}{items}{pager}',
    'columns' => array(
        'code',
        array(
            'header' => 'Guest',
            'name' => 'guest_user_id',
            'type' => 'raw',
            'value' => '(isset($data->Guest->name)) ? $data->Guest->name : ""',
        ),
        array(
            'header' => 'By',
            'name' => 'dp_by',
            'type' => 'raw',
            'value' => 'by($data->dp_by)',
        ),
        array(
            'header' => 'Amount',
            'name' => 'amount',
            'type' => 'raw',
            'value' => 'landa()->rp($data->amount)',
        ),
        array(
            'header' => 'Used',
            'name' => 'used_amount',
            'type' => 'raw',
            'value' => 'landa()->rp($data->used_amount)',
        ),
        array(
            'header' => 'Balance',
            'name' => 'balance_amount',
            'type' => 'raw',
            'value' => 'landa()->rp($data->balance_amount)',
        ),
        'description',
        array(
            'header' => 'Date',
            'name' => 'created',
            'type' => 'raw',
            'value' => '$data->created',
        ),
        array(
            'header' => 'Applied',
            'name' => 'is_applied',
            'type' => 'raw',
            'htmlOptions' => array('style' => 'text-align:center'),
            'value' => '($data->is_used==0)?"<span class=\"label label-warning\">Not Yet</span>":"<span class=\"label label-info\">Yes</span>"',
        ),
        array(
            'name' => 'created_user_id',
            'type' => 'raw',
            'value' => '(isset($data->Cashier->name)) ? $data->Cashier->name : ""',
        ),
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

