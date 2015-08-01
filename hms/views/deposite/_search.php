<?php
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id' => 'search-deposite-form',
    'type' => 'horizontal',
    'action' => Yii::app()->createUrl($this->route),
    'method' => 'get',
        ));
?>


<?php echo $form->textFieldRow($model, 'code', array('class' => 'span5', 'maxlength' => 45)); ?>
<?php
$data = array('' => 'Please Choose') + CHtml::listData(User::model()->listUsers('guest'), 'id', 'fullName');
echo $form->select2Row($model, 'guest_user_id', array(
    'asDropDownList' => true,
    'data' => $data,
    'options' => array(
        "placeholder" => 'Please Choose',
        "allowClear" => true,
        'width' => '30%',
)));
?>
<?php
$dp_by = array('cash' => 'Cash', 'cc' => 'Credit Card', 'debit' => 'Debit Card', 'ca' => 'City Ledger');
echo $form->dropDownListRow($model, 'dp_by', $dp_by, array('class' => 'span2', 'maxlength' => 5, 'empty' => 'Please Choose'));
?>

<?php
$data2 = array('' => 'Please Choose') + CHtml::listData(User::model()->listUsers('user'), 'id', 'name');
echo $form->select2Row($model, 'created_user_id', array(
    'asDropDownList' => true,
    'data' => $data2,
    'options' => array(
        "placeholder" => 'Please Choose',
        "allowClear" => true,
        'width' => '30%',
)));
?>

<div class="form-actions">
    <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType' => 'submit', 'type' => 'primary', 'icon' => 'search white', 'label' => 'Search')); ?>
</div>

<?php $this->endWidget(); ?>


<?php
$cs = Yii::app()->getClientScript();
;
$cs->registerCoreScript('jquery.ui');
$cs->registerCssFile(Yii::app()->request->baseUrl . '/css/bootstrap/jquery-ui.css');
?>	
<script type="text/javascript">
    jQuery(function($) {
        $(".btnreset").click(function() {
            $(":input", "#search-deposite-form").each(function() {
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

