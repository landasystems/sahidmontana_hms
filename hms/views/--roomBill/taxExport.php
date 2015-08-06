<?php
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id' => 'results',
    'enableAjaxValidation' => false,
    'method' => 'post',
    'type' => 'horizontal',
    'htmlOptions' => array(
        'enctype' => 'multipart/form-data'
    )
        ));
?>
<?php
$this->setPageTitle('Tax Export');

?>
<script>
    function hide() {
        $(".well").hide();
        $(".form-horizontal").hide();
    }

</script>
<div class="well">

    <div class="row-fluid">
        <div class="span11">

            <div class="control-group ">
                <label class="control-label" for="Reservation_date_from">Date Transaction</label>
                <div class="controls">
                    <?php
                    echo $form->datepickerRow(
                            $model, 'date_bill', array(
                        'options' => array('language' => 'en'),
                        'label' => false,
                        'hint' => 'Click here! To select date transaction.',
                        'prepend' => '<i class="icon-calendar"></i>'
                            )
                    );
                    ?>   
                </div>
            </div>


        </div>
        <div>
            <a onclick="hide()" class="btn btn-small view" title="Remove Form" rel="tooltip"><i class=" icon-remove-circle"></i></a>

        </div>

    </div>
    <div class="form-actions">
        <?php
        $this->widget('bootstrap.widgets.TbButton', array(
            'buttonType' => 'submit',
            'type' => 'primary',
            'icon' => 'eye-open white',
            'label' => 'View Transaction',
        ));
        ?>
        <a class="btn btn-primary" href="generateTax.html/?date=<?php echo $model->date_bill; ?>"><i class="icon-share icon-white"></i> Tax Export </a>        
    </div>

    <?php $this->endWidget(); ?>
</div>
<?php
if (isset($model->date_bill)) {
    $na = Na::model()->find(array('condition' => 'date_na="' . date('Y-m-d', strtotime($model->date_bill)) . '"'));
    $naDet = array();
    if (!empty($na))
        $naDet = NaDet::model()->findAll(array('condition' => 'na_id=' . $na->id));
    $this->renderPartial('_taxExport', array('naDet' => $naDet, 'date' => $model->date_bill));
}
?>
