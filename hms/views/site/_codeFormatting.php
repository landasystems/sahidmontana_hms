<legend>
    <p class="note">Fields with <span class="required">*</span> is Required.</p>
</legend>
<?php echo $form->textFieldRow($model, 'format_reservation', array('class' => 'span5', 'maxlength' => 255)); ?>
<?php echo $form->textFieldRow($model, 'format_registration', array('class' => 'span5', 'maxlength' => 255)); ?>
<?php echo $form->textFieldRow($model, 'format_bill', array('class' => 'span5', 'maxlength' => 255)); ?>
<?php echo $form->textFieldRow($model, 'format_bill_charge', array('class' => 'span5', 'maxlength' => 255)); ?>
<?php echo $form->textFieldRow($model, 'format_deposite', array('class' => 'span5', 'maxlength' => 255)); ?>
<?php
?>

<div class="well">
    <ul>
        <li>Isikan formating code, agar sistem dapat melakukan generate kode untuk module - module yang sudah tersedia</li>
        <li><b>{ai|<em>3</em>}</b> / <b>{ai|<em>4</em>}</b>  / <b>{ai|<em>5</em>}</b> / <b>{ai|<em>6</em>}</b> : berikan format berikut untuk generate Auto Increase Numbering, contoh {ai|5} untuk 5 digit angka, {ai|3} untuk 3 digit angka</li>
        <li><b>{dd}</b>/<b>{mm}</b>/<b>{yy}</b> : berikan format berikut untuk melakukan generate tanggal, bulan, dan tahun </li>
        <li>Contoh Formating : <b>BILL/{dd}/{mm}{yy}/{ai|5}</b>, Hasil Generate : <b>BILL/14/0713/00001</b></li>
    </ul>
</div> 