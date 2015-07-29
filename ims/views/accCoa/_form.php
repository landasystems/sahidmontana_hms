<div class="form">
    <?php
    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id' => 'acc-coa-form',
        'enableAjaxValidation' => true,
        'method' => 'post',
        'type' => 'horizontal',
        'htmlOptions' => array(
            'enctype' => 'multipart/form-data'
        )
    ));
    ?>

    <?php echo $form->errorSummary($model, 'Opps!!!', null, array('class' => 'alert alert-error')); ?>

    <fieldset>
        <legend>
            <p class="note">Fields dengan <span class="required">*</span> harus di isi.</p>
        </legend>

        <div class="box gradient invoice">
            <div class="title clearfix">
                <h4 class="left">
                    <span></span>
                </h4>
                <div class="invoice-info">
                    <span class="number"></span>
                    <span class="data gray"><br></span>
                    <div class="clearfix"></div>
                </div>

            </div>
            <div class="content">
                <div class="control-group">
                    <label class="control-label">Induk Perkiraan</label>
                    <div class="controls">
                        <?php
                        $data = array('0' => 'root') + CHtml::listData(AccCoa::model()->findAll(array('order' => 'root, lft')), 'id', 'nestedname');
                        $this->widget('bootstrap.widgets.TbSelect2', array(
                            'asDropDownList' => TRUE,
                            'data' => $data,
                            'name' => 'AccCoa[parent_id]',
                            'value' => (isset($model->parent_id) ? $model->parent_id : ''),
                            'options' => array(
                                "allowClear" => true,
                                "width" => "270px",
                            ),
                            'htmlOptions' => array(
                                'id' => 'parent_id',
                            ),
                        ));
                        ?>
                    </div>
                </div>
                <?php
                echo $form->textFieldRow($model, 'code', array('class' => 'span1', 'id' => 'code', 'maxlength' => 60));
                echo $form->textFieldRow($model, 'name', array('class' => 'span3', 'maxlength' => 60));
                echo $form->error($model, 'name');
                echo $form->radioButtonListRow(
                        $model, 'type', array('general' => 'General', 'detail' => 'Detail'), array('class' => 'form-inline span3', 'onChange' => '
                            if($("#AccCoa_type_1").prop("checked")){
                             $(".sub_ledger").show();
                            }else{
                             $(".sub_ledger").hide();
                        }')
                );
                echo $form->radioButtonListRow(
                        $model, 'group', array("aktiva" => "Aktiva", "pasiva" => "Pasiva", "receivable" => "Pendapatan", "cost" => "Biaya"), array('class' => 'span3')
                );
                ?>
                <?php
                if ($model->type == "detail") {
                    $class = 'block';
                } else {
                    $class = 'none';
                }
                ?>
                <div class="control-group sub_ledger" style="display:<?php echo $class ?>;">
                    <label class="control-label">Type Sub Ledger</label>
                    <div class="controls">
                        <?php
                        echo CHtml::dropDownList('AccCoa[type_sub_ledger]', 5, AccCoa::model()->typeSub(), array('class' => 'span3', 'empty' => 'Pilih', 'options' => array($model->type_sub_ledger => array('selected' => true))));
                        ?>
                    </div>
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
                'htmlOptions' => array(
                ),
            ));
            ?>
        </div>
    </fieldset>

    <?php $this->endWidget(); ?>

</div>
