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
    <fieldset>
        <legend>
            <p class="note">Fields dengan <span class="required">*</span> harus di isi.</p>
        </legend>

        <?php echo $form->errorSummary($model, 'Opps!!!', null, array('class' => 'alert alert-error span12')); ?>
        <div class="clearfix"></div>

        <?php if ($_GET['type'] == "user") { ?>
            <div class="box">
                <div class="title">
                    <h4>
                        <?php
                        echo 'Group <span class="required">*</span> :    ';
                        if ($model->id == User()->id) { //if same id, cannot change role it self
                            $listRoles = Roles::model()->listRoles();
                            if (User()->roles_id == -1) {
                                echo 'Super User';
                            } elseif (isset($listRoles[User()->roles_id])) {
                                echo $listRoles[User()->roles_id]['name'];
                            }
                        } else {

                            $array = Roles::model()->listRole($type);
                            if (!empty($array)) {
                                echo CHtml::dropDownList('User[roles_id]', $model->roles_id, CHtml::listData($array, 'id', 'name'), array(
                                    'empty' => t('choose', 'global'),
                                ));
                            } else {
                                echo'Data is empty please insert data group' . $type . '.';
                            }
                        }
                        ?>  
                    </h4>
                </div>
            </div>
            <?php
        } elseif ($_GET['type'] == "supplier") {
            echo '<input type="hidden" value="9" name="User[roles_id]"/>';
        } elseif ($_GET['type'] == "customer") {
            echo '<input type="hidden" value="10" name="User[roles_id]"/>';
        }
        ?>

        <ul class="nav nav-tabs" id="myTab">
            <li class="active"><a href="#personal">Personal</a></li>
        </ul>

        <div class="tab-content">
            <div class="tab-pane active" id="personal">

                <table>
                    <tr>
                        <td width="200">

                            <?php
//                          $imgs = '';
                            $cc = '';
                            if ($model->isNewRecord) {
                                $img = Yii::app()->landa->urlImg('', '', '');
                            } else {
                                $img = Yii::app()->landa->urlImg('avatar/', $model->avatar_img, $_GET['id']);
                                $del = '<div class="btn-group photo-det-btn">';
                                $imgs = param('urlImg') . '350x350-noimage.jpg';
                                $cc = CHtml::ajaxLink(
                                                '<i class="icon-trash">Remove Photo</i>', url('user/removephoto', array('id' => $model->id)), array(
                                            'type' => 'POST',
                                            'success' => 'function( data )
                                                    {
                                                           $("#my_image").attr("src","' . $imgs . '");
                                                           $("#yt0").fadeOut();
                                                    }'), array('class' => 'btn btn-large btn-block btn-primary', 'style' => 'width: 360px;font-size: 15px;')
                                        )
                                        . '</div>';
                            }
                            echo '<img width="230" src="' . $img['medium'] . '" alt="" class="image img-polaroid" id="my_image"  /> ';
                            echo $cc;
                            ?>
                            <br><br><div style="margin-left: -90px;"> <?php echo $form->fileFieldRow($model, 'avatar_img', array('class' => 'span3')); ?></div>

                        </td>
                        <td style="vertical-align: top;">                                
                            <?php if ($_GET['type'] == 'user') { ?>
                                <h3>Login Information</h3>
                                <hr/>
                                <?php echo $form->textFieldRow($model, 'username', array('class' => 'span5', 'maxlength' => 20)); ?>

                                <?php echo $form->textFieldRow($model, 'email', array('class' => 'span5', 'maxlength' => 100)); ?>

                                <?php echo $form->passwordFieldRow($model, 'password', array('class' => 'span3', 'maxlength' => 255, 'hint' => 'Fill the password, to change',)); ?>

                                <?php
                                $mDepartement = Departement::model()->findAll(array());
                                echo $form->dropDownListRow($model, 'departement_id', CHtml::listData($mDepartement, 'id', 'name'), array(
                                    'empty' => t('choose', 'global'),
                                ));
                                ?>
                            <?php } ?>

                            <br/> 
                            <h3>Profile Information</h3>
                            <hr/>
                            <?php echo $form->textFieldRow($model, 'code', array('class' => 'span5', 'maxlength' => 25)); ?>

                            <?php echo $form->textFieldRow($model, 'name', array('class' => 'span5', 'maxlength' => 255)); ?> 

                            <?php echo $form->toggleButtonRow($model, 'enabled'); ?>
                            <?php
                            echo $form->textFieldRow(
                                    $model, 'phone', array('prepend' => '+62')
                            );
                            ?>
                            <?php
                            echo $form->textAreaRow(
                                    $model, 'description', array('class' => 'span4', 'rows' => 2)
                            );
                            ?>

                            <div class="control-group ">
                                <?php
                                echo CHtml::activeLabel($model, 'province_id', array('class' => 'control-label'));
                                ?>
                                <div class="controls">
                                    <?php
                                    echo CHtml::dropDownList('province_id', $model->City->province_id, CHtml::listData(Province::model()->findAll(), 'id', 'name'), array(
                                        'empty' => t('choose', 'global'),
                                        'ajax' => array(
                                            'type' => 'POST',
                                            'url' => CController::createUrl('landa/city/dynacities'),
                                            'update' => '#User_city_id',
                                        ),
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
</div>


<div class="form-actions">
    <?php
    $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType' => 'submit',
        'type' => 'primary',
        'icon' => 'ok white',
        'label' => $model->isNewRecord ? 'Tambah' : 'Simpan',
    ));
    ?>
    <?php
    $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType' => 'reset',
        'icon' => 'remove',
        'label' => 'Reset',
    ));
    ?>
</div>
</fieldset>

<?php $this->endWidget(); ?>

</div>
