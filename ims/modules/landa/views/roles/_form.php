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
        <?php
        echo $form->toggleButtonRow($model, 'is_allow_login', array(
            'onChange' => '
                            if($("#Roles_is_allow_login").prop("checked")){
                            $(".elek").show();
                            }else{
                            $(".elek").hide();
                            }'
        ));
        $class = ($model->is_allow_login == 1) ? 'block' : 'none';
        ?>

        <div class="well elek" style="display:<?php echo $class ?>;">
            <ul class="nav nav-tabs" id="myTab">
                <li class="active"><a href="#module" data-toggle="tab">Module</a></li>
                <li><a href="#extended" data-toggle="tab">Extended</a></li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane active" id="module">
                    <table class="table">
                        <thead> 
                            <tr>
                                <th></th>
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

                            if ($model->isNewRecord == false) {
                                $mRolesAuth = RolesAuth::model()->findAll(array('condition' => 'roles_id=' . $model->id, 'select' => 'id,auth_id,crud', 'index' => 'auth_id'));
                            } else {
                                $mRolesAuth = array();
                            }
                            $this->renderPartial('_menuSub', array('arrMenu' => $arrMenu, 'mRolesAuth' => $mRolesAuth, 'mAuth' => $mAuth, 'model' => $model, 'space' => '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'));
                            ?>
                        </tbody>
                    </table>
                </div>
                <div class="tab-pane" id="extended">
                    
                    <table class="table">
                        <tr>
                            <th colspan="3">Advanced Options</th>
                        </tr>
                        <tr>
                            <td width="40%">Hak Akses Akun</td>
                            <td width="10%"><b>:</b></td>
                            <td>
                                <?php
                                $sWhere = '';
                                if (isset($model->id)) {
                                    $roles = RolesAuth::model()->find(array('condition' => 'roles_id=' . $model->id . ' AND auth_id="accesskb"'));
                                    if (isset($roles->auth_id)) {
                                        $idData = $roles->crud;
                                        $sWhere = json_decode($idData);
                                    } else {
                                        $sWhere = '';
                                    }
                                }
                                $data = AccCoa::model()->findAll(array(
                                                'condition' => '(type_sub_ledger ="ks") OR (type_sub_ledger="bk")'
                                            ));
                                $this->widget('bootstrap.widgets.TbSelect2', array(
                                    'asDropDownList' => TRUE,
                                    'data' => CHtml::listData(AccCoa::model()->findAll(array(
                                                'condition' => 'type="detail" AND ((type_sub_ledger ="ks") OR (type_sub_ledger="bk"))'
                                            )), 'id', 'nestedname'),
                                    'name' => 'accesskb[]',
                                    'value' => ($model->isNewRecord == true)? $data : $sWhere,
                                    'options' => array(
                                        'placeholder' => 'Data belum diisi',
                                        'width' => '60%',
                                        'tokenSeparators' => array(',', ' ')
                                    ),
                                    'htmlOptions' => array(
                                        'multiple' => 'multiple',
                                    )
                                ));
                                ?>
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
