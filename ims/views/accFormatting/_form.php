<ul class="nav nav-tabs" id="myTab">
    <li class="active"><a href="#site">Account Formatting</a></li>     
</ul>
<div class="tab-content">
    <div class="form">

        <?php
        $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
            'id' => 'User-form',
            'enableAjaxValidation' => false,
            'method' => 'post',
            'type' => 'horizontal',
            'htmlOptions' => array(
                'enctype' => 'multipart/form-data'
            )
        ));
        ?>

        <p class="note">Fields with <span class="required">*</span> are required.</p>

        <?php echo $form->errorSummary($model); ?>
        <div class="control-group">

            <div class="row well">
                <?php
                $mDepartement = Departement::model()->findAll(array());
                echo $form->dropDownListRow($model, 'departement_id', CHtml::listData($mDepartement, 'id', 'name'), array(
                    'empty' => 'Pilih',
                    'class' => 'span3'
                ));
                ?>
                <?php echo $form->textFieldRow($model, 'cash_in', array('size' => 11, 'maxlength' => 11, 'class' => 'span3')); ?>

                <?php echo $form->textFieldRow($model, 'cash_in_approval', array('size' => 11, 'maxlength' => 11, 'class' => 'span3')); ?>

                <?php echo $form->textFieldRow($model, 'bank_in_approval', array('size' => 11, 'maxlength' => 11, 'class' => 'span3')); ?>

                <?php echo $form->textFieldRow($model, 'cash_out', array('size' => 11, 'maxlength' => 11, 'class' => 'span3')); ?>

                <?php echo $form->textFieldRow($model, 'cash_out_approval', array('size' => 11, 'maxlength' => 11, 'class' => 'span3')); ?>

                <?php echo $form->textFieldRow($model, 'bank_out_approval', array('size' => 11, 'maxlength' => 11, 'class' => 'span3')); ?>

                <?php echo $form->textFieldRow($model, 'journal', array('size' => 11, 'maxlength' => 11, 'class' => 'span3')); ?>

                <?php echo $form->textFieldRow($model, 'journal_approval', array('size' => 11, 'maxlength' => 11, 'class' => 'span3')); ?>
            </div>
        </div>
        <div class="well">
            <ul>
                <li>Isikan formating code, agar sistem dapat melakukan generate kode untuk module - module yang sudah tersedia</li>
                <li><b>{ai|<em>3</em>}</b> / <b>{ai|<em>4</em>}</b>  / <b>{ai|<em>5</em>}</b> / <b>{ai|<em>6</em>}</b> : berikan format berikut untuk generate Auto Increase Numbering, contoh {ai|5} untuk 5 digit angka, {ai|3} untuk 3 digit angka</li>
                <li><b>{dd}</b>/<b>{mm}</b>/<b>{yy}</b> : berikan format berikut untuk melakukan generate tanggal, bulan, dan tahun </li>
                <li>Contoh Formating : <b>PO/{dd}/{mm}{yy}/{ai|5}</b>, Hasil Generate : <b>PO/14/0713/00001</b></li>
            </ul>
        </div>

        <div class="form-actions">
            <?php
            $this->widget('bootstrap.widgets.TbButton', array(
                'buttonType' => 'submit',
                'type' => 'primary',
                'icon' => 'ok white',
                'label' => $model->isNewRecord ? 'Tambah' : 'Simpan',
            ));
            ?>
        </div>

        <?php $this->endWidget(); ?>

    </div><!-- form -->
</div>