<?php
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id' => 'search-reservation-form',
    'action' => Yii::app()->createUrl($this->route),
    'method' => 'get',
        ));
?>




<?php echo $form->textFieldRow($model, 'code', array('class' => 'span5', 'maxlength' => 100)); ?>

<?php echo $form->textFieldRow($model, 'guest_user_id', array('class' => 'span5')); ?>

<div class="control-group ">
    <label class="control-label" for="until">Date From :</label>
    <div class="controls">        
        <div class="input-prepend"><span class="add-on"><i class="icon-calendar"></i></span>
            <?php                 
            $this->widget(
                    'bootstrap.widgets.TbDatePicker', array(
                        'name' => 'Reservation[date_from]',                       
                        'options' => array(                           
                            'format' => 'yyyy-mm-dd',
                            'viewformat' => 'yyyy-mm-dd',
                        ),
                    )
            );
            ?>
        </div>        
    </div>
</div>

<div class="control-group ">
    <label class="control-label" for="until">Date To :</label>
    <div class="controls">        
        <div class="input-prepend"><span class="add-on"><i class="icon-calendar"></i></span>
            <?php                 
            $this->widget(
                    'bootstrap.widgets.TbDatePicker', array(
                        'name' => 'Reservation[date_to]',                       
                        'options' => array(                           
                            'format' => 'yyyy-mm-dd',
                            'viewformat' => 'yyyy-mm-dd',
                        ),
                    )
            );
            ?>
        </div>        
    </div>
</div>


<div class="form-actions">
    <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType' => 'submit', 'type' => 'primary', 'icon' => 'search white', 'label' => 'Search')); ?>
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
            $(":input", "#search-reservation-form").each(function() {
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

