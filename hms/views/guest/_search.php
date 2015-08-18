<div class="row">   
    <div class="span4">
        <?php echo $form->dropDownListRow($model, 'roles_id', CHtml::listData(Roles::model()->user(), 'id', 'name'), array('class' => 'span4', 'empty' => 'Please Choose',)); ?>
    </div>

    <div class="span4" style="padding-left: 90px;">

       <?php echo $form->textFieldRow($model, 'email', array('class' => 'span4', 'maxlength' => 100)); ?>

    </div>
</div>

<div class="row">   
    <div class="span4">
        <?php echo $form->textFieldRow($model, 'code', array('class' => 'span4', 'maxlength' => 255)); ?>
         <?php echo $form->textFieldRow($model, 'name', array('class' => 'span4', 'maxlength' => 255)); ?>
    </div>

    <div class="span4" style="padding-left: 90px;">

        
        <?php
        echo $form->textFieldRow(
                $model, 'phone', array('prepend' => '+62')
        );
        ?>
    </div>
</div>

<div class="form-actions">
    <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType' => 'submit', 'type' => 'primary', 'icon' => 'search white', 'label' => 'Search')); ?>
</div>






