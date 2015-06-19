<?php
/* @var $this DateConfigController */
/* @var $dataProvider CActiveDataProvider */

$this->setPageTitle('Auto Number Configuration Setting');
$this->breadcrumbs = array(
    'Auto Number Configuration Setting',
);

$this->beginWidget('zii.widgets.CPortlet', array(
    'htmlOptions' => array(
        'class' => ''
    )
));
$this->widget('bootstrap.widgets.TbMenu', array(
    'type' => 'pills',
    'items' => array(
        array('visible' => landa()->checkAccess('DateConfig', 'r'), 'label' => 'Daftar', 'icon' => 'icon-th-list', 'url' => Yii::app()->controller->createUrl('index'), 'active' => true, 'linkOptions' => array()),
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
</div>
<?php
$this->widget('bootstrap.widgets.TbGridView', array(
        'id' => 'date-config-grid',
        'dataProvider' => $model->search(),
        'type' => 'striped bordered condensed',
        'template' => '{summary}{pager}{items}{pager}',
        'columns' => array(
//            array(
//                'name' => 'id',
//                'header' => 'Id',
//                'value' => '$data->id',
//                'htmlOptions' => array('style' => 'text-align:center;width:5%'),
//            ),
            array(
                'name' => 'year',
                'header' => 'Tahun',
                'value' => '$data->year',
                'htmlOptions' => array('style' => 'text-align:center'),
            ),
            array(
                'name' => 'departement_id',
                'header' => 'Departemen',
                'value' => '$data->Departement->name',
                'htmlOptions' => array('style' => 'text-align:left'),
            ),
            array(
                'name' => 'cash_in',
                'header' => 'Kas Masuk',
                'value' => '$data->cash_in',
                'htmlOptions' => array('style' => 'text-align:center'),
            ),
            array(
                'name' => 'cash_out',
                'header' => 'Kas Keluar',
                'value' => '$data->cash_out',
                'htmlOptions' => array('style' => 'text-align:center'),
            ),
            array(
                'name' => 'bk_in',
                'header' => 'Bank Masuk',
                'value' => '$data->bk_in',
                'htmlOptions' => array('style' => 'text-align:center'),
            ),
            array(
                'name' => 'bk_out',
                'header' => 'Bank Keluar',
                'value' => '$data->bk_out',
                'htmlOptions' => array('style' => 'text-align:center'),
            ),
            array(
                'name' => 'jurnal',
                'header' => 'Jurnal',
                'value' => '$data->jurnal',
                'htmlOptions' => array('style' => 'text-align:center'),
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
//                        'visible' => '(!isset($data->AccAdmin->status) or $data->AccAdmin->status != \'confirm\') and (!isset($data->AccManager->status) or $data->AccManager->status != \'confirm\') and landa()->checkAccess(\'AccCashIn\', \'u\')',
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
                    ),
                ),
                'htmlOptions' => array('style' => 'width: 175px'),
            )
        ),
    ));