<div class="form">
    <?php
    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id' => 'deposite-form',
        'enableAjaxValidation' => false,
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
            <p class="note">Fields with <span class="required">*</span> is Required.</p>
        </legend>        


        <div class="box gradient invoice">
            <div class="title clearfix">
                <h4 class="left">
                    <span class="blue cut-icon-bookmark"></span>
                    <span>Deposite Transaction</span>                        
                </h4>
                <div class="invoice-info">
                    <span class="number"> <strong class="red">
                            <?php
                            echo $model->code;
                            ?>                            
                        </strong></span><br>
                    <span class="data gray"><?php echo date('d M Y') ?></span>
                </div> 
            </div>

            <div class="content">                   
                <?php
//                $data = CHtml::listData(User::model()->listUsers('guest'), 'id', 'fullName');
                $id = isset($model->guest_user_id) ? $model->guest_user_id : 0;
                $selName = isset($model->Guest->name) ? '[' . $model->Guest->Roles->name . '] ' . $model->Guest->name : '';
                echo $form->select2Row($model, 'guest_user_id', array(
                    'asDropDownList' => false,
//                    'data' => $data,
                    'options' => array(
                        "placeholder" => 'Please Choose',
                        "allowClear" => true,
                        'width' => '30%',
                        'minimumInputLength' => '3',
                        'ajax' => array(
                            'url' => Yii::app()->createUrl('user/getBillUser'),
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
                        'initSelection' => 'js:function(element, callback) 
                            { 
                                data = {
                                    "id": ' . $id . ',
                                    "text": "' . $selName . '",
                                }
                                  callback(data);   
                            }',
                    )
                        )
                );


                echo $form->radioButtonListRow($model, 'dp_by', $model->by(), array('class' => 'span2', 'maxlength' => 5, 'empty' => 'Please Choose'));
                echo $form->textFieldRow($model, 'amount', array('class' => 'angka', 'prepend' => 'Rp'));
                echo $form->textFieldRow($model, 'cc_number', array('class' => 'span3'));
                echo $form->textAreaRow($model, 'description', array('rows' => 6, 'cols' => 50, 'class' => 'span8'));
                ?>                                                        

                <table style="width:100%">
                    <tr>
                        <td style="width:50%;text-align: center;vertical-align: top">
                            <br>
                            Guest Sign
                            <br>
                            <br>
                            <br>
                    <u>......................................</u>
                    </td>
                    <td style="width:50%;text-align: center;vertical-align: top">
                        <br>
                        Cashier
                        <br>
                        <br>
                        <br>
                    <u><?php echo Yii::app()->user->name; ?></u>
                    </td>
                    </tr>
                </table>
            </div>
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
