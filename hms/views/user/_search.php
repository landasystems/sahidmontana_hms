


	<div class="row">   
            <div class="span4">
                <?php echo $form->textFieldRow($model,'code',array('class'=>'span4','maxlength'=>255)); ?>
               
                <?php // echo $form->textFieldRow($model,'city_id',array('class'=>'span4')); ?>
                <div class="control-group ">
                    <label>Province</label>
                    <div class="controls">
                        <?php
                        echo CHtml::dropDownList('province_id', 0, CHtml::listData(Province::model()->findAll(), 'id', 'name'), array(
                            'empty' => 'Please Choose',
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
               
                <?php echo $form->textFieldRow($model,'email',array('class'=>'span4','maxlength'=>100)); ?>
                <?php
                    echo $form->textFieldRow(
                            $model, 'phone', array('prepend' => '+62')
                    );
                    ?>
            </div>
        </div>
	



	
 

