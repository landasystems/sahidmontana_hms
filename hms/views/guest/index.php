<?php
$this->setPageTitle('Guest');

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
    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id' => 'search-User-form',
        'action' => Yii::app()->createUrl($this->route),
        'method' => 'get',
    ));

    $this->renderPartial('_search', array(
        'model' => $model,
        'form' => $form,
    ));

    $this->endWidget();

    $cs = Yii::app()->getClientScript();
    ;
    $cs->registerCoreScript('jquery.ui');
    $cs->registerCssFile(Yii::app()->request->baseUrl . '/css/bootstrap/jquery-ui.css');
    ?>
</div><!-- search-form -->
<?php
if (Yii::app()->user->hasFlash('success')) {
    echo '<div class="alert alert-success">' . Yii::app()->user->getFlash('success') . '</div>';
}
?>
<?php
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id' => 'chargeAdditional-form',
    'enableAjaxValidation' => false,
    'method' => 'post',
    'action' => url('user/option'),
    'type' => 'horizontal',
    'htmlOptions' => array(
        'enctype' => 'multipart/form-data'
    )
        ));



    $btndelete = '<button type="submit" name="delete" value="dd" style="margin-left: 10px" class="btn btn-danger pull-right"><span class="icon16 brocco-icon-trashcan white"></span> Delete Checked</button>';
?>
<?php echo $btndelete ?>
<?php
    $buton = '{view}{update}{delete}';


$this->widget('bootstrap.widgets.TbGridView', array(
    'id' => 'User-grid',
    'dataProvider' => $model->search('guest'),
    'type' => 'table table-hover',
    'template' => '{pager}{items}{pager}{summary}',
    'columns' => array(
        array(
            'class' => 'CCheckBoxColumn',
            'selectableRows' => 2,
            'checkBoxHtmlOptions' => array(
                'name' => 'ceckbox[]',
                'value' => '$data->id',
            ),
        ),
        'guestName',
        'company',
        'code',
        array(
            'header' => 'Phone',
            'type' => 'raw',
            'value' => 'landa()->hp($data->phone)',
        ),
        array(
            'name' => 'Group Guest',
            'type' => 'raw',
            'value' => '(isset($data->Roles->name)) ? $data->Roles->name : ""',
        ),
        array(
            'class' => 'bootstrap.widgets.TbButtonColumn',
            'template' => $buton,
            'buttons' => array(
                'view' => array(
                    'label' => 'View',
                    'url' => 'Yii::app()->createUrl("user/view", array("id"=>$data->id))',
                    'options' => array(
                        'class' => 'btn btn-small view'
                    )
                ),
                'update' => array(
                    'label' => 'Edit',
                    'url' => 'Yii::app()->createUrl("user/update", array("id"=>$data->id))',
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
<?php $this->endWidget(); ?>
