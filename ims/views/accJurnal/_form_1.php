<?php
foreach (Yii::app()->user->getFlashes() as $key => $message) {
    echo '<div class="alert alert-' . $key . '">' . $message . '</div>';
}
?>
<div class="form">
    <?php
    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id' => 'acc-jurnal-form',
        'enableAjaxValidation' => false,
        'method' => 'post',
        'type' => 'horizontal',
        'htmlOptions' => array(
            'enctype' => 'multipart/form-data'
        )
    ));
    ?>
    <div class="box gradient invoice">

        <div class="title clearfix">

            <h4 class="left">
                <span></span>
            </h4>
            <div class="invoice-info">
                <span class="number"></span>
                <span class="data gray"></span>
                <div class="clearfix"></div>
            </div>

        </div>
        <div class="content">
            <fieldset>
                <legend>
                    <p class="note">Fields dengan <span class="required">*</span> harus di isi.</p>
                </legend>
                <?php echo $form->errorSummary($model, 'Opps!!!', null, array('class' => 'alert alert-error span12')); ?>
                <br>
                <div class="row" style="margin-left: 0px;">
                    <?php echo $form->textFieldRow($model, 'code', array('class' => 'span3', 'maxlength' => 255, 'readonly' => 'true')); ?>
                    <div class="control-group">
                        <label class="control-label">Tanggal</label>
                        <div class="controls">
                            <?php
                            $siteConfig = SiteConfig::model()->findByPk(param('id'));
                            if ($siteConfig->date_system != "0000-00-00") {
                                $dateSystem = $siteConfig->date_system;
                            } else {
                                $dateSystem = date("Y-m-d");
                            }

                            if (isset($_POST['valdebet'])) {
                                $model->date_trans = $_POST['AccJurnal']['date_trans'];
                            } else {
                                $model->date_trans = $model->date_trans;
                            }

                            $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                                'name' => 'AccJurnal[date_trans]',
                                'value' => $model->date_trans,
                                'options' => array(
                                    'minDate' => $dateSystem,
                                    'showAnim' => 'fold',
                                    'changeMonth' => 'true',
                                    'changeYear' => 'true',
                                    'dateFormat' => 'yy-mm-dd'
                                ),
                                'htmlOptions' => array(
                                    'style' => 'height:20px;',
                                    'id' => 'acccoa',
                                    'class' => 'span3'
                                ),
                            ));
                            ?> 
                        </div>
                    </div>
                    <?php
                    if (isset($_POST['valdebet'])) {
                        $desc = $_POST['AccJurnal']['description'];
                    } else {
                        $desc = $model->description;
                    }

                    echo $form->textAreaRow($model, 'description', array('class' => 'span4', 'value' => $desc, 'maxlength' => 255));
                    ?>
                </div>

                <br>
                <h4>Detail Dana</h4>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th width="20">#</th>
                            <th width="250">Kode Rekening</th>
                            <th width="150">Sub Ledger</th>
                            <th width="300">Keterangan</th>
                            <th>Debit</th>
                            <th>Kredit</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <?php
                                echo CHtml::ajaxLink(
                                        $text = '<i class="icon-plus-sign"></i>', $url = url('AccJurnal/addRow'), $ajaxOptions = array(
                                    'type' => 'POST',
                                    'success' => '
                                                function(data){   
                                                calculate();
                                                $("#addRow").replaceWith(data);  
                                                clear();
                                                
                                                 $(".totalDeb").on("keyup", function() {
                                                    var totalDebet=0;
                                                $(".totalDeb").each(function() {
                                               totalDebet += parseInt($(this).val());
                                                });
                                               $("#total_debet").val(totalDebet);

                                                var totalCredit=0;
                                                $(".totalCre").each(function() {
                                               totalCredit += parseInt($(this).val());
                                                });
                                               $("#total_credit").val(totalCredit);
                                               });
                                               
                                               $(".totalCre").on("keyup", function() {
                                                    var totalDebet=0;
                                                $(".totalDeb").each(function() {
                                               totalDebet += parseInt($(this).val());
                                                });
                                               $("#total_debet").val(totalDebet);

                                                var totalCredit=0;
                                                $(".totalCre").each(function() {
                                               totalCredit += parseInt($(this).val());
                                                });
                                               $("#total_credit").val(totalCredit);
                                               });
                                                
                                                $(".delRow").on("click", function() {
                                                  $(this).parent().parent().remove();
                                                  calculateMin();
                                                });
                                                
                                            
                                        }'), $htmlOptions = array('id' => 'btnAdd')
                                );
                                ?>
                            </td>
                            <td>
                                <?php
                                $data = array(0 => t('choose', 'global')) + CHtml::listData(AccCoa::model()->findAll(array('order' => 'root, lft')), 'id', 'nestedname');
                                $this->widget('bootstrap.widgets.TbSelect2', array(
                                    'asDropDownList' => TRUE,
                                    'data' => $data,
                                    'name' => 'account',
                                    'options' => array(
                                        "placeholder" => t('choose', 'global'),
                                        "allowClear" => true,
                                    ),
                                    'htmlOptions' => array(
                                        'id' => 'account',
                                        'style' => 'width:100%;'
                                    ), 'events' => array('change' => 'js: function() {
                                                     $.ajax({
                                                        url : "' . url('accCoa/retAccount') . '",
                                                        type : "POST",
                                                        data :  { ledger :  $(this).val()},
                                                        success : function(data){
                                                                $("#s2id_accountName").select2("data", "0")
                                                                $("#accountName").html(data);
                                                                }
                                                     });
                                     }')
                                ));
                                ?>
                            </td>
                            <td>
                                <?php
                                $data = array(0 => t('choose', 'global'));
                                $this->widget('bootstrap.widgets.TbSelect2', array(
                                    'asDropDownList' => TRUE,
                                    'data' => $data,
                                    'name' => 'accountName',
                                    'options' => array(
                                        "placeholder" => t('choose', 'global'),
                                        "allowClear" => true,
                                    ),
                                    'htmlOptions' => array(
                                        'id' => 'accountName',
                                        'style' => 'width:100%;'
                                    ),
                                ));
                                ?>
                            </td>
                            <td>
                                <?php echo CHtml::textfield('costDescription', '', array('style' => 'width:95%;', 'maxlength' => 255)); ?>
                            </td>
                            <td>
                                <div class="input-prepend">
                                    <span class="add-on">Rp.</span>
                                    <?php echo CHtml::textField('debit', '0', array('class' => 'angka', 'value' => 0, 'maxlength' => 60, 'prepend' => 'Rp')); ?>
                                </div>
                            </td>
                            <td>
                                <div class="input-prepend">
                                    <span class="add-on">Rp.</span>
                                    <?php echo CHtml::textField('credit', '0', array('class' => 'angka', 'value' => 0, 'maxlength' => 60, 'prepend' => 'Rp')); ?>
                                </div>
                            </td>
                        </tr>
                        <tr id="addRow" style="display:none">
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <?php
                        if ($model->isNewRecord == false) {
                            $no = 0;
                            foreach ($detailJurnal as $val) {
                                if (isset($val->AccCoa->name)) {
                                    $accCoaName = $val->AccCoa->code . ' - ' . $val->AccCoa->name;
                                } else {
                                    $accCoaName = ' - ';
                                }

                                if (!empty($val->ar_id)) {
                                    $account = User::model()->findByPk($val->ar_id);
                                    $name = $account->name;
                                    $id = $account->id;
                                } else if (!empty($val->ap_id)) {
                                    $account = User::model()->findByPk($val->ap_id);
                                    $name = $account->name;
                                    $id = $account->id;
                                } else if (!empty($val->as_id)) {
                                    $account = Product::model()->findByPk($val->as_id);
                                    $name = $account->name;
                                    $id = $account->id;
                                } else {
                                    $name = "-";
                                    $id = "0";
                                }

                                if (isset($_POST['valdebet'])) {
                                    $debet = $_POST['valdebet'][$no];
                                } else {
                                    $debet = $val->debet;
                                }

                                if (isset($_POST['valcredit'])) {
                                    $credit = $_POST['valcredit'][$no];
                                } else {
                                    $credit = $val->credit;
                                }

                                $no++;

                                echo '<tr id="addRow" style="display:none">
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <input type="hidden" name="acc_coa_id[]" id="acc_coa_id[]" value="' . $val->id . '"/>
                                            <input type="hidden" name="nameAccount[]" id="nameAccount[]" value="' . $id . '"/>
                                            <input type="hidden" name="description[]" id="description[]" value="' . $val->description . '"/>
                                            <i class="delRow icon-remove-circle" style="cursor:all-scroll;"></i> 
                                        </td>
                                        <td>' . $accCoaName . '</td>
                                        <td>' . $name . '</td>
                                        <td>' . $val->description . '</td>
                                         <td><div class="input-prepend"> <span class="add-on">Rp.</span><input type="text" style="width:75px;"  onkeyup="calculateMin()" class="angka totalDeb" name="valdebet[]" id="valdebet[]" class="totalDeb" value="' . $debet . '"/></div></td>
                                        <td><div class="input-prepend"> <span class="add-on">Rp.</span><input type="text" style="width:75px;" name="valcredit[]" id="valcredit[]" class="angka totalCre" value="' . $credit . '"/></div></td>
                                     </tr>';
                            }
                        } if ($model->isNewRecord == true and isset($_POST['valcredit'])) {
                            for ($i = 0; $i < count($_POST['acc_coa_id']); $i++) {
                                $accCoa = AccCoa::model()->find(array('condition' => 'id=' . $_POST['acc_coa_id'][$i]));

                                if (isset($_POST['nameAccount'][$i])) {
                                    $account = (object) array('name' => '', 'id' => '');
                                    if ($accCoa->type_sub_ledger == "ar")
                                        $account = User::model()->findByPk($_POST['nameAccount'][$i]);

                                    if ($accCoa->type_sub_ledger == "ap")
                                        $account = User::model()->findByPk($_POST['nameAccount'][$i]);

                                    if ($accCoa->type_sub_ledger == "as")
                                        $account = Product::model()->findByPk($_POST['nameAccount'][$i]);

                                    $name = $account->name;
                                    $id = $account->id;
                                } else {
                                    $name = "-";
                                    $id = "0";
                                }

                                if (isset($_POST['valdebet'])) {
                                    $debet = $_POST['valdebet'][$i];
                                } else {
                                    $debet = $val->debet;
                                }

                                if (isset($_POST['valcredit'])) {
                                    $credit = $_POST['valcredit'][$i];
                                } else {
                                    $credit = $val->credit;
                                }
                                echo '<tr id="addRow" style="display:none">
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <input type="hidden" name="acc_coa_id[]" id="acc_coa_id[]" value="' . $_POST['acc_coa_id'][$i] . '"/>
                                            <input type="hidden" name="nameAccount[]" id="nameAccount[]" value="' . $id . '"/>
                                            <input type="hidden" name="description[]" id="description[]" value="' . $_POST['description'][$i] . '"/>
                                            <input type="hidden" name="valdebet[]" id="valdebet[]" class="totalDeb" value="' . $_POST['valdebet'][$i] . '"/>
                                            <input type="hidden" name="valcredit[]" id="valcredit[]" class="totalCre" value="' . $_POST['valcredit'][$i] . '"/>
                                            <i class="delRow icon-remove-circle" style="cursor:all-scroll;"></i> 
                                        </td>
                                        <td>' . $accCoa->code . ' - ' . $accCoa->name . '</td>
                                        <td>' . $name . '</td>
                                        <td>' . $_POST['description'][$i] . '</td>
                                        <td><div class="input-prepend"> <span class="add-on">Rp.</span><input type="text" style="width:75px;"  onkeyup="calculateMin()" class="angka totalDeb" name="valdebet[]" id="valdebet[]" class="totalDeb" value="' . $debet . '"/></div></td>
                                        <td><div class="input-prepend"> <span class="add-on">Rp.</span><input type="text" style="width:75px;" name="valcredit[]" id="valcredit[]" class="angka totalCre" value="' . $credit . '"/></div></td>
                                      </tr>';
                            }
                        }
                        ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="4"><b>Total</b></td>
                            <?php
                            if (isset($_POST['AccJurnal']['total_debet'])) {
                                $total_debet = $_POST['AccJurnal']['total_debet'];
                            } else {
                                $total_debet = $model->total_debet;
                            }

                            if (isset($_POST['AccJurnal']['total_credit'])) {
                                $total_credit = $_POST['AccJurnal']['total_credit'];
                            } else {
                                $total_credit = $model->total_credit;
                            }
                            ?>
                            <td>
                                <div class="input-prepend">
                                    <span class="add-on">Rp.</span>
                                    <?php echo CHtml::textfield('AccJurnal[total_debet]', $total_debet, array('id' => 'total_debet', 'class' => 'angka', 'readonly' => true, 'maxlength' => 255)); ?>
                                </div>
                            </td>
                            <td>
                                <div class="input-prepend">
                                    <span class="add-on">Rp.</span>
                                    <?php echo CHtml::textfield('AccJurnal[total_credit]', $total_credit, array('id' => 'total_credit', 'class' => 'angka', 'readonly' => true, 'maxlength' => 255)); ?>
                                </div>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </fieldset>
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

    <?php $this->endWidget(); ?>

</div>
