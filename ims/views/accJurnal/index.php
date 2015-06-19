<?php
$this->setPageTitle('Jurnal/ Jurnal Memorial');
$this->breadcrumbs = array(
    'Acc Jurnals',
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
    $('.search-form').slideToggle('fast');
    return false;
});
$('.search-form form').submit(function(){
    $.fn.yiiGridView.update('acc-jurnal-grid', {
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
        array('visible' => landa()->checkAccess('AccJurnal', 'c'), 'label' => 'Tambah', 'icon' => 'icon-plus', 'url' => Yii::app()->controller->createUrl('create'), 'linkOptions' => array()),
        array('visible' => landa()->checkAccess('AccJurnal', 'r'), 'label' => 'Daftar', 'icon' => 'icon-th-list', 'url' => Yii::app()->controller->createUrl('index'), 'active' => true, 'linkOptions' => array()),
        array('label' => 'Pencarian & Eksport Excel', 'icon' => 'icon-search', 'url' => '#', 'linkOptions' => array('class' => 'search-button')),
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
    'id' => 'acc-jurnal-grid',
    'dataProvider' => $model->search(),
    'type' => 'striped bordered condensed',
    'template' => '{summary}{pager}{items}{pager}',
    'columns' => array(
        'code',
        array(
            'name' => 'date_trans',
            'type' => 'raw',
            'value' => 'date("d-M-Y", strtotime($data->date_trans))',
        ),
        'code_acc',
        array(
            'name' => 'date_posting',
            'type' => 'raw',
            'value' => '(isset($data->date_posting)) ? date("d-M-Y", strtotime($data->date_posting)) : ""',
        ),
//            'description',
       
        array(
            'name' => 'Total_debet',
            'header' => 'Total',
            'value' => 'landa()->rp($data->total_debet)',
            'htmlOptions' => array('style' => 'text-align:right'),
        ),
        array(
            'class' => 'bootstrap.widgets.TbButtonColumn',
            'template' => '{approval} {view} {update} {delete}',
            'buttons' => array(
                'approval' => array(
                    'label' => 'Approve',
                    'icon' => 'icon-ok',
                    'visible' => 'empty($data->code_acc)',
                    'url' => 'Yii::app()->createUrl("accJurnal/update", array("id"=>$data->id,"act" => "approve"))',
                    'options' => array(
                        'class' => 'btn btn-small ',
                    )
                ),
                'cancel' => array(
                    'label' => 'Cancel Approve',
                    'icon' => 'brocco-icon-cancel',
                    'visible' => '(isset($data->AccManager->status) and $data->AccManager->status == \'confirm\')',
                    'url' => 'Yii::app()->createUrl("accJurnal/unapprove", array("id"=>$data->id))',
                    'options' => array(
                        'class' => 'btn btn-small ',
                    )
                ),
                'view' => array(
                    'label' => 'Lihat',
                    'options' => array(
                        'class' => 'btn btn-small view'
                    )
                ),
                'update' => array(
                    'label' => 'Edit',
                    'visible' => 'landa()->checkAccess(\'AccCashIn\', \'u\')',
                    'url' => 'Yii::app()->createUrl("accJurnal/update", array("id"=>$data->id,"act"=>"edit"))',
                    'options' => array(
                        'class' => 'btn btn-small update'
                    )
                ),
                'delete' => array(
                    'label' => 'Hapus',
//                        'visible' => '(!isset($data->AccAdmin->status) or $data->AccAdmin->status != \'confirm\') and (!isset($data->AccManager->status) or $data->AccManager->status != \'confirm\') and landa()->checkAccess(\'AccCashIn\', \'d\')',
                    'options' => array(
                        'class' => 'btn btn-small delete'
                    )
                )
            ),
            'htmlOptions' => array('style' => 'width: 150px'),
        )
    ),
));
?>
