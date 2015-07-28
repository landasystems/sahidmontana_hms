<?php
$siteConfig = SiteConfig::model()->findByPk(param('id'));
$this->setPageTitle('Lihat Acc Cash Ins | ID : ' . $model->id);
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
        array('visible' => landa()->checkAccess('AccCashIn', 'c'), 'label' => 'Tambah', 'icon' => 'icon-plus', 'url' => Yii::app()->controller->createUrl('create'), 'linkOptions' => array()),
        array('visible' => landa()->checkAccess('AccCashIn', 'r'), 'label' => 'Daftar', 'icon' => 'icon-th-list', 'url' => Yii::app()->controller->createUrl('index'), 'linkOptions' => array()),
        array('visible' => landa()->checkAccess('AccCashIn', 'u'), 'label' => 'Edit', 'icon' => 'icon-edit', 'url' => Yii::app()->controller->createUrl('update', array('id' => $model->id)), 'linkOptions' => array()),
    //array('label'=>'Print', 'icon'=>'icon-print', 'url'=>'javascript:void(0);return false', 'linkOptions'=>array('onclick'=>'printDiv();return false;')),
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
                <span class="number"><?php echo (isset($model->code_acc)) ? '#' . $model->code_acc : ''; ?></span>
                <br><span class="data gray"><?php echo (isset($model->date_posting)) ? date('d-M-Y', strtotime($model->date_posting)) : ''; ?></span>
            </h4>

            <div class="print">
                <a href="#" onclick="js:printDiv('printableArea');
                        return false;">
                    <span class="icon24 entypo-icon-printer"></span> 
                </a>
            </div>
        </div>
        <div>
        </div>
        <div class="content">
            <fieldset>
                <?php
                if (isset($_GET['berhasil'])) {
                    echo '<div class="alert alert-success" role="alert">Data Berhasil Disimpan!</div>';
                } else {
                    echo '';
                }
                ?>
                <div class="row" style="margin-left: 0px;">
                    <table width="100%">
                        <tr>
                            <td width="50%"><?php echo $form->textFieldRow($model, 'code', array('class' => 'span3', 'maxlength' => 255, 'value' => $model->code, 'disabled' => true)); ?></td>
                            <td width="50%"><label for="AccCashIn_code">Masuk dari</label>
                                <?php
                                $data = array(0 => t('choose', 'global')) + CHtml::listData(AccCoa::model()->findAll(array('condition' => 'type_sub_ledger="ks" OR type_sub_ledger="bk"', 'order' => 'root, lft')), 'id', 'nestedname');
                                $this->widget('bootstrap.widgets.TbSelect2', array(
                                    'asDropDownList' => TRUE,
                                    'data' => $data,
                                    'name' => 'AccCashIn[accCoa]',
                                    'value' => (isset($model->acc_coa_id) ? $model->acc_coa_id : ''),
                                    'options' => array(
                                        "placeholder" => t('choose', 'global'),
                                        "allowClear" => true,
                                    ),
                                    'htmlOptions' => array(
                                        'id' => 'AccCashOut_account',
                                        'style' => 'width:250px;',
                                        'disabled' => true
                                    ),
                                ));
                                ?></td>
                        </tr>
                        <tr>
                            <td><?php echo $form->textFieldRow($model, 'date_trans', array('class' => 'span2', 'maxlength' => 255, 'disabled' => true, 'prepend' => '<i class="icon-calendar"></i>')); ?></td>
                            <td> <label for="TotalDebit">Total Debit</label>
                                <div class="input-prepend">
                                    <span class="add-on">Rp.</span>
                                    <?php echo CHtml::textfield('totalDebit', $model->total, array('class' => 'span2', 'maxlength' => 255, 'disabled' => true, 'onkeyup' => 'this.value=this.value.replace(/[^\d]/,\'\')')); ?>
                                </div></td>
                        </tr>
                        <tr>
                            <td><?php echo $form->textAreaRow($model, 'description', array('class' => 'span4', 'maxlength' => 255, 'disabled' => true)); ?></td>
                            <td><?php
                                if ($siteConfig->is_approval == "manual") {
                                    $date = AccCoaDet::model()->find(array('condition' => 'reff_type="cash_in" and reff_id= ' . $model->id));
                                    if (isset($date)) {
                                        $datePost = $date->date_coa;
                                    } else {
                                        $datePost = '';
                                    }
                                }
                                ?></td>
                        </tr>
                        <tr>
                            <td><?php echo $form->textFieldRow($model, 'description_to', array('class' => 'span4', 'maxlength' => 255,'disabled' => true)); ?></td>
                            <td><?php echo $form->textFieldRow($model, 'description_giro_an', array('class' => 'span4', 'maxlength' => 255, 'disabled' => true)); ?></td>
                        </tr>
                    </table>
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
                            <th>Kredit</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        foreach ($cashInDet as $viewCashInDet) {
                            if ($viewCashInDet->AccCoa !== NULL) {
                                $accCoaName = $viewCashInDet->AccCoa->code . ' - ' . $viewCashInDet->AccCoa->name;
                            } else {
                                $accCoaName = '-';
                            }

                            if (!empty($viewCashInDet->ar_id)) {
                                $account = User::model()->findByPk($viewCashInDet->ar_id);
                                $name = $account->name;
                            } else if (!empty($viewCashInDet->ap_id)) {
                                $account = User::model()->findByPk($viewCashInDet->ap_id);
                                $name = $account->name;
                            } else if (!empty($viewCashInDet->as_id)) {
                                $account = Product::model()->findByPk($viewCashInDet->as_id);
                                $name = $account->name;
                            } else {
                                $name = "-";
                            }

                            echo '  <tr>
                                            <td>' . $no . '</td>
                                            <td>' . $accCoaName . '</td>
                                            <td>' . $name . '</td>
                                            <td>' . $viewCashInDet->description . '</td>
                                            <td>' . landa()->rp($viewCashInDet->amount) . '</td>
                                        </tr>';
                            $no++;
                        }
                        ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="4" valign="middle" align="center"><b>Total Kredit</b></td>
                            <td><?php
                                echo landa()->rp($model->total);
                                ?>
                            </td>
                        </tr>
                    </tfoot>
                </table>
                <br>
                <?php
                $siteConfig = SiteConfig::model()->findByPk(param('id'));
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
                            $adminStatus = (isset($model->AccAdmin->status)) ? $model->AccAdmin->status : '';
                            $managerStatus = (isset($model->AccManager->status)) ? $model->AccManager->status : '';
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
                <?php
                if ($siteConfig->is_approval != "no") {
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
                    <?php
                }
                ?>
            </fieldset>
        </div>
    </div>
    <?php $this->endWidget(); ?>
