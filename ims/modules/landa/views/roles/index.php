<?php
$this->setPageTitle('Group ' . $type);
$this->breadcrumbs = array(
    'Group ' . $type,
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
    $('.search-form').slideToggle('fast');
    return false;
});
$('.search-form form').submit(function(){
    $.fn.yiiGridView.update('roles-grid', {
        data: $(this).serialize()
    });
    return false;
});
");
?>

<?php
$visible = "";
if ($type == "client") {
    $visible = landa()->checkAccess('GroupClient', 'c');
} elseif ($type == "contact") {
    $visible = landa()->checkAccess('GroupContact', 'c');
} elseif ($type == "customer") {
    $visible = landa()->checkAccess('GroupCustomer', 'c');
} elseif ($type == "employment") {
    $visible = landa()->checkAccess('GroupEmployment', 'c');
} elseif ($type == "guest") {
    $visible = landa()->checkAccess('GroupGuest', 'c');
} elseif ($type == "supplier") {
    $visible = landa()->checkAccess('GroupSupplier', 'c');
} else {
    $visible = landa()->checkAccess('GroupUser', 'c');
}
if (isset($type)) {
    $sType = $type;
} else {
    $sType = '';
}
$this->beginWidget('zii.widgets.CPortlet', array(
    'htmlOptions' => array(
        'class' => ''
    )
));
$this->widget('bootstrap.widgets.TbMenu', array(
    'type' => 'pills',
    'items' => array(
        array('visible' => $visible, 'label' => 'Tambah', 'icon' => 'icon-plus', 'url' => Yii::app()->controller->createUrl('create', array('type' => $sType)), 'linkOptions' => array()),
        array('label' => 'Daftar', 'icon' => 'icon-th-list', 'url' => Yii::app()->controller->createUrl('index', array('type' => $sType)), 'active' => true, 'linkOptions' => array()),
        array('label' => 'Pencarian', 'icon' => 'icon-search', 'url' => '#', 'linkOptions' => array('class' => 'search-button')),
//		array('label'=>'Export ke PDF', 'icon'=>'icon-download', 'url'=>Yii::app()->controller->createUrl('GeneratePdf'), 'linkOptions'=>array('target'=>'_blank'), 'visible'=>true),
//		array('label'=>'Export ke Excel', 'icon'=>'icon-download', 'url'=>Yii::app()->controller->createUrl('GenerateExcel'), 'linkOptions'=>array('target'=>'_blank'), 'visible'=>true),
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
$buton = "";
if ($type == "client") {
    if (landa()->checkAccess('GroupClient', 'r')) {
        $buton .= '{view}';
    }

    if (landa()->checkAccess('GroupClient', 'u')) {
        $buton .= '{update}';
    }
    if (landa()->checkAccess('GroupClient', 'd')) {
        $buton .= '{delete}';
    }
} elseif ($type == "contact") {
    if (landa()->checkAccess('GroupContact', 'r')) {
        $buton .= '{view}';
    }

    if (landa()->checkAccess('GroupContact', 'u')) {
        $buton .= '{update}';
    }
    if (landa()->checkAccess('GroupContact', 'd')) {
        $buton .= '{delete}';
    }
} elseif ($type == "customer") {
    if (landa()->checkAccess('GroupCustomer', 'r')) {
        $buton .= '{view}';
    }

    if (landa()->checkAccess('GroupCustomer', 'u')) {
        $buton .= '{update}';
    }
    if (landa()->checkAccess('GroupCustomer', 'd')) {
        $buton .= '{delete}';
    }
} elseif ($type == "employment") {
    if (landa()->checkAccess('GroupEmployment', 'r')) {
        $buton .= '{view}';
    }

    if (landa()->checkAccess('GroupEmployment', 'u')) {
        $buton .= '{update}';
    }
    if (landa()->checkAccess('GroupEmployment', 'd')) {
        $buton .= '{delete}';
    }
} elseif ($type == "guest") {
    if (landa()->checkAccess('GroupGuest', 'r')) {
        $buton .= '{view}';
    }

    if (landa()->checkAccess('GroupGuest', 'u')) {
        $buton .= '{update}';
    }
    if (landa()->checkAccess('GroupGuest', 'd')) {
        $buton .= '{delete}';
    }
} elseif ($type == "supplier") {
    if (landa()->checkAccess('GroupSupplier', 'r')) {
        $buton .= '{view}';
    }

    if (landa()->checkAccess('GroupSupplier', 'u')) {
        $buton .= '{update}';
    }
    if (landa()->checkAccess('GroupSupplier', 'd')) {
        $buton .= '{delete}';
    }
} else {
    if (landa()->checkAccess('GroupUser', 'r')) {
        $buton .= '{view}';
    }

    if (landa()->checkAccess('GroupUser', 'u')) {
        $buton .= '{update}';
    }
    if (landa()->checkAccess('GroupUser', 'd')) {
        $buton .= '{delete}';
    }
}

$this->widget('bootstrap.widgets.TbGridView', array(
    'id' => 'roles-grid',
    'dataProvider' => $model->search($sType),
    'type' => 'striped bordered condensed',
    'template' => '{summary}{pager}{items}{pager}',
    'columns' => array(
//		'id',
        'name',
//        array(
//            'header' => 'Is Allow Login',
//            'name' => 'is_allow_login',
//            'value' => '$data->status',
//            'type' => 'raw',
//        ),
        array(
            'class' => 'bootstrap.widgets.TbButtonColumn',
            'template' => $buton,
            'buttons' => array(
                'view' => array(
                    'label' => 'Lihat',
                    'url' => 'Yii::app()->createUrl("landa/roles/view", array("id"=>$data->id,"type"=>"' . $sType . '"))',
                    'options' => array(
                        'class' => 'btn btn-small view'
                    )
                ),
                'update' => array(
                    'label' => 'Edit',
                    'url' => 'Yii::app()->createUrl("landa/roles/update", array("id"=>$data->id,"type"=>"' . $sType . '"))',
                    'options' => array(
                        'class' => 'btn btn-small update'
                    )
                ),
                'delete' => array(
                    'label' => 'Hapus',
                    'url' => 'Yii::app()->createUrl("landa/roles/delete", array("id"=>$data->id,"type"=>"' . $sType . '"))',
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

