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
$this->setPageTitle('Buy Retur Report');
$this->breadcrumbs = array(
    'Buy Retur Report',
);

//$exam_category_id = (isset($model->Exam->exam_category_id)) ? $model->Exam->exam_category_id : 0;
//$school_year_id = (isset($model->Classroom->school_year_id)) ? $model->Classroom->school_year_id : 0;
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
            $user = User::model()->listUsers('supplier');
            ?>
            <?php echo $form->dropDownListRow($mBuy, 'departement_id', CHtml::listData(Departement::model()->findAll(), 'id', 'name'), array('class' => 'span5', 'empty' => t('choose', 'global'))); ?>      
            <?php echo $form->dropDownListRow($mBuy, 'supplier_id', CHtml::listData($user, 'id', 'name'), array('class' => 'span5', 'empty' => t('choose', 'global'))); ?>      

            <?php
            echo $form->dateRangeRow(
                    $mBuy, 'created', array(
                'hint' => 'Click inside! An even a date range field!.',
                'prepend' => '<i class="icon-calendar"></i>',
                'options' => array('callback' => 'js:function(start, end){console.log(start.toString("MMMM d, yyyy") + " - " + end.toString("MMMM d, yyyy"));}')
                    )
            );
            ?>    
        </div>
        <div><?php if (isset($mBuy->created)) { ?>
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
if (isset($mBuy->created)) {
    if (!empty($mBuy->departement_id)) {
        $departement = Departement::model()->findByPk($mBuy->departement_id);
        $departementId = ' AND departement_id="' . $departement->id . '"';
        $departementName = $departement->name;
    } else {
        $departementId = '';
        $departementName = 'All';
    }

    if (!empty($mBuy->supplier_id)) {
        $supplier = User::model()->findByPk($mBuy->supplier_id);
        $supplierId = ' AND supplier_id="' . $supplier->id . '"';
        $supplierName = $supplier->name;
    } else {
        $supplierId = '';
        $supplierName = 'All';
    }

    $a = explode('-', $mBuy->created);
    $start = date('Y/m/d', strtotime($a[0]));
    $end = date('Y/m/d', strtotime($a[1])) . " 23:59:59";
    ;

    $buyReport = BuyRetur::model()->findAll(array('condition' => '(created >="' . $start . '" AND created <="' . $end . '") ' . $departementId . $supplierId, 'order' => 'id'));
    $this->renderPartial('_buyReturResult', array('buy' => $buyReport, 'supplierName' => $supplierName, 'departement' => $departementName, 'created' => $mBuy->created));
}
?>