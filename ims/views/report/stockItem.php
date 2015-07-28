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
$this->setPageTitle('Stock Item');
$this->breadcrumbs = array(
    'Stock Item',
);

//$exam_category_id = (isset($model->Exam->exam_category_id)) ? $model->Exam->exam_category_id : 0;
//$school_year_id = (isset($model->Classroom->school_year_id)) ? $model->Classroom->school_year_id : 0;
?>

<div class="well">
    <div class="row-fluid">
        <div class="span6">
            <div class="control-group ">
                <label class="control-label">Departement</label>
                <div class="controls">
                    <?php echo CHtml::dropDownList('departement_id', (isset($_POST['departement_id']) ? $_POST['departement_id'] : ''), CHtml::listData(Departement::model()->findAll(), 'id', 'name'), array('class' => 'span3', 'empty' => t('choose', 'global'))); ?>
                </div>
            </div>

            <div class="control-group ">
                <label class="control-label">Product Category</label>
                <div class="controls">
                    <?php
                    $data = array(0 => t('choose', 'global')) + CHtml::listData(ProductCategory::model()->findAll(array('order' => 'root, lft')), 'id', 'nestedname');
                    $this->widget('bootstrap.widgets.TbSelect2', array(
                        'asDropDownList' => TRUE,
                        'data' => $data,
                        'value' => (isset($_POST['product_category_id']) ? $_POST['product_category_id'] : ''),
                        'name' => 'product_category_id',
                        'options' => array(
                            "placeholder" => t('choose', 'global'),
                            "allowClear" => true,
                            'width' => '100%',
                        ),
                        'htmlOptions' => array(
                            'id' => 'product_category_id'
                        ),
                    ));
                    ?>

                </div>
            </div>
        </div>
        <div class="span6">
            <?php
            // echo $form->datepickerRow($mProductStockItem, 'created', array('size'=>60,'maxlength'=>95, 'class'=>'span2', 'id'=>'datepicker', 'options'=>array('format' => 'mm-yyyy', 'weekStart' => 5, 'viewMode' => 1, 'minViewMode' => 1))); 
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
if (isset($_POST['product_category_id'])) {
      
        $inventory = CHtml::listData(Product::model()->findAll(array('condition' => 'type="inv"')), 'id', 'stock');
        $inventoryName = CHtml::listData(Product::model()->findAll(array('condition' => 'type="inv"')), 'id', 'name');

    $stockItem = Product::model()->findAll(array('condition' => 'product_category_id=' . $_POST['product_category_id'], 'order' => 'id'));
    $this->renderPartial('_stockItemResult', array('stockItem' => $stockItem,'inventory'=>$inventory,'inventoryName'=>$inventoryName));
}
?>

