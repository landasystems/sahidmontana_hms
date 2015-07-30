<?php
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id' => 'search-charge-additional-form',
    'action' => Yii::app()->createUrl($this->route),
    'method' => 'get',
    'type' => 'horizontal',
        ));
?>



<?php echo $form->dropDownListRow($model, 'charge_additional_category_id', CHtml::listData(ChargeAdditionalCategory::model()->findAll(array('order' => 'root, lft')), 'id', 'nestedname'), array('class' => 'span4', 'empty' => 'Please Choose')); ?>
<?php echo $form->dropDownListRow($model, 'account_id', CHtml::listData(Account::model()->findAll(), 'id', 'name'), array('class' => 'span4', 'empty' => 'Please Choose')); ?>
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
    'options' => array(
        'placeholder' => 'Please Choose',
        'width' => '40%',
        "allowClear" => true,
    )
        )
);
?>
<?php echo $form->textFieldRow($model, 'name', array('class' => 'span5', 'maxlength' => 45)); ?>

<?php echo $form->textFieldRow($model, 'charge', array('class' => 'span5')); ?>

<?php echo $form->textFieldRow($model, 'description', array('class' => 'span5', 'maxlength' => 255)); ?>

<div class="form-actions">
    <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType' => 'submit', 'type' => 'primary', 'icon' => 'search white', 'label' => 'Pencarian')); ?>
</div>

<?php $this->endWidget(); ?>


<?php
$cs = Yii::app()->getClientScript();
$cs->registerCoreScript('jquery');
$cs->registerCoreScript('jquery.ui');
$cs->registerCssFile(Yii::app()->request->baseUrl . '/css/bootstrap/jquery-ui.css');
?>	
<script type="text/javascript">
    jQuery(function($) {
        $(".btnreset").click(function() {
            $(":input", "#search-charge-additional-form").each(function() {
                var type = this.type;
                var tag = this.tagName.toLowerCase(); // normalize case
                if (type == "text" || type == "password" || tag == "textarea")
                    this.value = "";
                else if (type == "checkbox" || type == "radio")
                    this.checked = false;
                else if (tag == "select")
                    this.selectedIndex = "";
            });
        });
    })
</script>

