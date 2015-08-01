<script>
    $(".next").on("click", function () {
        alert('asdasd');
    });
</script>
<legend>
    <p class="note">Fields with <span class="required">*</span> is Required.</p>
</legend>
<table>
    <tr>
        <td width="300" align="center">
            <?php
            $siteConfig = SiteConfig::model()->listSiteConfig();
            $img = Yii::app()->landa->urlImg('site/', $siteConfig->client_logo, 1);
            echo '<img src="' . $img['big'] . '" class="img-polaroid" style="max-width: 350px;"/>';
            ?>
            <div style="margin-left: 0px; margin-top: 10px;"> <?php echo $form->fileFieldRow($model, 'client_logo', array('class' => 'span3', 'label' => false)); ?></div>

        </td>
        <td style="vertical-align: top;">                                

            <?php echo $form->textFieldRow($model, 'client_name', array('class' => 'span4', 'maxlength' => 255)); ?>

            <?php // echo $form->dropDownListRow($model, 'language_default', array('id' => 'Indonesia', 'en' => 'English'));  ?>

            <?php
            echo $form->textFieldRow(
                    $model, 'phone', array('prepend' => '+62')
            );
            ?>

            <?php echo $form->textFieldRow($model, 'email', array('class' => 'span4', 'maxlength' => 45)); ?>
            <?php
            echo $form->textFieldRow(
                    $model, 'npwp', array()
            );
            ?>
            <?php
//                            echo $form->dropDownListRow(
//                                    $model, 'roles_guest', CHtml::listData(User::model()->roles(), 'id', 'name'), array('multiple' => true, 'class' => 'span4',)
//                            );
            ?>
            <div class="control-group ">
                <label class="control-label" for="SiteConfig_province_id">Province <span class="required">*</span></label>
                <div class="controls">
                    <?php
                    echo CHtml::dropDownList('province_id', isset($model->City->province_id) ? $model->City->province_id : 0, CHtml::listData(Province::model()->findAll(), 'id', 'name'), array(
                        'empty' => 'Please Choose',
                        'ajax' => array(
                            'type' => 'POST',
                            'url' => CController::createUrl('landa/city/dynacities'),
                            'update' => '#SiteConfig_city_id',
                        ),
                    ));
                    ?>  
                </div>
            </div>
            <?php echo $form->dropDownListRow($model, 'city_id', CHtml::listData(City::model()->findAll('province_id=:province_id', array(':province_id' => (int) isset($model->City->province_id) ? $model->City->province_id : 0)), 'id', 'name'), array('class' => 'span3')); ?>
            <?php echo $form->textAreaRow($model, 'address', array('class' => 'span4', 'rows' => 4, 'maxlength' => 255)); ?>

        </td>

    </tr>
</table>