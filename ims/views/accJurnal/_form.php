<style>
    input, .select2-container, .input-prepend{
        margin:0 !important;
    }
</style>
<?php
foreach (Yii::app()->user->getFlashes() as $key => $message) {
    echo '<div class="alert alert-' . $key . '">' . $message . '</div>';
}
?>
<div class="form">
    <?php
    $siteConfig = SiteConfig::model()->findByPk(param('id'));
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
    <div class="box invoice">

        <div class="title clearfix">
            <h4 class="left">
                <span class="number"><?php echo (isset($model->code_acc)) ? '#' . $model->code_acc : ''; ?></span>
                <br><span class="data gray"><?php echo (isset($model->date_posting)) ? date('d-M-Y', strtotime($model->date_posting)) : ''; ?></span>
            </h4>
        </div>
        <div class="content">
            <fieldset>
                <?php echo $form->errorSummary($model, 'Opps!!!', null, array('class' => 'alert alert-error span12')); ?>
                <br>
                <div class="row" style="margin-left: 0px;">
                    <?php echo $form->textFieldRow($model, 'code', array('class' => 'span3', 'maxlength' => 255, 'readonly' => 'true')); ?>
                    <?php echo $form->textFieldRow($model, 'date_trans', array('class' => 'span2', 'readonly' => true, 'maxlength' => 255)); ?>
                    <?php
                    if (isset($_POST['valdebet'])) {
                        $desc = $_POST['AccJurnal']['description'];
                    } else {
                        $desc = $model->description;
                    }

                    echo $form->textAreaRow($model, 'description', array('class' => 'span4', 'value' => $desc, 'maxlength' => 255));
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
    <div class="box invoice">
        <div class="title clearfix">
            <h4 class="left">
                Detail Dana
            </h4>
        </div>
        <div class="content" style="padding: 0 !important">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th width="20">#</th>
                        <th width="250">Kode Rekening</th>
                        <th width="150">Sub Ledger</th>
                        <th width="300">Keterangan</th>
                        <th style="width:5%">Debit</th>
                        <th style="width:5%">Kredit</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class='insertNew'>
                        <td style="text-align: center">
                            <input type="hidden" name="subledgerid" value="" class="inVoiceDet">
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
                                                  $(this).parent().parent().parent().remove();
                                                  calculateMin();
                                                });
                                                
                                                $("#debit").attr("readonly", false);
                                                $("#credit").attr("readonly", false);
                                                removeSub();
                                                $("#account").select2("focus");
                                                $(".newRow").find(".selectDua").select2();
                                                $(".newRow").removeClass("newRow");
                                                $(".insertNew").find(".inVoiceDet").val("0");
                                                $(".insertNew").find(".subLedgerField").html("<a style=\"display:none\" class=\"btn showModal\">Select Sub-Ledger</a>");
                                        }'), $htmlOptions = array('id' => 'btnAdd', 'class' => 'btn')
                            );
                            ?>
                        </td>
                        <td style="vertical-align: center">
                            <?php
                            $data = array(0 => 'Pilih') + CHtml::listData(AccCoa::model()->findAll(array('order' => 'root, lft')), 'id', 'nestedname');
                            $this->widget('bootstrap.widgets.TbSelect2', array(
                                'asDropDownList' => TRUE,
                                'data' => $data,
                                'name' => 'account',
                                'options' => array(
                                    "placeholder" => 'Pilih',
                                    "allowClear" => true,
                                ),
                                'htmlOptions' => array(
                                    'id' => 'account',
                                    'style' => 'width:100%;',
                                    'class' => 'subLedger'
                                ), 'events' => array('change' => 'js: function() {
                                                     var elements = $(this).parent().parent().find(".showModal");
                                                    retAccount($(this).val(),elements);
                                     }')
                            ));
                            ?>
                        </td>
                        <td class="subLedgerField" style="text-align: center">
                            <?php
                            $this->widget(
                                    'bootstrap.widgets.TbButton', array(
                                'label' => 'Select Sub-Ledger',
                                'htmlOptions' => array(
                                    'style' => 'display:none',
                                    'class' => 'showModal',
                                ),
                                    )
                            );
                            ?>
                        </td>
                        <td>
                            <?php echo CHtml::textfield('costDescription', '', array('style' => 'width:95%;', 'maxlength' => 255, 'class' => 'span4')); ?>
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
                    if ($model->isNewRecord == false and ! isset($_POST['AccJurnal'])) {
                        $no = 0;
                        foreach ($detailJurnal as $val) {
                            $invoice = (!empty($val->invoice_det_id)) ? $val->invoice_det_id : 0;
                            $code = (!empty($val->InvoiceDet->code)) ? $val->InvoiceDet->code : "-";

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
                            $invoiceName = (!empty($val->InvoiceDet->code) && !empty($val->InvoiceDet->User->name)) ? '<a class="btn btn-mini removeSub"><i class=" icon-remove-circle"></i></a>[' . $val->InvoiceDet->code . ']' . $val->InvoiceDet->User->name : '';
                            $disp = ($val->AccCoa->type_sub_ledger == 'ar' || $val->AccCoa->type_sub_ledger == 'as' || $val->AccCoa->type_sub_ledger == 'ap') ? true : false;
                            $display = (empty($invoiceName) && $disp) ? '' : 'none';
                            $no++;
                            $sttCredit = "";
                            $sttDebet = "";

                            if ($debet == 0) {
                                $sttDebet = "readonly='readonly'";
                            } else {
                                $sttCredit = "readonly='readonly'";
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
                                            <input type="hidden" name="nameAccount[]" id="nameAccount[]" value="' . $id . '"/>
                                            <input type="hidden" name="inVoiceDet[]" id="inVoiceDet[]" class="inVoiceDet" value="' . $invoice . '"/>
                                            <span class="btn"><i class="delRow icon-remove-circle" style="cursor:all-scroll;"></i></span> 
                                        <td>';
                            echo '<select class="selectDua subLedger" style="width:100%" name="acc_coa_id[]" id="acc_coa_id[]">';

                            foreach ($data as $key => $val2) {
                                $value = ($key == $val->acc_coa_id) ? 'selected="selected"' : '';
                                echo '<option ' . $value . ' value="' . $key . '">' . $val2 . '</option>';
                            }
                            echo '</select>';
                            echo '</td>
                                        <td style="text-align:center"  class="subLedgerField">' . $invoiceName . '<a style="display:' . $display . '" class="btn showModal">Select Sub-Ledger</a></td>
                                        <td><input type="text" style="width:95%"  name="description[]" id="description[]" value="' . $val->description . '"/></td>
                                        <td><div class="input-prepend"> <span class="add-on">Rp.</span><input type="text" style="width:95%"  onkeyup="calculateMin()" class="angka totalDeb" name="valdebet[]" id="valdebet[]" class="totalDeb" value="' . $debet . '" ' . $sttDebet . '/></div></td>
                                        <td><div class="input-prepend"> <span class="add-on">Rp.</span><input type="text" style="width:95px;" name="valcredit[]" id="valcredit[]" class="angka totalCre" value="' . $credit . '" ' . $sttCredit . '/></div></td>
                                     </tr>';
                        }
                    } if (isset($_POST['AccJurnal'])) {
                        for ($i = 0; $i < count($_POST['acc_coa_id']); $i++) {

                            $accCoa = AccCoa::model()->find(array('condition' => 'id=' . $_POST['acc_coa_id'][$i]));

                            if ($_POST['nameAccount'][$i] != 0) {
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
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <input type="hidden" name="nameAccount[]" id="nameAccount[]" value="' . $id . '"/>
                                            <input type="hidden" name="inVoiceDet[]" id="inVoiceDet[]" class="inVoiceDet" value="' . $_POST['inVoiceDet'][$i] . '"/>
                                            <span class="btn"><i class="delRow icon-remove-circle" style="cursor:all-scroll;"></i></span> 
                                        </td>';

                            echo '<td><select class="selectDua subLedger" style="width:100%" name="acc_coa_id[]" id="acc_coa_id[]">';
                            foreach ($data as $key => $val2) {
                                $value = ($key == $_POST['acc_coa_id'][$i]) ? 'selected="selected"' : '';
                                echo '<option ' . $value . ' value="' . $key . '">' . $val2 . '</option>';
                            }
                            echo '<option value="0">Pilih</option>';
                            echo '</select></td>
                                        <td>' . $name . '</td>
                                        <td><input type="text" name="description[]" id="description[]" value="' . $_POST['description'][$i] . '"/></td>
                                        <td><div class="input-prepend"> <span class="add-on">Rp.</span><input type="text" style="width:75px;"  onkeyup="calculateMin()" class="angka totalDeb" name="valdebet[]" id="valdebet[]" class="totalDeb" value="' . $_POST['valdebet'][$i] . '"/></div></td>
                                        <td><div class="input-prepend"> <span class="add-on">Rp.</span><input type="text" style="width:75px;" name="valcredit[]" id="valcredit[]" class="angka totalCre" value="' . $_POST['valcredit'][$i] . '"/></div></td>
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
        </div>
    </div>
    <div class = "form-actions">
        <?php
        $act = (isset($_GET['act'])) ? $_GET['act'] : '';
        if ($model->isNewRecord || ($act != "approve" && empty($model->date_posting))) {
            $this->widget('bootstrap.widgets.TbButton', array(
                'buttonType' => 'submit',
                'type' => 'primary',
                'icon' => 'ok white',
                'label' => 'Simpan Perubahan',
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
//                    if ($siteConfig->autopostnumber == 0) {
                        ?>
                        <tr>
                            <th>
                                <label>No Posting</label>
                            </th>
                            <th>
                                <?php
                                echo CHtml::textfield('codeAcc', (isset($model->code_acc)) ? $model->code_acc : '', array('maxlength' => 255, 'placeholder' => 'Kosongkan untuk generate otomatis'));
                                ?>
                            </th>
                        </tr>
                        <?php
//                    }
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

    <?php $this->endWidget(); ?>

</div>
<script type="text/javascript">
    $("body").on("click", ".delRow", function () {
    $(this).parent().parent().parent().remove();
        calculateMin();
    });
        $("body").on("click", ".ambil", function () {
    var id = $(this).attr("det_id");
        var userId = $(this).attr("user_id");
        var acc = $(this).attr("account");
        var code = $(this).attr("code");
        /*var subledger = $("#subLedgers").html();*/
        var dell = '<a class="btn btn-mini removeSub";"><i class=" icon-remove-circle"></i></a>';

        $(".appeared").find(".subLedgerField").html(dell + '[ ' + code + ' ]' + acc);
        $(".appeared").find(".inVoiceDet").val(id);
        $("#modalSub").modal("hide");
        $(".appeared").removeClass('appeared');
        calculate();
        calculateMin();
    });

        function removeSub(elements) {
        $(elements).html('<a style="display:" class="btn showModal">Select Sub-Ledger</a>');
    }
    $("body").on("click", ".removeSub", function () {
        $(this).parent().parent().find(".inVoiceDet").val(0);         var elements = $(this).parent();
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
                            }         });
    }
                    $("body").on("click", ".addNewInvoice", function () {
                var code = $("#code_invoice").val();
                    var user_id = $("#accountName").val();
                    var type = $("#type_invoice").val();
                    var term_date = $("#AccJurnal_date_trans").val();
                    var description = $("#invoice_description").val();
                    var amount = parseInt($("#invoice_amount").val());
                    if (amount != 0 || amount != "" || code != "") {
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

                            $("#yw5").on("click", function () {
                            if ($("#total_debet").val() == $("#total_credit").val()) {
            return true;
        } else {
                            alert("Total Debet dan Kredit Harus Sama!!");
                                return false;
        }
    });
</script>
