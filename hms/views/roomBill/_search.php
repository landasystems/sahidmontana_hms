<?php  $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
'id'=>'search-room-bill-form',
'action'=>Yii::app()->createUrl($this->route),
'method'=>'get',
));  ?>


        <?php echo $form->textFieldRow($model,'id',array('class'=>'span5')); ?>

        <?php echo $form->textFieldRow($model,'room_id',array('class'=>'span5')); ?>

        <?php echo $form->textFieldRow($model,'room_number',array('class'=>'span5','maxlength'=>3)); ?>

        <?php echo $form->textFieldRow($model,'date_bill',array('class'=>'span5')); ?>

        <?php echo $form->textFieldRow($model,'charge',array('class'=>'span5')); ?>

        <?php echo $form->textFieldRow($model,'processed',array('class'=>'span5')); ?>

        <?php echo $form->textFieldRow($model,'created_user_id',array('class'=>'span5')); ?>

        <?php echo $form->textFieldRow($model,'modified',array('class'=>'span5')); ?>

        <?php echo $form->textFieldRow($model,'created',array('class'=>'span5')); ?>

<div class="form-actions">
    <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'type'=>'primary', 'icon'=>'search white', 'label'=>'Search')); ?>
</div>

<?php $this->endWidget(); ?>


<?php $cs = Yii::app()->getClientScript();
;
$cs->registerCoreScript('jquery.ui');
$cs->registerCssFile(Yii::app()->request->baseUrl.'/css/bootstrap/jquery-ui.css');
?>	
<script type="text/javascript">
    jQuery(function($) {
        $(".btnreset").click(function() {
            $(":input", "#search-room-bill-form").each(function() {
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

