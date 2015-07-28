<?php
/* @var $this DateConfigController */
/* @var $model DateConfig */
/* @var $form CActiveForm */
?>
<?php
foreach (Yii::app()->user->getFlashes() as $key => $message) {
    echo '<div class="alert alert-' . $key . '">' . $message . '</div>';
}
?>
<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'date-config-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

<div class="img-polaroid">
    <?php echo $form->errorSummary($model); ?>
    <div class="well">
    <table>
        <tr>
            <td rowspan="7" width="15%"></td>
        </tr>
        <tr>
            <td><?php echo $form->labelEx($model,'year'); ?></td>
            <td><?php echo $form->textField($model,'year',array('class' => 'angka', 'readonly' => 'readonly')); ?></td>
            <td rowspan="7">
                <div class="well" style="width:400px;height:350px;">
                    <Strong>Keterangan :</Strong>
                    <ul>
                        <li>Atur Auto Number untuk kode approve di sini dengan mengganti angka pada masing - masing tipe.</li>
                        <li>Nomor yang dimasukkan adalah nomor sebelum angka yang akan keluar di kode ACC.</li>
                        <li>Contoh : Pada Bank Masuk di atur angka 5, maka hasil generate adalah <strong>BNM00006</strong>.</li>
                    </ul>
                </div>
            </td>
        </tr>
        <tr>
            <td><?php echo $form->labelEx($model,'cash_in'); ?></td>
            <td><?php echo $form->textField($model,'cash_in',array('class' => 'angka')); ?></td>
        </tr>
        <tr>
            <td><?php echo $form->labelEx($model,'cash_out'); ?></td>
            <td><?php echo $form->textField($model,'cash_out',array('class' => 'angka')); ?></td>
        </tr>
        <tr>
            <td><?php echo $form->labelEx($model,'bk_in'); ?></td>
            <td><?php echo $form->textField($model,'bk_in',array('class' => 'angka')); ?></td>
        </tr>
        <tr>
            <td><?php echo $form->labelEx($model,'bk_out'); ?></td>
            <td><?php echo $form->textField($model,'bk_out',array('class' => 'angka')); ?></td>
        </tr>
        <tr>
            <td><?php echo $form->labelEx($model,'jurnal'); ?></td>
            <td><?php echo $form->textField($model,'jurnal',array('class' => 'angka')); ?></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td style="text-align: right">
                <?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
                        'icon'=>'ok white',  
			'label'=>'Simpan',
		)); ?>
            </td>
        </tr>
    </table>
    </div>
</div>

<?php $this->endWidget(); ?>

</div><!-- form -->