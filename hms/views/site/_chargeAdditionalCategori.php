<fieldset>
    <legend>
        <p class="note">Fields with <span class="required">*</span> is Required.</p>
    </legend>
    <div class="control-group ">
        <label class="control-label">
            Parent Category
            <span class="required">*</span>
        </label>
        <div class="controls">
            <?php echo CHtml::dropDownList('additionalCategoriParent', 0, CHtml::listData(ChargeAdditionalCategory::model()->findAll(array('order' => 'root, lft')), 'id', 'nestedname'), array('class' => 'span3', 'empty' => 'root', 'id' => 'additionalCategoriParent')); ?>
        </div>
    </div>
    <div class="control-group ">
        <label class="control-label" for="additionalCategoriName">
            Name
            <span class="required">*</span>
        </label>
        <div class="controls">
            <?php echo CHtml::textField('additionalCategoriName', '', array('placeholder' => 'Ex. Banquet and Party', 'class' => 'span5', 'id' => 'additionalCategoriName')); ?>
        </div>
    </div>
    <div class="control-group ">
        <label class="control-label" for="additionalCategoriCode">
            Code
            <span class="required">*</span>
        </label>
        <div class="controls">
            <?php echo CHtml::textField('additionalCategoriCode', '', array('placeholder' => 'Ex. BP', 'class' => 'span5', 'id' => 'additionalCategoriCode')); ?>
        </div>
    </div>
    <div class="control-group ">
        <label class="control-label"></label>
        <div class="controls">
            <?php
            echo CHtml::ajaxLink(
                    $text = 'Add', $url = url('site/saveChargeAdditionalCategory'), $ajaxOptions = array(
                'type' => 'POST',
                'success' => 'function(data){
                        obj = JSON.parse(data);
                        $("#listDepartement").replaceWith(obj.list);
                        $("#additionalCategoriParent").html(obj.parent);
                        $("#additionalDepartment").html(obj.parent);
                        $("#additional_id").html(obj.additional);
                        if(obj.ket == "error"){
                            $("#alertContent").html("<strong>Error! </strong> <p class=\"note\">Fields with <span class=\"required\">*</span> is Required.</p>");
                            $("#alert").modal("show");
                        }
                        
                    }'), $htmlOptions = array('id' => 'btnChargeAdditionalCategory', 'class' => 'btn btn-primary')
            );
            ?> 
        </div>
    </div>
    <div class="row" style="margin-left: 50px;">
        <?php
        $departement = ChargeAdditionalCategory::model()->findAll();
        if (empty($departement)) {
            echo '<div id="listDepartement"></div>';
        } else {
            $list = ' <table class="table " id="listDepartement">
                            <thead>
                                <tr>
                                    <th>Code</th>
                                    <th>Department</th>
                                    <th width="50">#</th>
                                </tr>
                            </thead>
                            <tbody>';
            foreach ($departement as $val) {
                $list .= '<tr id="' . $val->id . '" class="' . $val->id . '">
                                <td>' . $val->code . '</td>
                                <td>' . $val->name . '</td>
                                <td align="center"><a href="#" class="btn" onclick="delRow(' . $val->id . ')"><i class="delRow icon-remove-circle" style="cursor:all-scroll;"></i></a></td>
                              </tr>
                            ';
            }
            $list .= '</body>
                        </table>
                        ';

            echo $list;
        }
        ?>
        <div id="listDepartement"></div>
    </div>
</fieldset>
<script>
    function delRow(id) {
        var id = id;
        $.ajax({
            type: 'POST',
            url: "<?php echo url('site/delChargeAdditionalCategory') ?>",
            data: {id: id},
            success: function (data) {
                obj = JSON.parse(data);
                $("#additionalCategoriParent").html(obj.parent);
                $("#additionalDepartment").html(obj.parent);
                $("#additional_id").html(obj.additional);
                $("#" + id).remove();
            }
        });
    }
</script>