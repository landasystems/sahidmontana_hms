<?php
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id' => 'search-bill-charge-form',
    'type' => 'horizontal',
    'action' => Yii::app()->createUrl($this->route),
    'method' => 'get',
        ));

$departement = Chtml::listdata(ChargeAdditionalCategory::model()->findAll(array('condition' => 'level=1')), 'id', 'name');
?>



<?php echo $form->textFieldRow($model, 'code', array('class' => 'span2', 'maxlength' => 45)); ?>       
<?php echo $form->dropDownListRow($model, 'charge_additional_category_id', $departement,array('class' => 'span2', 'empty' => 'Please Choose')); ?>       
<?php echo $form->textFieldRow($model, 'total', array('prepend'=>'Rp','class' => 'span2', 'maxlength' => 45)); ?>       
<?php echo $form->textFieldRow($model, 'created_user_id', array('class' => 'span2', 'maxlength' => 45)); ?>       

<div class="form-actions">
    <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType' => 'submit', 'type' => 'primary', 'icon' => 'search white', 'label' => 'Pencarian')); ?>
    <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType' => 'button', 'icon' => 'icon-remove-sign white', 'label' => 'Reset', 'htmlOptions' => array('class' => 'btnreset btn-small'))); ?>
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
            $(":input", "#search-bill-charge-form").each(function() {
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

