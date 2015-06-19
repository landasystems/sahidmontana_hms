<?php
/* @var $this AccFormattingController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Acc Formattings',
);
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
        array('visible' => landa()->checkAccess('AccCoa', 'c'), 'label' => 'Tambah', 'icon' => 'icon-plus', 'url' => Yii::app()->controller->createUrl('create'), 'linkOptions' => array()),
        array('visible' => landa()->checkAccess('AccCoa', 'r'), 'label' => 'Daftar', 'icon' => 'icon-th-list', 'url' => Yii::app()->controller->createUrl('index'), 'active' => true, 'linkOptions' => array()),
        array('label' => 'Pencarian', 'icon' => 'icon-search', 'url' => '#', 'linkOptions' => array('class' => 'search-button')),
        array('label'=>'Print', 'icon'=>'icon-print', 'url'=>'javascript:void(0);return false', 'linkOptions'=>array('onclick'=>'printDiv();return false;')),
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
</div>

<?php 
$this->widget('bootstrap.widgets.TbGridView', array(
    'id' => 'acc-formatting',
    'dataProvider' => $model->search(),
    'type' => 'striped bordered condensed',
    'template' => '{summary}{pager}{items}{pager}',
    'columns' => array(
        array(
            'name' => 'id',
            'type' => 'raw',
            'value' => '$data->id',
            'htmlOptions' => array('style' => 'width:5%;text-align:center'),
        ),
        array(
            'name' => 'departement_id',
            'type' => 'raw',
            'value' => '$data->Departement->name',
            'htmlOptions' => array('style' => 'width: 20%;text-align:Left'),
        ),
        array(
            'name' => 'cash_in',
            'type' => 'raw',
            'value' => '$data->cash_in',
            'htmlOptions' => array('style' => 'width: 8%;text-align:center'),
        ),
        array(
            'name' => 'cash_in_approval',
            'type' => 'raw',
            'value' => '$data->cash_in_approval',
            'htmlOptions' => array('style' => 'width: 8%;text-align:center'),
        ),
        array(
            'name' => 'bank_in_approval',
            'type' => 'raw',
            'value' => '$data->bank_in_approval',
            'htmlOptions' => array('style' => 'width: 8%;text-align:center'),
        ),
        array(
            'name' => 'cash_out',
            'type' => 'raw',
            'value' => '$data->cash_out',
            'htmlOptions' => array('style' => 'width: 8%;text-align:center'),
        ),
        array(
            'name' => 'cash_out_approval',
            'htmlOptions' => array('style' => 'width: 8%;text-align:center'),
            'type' => 'raw',
            'value' => '$data->cash_out_approval',
            'htmlOptions' => array('style' => 'width: 8%;text-align:center'),
        ),
        array(
            'name' => 'bank_out_approval',
            'type' => 'raw',
            'value' => '$data->bank_out_approval',
            'htmlOptions' => array('style' => 'width: 8%;text-align:center'),
        ),
        array(
            'name' => 'journal',
            'type' => 'raw',
            'value' => '$data->journal',
            'htmlOptions' => array('style' => 'width: 8%;text-align:center'),
        ),
        array(
            'name' => 'journal_approval',
            'type' => 'raw',
            'value' => '$data->journal_approval',
            'htmlOptions' => array('style' => 'width: 8%;text-align:center'),
        ),
        array(
            'class' => 'bootstrap.widgets.TbButtonColumn',
            'template' => '{view} {update} {delete}',
            'buttons' => array(
                'view' => array(
                    'label' => 'Lihat',
                    'options' => array(
                        'class' => 'btn btn-small view'
                    )
                ),
                'update' => array(
                    'label' => 'Edit',
//                    'visible' => '$data->code!="1" && $data->code!="2" && $data->code!="3" && $data->code!="4" && $data->code!="5"',
                    'options' => array(
                        'class' => 'btn btn-small update'
                    )
                ),
                'delete' => array(
                    'label' => 'Hapus',
//                    'visible' => '$data->code!="1" && $data->code!="2" && $data->code!="3" && $data->code!="4" && $data->code!="5"',
                    'options' => array(
                        'class' => 'btn btn-small delete'
                    )
                )
            ),
            'htmlOptions' => array('style' => 'width: 125px;text-align:center'),
        )
    ),
));
