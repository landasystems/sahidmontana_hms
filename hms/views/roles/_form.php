<div class="form">
    <?php
    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id' => 'roles-form',
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

        <?php echo $form->textFieldRow($model, 'name', array('class' => 'span5', 'maxlength' => 255)); ?>
        <div class="well elek">
            <table class="table">
                <thead> 
                    <tr>
                        <th>Module/ Fitur</th>
                        <th>Read</th>
                        <th>Create</th>
                        <th>Update</th>
                        <th>Delete</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $arrMenu = Auth::model()->modules();
                    $mAuth = Auth::model()->findAll(array('index' => 'id', 'select' => 'id,crud'));
                    trace($mAuth);

                    if ($model->isNewRecord == false) {
                    $mRolesAuth = RolesAuth::model()->findAll(array('condition' => 'roles_id=' . $model->id, 'select' => 'id,auth_id,crud', 'index' => 'auth_id'));
                    trace($mRolesAuth);
                    }

                    foreach ($arrMenu as $arr) {
                    if (isset($arr['visible']) && $arr['visible'] == false) {
                    //do nothing
                    } else {
                    if (isset($arr['auth_id'])) {
                    $r = '';
                    $c = '';
                    $u = '';
                    $d = '';

                    $cValue = 0;
                    $rValue = 0;
                    $uValue = 0;
                    $dValue = 0;

                    //check value of checkbox
                    if (isset($mRolesAuth[$arr['auth_id']])) {
                    //check value
                    if ($model->isNewRecord == false) {
                    if (isset($mRolesAuth[$arr['auth_id']])) {
                    $arrRolesAuth = json_decode($mRolesAuth[$arr['auth_id']]->crud, true);
                    $cValue = (isset($arrRolesAuth['c']) && $arrRolesAuth['c'] == 1) ? 1 : 0;
                    $rValue = (isset($arrRolesAuth['r']) && $arrRolesAuth['r'] == 1) ? 1 : 0;
                    $uValue = (isset($arrRolesAuth['u']) && $arrRolesAuth['u'] == 1) ? 1 : 0;
                    $dValue = (isset($arrRolesAuth['d']) && $arrRolesAuth['d'] == 1) ? 1 : 0;
                    }
                    }
                    }
                    //-------------end of checkbox--------------------

                    $arrAuth = json_decode($mAuth[$arr['auth_id']]->crud, true);
                    $r = (isset($arrAuth['r']) && $arrAuth['r'] == 1) ? CHtml::CheckBox($arr['auth_id'] . '[r]', $rValue) : '';
                    $c = (isset($arrAuth['c']) && $arrAuth['c'] == 1) ? CHtml::CheckBox($arr['auth_id'] . '[c]', $cValue) : '';
                    $u = (isset($arrAuth['u']) && $arrAuth['u'] == 1) ? CHtml::CheckBox($arr['auth_id'] . '[u]', $uValue) : '';
                    $d = (isset($arrAuth['d']) && $arrAuth['d'] == 1) ? CHtml::CheckBox($arr['auth_id'] . '[d]', $dValue) : '';

                    echo '<tr>
                                    <td><input type="hidden" name="auth_id[]" value="' . $arr['auth_id'] . '"/>' . $arr['label'] . '</td>
                                    <td>' . $r . '</td>
                                    <td>' . $c . '</td>
                                    <td>' . $u . '</td>
                                    <td>' . $d . '</td>
                                </tr>';
                    } else {
                    echo '<tr>
                                    <td colspan="5">' . $arr['label'] . '</td>
                                </tr>';
                    }


                    if (isset($arr['items'])) {
                    foreach ($arr['items'] as $arrItems) {
                    if (isset($arrItems['visible']) && $arrItems['visible'] == false) {
                    //do nothing
                    } else {
                    $cValue = 0;
                    $rValue = 0;
                    $uValue = 0;
                    $dValue = 0;
                    $r = '';
                    $c = '';
                    $u = '';
                    $d = '';

                    //check the module have access or not
                    if (isset($arrItems['auth_id'])) {
                    //-----------check value of checkbox
                    if ($model->isNewRecord == false) {
                    if (isset($mRolesAuth[$arrItems['auth_id']])) {
                    $arrRolesAuth = json_decode($mRolesAuth[$arrItems['auth_id']]->crud, true);
                    $cValue = (isset($arrRolesAuth['c']) && $arrRolesAuth['c'] == 1) ? 1 : 0;
                    $rValue = (isset($arrRolesAuth['r']) && $arrRolesAuth['r'] == 1) ? 1 : 0;
                    $uValue = (isset($arrRolesAuth['u']) && $arrRolesAuth['u'] == 1) ? 1 : 0;
                    $dValue = (isset($arrRolesAuth['d']) && $arrRolesAuth['d'] == 1) ? 1 : 0;
                    }
                    }
                    //----------end of check value of checkbox----------------

                    if (isset($mAuth[$arrItems['auth_id']])) {
                    $arrAuth = json_decode($mAuth[$arrItems['auth_id']]->crud, true);
                    $r = (isset($arrAuth['r']) && $arrAuth['r'] == 1) ? CHtml::CheckBox($arrItems['auth_id'] . '[r]', $rValue) : '';
                    $c = (isset($arrAuth['c']) && $arrAuth['c'] == 1) ? CHtml::CheckBox($arrItems['auth_id'] . '[c]', $cValue) : '';
                    $u = (isset($arrAuth['u']) && $arrAuth['u'] == 1) ? CHtml::CheckBox($arrItems['auth_id'] . '[u]', $uValue) : '';
                    $d = (isset($arrAuth['d']) && $arrAuth['d'] == 1) ? CHtml::CheckBox($arrItems['auth_id'] . '[d]', $dValue) : '';
                    }

                    echo '<tr>
                                                    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="hidden" name="auth_id[]" value="' . $arrItems['auth_id'] . '"/>' . $arrItems['label'] . '</td>
                                                    <td>' . $r . '</td>
                                                    <td>' . $c . '</td>
                                                    <td>' . $u . '</td>
                                                    <td>' . $d . '</td>
                                                </tr>';
                    } else {
                    echo '<tr>
                                                    <td colspan="5">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $arrItems['label'] . '</td>
                                                </tr>';
                    }
                    }
                    }
                    }
                    }
                    }
                    ?>
                </tbody>
            </table>
        </div>


        <div class="form-actions">
            <?php
            $this->widget('bootstrap.widgets.TbButton', array(
                'buttonType' => 'submit',
                'type' => 'primary',
                'icon' => 'ok white',
                'label' => $model->isNewRecord ? 'Create' : 'Save',
            ));
            ?>
        </div>
    </fieldset>

    <?php $this->endWidget(); ?>

</div>
