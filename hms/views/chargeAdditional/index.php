<?php
$this->setPageTitle('Charge Additionals');
$this->breadcrumbs = array(
    'Charge Additionals',
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
    $('.search-form').slideToggle('fast');
    return false;
});
$('.search-form form').submit(function(){
    $.fn.yiiGridView.update('charge-additional-grid', {
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
        array('label' => 'Export Excel', 'icon' => 'icomoon-icon-file-excel', 'url' => Yii::app()->controller->createUrl('chargeAdditional/generateExcel'), 'linkOptions' => array()),
//        array('label' => 'Export ke PDF', 'icon' => 'icon-download', 'url' => Yii::app()->controller->createUrl('GeneratePdf'), 'linkOptions' => array('target' => '_blank'), 'visible' => true),
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
<div class="form">
    <?php
    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id' => 'chargeAdditional-form',
        'enableAjaxValidation' => false,
        'method' => 'post',
        'type' => 'horizontal',
        'htmlOptions' => array(
            'enctype' => 'multipart/form-data'
        )
    ));
    ?>
    <button type="submit" name="delete" value="dd" style="margin-left: 10px" class="btn btn-danger pull-right"><span class="icon16 brocco-icon-trashcan white"></span> Delete</button>    
    <button type="submit" name="buttonunpublish" value="dd" style="margin-left: 10px" class="btn btn-warning pull-right"><span class="icon16 entypo-icon-close white"></span> Unpublish</button>
    <button type="submit" name="buttonpublish" value="dd" style="margin-left: 10px" class="btn btn-info pull-right"><span class="icon16 entypo-icon-publish white"></span> Publish </button>
    <br>
    <br>
    <?php
    $this->widget('bootstrap.widgets.TbGridView', array(
        'id' => 'charge-additional-grid',
        'dataProvider' => $model->search(),
        'type' => 'striped bordered condensed',
        'template' => '{items}{summary}{pager}',
        'columns' => array(
            array(
                'class' => 'CCheckBoxColumn',
                'selectableRows' => 2,
                'checkBoxHtmlOptions' => array(
                    'name' => 'ceckbox[]',
                    'value' => '$data->id',
                ),
            ),
            array(
                'header' => 'Name',
                'name' => 'name',
                'type' => 'raw',
                'value' => 'strtoupper($data->name)',
                'htmlOptions' => array('style' => 'width: ')
            ),
            array(
                'header' => 'Departement',
                'name' => 'name',
                'type' => 'raw',
                'value' => '"$data->category"',
                'htmlOptions' => array('style' => 'width: ')
            ),
            array(
                'header' => 'Account',
                'name' => 'account_id',
                'type' => 'raw',
                'value' => '(isset($data->Account->name)) ? $data->Account->name : ""',
                'htmlOptions' => array('style' => 'width: ')
            ),
            array(
                'header' => 'Type Transaction',
                'name' => 'type_transaction',
                'value' => '$data->fullTransaction',
                'htmlOptions' => array('style' => 'text-align: left;')
            ),
            array(
                'header' => 'Charge',
                'name' => 'charge',
                'type' => 'raw',
                'value' => '"$data->price"',
                'htmlOptions' => array('style' => 'width: 100px;')
            ),
            'discount',
            array(
                'header' => 'Total Charge',
                'name' => 'charge',
                'type' => 'raw',
                'value' => '$data->charge - (($data->discount/100)*$data->charge) ',
                'htmlOptions' => array('style' => 'width: 100px;')
            ),
            array(
                'name' => 'is_publish',
                'type' => 'raw',
                'value' => '($data->is_publish==1)?"<span class=\"label label-info\">&nbsp;&nbsp;&nbsp;Yes&nbsp;&nbsp;&nbsp;</span>":"<span class=\"label label-warning\">&nbsp;&nbsp;&nbsp;No&nbsp;&nbsp;&nbsp;</span>"',
                'htmlOptions' => array('style' => 'width: 100px;text-align:center')
            ),
//        'descripstion',
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


    <?php $this->endWidget(); ?>

</div>