<?php
$this->setPageTitle('Beginning Balance');
foreach (Yii::app()->user->getFlashes() as $key => $message) {
    echo '<div class="alert alert-' . $key . '">' . $message . '</div>';
}
?>

<div class="form">

    <div class="col-xs-12 col-md-6">

        <legend>
            <p class="note">
                <span class="required">-</span> Mengubah saldo awal akan berpengaruh terhadap transaksi yang telah diinput<br/>
                <span class="required">-</span> Saldo awal akan di setting pada tanggal <span class="label label-info"><?php echo $siteConfig->date_system?></span>
            </p>
        </legend>

        <div id="yw22" class="tabs-left">
            <ul id="yw23" class="nav nav-tabs">
                <?php
                foreach ($tabHeader as $valHead) {
                    ?>
                    <li class="">
                        <a data-toggle="tab" href="#tab<?php echo $valHead->id; ?>"><?php echo $valHead->name ?></a>
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
                $no = 0;
                $valDebet = 0;
                $valCredit = 0;
                foreach ($tabHeader as $valHead) {
                    if ($no == 0) {
                        $status = "active in";
                    } else {
                        $status = "";
                    }
                    $child = AccCoa::model()->findByPk($valHead->id);
                    $valChild = $child->descendants()->findAll();
                    ?>
                    <div id="tab<?php echo $valHead->id; ?>" class="tab-pane fade <?php echo $status ?>">
                        <h3><?php echo $valHead->name; ?></h3>
                        <table class="responsive table table-bordered">
                            <thead>
                                <tr>
                                    <th>Nama Akun</th>
                                    <th>Debit</th>
                                    <th>Kredit</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($valChild as $val) {
                                    ?>
                                    <tr>
                                        <td><?php echo $val->nestedname ?></td>
                                        <?php
                                        if ($val->type == "detail") {
                                            $balance = AccCoaDet::model()->find(array('condition' => 'reff_type="balance" and acc_coa_id=' . $val->id));
                                            if (isset($balance)) {
                                                $debit = $balance->debet;
                                                $credit = $balance->credit;
                                            } else {
                                                $debit = 0;
                                                $credit = 0;
                                            }
                                            ?>
                                            <td width="125">
                                                <div class="input-prepend">
                                                    <span class="add-on">Rp.</span>
                                                    <input type="hidden" name="id[]" value="<?php echo $val->id ?>">
                                                    <?php echo CHtml::textField('debet[]', $debit, array('maxlength' => 60, 'class' => 'angka debet', 'style' => 'width:75px;')); ?>
                                                </div>                                                
                                            </td>
                                            <td width="125">
                                                <div class="input-prepend">
                                                    <span class="add-on">Rp.</span>
                                                    <?php echo CHtml::textField('credit[]', $credit, array('maxlength' => 60, 'class' => 'angka credit', 'style' => 'width:75px;')); ?>
                                                </div>
                                            </td>
                                            <?php
                                            $valDebet = $valDebet + $debit;
                                            $valCredit = $valCredit + $credit;
                                        } else {
                                            echo '<td style="width:150px;" colspan="2"></td>';
                                        }
                                        ?>
                                    </tr>
                                    <?php
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                    <?php
                    $no++;
                }
                ?>
                <table class="responsive table table-bordered">
                    <tfoot>
                        <tr>
                            <td></td>    
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
                        'label' => 'simpan',
                    ));
                    ?>
                </div>

            </div>
            <?php $this->endWidget(); ?>
        </div>
    </div>

</div>

