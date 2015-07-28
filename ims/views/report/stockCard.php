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
$this->setPageTitle('Stock Card');
$this->breadcrumbs = array(
    'Stock Card',
);

//$exam_category_id = (isset($model->Exam->exam_category_id)) ? $model->Exam->exam_category_id : 0;
//$school_year_id = (isset($model->Classroom->school_year_id)) ? $model->Classroom->school_year_id : 0;
?>

<div class="well">
    <div class="row-fluid">
        <div class="span6">
            <?php echo $form->dropDownListRow($mProductStockCard, 'departement_id', CHtml::listData(Departement::model()->findAll(), 'id', 'name'), array('class' => 'span6', 'empty' => t('choose', 'global'))); ?>

            <div class="control-group ">
                <label class="control-label">Product</label>
                <div class="controls">
                    <?php
                    $data = array(0 => t('choose', 'global')) + CHtml::listData(Product::model()->findAll(), 'id', 'codename');
                    $this->widget('bootstrap.widgets.TbSelect2', array(
                        'asDropDownList' => TRUE,
                        'data' => $data,
                        'value'=> (isset($_POST['product_id']) ? $_POST['product_id'] : ''),
                        'name' => 'product_id',
                        'options' => array(
                            "placeholder" => t('choose', 'global'),
                            "allowClear" => true,
                            'width' => '100%',
                        ),
                        'htmlOptions' => array(
                            'id' => 'product_id'
                        ),
                    ));
                    ?>
                    
                </div>
            </div>
        </div>
        <div class="span6">
            <?php
                echo $form->datepickerRow($mProductStockCard, 'created', array('size'=>60,'maxlength'=>95, 'class'=>'span3', 'id'=>'datepicker', 'options'=>array('format' => 'm-yyyy', 'weekStart' => 5, 'viewMode' => 1, 'minViewMode' => 1))); 
            ?>

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
if (isset($mProductStockCard->departement_id) && isset($_POST['product_id']) && isset($mProductStockCard->created)) {
    $created = explode('-', $mProductStockCard->created);
    $monthly = landa()->monthly();
    $month = $monthly[$created[0]];
    $stockCard = ProductStockCard::model()->findAll(array('condition' => 'departement_id=' . $mProductStockCard->departement_id . ' AND product_id=' . $_POST['product_id'] . ' AND month(created)='.$created[0].' AND year(created)='.$created[1], 'order'=>'id'));
    $this->renderPartial('_stockCardResult', array('stockCard' => $stockCard, 'mProductStockCard'=>$mProductStockCard, 'month'=>$month));
}
?>

