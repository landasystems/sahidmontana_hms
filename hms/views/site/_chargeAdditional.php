<legend>
    <p class="note">Fields with <span class="required">*</span> is Required.</p>
</legend>
<div class="row">
    <div class="span6">
        <div class="control-group ">
            <label class="control-label" for="additionalName">Name <span class="required">*</span></label>
            <div class="controls">
                <?php echo CHtml::textField('additionalName', '', array('placeholder' => 'Ex. FOOT MASSAGE', 'class' => 'span3', 'id' => 'additionalName')); ?>
            </div>
        </div>
        <div class="control-group ">
            <label class="control-label" for="additionalDepartment">Department <span class="required">*</span></label>
            <div class="controls">
                <?php
                echo CHtml::dropDownList('additionalDepartment', '', !empty(CHtml::listData(ChargeAdditionalCategory::model()->findAll(array('order' => 'root, lft')), 'id', 'nestedname')) ? CHtml::listData(ChargeAdditionalCategory::model()->findAll(array('order' => 'root, lft')), 'id', 'nestedname') : array(0 => 'insert department before'), array('class' => 'span3'));
                ?>
            </div>
        </div>
        <div class="control-group ">
            <label class="control-label" for="additionalAccount">Account <span class="required">*</span></label>
            <div class="controls">
                <?php
                echo CHtml::dropDownList('additionalAccount', '', array(0 => 'Please Choose') + CHtml::listData(Account::model()->findAll(), 'id', 'name'), array('class' => 'span3'));
                ?>
            </div>
        </div>
        <div class="control-group ">
            <label class="control-label" for="additionalTypeTransaction">Type Transaction <span class="required">*</span></label>
            <div class="controls">
                <?php
                $transaction = SiteConfig::model()->getStandartTransactionMalang();
                $type_transaction = array();
                foreach ($transaction as $key => $value) {
                    $type_transaction[$key] = '[ ' . $key . ' ] - ' . ucwords($value);
                }
                $data = array('0' => 'Please Choose') + $type_transaction;
                $this->widget('bootstrap.widgets.TbSelect2', array(
                    'asDropDownList' => TRUE,
                    'data' => $data,
                    'name' => 'additionalTypeTransaction',
                    'options' => array(
                        "placeholder" => 'Please Choose',
                        "allowClear" => true,
                        "width" => '270px',
                    ),
                    'htmlOptions' => array(
                        'id' => 'additionalTypeTransaction',
                    ),
                ));
                ?>
            </div>
        </div>
        <div class="control-group ">
            <label class="control-label" for="additionalCharge">Charge <span class="required">*</span></label>
            <div class="controls">
                <div class="input-prepend">
                    <span class="add-on">Rp</span>
                    <input name="additionalCharge" id="additionalCharge" type="text" class="span3 changeTotal">
                </div>
            </div>
        </div>
    </div>
    <div class="span6">
        <div class="control-group ">
            <label class="control-label" for="additionalDiscount">Discount</label>
            <div class="controls">
                <div class="input-append">
                    <input name="additionalDiscount" id="additionalDiscount" type="text" class="span3 changeTotal">
                    <span class="add-on">%</span>
                </div>
            </div>
        </div>
        <div class="control-group ">
            <label class="control-label" for="ChargeAdditional_charge">Total Charge</label>
            <div class="controls">
                <div class="input-prepend">
                    <span class="add-on">Rp</span>
                    <input name="totalCharge" id="totalCharge" disabled type="text" class="span3">
                </div>
            </div>
        </div>
        <div class="control-group ">
            <label class="control-label" for="additionalDescription">Description</label>
            <div class="controls">
                <?php echo CHtml::textArea('additionalDescription', '', array('placeholder' => '', 'class' => 'span3', 'id' => 'additionalDescription')); ?>
            </div>
        </div>
        <div class="control-group ">
            <label class="control-label"></label>
            <div class="controls">
                <?php
                echo CHtml::ajaxLink(
                        $text = 'Add', $url = url('site/saveChargeAdditional'), $ajaxOptions = array(
                    'type' => 'POST',
                    'success' => 'function(data){
                        obj = JSON.parse(data);
                        $("#listCharge").replaceWith(obj.list);
                        $("#additional_id").html(obj.additional);
                        if(obj.ket == "error"){
                            $("#alertContent").html("<strong>Error! </strong> <p class=\"note\">Fields with <span class=\"required\">*</span> is Required.</p>");
                            $("#alert").modal("show");
                        }
                    }'), $htmlOptions = array('id' => 'btnAdd', 'class' => 'btn btn-primary')
                );
                ?>   
            </div>
        </div>
    </div>
</div>
<div class="row" style="margin-left: 50px;">
    <?php
    $charge = ChargeAdditional::model()->findAll();
    if (empty($charge)) {
        echo '<div id="listCharge"></div>';
    } else {
        $list = '';
        foreach ($charge as $value) {
            $list .= '<tr id="charge' . $value->id . '" class="' . $value->id . '">
                        <td>' . $value->name . '</td>
                        <td>' . $value->ChargeAdditionalCategory->name . '</td>
                        <td>' . landa()->rp($value->charge) . '</td>
                        <td>' . $value->discount . '%</td>
                        <td>' . landa()->rp($value->charge - (($value->charge * $value->discount) / 100)) . '</td>
                        <td align="center"><a href="#" class="btn" onclick="delRowCharge(' . $value->id . ')"><i class="delRow icon-remove-circle" style="cursor:all-scroll;"></i></a></td>
                      </tr>';
        }
        echo '<table class="table table-bordered" id="listCharge">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Department</th>
                            <th>Charge</th>
                            <th>Discount</th>
                            <th>Total Charge</th>
                            <th width="50">#</th>
                        </tr>
                    </thead>
                    <tbody>
                       ' . $list . '
                    </tbody>
                </table>';
    }
    ?>
</div>
<script>
    $(".changeTotal").on("input", function () {
        var charge = $("#additionalCharge").val();
        var discount = $("#additionalDiscount").val();
        charge = charge || 0;
        discount = discount || 0;
        var total = charge - ((discount / 100) * charge);
        $("#totalCharge").val(total);
    });

    function delRowCharge(id) {
        var id = id;
        $.ajax({
            type: 'POST',
            url: "<?php echo url('site/delChargeAdditional') ?>",
            data: {id: id},
            success: function (data) {
                obj = JSON.parse(data);
                $("#additionalCategoriParent").html(obj.parent);
                $("#additionalDepartment").html(obj.parent);
                $("#additional_id").html(obj.additional);
                $("#charge" + id).remove();
            }
        });
    }
</script>