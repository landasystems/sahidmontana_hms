<?php
foreach (Yii::app()->user->getFlashes() as $key => $message) {
    echo '<div class="alert alert-' . $key . '">' . $message . '</div>';
}
?>
<div class="form">
    <?php
    $siteConfig = SiteConfig::model()->findByPk(param('id'));
    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id' => 'acc-cash-in-form',
        'enableAjaxValidation' => false,
        'method' => 'post',
        'type' => 'vertical',
        'htmlOptions' => array(
            'enctype' => 'multipart/form-data'
        )
    ));
    ?>
    <div class="box gradient invoice">

        <div class="title clearfix">

            <h4 class="left">
                <span class="number"><?php echo (isset($model->code_acc)) ? '#' . $model->code_acc : ''; ?></span>
                <span class="data gray"><?php echo (isset($model->date_posting)) ? date('d-M-Y', strtotime($model->date_posting)) : ''; ?></span>
            </h4>
            <div class="invoice-info">
                <span class="number"></span>
                <span class="data gray"></span>
                <div class="clearfix"></div>
            </div>

        </div>
        <div class="content">
            <?php
            if (isset($_POST['AccCashIn']['code'])) {
                $code = $_POST['AccCashIn']['code'];
            } else {
                $code = $model->code;
            }
            if (isset($_POST['AccCashIn']['description'])) {
                $desc = $_POST['AccCashIn']['description'];
            } else {
                $desc = $model->description;
            }
            if (isset($_POST['AccCashIn']['date_trans'])) {
                $date = $_POST['AccCashIn']['date_trans'];
            } else {
                $date = $model->date_trans;
            }
            if (isset($_POST['AccCashIn']['accCoa'])) {
                $accCoa = $_POST['AccCashIn']['accCoa'];
            } else {
                $accCoa = $model->acc_coa_id;
            }
            if (isset($_POST['totalDebit'])) {
                $totalDebit = $_POST['totalDebit'];
            } else {
                $totalDebit = $model->total;
            }
            ?>
            <fieldset>
                <legend>
                    <p class="note">Fields dengan <span class="required">*</span> harus di isi.</p>
                </legend>
                <?php echo $form->errorSummary($model, 'Opps!!!', null, array('class' => 'alert alert-error')); ?>
                <div class="row" style="margin-left: 0px;">
                    <table width="100%">
                        <tr>
                            <td width="50%"><?php echo $form->textFieldRow($model, 'code', array('class' => 'span2', 'maxlength' => 255, 'value' => $code, 'readonly' => true)); ?></td>
                            <td width="50%"><label for="AccCashIn_accCoa">Masuk Ke</label>
                                <?php
                                $accessCoa = AccCoa::model()->accessCoa();
                                $data = array(0 => t('choose', 'global')) + CHtml::listData(AccCoa::model()->findAll(array('condition' => $accessCoa, 'order' => 'root, lft')), 'id', 'nestedname');
                                $this->widget('bootstrap.widgets.TbSelect2', array(
                                    'asDropDownList' => TRUE,
                                    'data' => $data,
                                    'name' => 'AccCashIn[accCoa]',
                                    'value' => (isset($accCoa) ? $accCoa : ''),
                                    'options' => array(
                                        "placeholder" => t('choose', 'global'),
                                        "allowClear" => true,
                                    ),
                                    'htmlOptions' => array(
                                        'id' => 'AccCashIn_account',
                                        'style' => 'width:250px;',
                                        'onChange' => 'checkSelected();'
                                    ),
                                ));
                                ?></td>
                        </tr>
                        <tr>
                            <td> 
                                <?php echo $form->textFieldRow($model, 'date_trans', array('class' => 'span2', 'readonly' => true, 'maxlength' => 255)); ?>
                            </td>
                            <td> <label for="TotalDebit">Total Debit</label>
                                <div class="input-prepend">
                                    <span class="add-on">Rp.</span>
                                    <?php echo CHtml::textfield('totalDebit', $totalDebit, array('class' => 'angka', 'maxlength' => 255)); ?>
                                </div></td>
                        </tr>
                        <tr>
                            <td><?php echo $form->textAreaRow($model, 'description', array('class' => 'span4', 'value' => $desc, 'maxlength' => 255)); ?></td>
                            <td><?php echo $form->textFieldRow($model, 'description_to', array('class' => 'span4', 'maxlength' => 255)); ?></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td><?php echo $form->textFieldRow($model, 'description_giro_an', array('class' => 'span4', 'maxlength' => 255)); ?></td>
                        </tr>
                    </table>
                </div>
                <h4>Detail Dana</h4>
                <table class="responsive table table-bordered">
                    <thead>
                        <tr>
                            <th width="20">#</th>
                            <th width="250">Kode Rekening</th>
                            <th width="150">Sub Ledger</th>
                            <th width="300">Keterangan</th>
                            <th style="width:5%">Kredit</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class='insertNew'>
                            <td style="text-align: center">
                                <input type="hidden" name="subledgerid" value="" class="inVoiceDet">
                                <?php
                                echo CHtml::ajaxLink(
                                        $text = '<i class="icon-plus-sign"></i>', $url = url('AccCashIn/addRow'), $ajaxOptions = array(
                                    'type' => 'POST',
                                    'success' => 'function(data){
                                                           calculate();
                                                           $("#addRow").replaceWith(data);     
                                                           clear();
                                                           
                                                            $(".totalDet").on("keyup", function() {
                                                            var subTotal=0;
                                                            $(".totalDet").each(function() {
                                                               subTotal += parseInt($(this).val());
                                                            });
                                                            var selisih = parseInt($("#totalDebit").val()) - parseInt(subTotal);
                                                            $("#difference").val(selisih);
                                                            $("#AccCashIn_total").val(subTotal);
                                                            });
                                                           
                                                           $(".delRow").on("click", function() {
                                                           $(this).parent().parent().parent().remove();
//                                                           calculateMin();
                                                           });
                                                           $("#account").select2("focus");
                                                            $(".newRow").find(".selectDua").select2();
                                                            $(".newRow").removeClass("newRow");
                                                            $(".insertNew").find(".inVoiceDet").val("0");
                                                            $(".insertNew").find(".subLedgerField").html("<a style=\"display:none\" class=\"btn showModal\">Select Sub-Ledger</a>");
                                                           
                                        }'), $htmlOptions = array('id' => 'btnAdd', 'class' => 'btn AddNewRow')
                                );
                                ?>
                            </td>
                            <td>
                                <?php
                                $data = array(0 => t('choose', 'global')) + CHtml::listData(AccCoa::model()->findAll(array('order' => 'root, lft')), 'id', 'nestedname');
