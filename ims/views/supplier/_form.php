<div class="form">
    <?php
    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id' => 'supplier-form',
        'enableAjaxValidation' => false,
        'method' => 'post',
        'type' => 'horizontal',
        'htmlOptions' => array(
            'enctype' => 'multipart/form-data'
        )
    ));
    ?>
    <fieldset>
        <legend>
            <p class="note">Fields dengan <span class="required">*</span> harus di isi.</p>
        </legend>

        <ul class="nav nav-tabs" id="myTab">
            <li class="active"><a href="#personal">Personal</a></li>
        </ul>

        <div class="tab-content">
            <div class="tab-pane active" id="personal">

                <table>
                    <tr>
                        <td style="vertical-align: top;">
                            <br/> 
                            <h3>Profile Information</h3>
                            <hr/>
                            <?php echo $form->textFieldRow($model, 'code', array('class' => 'span5', 'maxlength' => 25)); ?>

                            <?php echo $form->textFieldRow($model, 'name', array('class' => 'span5', 'maxlength' => 255)); ?> 
                            <?php echo $form->textFieldRow($model, 'email', array('class' => 'span5', 'maxlength' => 255)); ?> 

                            <?php
                            echo $form->textFieldRow(
                                    $model, 'phone', array('prepend' => '+62','class' => 'span2')
                            );
                            ?>

                            <div class="control-group ">
                                <?php
                                echo CHtml::activeLabel($model, 'province_id', array('class' => 'control-label'));
                                ?>
                                <div class="controls">
                                    <?php
                                    echo CHtml::dropDownList('province_id', $model->City->province_id, CHtml::listData(Province::model()->findAll(), 'id', 'name'), array(
                                        'empty' => 'Pilih',
                                        'ajax' => array(
                                            'type' => 'POST',
                                            'url' => CController::createUrl('landa/city/dynacities'),
                                            'update' => '#Supplier_city_id',
                                        ),
                                        'class' => 'span3'
                                    ));
                                    ?>  
                                </div>
                            </div>


                            <?php echo $form->dropDownListRow($model, 'city_id', CHtml::listData(City::model()->findAll('province_id=:province_id', array(':province_id' => (int) $model->City->province_id)), 'id', 'name'), array('class' => 'span3')); ?>
                            <?php echo $form->textAreaRow($model, 'address', array('class' => 'span5', 'maxlength' => 255)); ?>

                        </td>
                    </tr>
                </table>

            </div> 
        </div>
        <?php if (!isset($_GET['v'])) { ?>
            <div class="form-actions">
                <?php
                $this->widget('bootstrap.widgets.TbButton', array(
                    'buttonType' => 'submit',
                    'type' => 'primary',
                    'icon' => 'ok white',
                    'label' => $model->isNewRecord ? 'Tambah' : 'Simpan',
                ));
                ?>
            </div>
        <?php } ?>
    </fieldset>

    <?php $this->endWidget(); ?>

</div>