</div>

<!--------------------------print------------------------------------->
<div id="printableArea" style="width: 100%;display: none">
    <?php
    $detailCash = '
                                            <table class="tbPrint">
                                            <tr>
                                                <th class="print" width="50" style="text-align:center">No.</th>
                                                <th class="print" width="130" style="text-align:center">Perkiraan </th>
                                                <th class="print" style="text-align:center">Keterangan </th>
                                                <th class="print" width="200" style="text-align:center">Jumlah </th>
                                            </tr>';

    $no = 1;
    $jmlCash = count($cashInDet);
    if ($jmlCash <= 7) {
        $batas = 7;
    } else {
        $batas = $jmlCash;
    }

    for ($i = 0; $i < $batas; $i++) {
        $viewCashInDet = $cashInDet;

        if (!empty($viewCashInDet[$i])) {
            if ($viewCashInDet[$i]->AccCoa !== NULL) {
                $accCoaName = $viewCashInDet[$i]->AccCoa->code;
            } else {
                $accCoaName = '-';
            }
            $detailCash .= '
                                            <tr>                        
                                                 <td class="print" style="text-align:center">' . $no . '</td>
                                                 <td class="print" style="text-align:center">' . $accCoaName . '</td>                                            
                                                 <td class="print">' . $viewCashInDet[$i]->description . '</td>
                                                 <td class="print" style="text-align:right">' . landa()->rp($viewCashInDet[$i]->amount) . '</td>    
                                            </tr>';
        } else {
            $detailCash .= '
                                            <tr>                        
                                                <td class="print">&nbsp;</td>
                                                <td class="print">&nbsp;</td>                                            
                                                <td class="print">&nbsp;</td>
                                                <td class="print">&nbsp;</td>    
                                            </tr>';
        }
        $no++;
    }

    $detailCash .= '<tr>  
                        <td class="print" colspan="3" style="text-align:center"><i>' . AccCoa::model()->angkaTerbilang($model->total) . '</i></td>
                        <td class="print" style="text-align:right">' . landa()->rp($model->total, false) . '</td>
                    </tr>
                    </table>';

    $adminStatus = (isset($model->AccAdmin->status)) ? $model->AccAdmin->status : '';
    $managerStatus = (isset($model->AccManager->status)) ? $model->AccManager->status : '';

    $adminName = (isset($model->AccAdmin->User->name) and $adminStatus == "confirm") ? $model->AccAdmin->User->name : '';
    $adminDate = (isset($model->AccAdmin->created) and $adminStatus == "confirm") ? date('d M Y', strtotime($model->AccAdmin->created)) : '';

    $managerName = (isset($model->AccManager->User->name) and $managerStatus == "confirm") ? $model->AccManager->User->name : '';
    $managerDate = (isset($model->AccManager->created) and $managerStatus == "confirm") ? date('d M Y', strtotime($model->AccManager->created)) : '';

    $dateApprove = ".........";
    $noApprove = ".........";
    if (!empty($model->code_acc))
        $noApprove = $model->code_acc;

    if (!empty($datePost))
        $dateApprove = date('d M Y', strtotime($datePost));

    $accCoaName = (isset($model->AccCoa->name)) ? $model->AccCoa->name : '';

    $content = $siteConfig->report_cash_in;
    $content = str_replace('{account}', $accCoaName, $content);
    $content = str_replace('{cash_in}', $model->code, $content);
    $content = str_replace('{date}', date('d M Y', strtotime($model->date_trans)), $content);
    $content = str_replace('{detail_cash}', $detailCash, $content);
    $content = str_replace('{tellerName}', $model->User->name, $content);
    $content = str_replace('{tellerApprove}', date('d M Y', strtotime($model->created)), $content);
    $content = str_replace('{adminName}', $adminName, $content);
    $content = str_replace('{adminApprove}', $adminDate, $content);
    $content = str_replace('{managerName}', $managerName, $content);
    $content = str_replace('{managerApprove}', $managerDate, $content);
    $content = str_replace("{no_approval}", $noApprove, $content);
    $content = str_replace("{date_approval}", $dateApprove, $content);
    $content = str_replace("{description_to}", $model->description_to, $content);
    $content = str_replace("{description_giro_an}", $model->description_giro_an, $content);
    echo $content;
    ?>
</div>   
<script type="text/javascript">
    function printDiv(divName)
    {
        var printContents = document.getElementById(divName).innerHTML;
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
    }
</script>