//                                logs($data);
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
                                        'style' => 'width:100%;',
                                        'class' => 'subLedger'
                                    ), 'events' => array('change' => 'js: function() {
                                        var elements = $(this).parent().parent().find(".showModal");
                                                    checkSelected();
                                                    retAccount($(this).val(),elements);
                                                     
                                     }')
                                ));
                                ?>
                            </td>
                            <td  class="subLedgerField" style="text-align: center">
                                <?php
                                $this->widget(
                                        'bootstrap.widgets.TbButton', array(
                                    'label' => 'Select Sub-Ledger',
                                    'htmlOptions' => array(
//                                        'data-toggle' => 'modal',
//                                        'data-target' => '#modalSub',
                                        'style' => 'display:none',
                                        'class' => 'showModal',
                                    ),
                                        )
                                );
                                ?>
                            </td>
                            <td><?php echo CHtml::textfield('costDescription', '', array('style' => 'width:95%;', 'maxlength' => 255)); ?></td>
                            <td>
                                <div class="input-prepend">
                                    <span class="add-on">Rp.</span>
                                    <?php echo CHtml::textField('credit', '0', array('class' => 'angka', 'value' => 0, 'maxlength' => 60, 'prepend' => 'Rp',)); ?>
                                </div>
                            </td>
                        </tr>
                        <tr id="addRow" style="display:none">
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <?php
                        $invoiceName = '';
                        if ($model->isNewRecord == false and ! isset($_POST['AccCashInDet'])) {
                            $i = 0;
                            $value = '';
                            foreach ($cashInDet as $viewCashInDet) {
                                $invoice = (!empty($viewCashInDet->invoice_det_id)) ? $viewCashInDet->invoice_det_id : 0;
                                $code = (!empty($viewCashInDet->InvoiceDet->code)) ? $viewCashInDet->InvoiceDet->code : "-";

                                if ($viewCashInDet->AccCoa !== NULL) {
                                    $accCoaName = $viewCashInDet->AccCoa->code . ' - ' . $viewCashInDet->AccCoa->name;
                                } else {
                                    $accCoaName = ' - ';
                                }

                                if (!empty($viewCashInDet->ar_id)) {
                                    $account = User::model()->findByPk($viewCashInDet->ar_id);
                                    $name = $account->name;
                                    $id = $account->id;
                                } else if (!empty($viewCashInDet->ap_id)) {
                                    $account = User::model()->findByPk($viewCashInDet->ap_id);
                                    $name = $account->name;
                                    $id = $account->id;
                                } else if (!empty($viewCashInDet->as_id)) {
                                    $account = Product::model()->findByPk($viewCashInDet->as_id);
                                    $name = $account->name;
                                    $id = $account->id;
                                } else {
                                    $name = "-";
                                    $id = "0";
                                }
                                $invoiceName = (!empty($viewCashInDet->InvoiceDet->code) && !empty($viewCashInDet->InvoiceDet->User->name)) ? '<a class="btn btn-mini removeSub"><i class=" icon-remove-circle"></i></a>[' . $viewCashInDet->InvoiceDet->code . ']' . $viewCashInDet->InvoiceDet->User->name : '';
                                if (isset($_POST['AccCashInDet'])) {
                                    $amount = $_POST['AccCashInDet']['amount'][$i];
                                } else {
                                    $amount = $viewCashInDet->amount;
                                }
                                $disp = ($viewCashInDet->AccCoa->type_sub_ledger == 'ar' || $viewCashInDet->AccCoa->type_sub_ledger == 'as' || $viewCashInDet->AccCoa->type_sub_ledger == 'ap') ? true : false;
                                $display = (empty($invoiceName) && $disp) ? '' : 'none';
                                $i++;

                                echo '  <tr>
                                            <td style="text-align:center">
                                                <input type="hidden" name="nameAccount[]" id="nameAccount[]" value="' . $id . '"/>
                                                <input type="hidden" name="inVoiceDet[]" id="inVoiceDet[]"  class="inVoiceDet" value="' . $invoice . '"/>
                                                <span class="btn"><i class="delRow icon-remove-circle" style="cursor:all-scroll;"></i></span>
                                            </td>
                                            <td>';
                                echo '<select class="selectDua subLedger" style="width:100%" name="AccCashInDet[acc_coa_id][]" id="AccCashInDet[acc_coa_id][]">';

                                foreach ($data as $key => $val) {
                                    $value = ($key == $viewCashInDet->acc_coa_id) ? 'selected="selected"' : '';
                                    echo '<option ' . $value . ' value="' . $key . '">' . $val . '</option>';
                                }
                                echo '</select>';
                                echo '</td>
                                            <td style="text-align:center"  class="subLedgerField">' . $invoiceName . '<a style="display:'.$display.'" class="btn showModal">Select Sub-Ledger</a></td>
                                            <td><input type="text" name="AccCashInDet[description][]" id="AccCashInDet[description][]" style="width:95%"  value="' . $viewCashInDet->description . '"/></td>
                                            <td><div class="input-prepend"> <span class="add-on">Rp.</span><input type="text" name="AccCashInDet[amount][]" id="AccCashInDet[amount][]" class="angka totalDet" value="' . $amount . '"/></div></td>
                                        </tr>';
                            }
                        } if (isset($_POST['AccCashInDet'])) {
                            for ($i = 0; $i < count($_POST['AccCashInDet']['acc_coa_id']); $i++) {
                                $accCoa = AccCoa::model()->find(array('condition' => 'id=' . $_POST['AccCashInDet']['acc_coa_id'][$i]));
                                
                                if (!empty($_POST['nameAccount'][$i])) {
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

                                echo '  <tr>
                                            <td style="text-align:center">
                                                <input type="hidden" name="nameAccount[]" id="nameAccount[]" class="nameAccount" value="' . $id . '"/>
                                                <input type="hidden" name="inVoiceDet[]" id="inVoiceDet[]" class="inVoiceDet" value="' . $_POST['inVoiceDet'][$i] . '"/>
                                                <span class="btn"><i class="delRow icon-remove-circle" style="cursor:all-scroll;"></i></span>
                                            </td>';

                                echo '<td><select class="selectDua subLedger" style="width:100%" name="AccCashInDet[acc_coa_id][]" id="AccCashInDet[acc_coa_id][]">';
                                foreach ($data as $key => $val) {
                                    $value = ($key == $_POST['AccCashInDet']['acc_coa_id'][$i]) ? 'selected="selected"' : '';
                                    echo '<option ' . $value . ' value="' . $key . '">' . $val . '</option>';
                                }
                                echo '<option value="0">Pilih</option>';
                                echo '</select></td>
                                            <td style="text-align:center" class="subLedgerField">' . $name . '<a style="display:none" class="btn showModal">Select Sub-Ledger</a></td>
                                            <td><input type="text" name="AccCashInDet[description][]" id="AccCashInDet[description][]" style="width:95%"  value="' . $_POST['AccCashInDet']['description'][$i] . '"/></td>
                                            <td><div class="input-prepend"> <span class="add-on">Rp.</span><input type="text" name="AccCashInDet[amount][]" id="AccCashInDet[amount][]" class="angka totalDet" value="' . $_POST['AccCashInDet']['amount'][$i] . '"/></div></td>
                                        </tr>';
                            }
                        }
                        ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="4" valign="middle" align="center"><b>Total Kredit</b></td>
                            <td><?php
                                if (isset($_POST['AccCashIn']['total'])) {
                                    $total = $_POST['AccCashIn']['total'];
                                } else {
                                    $total = $model->total;
                                }
                                echo $form->textFieldRow($model, 'total', array('class' => 'angka', 'readonly' => true, 'maxlength' => 60, 'value' => $total, 'prepend' => 'Rp.', 'label' => false));
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="4" valign="middle" align="center"><b>Selisih</b></td>
                            <td>
                                <?php
                                if (isset($_POST['AccCashInDet'])) {
                                    $diff = $_POST['totalDebit'] - $_POST['AccCashIn']['total'];
                                } else {
                                    $diff = 0;
                                }
                                ?>
                                <div class="input-prepend">
                                    <span class="add-on">Rp.</span>
                                    <?php echo CHtml::textField('difference', $diff, array('class' => 'angka', 'value' => 0, 'maxlength' => 60, 'prepend' => 'Rp', 'readonly' => true, 'id' => 'difference')); ?>
                                </div>
                            </td>
                        </tr>
                    </tfoot>
                </table>
                <div class = "form-actions">
                    <?php
                    $act = (isset($_GET['act'])) ? $_GET['act'] : '';
                    if ($model->isNewRecord || ($act != "approve" && empty($model->date_posting))) {
                        $this->widget('bootstrap.widgets.TbButton', array(
                            'buttonType' => 'submit',
                            'type' => 'primary',
                            'icon' => 'ok white',
                            'label' => $model->isNewRecord ? 'Tambah' : 'Simpan',
                        ));

                        $this->widget('bootstrap.widgets.TbButton', array(
                            'buttonType' => 'reset',
                            'icon' => 'remove',
                            'label' => 'Reset',
                        ));
                    } else {
                        $this->beginWidget(
                                'bootstrap.widgets.TbModal', array('id' => 'myModal', 'htmlOptions' => array('style' => 'width:450px;left:60%;'))
                        );
                        ?>
                        <div class="modal-header">
                            <a class="close" data-dismiss="modal">&times;</a>
                            <h4>Persetujuan</h4>
                        </div>
                        <div class="modal-body" align="left">
                            <table>
                                <tr>
                                    <th>
                                        <label for="Date_Post">Tanggal Posting</label>
                                    </th>
                                    <th>
                                <div class="input-prepend">
                                    <span class="add-on"><i class="icon-calendar"></i></span>
                                    <?php
                                    if ($siteConfig->date_system != "0000-00-00") {
                                        $dateSystem = $siteConfig->date_system;
                                    } else {
                                        $dateSystem = date("Y-m-d");
                                    }

                                    $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                                        'name' => 'date_post',
                                        'value' => (empty($model->date_posting)) ? date("Y-m-d") : $model->date_posting,
                                        'options' => array(
                                            'minDate' => $dateSystem,
                                            'showAnim' => 'fold',
                                            'changeMonth' => 'true',
                                            'changeYear' => 'true',
                                            'dateFormat' => 'yy-mm-dd'
                                        ),
                                        'htmlOptions' => array(
                                            'style' => 'height:20px;',
                                            'class' => 'span2',
                                        ),
                                    ));
                                    ?>
                                </div>
                                </th>

                                </tr>
                                <?php
                                if ($siteConfig->autopostnumber == 0) {
                                    ?>
                                    <tr>
                                        <th>
                                            <label>No Posting </label>
                                        </th>
                                        <th>
                                            <?php
                                            echo CHtml::textfield('codeAcc', (isset($model->code_acc)) ? $model->code_acc : '', array('maxlength' => 255, 'placeholder' => 'Kosongkan untuk generate otomatis'));
                                            ?>
                                        </th>
                                    </tr>
                                    <?php
                                }
                                ?>
                            </table>
                        </div>

                        <div class="modal-footer">
                            <?php
                            $this->widget('bootstrap.widgets.TbButton', array(
                                'buttonType' => 'submit',
                                'type' => 'primary',
                                'icon' => 'ok white',
                                'label' => $model->isNewRecord ? 'Approve' : 'Simpan',
                            ));
                            ?>
                            <?php
                            $this->widget(
                                    'bootstrap.widgets.TbButton', array(
                                'label' => 'Close',
                                'url' => '#',
                                'htmlOptions' => array('data-dismiss' => 'modal'),
                                    )
                            );
                            ?>
                        </div>
                        <?php
                        $this->endWidget();
                        $this->widget(
                                'bootstrap.widgets.TbButton', array(
                            'label' => 'Simpan',
                            'type' => 'primary',
                            'icon' => 'ok white',
                            'htmlOptions' => array(
                                'data-toggle' => 'modal',
                                'data-target' => '#myModal',
                            ),
                                )
                        );
                    }
                    ?>
                </div>
            </fieldset>
            <?php
            $this->beginWidget(
                    'bootstrap.widgets.TbModal', array(
                'id' => 'modalSub',
                'htmlOptions' => array(
                    'style' => 'width:700px'
                )
                    )
            );
            ?>

            <div class="modal-header">
                <a class="close" data-dismiss="modal">&times;</a>
                <h4>Select Subledger</h4>
            </div>

            <div class="modal-body isiModal">

            </div>

            <div class="modal-footer">
            </div>

            <?php $this->endWidget(); ?>
        </div>
    </div>

    <?php $this->endWidget(); ?>

