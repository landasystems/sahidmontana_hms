<?php
/**
 * The following variables are available in this template:
 * - $this: the BootCrudCode object
 */
?>
<div class="form">
    <?php echo "<?php \$form=\$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'" . $this->class2id($this->modelClass) . "-form',
	'enableAjaxValidation'=>false,
        'method'=>'post',
	'type'=>'horizontal',
	'htmlOptions'=>array(
		'enctype'=>'multipart/form-data'
	)
)); ?>\n"; ?>
    <fieldset>
        <legend>
            <p class="note">Fields dengan <span class="required">*</span> harus di isi.</p>
        </legend>

        <?php echo "<?php echo \$form->errorSummary(\$model,'Opps!!!', null,array('class'=>'alert alert-error span12')); ?>\n"; ?>


                <?php
                foreach ($this->tableSchema->columns as $column) {

                    if ($column->autoIncrement)
                        continue;
                    ?>
                    <?php echo "<?php echo " . $this->generateActiveRow($this->modelClass, $column) . "; ?>\n"; ?>

                    <?php
                }
                ?>

        <?php echo "<?php if (!isset(\$_GET['v'])) { ?>" ?>
        <div class="form-actions">
            <?php echo "<?php \$this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
                        'icon'=>'ok white',  
			'label'=>\$model->isNewRecord ? 'Tambah' : 'Simpan',
		)); ?>\n";
                echo "<?php \$this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'reset',
                        'icon'=>'remove',  
			'label'=>'Reset',
		)); ?>\n"; ?>
        </div>
        <?php echo "<?php } ?>" ?>
    </fieldset>

    <?php echo "<?php \$this->endWidget(); ?>\n"; ?>

</div>
