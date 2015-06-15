<?php
$this->setPageTitle('View Charge Additionals | ID : ' . $model->id);
$this->breadcrumbs = array(
    'Charge Additionals' => array('index'),
    $model->name,
);
?>

<?php
//$this->beginWidget('zii.widgets.CPortlet', array(
//	'htmlOptions'=>array(
//		'class'=>''
//	)
//));
$this->widget('bootstrap.widgets.TbMenu', array(
    'type' => 'pills',
    'items' => array(
        array('label' => 'Create', 'icon' => 'icon-plus', 'url' => Yii::app()->controller->createUrl('create'), 'linkOptions' => array()),
        array('label' => 'List Data', 'icon' => 'icon-th-list', 'url' => Yii::app()->controller->createUrl('index'), 'linkOptions' => array()),
        array('label' => 'Edit', 'icon' => 'icon-edit', 'url' => Yii::app()->controller->createUrl('update', array('id' => $model->id)), 'linkOptions' => array()),
    //array('label'=>'Pencarian', 'icon'=>'icon-search', 'url'=>'#', 'linkOptions'=>array('class'=>'search-button')),
//        array('label' => 'Print', 'icon' => 'icon-print', 'url' => 'javascript:void(0);return false', 'linkOptions' => array('onclick' => 'printDiv();return false;')),
)));
//$this->endWidget();
?>
<div class="form">
    <?php
    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id' => 'charge-additional-form',
        'enableAjaxValidation' => false,
        'method' => 'post',
        'type' => 'horizontal',
        'htmlOptions' => array(
            'enctype' => 'multipart/form-data'
        )
    ));
    ?>
    <fieldset>
        <hr>

        <?php echo $form->errorSummary($model, 'Opps!!!', null, array('class' => 'alert alert-error span12')); ?>        


        <?php echo $form->textFieldRow($model, 'name', array('disabled' => true, 'class' => 'span4', 'maxlength' => 45)); ?>
        <?php echo $form->dropDownListRow($model, 'charge_additional_category_id', CHtml::listData(ChargeAdditionalCategory::model()->findAll(array('order' => 'root, lft')), 'id', 'nestedname'), array('disabled' => true, 'class' => 'span4', 'empty' => 'root')); ?>

        <?php echo $form->dropDownListRow($model, 'account_id', array(0 => t('choose', 'global')) + CHtml::listData(Account::model()->findAll(), 'id', 'name'), array('disabled' => true, 'class' => 'span4')); ?>
        <?php
        $transaction = SiteConfig::model()->getStandartTransactionMalang();
        $type_transaction = array();
        foreach ($transaction as $key => $value) {
            $type_transaction[$key] = '[ ' . $key . ' ] - ' . ucwords($value);
        }
        $data = array('' => '') + $type_transaction;
        echo $form->select2Row(
                $model, 'type_transaction', array(
            'asDropDownList' => true,
            'data' => $data,
            'disabled' => true,
            'options' => array(
                'placeholder' => 'Please Choose',
                'width' => '40%',
                "allowClear" => true,
            )
                )
        );
        ?>


        <?php // echo $form->textFieldRow($model, 'charge', array('class' => 'span5'));  ?>
        <?php
        echo $form->textFieldRow(
                $model, 'charge', array('disabled' => true, 'prepend' => 'Rp',
            'class' => 'span3')
        );
        ?>

        <?php
        $totalCharge = $model->charge - (($model->discount / 100) * $model->charge);
        ?>
        <?php
        echo $form->textFieldRow(
                $model, 'discount', array('append' => '%',
            'class' => 'span1 changeTotal', 'disabled' => true)
        );
        ?>
        <div class="control-group ">
            <label class="control-label" for="ChargeAdditional_charge">Total Charge</label>
            <div class="controls"><div class="input-prepend"><span class="add-on">Rp</span><input disabled class="span3" value="<?php echo $totalCharge; ?>" name="totalCharge" id="totalCharge" disabled type="text"></div></div>
        </div>
        <?php
        echo $form->textAreaRow(
                $model, 'description', array('disabled' => true, 'class' => 'span4', 'rows' => 5)
        );
        ?>


        <hr>   
    </fieldset>

    <?php $this->endWidget(); ?>

</div>
