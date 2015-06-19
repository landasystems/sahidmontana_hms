<div class="form">
    <?php
    $this->setPageTitle('Import From Excel');
    $this->breadcrumbs = array(
        'Acc Coas' => array('index'),
        'Import from Excel',
    );
    ?>
    <?php
    $this->beginWidget('zii.widgets.CPortlet', array(
        'htmlOptions' => array(
            'class' => ''
        )
    ));
    $this->widget('bootstrap.widgets.TbMenu', array(
        'type' => 'pills',
        'items' => array(
            array('label' => 'Tambah', 'icon' => 'icon-plus', 'url' => Yii::app()->controller->createUrl('create'), 'active' => true, 'linkOptions' => array()),
            array('label' => 'Daftar', 'icon' => 'icon-th-list', 'url' => Yii::app()->controller->createUrl('index'), 'linkOptions' => array()),
        ),
    ));
    $this->endWidget();
    ?>
    <legend>
        Upload File anda disini
    </legend>

    <?php
    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id' => 'Article-form',
        'enableAjaxValidation' => false,
        'method' => 'post',
        'type' => 'horizontal',
        'htmlOptions' => array(
            'enctype' => 'multipart/form-data'
        )
    ));
    ?>
    <div class="well">
        <div class="row-fluid">
            <?php
            $sukses = '<div class="alert alert-success" role="alert">[Sukses!] Data Berhasil di Import</div>';
            echo (isset($berhasil) and $berhasil==true) ? $sukses : ''; ?>
            <div class="span11">
                <table>
                    <tr>
                        <td width="80%">
                            <?php echo $form->fileFieldRow($model, 'filee', array('class' => 'span5')); ?>
                            <div class="control-group ">
                                <div class="controls">
                                    <?php
                                    $this->widget(
                                            'bootstrap.widgets.TbButton', array('buttonType' => 'submit',
                                        'label' => 'Upload',
                                        'icon' => 'icomoon-icon-upload-2'
                                            )
                                    );
                                    ?>
                                </div>
                            </div>
                        </td>
                        <td class="table-striped table-bordered table-hover red">
                            <div class="">
                                <p>File anda harus berekstensi .xls (excel file)</p><br/>
                                <p>Silahkan download file untuk input excel di <a href="<?php echo Yii::app()->controller->createUrl('DefaultExcel',array());?>">sini</a></p>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <?php $this->endWidget(); ?>
</div><!-- form -->