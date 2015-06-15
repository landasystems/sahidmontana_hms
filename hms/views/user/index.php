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
$this->beginWidget('zii.widgets.CPortlet', array(
    'htmlOptions' => array(
        'class' => ''
    )
));
$this->widget('bootstrap.widgets.TbMenu', array(
    'type' => 'pills',
    'items' => array(
//        array('label' => 'Create', 'icon' => 'icon-plus', 'url' => Yii::app()->controller->createUrl('create', array('type' => $type)), 'linkOptions' => array()),
//        array('label' => 'List Data', 'icon' => 'icon-th-list', 'url' => Yii::app()->controller->createUrl($type), 'active' => true, 'linkOptions' => array()),
//        array('label' => 'Pencarian', 'icon' => 'icon-search', 'url' => '#', 'linkOptions' => array('class' => 'search-button')),

        array('label' => 'Export Excel', 'icon' => 'icomoon-icon-file-excel', 'url' => Yii::app()->controller->createUrl('user/generateExcel'), 'linkOptions' => array()),
    ),
));
$this->endWidget();
?>


<div class="search-form" >
    <?php
    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id' => 'search-User-form',
        'action' => Yii::app()->createUrl($this->route),
        'method' => 'get',
    ));
    ?>


    <div class="row">   

        <div class="span4">
            <?php echo $form->dropDownListRow($model, 'roles_id', CHtml::listData(Roles::model()->listRole($type), 'id', 'name'), array('class' => 'span4', 'empty' => t('choose', 'global'),)); ?>
            <?php // echo $form->textFieldRow($model, 'name', array('class' => 'span4', 'maxlength' => 255)); ?>
        </div>

        <div class="span4" style="padding-left: 90px;">
            <div class="control-group">
                <label class="control-label" for="inputEmail">Name</label>
                <div class="controls">
                    <div class="input-append">
                        <input class="span4" maxlength="255" name="User[name]" id="User_name" type="text">
                        <button class="btn btn-primary" type="submit">Go!</button>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <div class="search-form2" style="display: none">
        <?php
        $this->renderPartial('_search', array(
            'model' => $model,
            'form' => $form,
        ));
        ?>
    </div>



    <?php $this->endWidget(); ?>
    <?php
    $cs = Yii::app()->getClientScript();
    $cs->registerCoreScript('jquery');
    $cs->registerCoreScript('jquery.ui');
    $cs->registerCssFile(Yii::app()->request->baseUrl . '/css/bootstrap/jquery-ui.css');
    ?>
</div><!-- search-form -->
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

$btncreate = '';
if (landa()->checkAccess('User', 'c'))
    $btncreate .= '<a class="btn btn-primary pull-right" style="margin-left: 10px" href="' . url('user/create', array('type' => $type)) . '"><i class="icon-plus"></i> Create</a>&nbsp;&nbsp;';
$btndelete = '';
if (landa()->checkAccess('User', 'd'))
    $btncreate .= '<button type="submit" name="delete" value="dd" style="margin-left: 10px" class="btn btn-danger pull-right"><span class="icon16 brocco-icon-trashcan white"></span> Delete</button>';
?>
<?php echo $btndelete ?>
<a class="search-button2 btn btn-inverse pull-right" style="margin-left: 10px" href="#"><i class="icon-search"></i> Search</a> 
<?php echo $btncreate ?>
<br>
<br>
<?php
$buton = '';

if (landa()->checkAccess('User', 'r'))
    $buton .= '{view}';

if (landa()->checkAccess('User', 'u'))
    $buton .= '{update} ';

if (landa()->checkAccess('User', 'd'))
    $buton .= '{delete}';

$this->widget('bootstrap.widgets.TbGridView', array(
    'id' => 'User-grid',
    'dataProvider' => $model->search($type),
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
        array(
            'name' => 'Photo',
            'type' => 'raw',
            'value' => '"$data->tagImg"',
            'htmlOptions' => array('style' => 'text-align: center; width:90px;text-align:center;')
        ),
        array(
            'name' => 'Information',
            'type' => 'raw',
            'value' => '"$data->tagBiodata"',
            'htmlOptions' => array('style' => 'text-align: center;width:30%')
        ),
        array(
            'name' => 'Access',
            'type' => 'raw',
            'visible' => '($data->Roles->is_allow_login == 1) ? TRUE : FALSE',
            'value' => '"$data->tagAccess"',
            'htmlOptions' => array('style' => 'text-align: center;width:30%'),
            'headerHtmlOptions' => array('text-align' => 'center'),
        ),
        array(
            'class' => 'bootstrap.widgets.TbButtonColumn',
            'template' => $buton,
            'buttons' => array(
                'view' => array(
                    'label' => 'View',
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
<?php $this->endWidget(); ?>
<script>
    $('.search-button2').click(function() {
        $('.search-form2').slideToggle('fast');
        return false;
    });
    $('.search-button').click(function() {
        $('.search-form').slideToggle('fast');
        return false;
    });
</script>
