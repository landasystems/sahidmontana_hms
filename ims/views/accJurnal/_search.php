<?php
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id' => 'search-acc-jurnal-form',
    'action' => Yii::app()->createUrl($this->route),
    'method' => 'get',
        ));
?>

<?php echo $form->textFieldRow($model, 'code', array('class' => 'span3', 'maxlength' => 255)); ?>
<?php echo $form->textFieldRow($model, 'code_acc', array('class' => 'span3', 'maxlength' => 255)); ?>
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
    <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType' => 'button', 'icon' => 'icon-remove-sign white', 'label' => 'Reset', 'htmlOptions' => array('class' => 'btnreset'))); ?>
</div>

<?php $this->endWidget(); ?>

<script type="text/javascript">
    jQuery(function ($) {
        $(".btnreset").click(function () {
            $(":input", "#search-acc-jurnal-form").each(function () {
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
        var condition = $("#search-acc-jurnal-form").serialize();
        var date = $("#AccJurnal_date_posting").val();
        if (date != "") {
            window.open("<?php echo url('accJurnal/generateExcel') ?>?" + condition);
        } else {
            alert("Rentang Tgl Posting tidak boleh kosong!");
        }
    }
</script>

