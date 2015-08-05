<?php
$this->setPageTitle('Access Roles');

$this->beginWidget('zii.widgets.CPortlet', array(
    'htmlOptions' => array(
        'class' => ''
    )
));
$this->widget('bootstrap.widgets.TbMenu', array(
    'type' => 'pills',
    'items' => array(
        array('visible' => landa()->checkAccess('GroupUser', 'c'), 'label' => 'Create', 'icon' => 'icon-plus', 'url' => Yii::app()->controller->createUrl('create'), 'linkOptions' => array()),
        array('label' => 'List Data', 'icon' => 'icon-th-list', 'url' => Yii::app()->controller->createUrl('index'), 'active' => true, 'linkOptions' => array()),
    ),
));
$this->endWidget();
?>
<?php
$buton = "";
    if (landa()->checkAccess('GroupUser', 'r')) {
        $buton .= '{view}';
    }

    if (landa()->checkAccess('GroupUser', 'u')) {
        $buton .= '{update}';
    }
    if (landa()->checkAccess('GroupUser', 'd')) {
        $buton .= '{delete}';
    }

$this->widget('bootstrap.widgets.TbGridView', array(
    'id' => 'roles-grid',
    'dataProvider' => $model->search(),
    'type' => 'striped condensed',
    'template' => '{summary}{pager}{items}{pager}',
    'columns' => array(
        'name',
        array(
            'class' => 'bootstrap.widgets.TbButtonColumn',
            'template' => $buton,
            'buttons' => array(
                'view' => array(
                    'label' => 'Lihat',
                    'url' => 'Yii::app()->createUrl("roles/view", array("id"=>$data->id))',
                    'options' => array(
                        'class' => 'btn btn-small view'
                    )
                ),
                'update' => array(
                    'label' => 'Edit',
                    'url' => 'Yii::app()->createUrl("roles/update", array("id"=>$data->id))',
                    'options' => array(
                        'class' => 'btn btn-small update'
                    )
                ),
                'delete' => array(
                    'label' => 'Hapus',
                    'url' => 'Yii::app()->createUrl("roles/delete", array("id"=>$data->id))',
                    'options' => array(
                        'class' => 'btn btn-small delete'
                    )
                )
            ),
            'htmlOptions' => array('style' => 'width: 125px'),
        )
    ),
));
?>

