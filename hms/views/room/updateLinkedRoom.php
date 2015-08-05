<?php
$this->setPageTitle('Linked Room');

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
        array('label' => 'List Data', 'icon' => 'icon-th-list', 'url' => Yii::app()->controller->createUrl('linkedRoom'), 'linkOptions' => array()),
        array('label' => 'Update', 'icon' => 'icon-pencil', 'url' => '#', 'active' => true, 'linkOptions' => array()),
//        array('label' => 'Pencarian', 'icon' => 'icon-search', 'url' => '#', 'linkOptions' => array('class' => 'search-button')),
//        array('label' => 'Export ke PDF', 'icon' => 'icon-download', 'url' => Yii::app()->controller->createUrl('GeneratePdf'), 'linkOptions' => array('target' => '_blank'), 'visible' => true),
//        array('label' => 'Export ke Excel', 'icon' => 'icon-download', 'url' => Yii::app()->controller->createUrl('GenerateExcel'), 'linkOptions' => array('target' => '_blank'), 'visible' => true),
//        array('label' => 'Print', 'icon' => 'icon-print', 'url' => 'javascript:void(0);return false', 'linkOptions' => array('onclick' => 'printDiv();return false;')),
    ),
));
$this->endWidget();
?>

<?php
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id' => 'sms-form',
    'enableAjaxValidation' => false,
    'method' => 'post',
    'type' => 'horizontal',
    'htmlOptions' => array(
        'enctype' => 'multipart/form-data'
    )
        ));
?>

<table>
    <tr>
        <td class="span2">Room Number</td>
        <td style="width: 1px">:</td>
        <td class="span12"><?php echo $model->number; ?><input name="id" value="<?php echo $model->id;?>" type="hidden" /></td>
    </tr>
    <tr>
        <td class="span2">Guest Name</td>
        <td style="width: 1px">:</td>
        <td><?php echo $model->Registration->Guest->name; ?></td>
    </tr>
    <tr>
        <td style="vertical-align: top" class="span2">Linked Room With</td>
        <?php
        $data = CHtml::listData($list, 'id', 'fullRoom');
        $value = CHtml::listData($room, 'id', 'id');
        ?>
        <td  style="vertical-align: top; width: 1px">:</td>
        <td style="vertical-align: top">
            <?php echo $this->renderPartial('_linkedRoom', array('data' => $data, 'value' => $value)); ?>
        </td>
    </tr>
</table>


<div class="form-actions">
    <?php
    $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType' => 'submit',
        'type' => 'primary',
        'icon' => 'ok white',
        'label' => $model->isNewRecord ? 'Create' : 'Save',
    ));
    ?>
</div>

<?php $this->endWidget(); ?>
