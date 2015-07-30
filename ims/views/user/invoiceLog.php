<?php
$type = $_GET['type'];
if ($type == 'supplier') {
    $title = 'Supplier Payment Logs';
} else {
    $title = 'Customer Invoice Logs';
}
foreach (Yii::app()->user->getFlashes() as $key => $message) {
    echo '<div class="alert alert-' . $key . '">' . $message . '</div>';
}
?>

<div class="form">

    <div class="col-xs-12 col-md-6">

        <legend>
            <p class="note">
                <span class="required">*</span> Berikut adalah daftar <?php
                echo $title . ' ';
                Yii::app()->name;
                echo param('clientName');
                ?><br/>
                <!--<span class="required">-</span> Saldo awal akan di setting pada tanggal <span class="label label-info"><?php // echo $siteConfig->date_system                ?></span>-->
            </p>
        </legend>

        <div id="yw22" class="tabs-left">
            <ul id="yw23" class="nav nav-tabs">
                <?php
                foreach ($header as $head) {
//                    
                    ?>
                    <li class="">
                        <a data-toggle="tab" href="#tab<?php echo $head->id; ?>"><?php echo $head->code ?></a>
                    </li>
                    <?php
                }
                ?>
            </ul>
            <?php
            $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
                'id' => 'supplier-payment-form',
                'enableAjaxValidation' => false,
                'method' => 'post',
                'type' => 'vertical',
                'htmlOptions' => array(
                    'enctype' => 'multipart/form-data',
                    'name' => 'supplier-payment',
                )
            ));
            ?>
            <div class="tab-content">
                <?php
                $no = 0;
                $valDebet = 0;
                $valCredit = 0;
                $charge = 0;
                $payments = 0;
                foreach ($header as $head) {
                    if ($no == 0) {
                        $status = "active in";
                    } else {
                        $status = "";
                    }
//                    $list = InvoiceDet::model()->findAllByAttributes(array(
//                        'user_id' => $head->id,
//                        'type' => $_GET['type'],
//                    ));
                    ?>
                    <div id="tab<?php echo $head->id; ?>" class="tab-pane fade <?php echo $status ?>">
                        <h3><?php echo $head->description; ?></h3>
                        <?php
                        $transDetail = AccCoaDet::model()->findAll(array(
                            'condition' => 'invoice_det_id=' . $head->id,
                            'order' => 'date_coa ASC'
                        ));
                        ?>
                        <table class="responsive table table-bordered">
                            <thead>
                                <tr>
                                    <td style="border:none" colspan="3">Kode : <?php echo $head->code ?></td>
                                    <td style="border:none;width:15%">Tgl. Jatuh Tempo : </td>
                                    <td style="border:none"><?php echo date('d-F-Y',  strtotime($head->term_date)); ?></td>
                                </tr>
                                <tr>
                                    <th width="5%" style="max-width:10%">No.</th>
                                    <th>Keterangan</th>
                                    <th style="width:15%">Tgl. Transaksi</th>
                                    <th style="width: 15%">Charge</th>
                                    <th style="width:15%">Saldo</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $i = 1;
                                $total = 0;
                                $charge = 0;
                                foreach ($transDetail as $detail) {
                                    if ($_POST['type'] = 'customer') {
                                        if ($detail->debet == 0) {
                                            $total += -$detail->credit;
                                            $charge = $detail->credit;
                                        } else {
                                            $total += + $detail->debet;
                                            $charge = $detail->debet;
                                        }
                                    } else {
                                        if ($detail->debet == 0) {
                                            $total += + $detail->credit;
                                            $charge = $detail->credit;
                                        } else {
                                            $total += -$detail->debet;
                                            $charge = $detail->debet;
                                        }
                                    }
                                    if ($i == 1){
                                        $descriptions = 'Saldo Awal';
                                    }else{
                                        $descriptions = $detail->description;
                                    }
                                    echo '<tr>'
                                    . '<td style="text-align:center">' . $i++ . '</td>'
                                    . '<td>' . $descriptions . '</td>'
                                    . '<td style="text-align:center">' . $detail->date_coa . '</td>'
                                    . '<td style="text-align:center">' . landa()->rp($charge) . '</td>'
                                    . '<td style="text-align:center">' . landa()->rp($total) . '</td>'
                                    . '</tr>';
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                    <?php
                    $no++;
                }
                ?>
            </div>
            <?php $this->endWidget(); ?>
        </div>
    </div>

</div>
