<?php
$this->setPageTitle('Saldo Awal Kartu');
foreach (Yii::app()->user->getFlashes() as $key => $message) {
    echo '<div class="alert alert-' . $key . '">' . $message . '</div>';
}
?>

<div class="form">

    <div class="col-xs-12 col-md-6">

        <legend>
            <p class="note"><span class="required">*</span> Mengubah saldo awal akan berpengaruh terhadap transaksi yang telah diinput</p>
        </legend>

        <div id="yw22" class="tabs-left">
            <ul id="yw23" class="nav nav-tabs">
                <?php
                $tab = array(
                    '1' => array('id' => 1, 'nama' => 'Kartu Piutang'),
                    '2' => array('id' => 2, 'nama' => 'Kartu Hutang'),
                    '3' => array('id' => 3, 'nama' => 'Kartu Stock'),
                );
                foreach ($tab as $d) {
                    ?>
                    <li class="">
                        <a data-toggle="tab" href="#tab<?php echo $d['id']; ?>"><?php echo $d['nama']; ?></a>
                    </li>
                    <?php
                }
                ?>
            </ul>
            <?php
            $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
                'id' => 'acc-beginning-balance-form',
                'enableAjaxValidation' => false,
                'method' => 'post',
                'type' => 'vertical',
                'htmlOptions' => array(
                    'enctype' => 'multipart/form-data',
                    'name' => 'beginning-balance',
                )
            ));
            ?>
            <div class="tab-content">
                <?php
                $valDebet = 0;
                $valCredit = 0;
                ?>
                <div id="tab1" class="tab-pane active">
                    <h3><?php echo 'Kartu Piutang'; ?></h3>
                    <table class="responsive table table-bordered">
                        <thead>
                            <tr>
                                <th> Nama Customer</th>
                                <th class="span2"> Debet</th>
                                <th class="span2"> Credit</th>
                            </tr>
                            <tr>
                                <?php
                                foreach ($Customer as $cust) {
                                    $subDetail = AccCoaSub::model()->find(array(
                                        'condition' => 'ar_id ="' . $cust->id . '" AND reff_type="balance"',
                                        'order' => 'date_coa DESC'
                                    ));
                                    if (isset($subDetail)) {
                                        $debit = $subDetail->debet;
                                        $credit = $subDetail->credit;
                                    } else {
                                        $debit = 0;
                                        $credit = 0;
                                    }
                                    ?>
                                <tr>
                                    <td><?php echo $cust->name; ?></td>
                                    <td width="125">
                                        <div class="input-prepend">
                                            <span class="add-on">Rp.</span>
                                            <input type="hidden" name="idc[]" value="<?php echo $cust->id; ?>">
                                            <?php echo CHtml::textField('debetc[]', $debit, array('maxlength' => 60, 'class' => 'angka debet', 'style' => 'width:75px;')); ?>
                                        </div>

                                    </td>
                                    <td width="125">
                                        <div class="input-prepend">
                                            <span class="add-on">Rp.</span>
                                            <?php echo CHtml::textField('creditc[]', $credit, array('maxlength' => 60, 'class' => 'angka credit', 'style' => 'width:75px;')); ?>
                                        </div>

                                    </td>
                                </tr>
                                <?php
                            }
                            ?>
                            </tr>
                        </thead>

                    </table>

                </div>
                <div id="tab2" class="tab-pane">
                    <h3><?php echo 'Kartu Hutang'; ?></h3>
                    <table class="responsive table table-bordered">
                        <thead>
                            <tr>
                                <th> Nama Supplier</th>
                                <th class="span2"> Debet</th>
                                <th class="span2"> Credit</th>
                            </tr>
                            <tr>
                                <?php
                                foreach ($Supplier as $cust) {
                                    $subDetail = AccCoaSub::model()->find(array(
                                        'condition' => 'ap_id ="' . $cust->id . '" AND reff_type="balance"',
                                        'order' => 'date_coa DESC'
                                    ));
                                    if (isset($subDetail)) {
                                        $debit = $subDetail->debet;
                                        $credit = $subDetail->credit;
                                    } else {
                                        $debit = 0;
                                        $credit = 0;
                                    }
                                    ?>
                                <tr>
                                    <td><?php echo $cust->name; ?></td>
                                    <td width="125">
                                        <div class="input-prepend">
                                            <span class="add-on">Rp.</span>
                                            <input type="hidden" name="idp[]" value="<?php echo $cust->id; ?>">
                                            <?php echo CHtml::textField('debetp[]', $debit, array('maxlength' => 60, 'class' => 'angka debet', 'style' => 'width:75px;')); ?>
                                        </div>

                                    </td>
                                    <td width="125">
                                        <div class="input-prepend">
                                            <span class="add-on">Rp.</span>
                                            <?php echo CHtml::textField('creditp[]', $credit, array('maxlength' => 60, 'class' => 'angka credit', 'style' => 'width:75px;')); ?>
                                        </div>

                                    </td>
                                </tr>
                                <?php
                            }
                            ?>
                            </tr>
                        </thead>
                    </table>

                </div>
                <div id="tab3" class="tab-pane">
                    <h3><?php echo 'Kartu Stock'; ?></h3>

                    <table class="responsive table table-bordered">
                        <thead>
                            <tr>
                                <th> Nama Barang</th>
                                <th class="span2"> Debet</th>
                                <th class="span2"> Credit</th>
                            </tr>
                            <tr>
                                <?php
                                foreach ($Product as $cust) {
                                    $subDetail = AccCoaSub::model()->find(array(
                                        'condition' => 'as_id ="' . $cust->id . '" AND reff_type="balance"',
                                        'order' => 'date_coa DESC'
                                    ));
                                    if (isset($subDetail)) {
                                        $debit = $subDetail->debet;
                                        $credit = $subDetail->credit;
                                    } else {
                                        $debit = 0;
                                        $credit = 0;
                                    }
                                    ?>
                                <tr>
                                    <td><?php echo $cust->name; ?></td>
                                    <td width="125">
                                        <div class="input-prepend">
                                            <span class="add-on">Rp.</span>
                                            <input type="hidden" name="ids[]" value="<?php echo $cust->id; ?>">
                                            <?php echo CHtml::textField('debets[]', $debit, array('maxlength' => 60, 'class' => 'angka debet', 'style' => 'width:75px;')); ?>
                                        </div>

                                    </td>
                                    <td width="125">
                                        <div class="input-prepend">
                                            <span class="add-on">Rp.</span>
                                            <?php echo CHtml::textField('credits[]', $credit, array('maxlength' => 60, 'class' => 'angka credit', 'style' => 'width:75px;')); ?>
                                        </div>

                                    </td>
                                </tr>
                                <?php
                            }
                            ?>
                            </tr>
                        </thead>
                    </table>
                </div>

                <table class="responsive table table-bordered">
                    <tfoot>
                        <tr>
                            <td> Total</td>    
                            <td class="span2"><div class="input-prepend">
                                    <span class="add-on">Rp.</span>
                                    <?php echo CHtml::textField('total_debet', $valDebet, array('id' => 'total_debet', 'maxlength' => 60, 'readonly' => true, 'style' => 'width:75px;')); ?>
                                </div>
                            </td>
                            <td class="span2"><div class="input-prepend">
                                    <span class="add-on">Rp.</span>
                                    <?php echo CHtml::textField('total_credit', $valCredit, array('id' => 'total_credit', 'maxlength' => 60, 'readonly' => true, 'style' => 'width:75px;')); ?>
                                </div>
                            </td>
                        </tr>
                    </tfoot>
                </table>
                <div class = "form-actions">
                    <?php
                    $this->widget('bootstrap.widgets.TbButton', array(
                        'buttonType' => 'submit',
                        'type' => 'primary',
                        'icon' => 'ok white',
                        'label' => 'Simpan',
                    ));
                    ?>
                </div>

            </div>
            <?php $this->endWidget(); ?>
        </div>
    </div>
   
</div>

