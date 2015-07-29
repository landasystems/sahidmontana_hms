<?php

$this->setPageTitle('Auto Number Setting');
$this->widget('bootstrap.widgets.TbGridView', array(
        'id' => 'date-config-grid',
        'dataProvider' => $model->search(),
        'type' => 'striped bordered condensed',
        'template' => '{summary}{pager}{items}{pager}',
        'columns' => array(
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