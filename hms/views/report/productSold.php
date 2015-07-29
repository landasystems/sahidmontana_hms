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
$this->setPageTitle('Product Sold');
$this->breadcrumbs = array(
    'Product Sold',
);
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
            <?php
            echo $form->dateRangeRow(
                    $model, 'created', array(
                'hint' => 'Click inside! An even a date range field!.',
                'prepend' => '<i class="icon-calendar"></i>',
                'options' => array('callback' => 'js:function(start, end){console.log(start.toString("MMMM d, yyyy") + " - " + end.toString("MMMM d, yyyy"));}')
                    )
            );

            $departement = Chtml::listdata(ChargeAdditionalCategory::model()->findAll(array('condition' => 'level=1')), 'id', 'name');
            echo $form->dropDownListRow($model, 'charge_additional_category_id', $departement, array('id' => 'departement', 'class' => 'span4', 'empty' => 'Please Choose',));
            ?>

        </div>
        <div><?php if (isset($model->created)) { ?>
                <a onclick="hide()" class="btn btn-small view" title="Remove Form" rel="tooltip"><i class=" icon-remove-circle"></i></a>
            <?php } ?>
        </div>

    </div>
    <div class="form-actions">
        <?php
        $this->widget('bootstrap.widgets.TbButton', array(
            'buttonType' => 'submit',
            'type' => 'primary',
            'icon' => 'ok white',
            'label' => 'View Report',
        ));
        ?>
    </div>

    <?php $this->endWidget(); ?>
</div>

<?php
if (isset($model->created)) {
    $this->renderPartial('_productSold', array('model' => $model));
}
?>