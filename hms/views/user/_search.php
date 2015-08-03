<div class="row">   
    <div class="span4">
        <?php echo $form->dropDownListRow($model, 'roles_id', CHtml::listData(Roles::model()->user(), 'id', 'name'), array('class' => 'span4', 'empty' => 'Please Choose',)); ?>
    </div>

    <div class="span4" style="padding-left: 90px;">
        <div class="control-group">
            <label class="control-label" for="inputEmail">Name</label>
            <div class="controls">
                <div class="input-append">
                    <input class="span4" maxlength="255" name="User[name]" id="User_name" type="text">
                    <button class="btn btn-primary" type="submit">Go!</button>
                </div>
            </div>
        </div>

    </div>
</div>

<div class="row">   
    <div class="span4">
        <?php echo $form->textFieldRow($model, 'code', array('class' => 'span4', 'maxlength' => 255)); ?>

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

        <?php echo $form->textFieldRow($model, 'email', array('class' => 'span4', 'maxlength' => 100)); ?>
        <?php
        echo $form->textFieldRow(
                $model, 'phone', array('prepend' => '+62')
        );
        ?>
    </div>
</div>

<div class="form-actions">
    <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType' => 'submit', 'type' => 'primary', 'icon' => 'search white', 'label' => 'Pencarian')); ?>
</div>






