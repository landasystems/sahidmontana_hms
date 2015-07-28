<?php
$this->setPageTitle('Approve Acc Jurnal | ID : ' . $model->id);
$this->breadcrumbs = array(
    'Acc Cash Ins' => array('index'),
    $model->id,
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
        array('visible' => landa()->checkAccess('AccJurnal', 'c'), 'label' => 'Tambah', 'icon' => 'icon-plus', 'url' => Yii::app()->controller->createUrl('create'), 'linkOptions' => array()),
        array('visible' => landa()->checkAccess('AccJurnal', 'r'), 'label' => 'Daftar', 'icon' => 'icon-th-list', 'url' => Yii::app()->controller->createUrl('index'), 'linkOptions' => array()),
)));
$this->endWidget();
?>
<div class="form">
    <?php
    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id' => 'acc-cash-in-form',
        'enableAjaxValidation' => false,
        'method' => 'post',
        'type' => 'vertical',
        'htmlOptions' => array(
            'enctype' => 'multipart/form-data'
        )
    ));
    ?>
    <div class="box gradient invoice">

        <div class="title clearfix">

            <h4 class="left">
                <span></span>
            </h4>
            <div class="invoice-info">
                <span class="number"><strong class="red">                        
                        <?php echo $model->code; ?>
                    </strong></span>
                <span class="data gray"><?php echo date('d M Y') ?></span>
                <div class="clearfix"></div>
            </div>

        </div>
        <div class="content">
            <fieldset>
                <?php echo $form->errorSummary($model, 'Opps!!!', null, array('class' => 'alert alert-error span12', 'disabled' => true)); ?>
                <br>
                <div class="row" style="margin-left: 0px;">
                    <?php echo $form->textFieldRow($model, 'code', array('class' => 'span3', 'maxlength' => 255, 'disabled' => true)); ?>

                    <?php echo $form->datepickerRow($model, 'date_trans', array('class' => 'span2', 'maxlength' => 255, 'prepend' => '<i class="icon-calendar"></i>', 'disabled' => true, 'options' => array('todayBtn' => true, 'todayHighlight' => true, 'startDate' => date('j/m/Y'), 'format' => 'yyyy/m/d'))); ?>

                    <?php
                    $siteConfig = SiteConfig::model()->listSiteConfig();
                    ?>
                    <?php echo $form->textAreaRow($model, 'description', array('class' => 'span4', 'maxlength' => 255, 'disabled' => true)); ?>
                </div>

                <br>
                <h4>Detail Dana</h4>
                <table class="responsive table table-bordered">
                    <thead>
                        <tr>
                            <th width="20">#</th>
                            <th>Kode Akun</th>
                            <th>Sub Ledger</th>
                            <th>Keterangan</th>
                            <th>Debit</th>
                            <th>Kredit</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        foreach ($jurnalDetail as $val) {
                            if (isset($val->AccCoa->name)) {
                                $accCoaName = $val->AccCoa->code . ' - ' . $val->AccCoa->name;
                            } else {
                                $accCoaName = '-';
                            }

                            if (!empty($val->ar_id)) {
                                $account = User::model()->findByPk($val->ar_id);
                                $name = $account->name;
                            } else if (!empty($val->ap_id)) {
                                $account = User::model()->findByPk($val->ap_id);
                                $name = $account->name;
                            } else if (!empty($val->as_id)) {
                                $account = Product::model()->findByPk($val->as_id);
                                $name = $account->name;
                            } else {
                                $name = "-";
                            }

                            echo '
                                    <tr>
                                        <td>' . $no . '</td>
                                        <td>' . $accCoaName . '</td>
                                        <td>' . $name . '</td>
                                        <td>' . $val->description . '</td>
                                        <td>' . landa()->rp($val->debet) . '</td>
                                        <td>' . landa()->rp($val->credit) . '</td>
                                    </tr>';
                            $no++;
                        }
                        ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="4"><b>Total</b></td>
                            <td><?php echo $form->textFieldRow($model, 'total_debet', array('class' => 'span2', 'id' => 'total_debet', 'label' => false, 'readonly' => true, 'prepend' => 'Rp.', 'disabled' => true)); ?></td>
                            <td><?php echo $form->textFieldRow($model, 'total_credit', array('class' => 'span2', 'id' => 'total_credit', 'label' => false, 'readonly' => true, 'prepend' => 'Rp.', 'disabled' => true)); ?></td>
                        </tr>
                    </tfoot>
                </table>
                <br>
                <?php
                $adminStatus = (isset($model->AccAdmin->status)) ? $model->AccAdmin->status : '';
                $managerStatus = (isset($model->AccManager->status)) ? $model->AccManager->status : '';

                if ($siteConfig->is_approval == "yes") {
                    ?>
                    <table class="responsive table table-bordered">
                        <thead>
                            <tr>
                                <th width="30%"><center>Dibuat Oleh</center></th>
                        <th><center>Diperiksa Oleh</center></th>
                        <th width="30%"><center>Disetujui Oleh</center></th>
                        </tr>
                        </thead>
                        <tfoot>
                            <tr height="100">
                                <td> <center><br><br>
                            <b><?php echo $model->User->name ?></b> 
                            <br>
                            <?php echo date('d M Y', strtotime($model->created)); ?><br>
                            <?php echo date('H:i:s', strtotime($model->created)); ?><br><br></center>
                        </td>
                        <td>
                            <?php
                            if ($adminStatus != 'confirm' and landa()->checkAccess('AccApprovalAdmin', 'r')) {
                                ?>
                                <label for="adminDescription">Keterangan</label>
                                <textarea style="width:90%;margin:10px;"  name="adminDescription" id="adminDescription"></textarea>
                                <div align="center"><?php echo CHtml::radioButtonList('adminStatus', '', array('pending' => 'Pending', 'reject' => 'Reject', 'confirm' => 'Confirm'), array('separator' => '')); ?></div>
                                <?php
                            } else {
                                ?>
                            <center><br><br>
                                <b><?php echo (isset($model->AccAdmin->User->name) ? $model->AccAdmin->User->name : '') ?></b> 
                                <br>
                                <?php echo (isset($model->AccAdmin->created) ? date('d M Y', strtotime($model->AccAdmin->created)) : ''); ?><br>
                                <?php echo (isset($model->AccAdmin->created) ? date('H:i:s', strtotime($model->AccAdmin->created)) : ''); ?><br><br></center>
                            <?php
                        }
                        ?>
                        </td>
                        <td>
                            <?php
                            if ($adminStatus == 'confirm' and $managerStatus != 'confirm' and landa()->checkAccess('AccApproval', 'r')) {
                                ?>
                                <label for="managerDescription">Keterangan</label>
                                <textarea style="width:90%;margin:10px;"  name="managerDescription" id="managerDescription"></textarea>
                                <div align="center"><?php echo CHtml::radioButtonList('managerStatus', $managerStatus, array('pending' => 'Pending', 'reject' => 'Reject', 'confirm' => 'Confirm'), array('separator' => '')); ?></div>
                                <?php
                            } else if ($managerStatus == "confirm") {
                                ?>
                            <center><br><br>
                                <b><?php echo (isset($model->AccManager->User->name) ? $model->AccManager->User->name : '') ?></b> 
                                <br>
                                <?php echo (isset($model->AccManager->created) ? date('d M Y', strtotime($model->AccManager->created)) : ''); ?><br>
                                <?php echo (isset($model->AccManager->created) ? date('H:i:s', strtotime($model->AccManager->created)) : ''); ?><br><br></center>
                            <?php
                        }
                        ?>
                        </td>
                        </tr>
                        </tfoot>
                    </table>
                    <?php
                }
                ?>
                <br>
                <h4>History</h4>
                <table class="responsive table table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                            <th>Keterangan</th>
                            <th>User</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        foreach ($approveDetail as $val) {
                            if ($val->status == "open") {
                                $stt = '<span class="label">Open</span>';
                            } elseif ($val->status == "pending") {
                                $stt = '<span class="label label-info">Pending</span>';
                            } elseif ($val->status == "reject") {
                                $stt = '<span class="label label-important">Reject</span>';
                            } elseif ($val->status == "confirm") {
                                $stt = '<span class="label label-success">Confirm</span>';
                            }
                            echo '  <tr>
                                        <td>' . $no . '</td>
                                        <td>' . date('d M Y H:i:s', strtotime($val->created)) . '</td>
                                        <td>' . $stt . '</td>
                                        <td>' . $val->description . '</td>
                                        <td>' . $val->User->name . '</td>
                                    </tr>';
                            $no++;
                        }
                        ?>
                    </tbody>
                </table>
            </fieldset>
        </div>
        <?php
        if ($managerStatus != 'confirm') {
            if (( $managerStatus != "confirm" and landa()->checkAccess('AccApproval', 'r')) or ( $adminStatus != "confirm" and landa()->checkAccess('AccApprovalAdmin', 'r')) or ( $siteConfig->is_approval == "manual" and (!landa()->checkAccess('AccApprovalAdmin', 'r') or !landa()->checkAccess('AccApproval', 'r')))) {
                ?>
                <div class="form-actions">
                    <?php
                    if ($siteConfig->is_approval == "manual") {
                        ?>
                        <?php
                        $this->beginWidget(
                                'bootstrap.widgets.TbModal', array('id' => 'myModal', 'htmlOptions' => array('style' => 'width:300px;left:60%;'))
                        );
                        ?>

                        <div class="modal-header">
                            <a class="close" data-dismiss="modal">&times;</a>
                            <h4>Approve Cash Jurnal</h4>
                        </div>

                        <div class="modal-body" align="center">
                            <?php
                            if ($siteConfig->is_approval == "manual") {
                                ?>
                                <label for="Date_Post">Tanggal Posting</label>
                                <div class="input-prepend">
                                    <span class="add-on"><i class="icon-calendar"></i></span>
                                    <?php
                                    if ($siteConfig->date_system != "0000-00-00") {
                                        $dateSystem = $siteConfig->date_system;
                                    } else {
                                        $dateSystem = date("Y-m-d");
                                    }

                                    $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                                        'name' => 'date_post',
                                        'value' => date("Y-m-d"),
                                        'options' => array(
                                            'minDate' => $dateSystem,
                                            'showAnim' => 'fold',
                                            'changeMonth' => 'true',
                                            'changeYear' => 'true',
                                            'dateFormat' => 'yy-mm-dd'
                                        ),
                                        'htmlOptions' => array(
                                            'style' => 'height:20px;',
                                            'id' => 'acccoa',
                                            'class' => 'span2',
                                        ),
                                    ));
                                    ?>
                                </div>
                                <?php
                            }
                            ?>
                        </div>

                        <div class="modal-footer">
                            <?php
                            $this->widget('bootstrap.widgets.TbButton', array(
                                'buttonType' => 'submit',
                                'type' => 'primary',
                                'icon' => 'ok white',
                                'label' => $model->isNewRecord ? 'Approve' : 'Approve',
                            ));
                            ?>
                            <?php
                            $this->widget(
                                    'bootstrap.widgets.TbButton', array(
                                'label' => 'Close',
                                'url' => '#',
                                'htmlOptions' => array('data-dismiss' => 'modal'),
                                    )
                            );
                            ?>
                        </div>

                        <?php $this->endWidget(); ?>
                        <?php
                        $this->widget(
                                'bootstrap.widgets.TbButton', array(
                            'label' => 'Simpan',
                            'type' => 'primary',
                            'icon' => 'ok white',
                            'htmlOptions' => array(
                                'data-toggle' => 'modal',
                                'data-target' => '#myModal',
                            ),
                                )
                        );
                        ?>
                        <?php
                    } else {
                        $this->widget('bootstrap.widgets.TbButton', array(
                            'buttonType' => 'submit',
                            'type' => 'primary',
                            'icon' => 'ok white',
                            'label' => $model->isNewRecord ? 'Approve' : 'Approve',
                        ));
                    }
                    ?>
                </div>
                <?php
            }
        }
        ?>
    </div>
    <?php $this->endWidget(); ?>
</div>
