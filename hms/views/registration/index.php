<?php
$this->setPageTitle('Registrations');
$this->breadcrumbs = array(
    'Registrations',
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
    $('.search-form').slideToggle('fast');
    return false;
});
$('.search-form form').submit(function(){
    $.fn.yiiGridView.update('registration-grid', {
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
$button = '';
$this->widget('bootstrap.widgets.TbGridView', array(
    'id' => 'registration-grid',
    'dataProvider' => $model->search(),
    'type' => 'striped bordered condensed',
    'template' => '{summary}{pager}{items}{pager}',
    'columns' => array(
        'code',
        array(
            'header' => 'Type',
            'name' => 'created_user_id',
            'type' => 'raw',
            'value' => '(isset($data->Guest->Roles->name))?$data->Guest->Roles->name:""',
        ),
        array(
            'header' => 'Guest',
            'name' => 'guest_user_id',
            'type' => 'raw',
            'value' => '$data->Guest->guestName',
        ),
        array(
            'header' => 'Room',
            'name' => 'id',
            'type' => 'raw',
            'htmlOptions' => array('class' => 'span3'),
            'value' => '$data->roomNumber',
        ),
        array(
            'header' => 'Arrival',
            'name' => 'date_from',
            'type' => 'raw',
            'value' => 'date("d M", strtotime($data->date_from))',
        ),
        array(
            'header' => 'Depart',
            'name' => 'date_to',
            'type' => 'raw',
            'value' => 'date("d M", strtotime($data->date_to))',
        ),
        array(
            'name' => 'package_room_type_id',
            'type' => 'raw',
            'value' => 'ucwords($data->thisPackage)',
        ),
//        array(
//            'header' => 'Approval',
//            'name' => 'approval_user_id',
//            'type' => 'raw',
//            'value' => '($data->approval_user_id=="0")?"-":$data->Approval->name',
//        ),
        array(
            'header' => 'Checkout',
            'name' => 'id',
            'type' => 'raw',
            'htmlOptions' => array('style' => 'text-align:center'),
            'value' => '($data->is_checkedout==0)?"<span class=\"label label-warning\">Not Yet</span>":"<span class=\"label label-info\">Yes</span>"',
        ),
        array(
            'header' => 'Created',
            'name' => 'created',
            'type' => 'raw',
            'value' => 'date("d M, H:i", strtotime($data->created))',
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
                    'visible' => '(!isset($data->is_na) or $data->is_na==0)',
                    'options' => array(
                        'class' => 'btn btn-small update'
                    )
                ),
                'delete' => array(
                    'label' => 'Hapus',
                    'visible' => '(!isset($data->is_na) or $data->is_na==0)',
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


<div class="alert alert-danger fade in">   
    <button type="button" class="close" data-dismiss="alert">×</button>
    <strong>Important! </strong> &nbsp;&nbsp;Data has been entered and has been <b>Night Audit</b> can not be <b>Edited</b> or <b>Deleted</b>.    
</div> 