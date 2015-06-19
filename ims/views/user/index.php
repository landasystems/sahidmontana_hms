<?php
$this->setPageTitle(ucfirst($type));
$this->breadcrumbs = array(
    ucfirst($type),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
    $('.search-form').slideToggle('fast');
    return false;
});
$('.search-form form').submit(function(){
    $.fn.yiiGridView.update('User-grid', {
        data: $(this).serialize()
    });
    return false;
});
");
?>

<?php
$visible = "";
if ($type == "customer") {
    $visible = landa()->checkAccess('Customer', 'c');
} elseif ($type == "supplier") {
    $visible = landa()->checkAccess('Supplier', 'c');
} elseif ($type == "employment") {
    $visible = landa()->checkAccess('Employment', 'c');
} else {
    $visible = landa()->checkAccess('User', 'c');
}
$this->beginWidget('zii.widgets.CPortlet', array(
    'htmlOptions' => array(
        'class' => ''
    )
));
$this->widget('bootstrap.widgets.TbMenu', array(
    'type' => 'pills',
    'items' => array(
        array('visible' => $visible, 'label' => 'Tambah', 'icon' => 'icon-plus', 'url' => Yii::app()->controller->createUrl('create', array('type' => $type)), 'linkOptions' => array()),
        array('label' => 'Daftar', 'icon' => 'icon-th-list', 'url' => Yii::app()->controller->createUrl($type), 'active' => true, 'linkOptions' => array()),
        array('label' => 'Pencarian', 'icon' => 'icon-search', 'url' => '#', 'linkOptions' => array('class' => 'search-button')),
        array('label' => 'Export Excel', 'icon' => 'icomoon-icon-file-excel', 'url' => Yii::app()->controller->createUrl('user/generateExcel'), 'linkOptions' => array()),
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
if ($type == "customer") {
    if (landa()->checkAccess('Customer', 'r')) {
        $buton .= '{view}';
    }
    if (landa()->checkAccess('Customer', 'd')) {
        $buton .= '{delete}';
    }
    if (landa()->checkAccess('Customer', 'u')) {
        $buton .= '{update}';
    }
} elseif ($type == "supplier") {
    if (landa()->checkAccess('Supplier', 'r')) {
        $buton .= '{view}';
    }
    if (landa()->checkAccess('Supplier', 'd')) {
        $buton .= '{delete}';
    }
    if (landa()->checkAccess('Supplier', 'u')) {
        $buton .= '{update}';
    }
} elseif ($type == "employment") {
    if (landa()->checkAccess('Employment', 'r')) {
        $buton .= '{view}';
    }
    if (landa()->checkAccess('Employment', 'd')) {
        $buton .= '{delete}';
    }
    if (landa()->checkAccess('Employment', 'u')) {
        $buton .= '{update}';
    }
} else {
    if (landa()->checkAccess('User', 'r')) {
        $buton .= '{view}';
    }
    if (landa()->checkAccess('User', 'd')) {
        $buton .= '{delete}';
    }
    if (landa()->checkAccess('User', 'u')) {
        $buton .= '{update}';
    }
}
$this->widget('bootstrap.widgets.TbGridView', array(
    'id' => 'User-grid',
    'dataProvider' => $model->search($type),
    'type' => 'striped bordered condensed',
    'template' => '{summary}{pager}{items}{pager}',
    'columns' => array(
        array(
            'name' => 'Foto',
            'type' => 'raw',
            'value' => '"$data->tagImg"',
            'htmlOptions' => array('style' => 'text-align: center; width:180px;text-align:center;')
        ),
        'code',
        'name',
//        array(
//            'name' => 'Biodata',
//            'type' => 'raw',
//            'value' => '"$data->tagBiodata"',
//            'htmlOptions' => array('style' => 'text-align: center;')
//        ),
//        array(
//            'visible'=>($type=="user"),
//            'name' => 'Access',
//            'type' => 'raw',
//            'value' => '"$data->tagAccess"',
//            'htmlOptions' => array('style' => 'text-align: center;'),
//            'headerHtmlOptions' => array('text-align' => 'center'),
////            'value' => '"<img src=\"$data->imgUrl[\\"medium\\"]\" class="image"/>"', 
////            'value' => 'aa', 
//        ),
//        array('header'=>'Enabled',
//        'name'=>'enabled',
//        'type'=>'raw',    
//        'htmlOptions' => array('class' => 'span1'),    
//        'value'=>'($data->enabled==0) ? "<span class=\"label label-important\">No</span>":
//         "<span class=\"label label-info\">Yes</span>"',
//        ),   
//        'UserPosition.name',
        array(
            'class' => 'bootstrap.widgets.TbButtonColumn',
            'template' => $buton,
            'buttons' => array(
                'view' => array(
                    'label' => 'Lihat',
                    'url' => 'Yii::app()->createUrl("user/view", array("id"=>$data->id,"type"=>"' . $type . '"))',
                    'options' => array(
                        'class' => 'btn btn-small view'
                    )
                ),
                'update' => array(
                    'label' => 'Edit',
                    'url' => 'Yii::app()->createUrl("user/update", array("id"=>$data->id,"type"=>"' . $type . '"))',
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
            'htmlOptions' => array('style' => 'width: 125px'),
        )
    ),
));
?>