</div>
<script type="text/javascript">
    function checkSelected() {
        var val1 = $("#AccCashIn_account").val();
        var val2 = $("#account").val();
        var nol = 0;

        if (val1 === val2) {
            alert('debet dan kredit tidak boleh sama!!');
            $("#account").select2('val', nol);
        } else {
            //do nothing
        }
    }
    $("body").on("click", ".ambil", function () {
        var id = $(this).attr("det_id");
        var userId = $(this).attr("user_id");
        var nilai = $(this).attr("nilai");
        var acc = $(this).attr("account");
        var code = $(this).attr("code");
        /*var subledger = $("#subLedgers").html();*/
        var dell = '<a class="btn btn-mini removeSub";"><i class=" icon-remove-circle"></i></a>';

        $(".appeared").find(".subLedgerField").html(dell + '[ ' + code + ' ]' + acc);
        $(".appeared").find(".inVoiceDet").val(id);
        $(".appeared").find(".totalDet").val(nilai);
        $("#modalSub").modal("hide");
        $(".appeared").removeClass('appeared');
    });

    function removeSub(elements) {
        $(elements).html('<a style="display:" class="btn showModal">Select Sub-Ledger</a>');
    }
    $("body").on("click", ".removeSub", function () {
        $(this).parent().parent().find(".inVoiceDet").val(0);
        var elements = $(this).parent();
        removeSub(elements);
    });
    $("body").on("change", "#accountName", function () {
        selectInvoice();
    });
    function selectInvoice() {
        var id = $("#accountName").val();
        $.ajax({
            type: 'POST',
            url: "<?php echo url('accCoa/selectInvoice') ?>",
            data: {id: id},
            success: function (data) {
                $("#detail").html(data);
            }
        });
    }

    $("body").on("keyup", "#invoice_amount", function () {
        var value = parseInt($(this).val());
        $("#credit").val(value);
    });

    $("body").on("click", ".addNewInvoice", function () {
        var code = $("#code_invoice").val();
        var user_id = $("#accountName").val();
        var type = $("#type_invoice").val();
        var term_date = $("#AccCashIn_date_trans").val();
        var description = $("#invoice_description").val();
        var amount = parseInt($("#invoice_amount").val());
        if (amount !== 0 || amount !== "" || code !== "") {
            $.ajax({
                type: 'post',
                data: {code: code, description: description, user_id: user_id, amount: amount, type: type, term_date: term_date},
                url: "<?php echo url('accCoa/newInvoice'); ?>",
                success: function (data) {
                    if (data == 1) {
                        selectInvoice();
                    }
                }
            });
        } else {
            alert("code dan/atau nilai belum di inputkan!");
        }
    });
    $(document).ready(function () {
        selectDua();
    });

    function selectDua() {
        $(".selectDua").select2();
    }

    function retAccount(id, elements) {
        $.ajax({
            url: "<?php echo url('accCoa/retAccount') ?>",
            type: "POST",
            data: {ledger: id},
            success: function (data) {
                obj = JSON.parse(data);
                $(".isiModal").html(obj.render);
                if (obj.tampil) {
                    elements.attr('style', 'display:');
                } else {
                    elements.attr('style', 'display:none');
                }
            }
        });
    }

    $("body").on("change", ".subLedger", function () {
        var id = $(this).val();
        var elements = $(this).parent().parent().find(".showModal");
        retAccount(id, elements);
    });
    $("body").on("click", ".showModal", function () {
        var id = $(this).parent().parent().find("select.subLedger").val();
        var elements = $(this);
        retAccount(id, elements);
        $(this).parent().parent().addClass("appeared");
        $("#modalSub").modal("show");
    });
    $("#modalSub").on('hidden', function () {
        $(".appeared").removeClass('appeared');
    });
    
    $("#yw5").on("click",function(){
       if($("#difference").val() == 0){
           return true;
       }else{
           alert("Total Debet dan Kredit Harus Sama!!");
           return false;
       }
    });
</script>