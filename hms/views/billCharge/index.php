<?php
$this->setPageTitle('Bill Charges');


Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
    $('.search-form').slideToggle('fast');
    return false;
});
$('.search-form form').submit(function(){
    $.fn.yiiGridView.update('bill-charge-grid', {
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
    'id' => 'bill-charge-grid',
    'dataProvider' => $model->search(),
    'type' => 'striped condensed',
    'template' => '{summary}{pager}{items}{pager}',
    'columns' => array(
        array(
            'header' => 'Code',
            'name' => 'code',
            'type' => 'raw',
            'value' => '$data->code',
        ),
        array(
            'header' => 'Departement',
            'name' => 'description',
            'type' => 'raw',
            'value' => '$data->departement',
        ),
        array(
            'header' => 'Is Paid',
            'name' => 'is_temp',
            'type' => 'raw',
            'htmlOptions' => array('style' => 'text-align:center'),
            'value' => '($data->is_temp==1)?"<span class=\'label label-warning\'>Not Yet</span>":"<span class=\'label label-info\'>Yes</span>"',
        ),
        array(
            'header' => 'Cover',
            'name' => 'cover',
            'type' => 'raw',
            'value' => '$data->cover',
        ),
        array(
            'header' => 'Note',
            'name' => 'description',
            'type' => 'raw',
            'value' => '$data->description',
        ),
        array(
            'header' => 'Created',
            'name' => 'created',
            'type' => 'raw',
            'value' => 'date("d-m-Y, H:i", strtotime($data->created))',
        ),
        array(
            'header' => 'Total',
            'name' => 'total',
            'type' => 'raw',
            'htmlOptions' => array('style' => 'text-align:right'),
            'value' => '(!empty($data->total))?landa()->rp($data->total, false):landa()->rp(0)',
        ),
        array(
            'header' => 'Cashier',
            'name' => 'created_user_id',
            'type' => 'raw',
            'value' => '(isset($data->Cashier->name)) ? $data->Cashier->name : ""',
        ),
        array(
            'class' => 'bootstrap.widgets.TbButtonColumn',
            'template' => '{view} {update} {delete}',
            'buttons' => array(
                'view' => array(
                    'label' => 'View / Print',
                    'options' => array(
                        'class' => 'btn btn-small view'
                    )
                ),
                'update' => array(
                    'label' => 'Edit',
                    'options' => array(
                        'class' => 'btn btn-small update'
                    ),
                    'visible'=>'$data->is_temp'
                ),
                'delete' => array(
                    'label' => 'Hapus',
                    'visible'=>'landa()->checkAccess("BillCharge", "d")',
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
<div class="clearfix"></div>
<div class="alert alert-danger fade in">
    <button type="button" class="close" data-dismiss="alert">×</button>
    <strong>Important! </strong> Transactions that have been paid can not be edited.
</div> 



