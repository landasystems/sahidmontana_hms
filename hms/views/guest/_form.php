<div class="form">
    <?php
    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id' => 'User-form',
        'enableAjaxValidation' => false,
        'method' => 'post',
        'type' => 'horizontal',
        'htmlOptions' => array(
            'enctype' => 'multipart/form-data'
        )
    ));
    ?>
    <legend>
        <p class="note">Fields with <span class="required">*</span> is Required.</p>
    </legend>

    <?php echo $form->errorSummary($model, 'Opps!!!', null, array('class' => 'alert alert-error span12')); ?>
    <div class="clearfix"></div>

    <div class="box">
        <div class="title">
            <h4>
                <?php
                echo 'Guest Group<span class="required">*</span> :    ';
                $array = Roles::model()->guest();
                if (!empty($array)) {
                    echo CHtml::dropDownList('User[roles_id]', $model->roles_id, CHtml::listData($array, 'id', 'name'), array(
                        'empty' => 'Please Choose',
                    ));
                } else {
                    echo'Data is empty please insert data group guest.';
                }
                ?> 
            </h4>
        </div>
    </div>


    <ul class="nav nav-tabs" id="myTab">
        <li class="active"><a href="#personal">Biodata</a></li>
    </ul>

    <div class="tab-content">
        <div class="tab-pane active" id="personal">
            <?php echo $form->textFieldRow($model, 'email', array('class' => 'span5', 'maxlength' => 100)); ?>
            <?php echo $form->textFieldRow($model, 'code', array('class' => 'span5', 'maxlength' => 25)); ?>

            <?php echo $form->textFieldRow($model, 'name', array('class' => 'span5', 'maxlength' => 255)); ?> 
            <?php echo $form->textFieldRow($model, 'company', array('class' => 'span5', 'maxlength' => 255)); ?> 

            <?php echo $form->toggleButtonRow($model, 'enabled'); ?>
            <?php
            echo $form->textFieldRow(
                    $model, 'phone', array('prepend' => '+62')
            );
            ?>
            <?php
            echo $form->radioButtonListRow($model, 'sex', array('m' => 'Male', 'f' => 'Female'), array('class' => 'span2'));
            ?>
            <?php
            echo $form->datepickerRow(
                    $model, 'birth', array(
                'options' => array('language' => 'en', 'format' => 'yyyy-mm-dd'),
                'prepend' => '<i class="icon-calendar"></i>'
                    )
            );
            ?>
            <?php
            echo $form->textAreaRow(
                    $model, 'description', array('class' => 'span4', 'rows' => 5)
            );
            ?>

            <?php echo $form->textFieldRow($model, 'nationality', array('class' => 'span5', 'maxlength' => 30)); ?>

            <div class="control-group">
                <div class="control-label">District</div>
                <div class="controls">
                    <?php
                    $city = City::model()->findByPk($model->city_id);
                    if (isset($city)) {
                        $city_id = $city->id;
                        $city_name = $city->name;
                    } else {
                        $city_id = 0;
                        $city_name = '';
                    }
                    $this->widget(
                            'bootstrap.widgets.TbSelect2', array(
                        'name' => "User[city_id]",
                        'val' => $model->city_id,
                        'asDropDownList' => false,
                        'options' => array(
                            'allowClear' => true,
                            'minimumInputLength' => 3,
                            'width' => '100%;margin:0px;text-align:left',
                            'minimumInputLength' => '3',
                            'initSelection' => 'js:function(element, callback) 
                            { 
                                data = {"id": ' . $city_id . ',"text": "' . $city_name . '"}
                                callback(data);   
                            }',
                            'ajax' => array(
                                'url' => Yii::app()->createUrl('city/listajax'),
                                'dataType' => 'json',
                                'data' => 'js:function(term, page) { 
                                                        return {
                                                            q: term 
                                                        }; 
                                                    }',
                                'results' => 'js:function(data) { 
                                                        return {
                                                            results: data
                                                        };
                                                    }',
                            ),
                        ),
                            )
                    );
                    ?>   
                </div>
            </div>
            <?php echo $form->textAreaRow($model, 'address', array('class' => 'span5', 'maxlength' => 255)); ?>
        </div> 
    </div>
</div>


<div class="form-actions">
    <?php
    $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType' => 'submit',
        'type' => 'primary',
        'icon' => 'ok white',
        'label' => $model->isNewRecord ? 'Create' : 'Save',
        'visible' => !isset($_GET['v']),
    ));
    ?>
</div>

<?php $this->endWidget(); ?>
</div>

