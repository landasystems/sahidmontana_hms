<?php  $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
        'id'=>'search-User-form',
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
));  ?>


	<div class="row">   
            <div class="span4">
                <?php echo $form->textFieldRow($model,'code',array('class'=>'span4','maxlength'=>255)); ?>
                <?php echo $form->textFieldRow($model,'name',array('class'=>'span4','maxlength'=>255)); ?>
                <?php // echo $form->textFieldRow($model,'city_id',array('class'=>'span4')); ?>
                <div class="control-group ">
                    <label>Province</label>
                    <div class="controls">
                        <?php
                        echo CHtml::dropDownList('province_id', 0, CHtml::listData(Province::model()->findAll(), 'id', 'name'), array(
                            'empty' => 'Pilih',
                            'ajax' => array(
                                'type' => 'POST',
                                'url' => CController::createUrl('landa/city/dynacities'),
                                'update' => '#User_city_id',
                            ),
                        ));
                        ?>  
                    </div>
                </div>
                <?php echo $form->dropDownListRow($model, 'city_id', array(), array('class' => 'span3')); ?>
            </div>
            
            <div class="span4" style="padding-left: 90px;">
                <?php echo $form->dropDownListRow($model, 'roles_id', CHtml::listData(User::model()->roles(), 'id', 'name'), array('class' => 'span4','empty' => 'Pilih',)); ?>
                <?php echo $form->textFieldRow($model,'email',array('class'=>'span4','maxlength'=>100)); ?>
                <?php
                    echo $form->textFieldRow(
                            $model, 'phone', array('prepend' => '+62')
                    );
                    ?>
            </div>
        </div>
	

	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'type'=>'primary', 'icon'=>'search white', 'label'=>'Pencarian')); ?>
	</div>

<?php $this->endWidget(); ?>


<?php $cs = Yii::app()->getClientScript();
$cs->registerCoreScript('jquery');
$cs->registerCoreScript('jquery.ui');
$cs->registerCssFile(Yii::app()->request->baseUrl.'/css/bootstrap/jquery-ui.css');
?>	
   <script>
	$(".btnreset").click(function(){
		$(":input","#search-User-form").each(function() {
		var type = this.type;
		var tag = this.tagName.toLowerCase(); // normalize case
		if (type == "text" || type == "password" || tag == "textarea") this.value = "";
		else if (type == "checkbox" || type == "radio") this.checked = false;
		else if (tag == "select") this.selectedIndex = "";
	  });
	});
   </script>

