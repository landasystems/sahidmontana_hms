<?php  $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
'id'=>'search-gl-form',
'action'=>Yii::app()->createUrl($this->route),
'method'=>'get',
));  ?>


        <?php echo $form->textFieldRow($model,'id',array('class'=>'span5')); ?>

        <?php echo $form->textFieldRow($model,'na_id',array('class'=>'span5')); ?>

        <?php echo $form->textFieldRow($model,'room_id',array('class'=>'span5')); ?>

        <?php echo $form->textFieldRow($model,'guest_user_id',array('class'=>'span5')); ?>

        <?php echo $form->textFieldRow($model,'charge_previous',array('class'=>'span5')); ?>

        <?php echo $form->textFieldRow($model,'charge',array('class'=>'span5')); ?>

        <?php echo $form->textFieldRow($model,'charge_settle',array('class'=>'span5')); ?>

        <?php echo $form->textFieldRow($model,'charge_balance',array('class'=>'span5')); ?>

<div class="form-actions">
    <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'type'=>'primary', 'icon'=>'search white', 'label'=>'Pencarian')); ?>
    <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'button', 'icon'=>'icon-remove-sign white', 'label'=>'Reset', 'htmlOptions'=>array('class'=>'btnreset btn-small'))); ?>
</div>

<?php $this->endWidget(); ?>


<?php $cs = Yii::app()->getClientScript();
$cs->registerCoreScript('jquery');
$cs->registerCoreScript('jquery.ui');
$cs->registerCssFile(Yii::app()->request->baseUrl.'/css/bootstrap/jquery-ui.css');
?>	
<script type="text/javascript">
    jQuery(function($) {
        $(".btnreset").click(function() {
            $(":input", "#search-gl-form").each(function() {
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

