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

        <?php echo $form->errorSummary($model, 'Opps!!!', null, array('class' => 'alert alert-error span12')); ?>

        <?php echo $form->textFieldRow($model, 'name', array('class' => 'span5', 'maxlength' => 255)); ?>

        <div class="well elek">
            <ul class="nav nav-tabs" id="myTab">
                <li class="active"><a href="#module" data-toggle="tab">Module</a></li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane active" id="module">
                    <table class="table">
                        <thead> 
                            <tr>
                                <th></th>
                                <th>Access</th>
                                <th>Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $arrMenu = Auth::model()->modules();
                            if ($model->isNewRecord == false) {
                                $mRolesAuth = RolesAuth::model()->findAll(array('condition' => 'roles_id=' . $model->id, 'select' => 'id,auth_id,crud', 'index' => 'auth_id'));
                            } else {
                                $mRolesAuth = array();
                            }
                            $this->renderPartial('_menuSub', array('arrMenu' => $arrMenu, 'mRolesAuth' => $mRolesAuth, 'model' => $model, 'space' => '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'));
                            ?>
                        </tbody>
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
                'visible' => !isset($_GET['v']),
                'label' => 'Save',
            ));
            ?>
        </div>
    </fieldset>

    <?php $this->endWidget(); ?>

</div>
