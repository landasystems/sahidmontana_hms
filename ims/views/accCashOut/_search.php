<?php
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id' => 'search-acc-cash-out-form',
    'action' => Yii::app()->createUrl($this->route),
    'method' => 'get',
        ));
?>

<?php echo $form->textFieldRow($model, 'code', array('class' => 'span3', 'maxlength' => 255)); ?>
<?php echo $form->textFieldRow($model, 'code_acc', array('class' => 'span3', 'maxlength' => 255)); ?><br>
Keluar Dari <br>
<?php
$accessCoa = AccCoa::model()->accessCoa();

$data = array(0 => 'Pilih') + CHtml::listData(AccCoa::model()->findAll(array('condition' => $accessCoa, 'order' => 'root, lft')), 'id', 'nestedname');
$this->widget('bootstrap.widgets.TbSelect2', array(
    'asDropDownList' => TRUE,
    'data' => $data,
    'name' => 'AccCashOut[acc_coa_id]',
//    'value' => (isset($accCoa) ? $accCoa : ''),
    'options' => array(
        "placeholder" => 'Pilih',
        "allowClear" => true,
    ),
    'htmlOptions' => array(
        'id' => 'AccCashOut_account',
        'style' => 'width:250px;'
    ), 'events' => array('change' => 'js: function(){
                                            checkSelected();

                                        }')
));
?>
<?php
echo $form->dateRangeRow(
        $model, 'date_posting', array(
    'prepend' => '<i class="icon-calendar"></i>',
        )
);
?>

<div class="form-actions">
    <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType' => 'submit', 'type' => 'primary', 'icon' => 'search white', 'label' => 'Pencarian')); ?>
    <?php
    $this->widget('bootstrap.widgets.TbButton', array('buttonType' => 'submit', 'type' => 'primary', 'icon' => 'icon', 'label' => 'Export Excel', 'htmlOptions' => array(
            'onclick' => 'submitForm()'
    )));
    ?>
</div>

<?php $this->endWidget(); ?>

<script type="text/javascript">
    jQuery(function ($) {
        $(".btnreset").click(function () {
            $(":input", "#search-acc-cash-out-form").each(function () {
                var type = this.type;
                var tag = this.tagName.toLowerCase(); // normalize case
                if (type == "text" || type == "password" || tag == "textarea")
                    this.value = "";
                else if (type == "checkbox" || type == "radio")
                    this.checked = false;
                else if (tag == "select")
                    this.selectedIndex = "";
            });
        });
    });
    function submitForm() {
        var condition = $("#search-acc-cash-out-form").serialize();
        var date = $("#AccCashOut_date_posting").val();
        if (date != "") {
            window.open("<?php echo url('accCashOut/generateExcel') ?>?" + condition);
        }else{
            alert("Rentang Tgl Posting tidak boleh kosong!");
        }
    }

</script>
