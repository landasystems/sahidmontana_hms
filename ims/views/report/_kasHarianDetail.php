<div id='printableArea'>
    <div class="img-polaroid" style="border:none;">
        <table class="table table-bordered table tt" border="1" width="100%">
            <thead>
                <tr>
                    <td style="text-align: center;border:none" colspan="5"><h2>LAPORAN KAS HARIAN</h2>
                        <h4><?php echo date('d F Y', strtotime($a)); ?></h4>
                        <hr>
                    </td>
                </tr>
                <tr> 
                    <th width="10%" style="text-align:center;">REFF</th>
                    <th width="5%" style="text-align:center;">REK</th>
                    <th width="55%" style="text-align:center;">URAIAN</th>
                    <th width="15%" style="text-align:center;">DEBIT</th>
                    <th width="15%" style="text-align:center;">KREDIT</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $balance = AccCoaDet::model()->beginingBalance(date('Y-m-d', strtotime($a)), $cash, false);
                ?>
                <?php if ($balance < 0) {
                    ?>
                    <tr>
                        <td></td>
                        <td></td>
                        <td>Saldo Awal</td>
                        <td></td>
                        <td style="text-align:right"><?php echo landa()->rp($balance, $prefix); ?></td>
                    </tr>
                <?php } else { ?>
                    <tr>
                        <td></td>
                        <td></td>
                        <td>Saldo Awal</td>
                        <td style="text-align:right"><?php echo landa()->rp($balance, $prefix); ?></td>
                        <td></td>
                    </tr>
                <?php } ?>
                <tr>
                    <?php
                    $totald = 0;
                    $totalk = 0;

                    foreach ($cashindet as $ui) {
//                $terimaDari = (isset($ui->AccCashIn->description_to)) ? '<br/>(' . $ui->AccCashIn->description_to . ')' : '';
                        $acc = (isset($ui->AccCashIn->code_acc)) ? $ui->AccCashIn->code_acc : '';

                        echo '<tr><td style="text-align:center;">' . $acc . '</td>'
                        . '<td style="text-align:center;">' . $ui->AccCoa->code . '</td>'
                        . '<td>' . $ui->description . '</td>'
                        . '<td style="text-align: right">' . landa()->rp($ui->amount, $prefix) . '</td>'
                        . '<td></td></tr>';
                        $totald += $ui->amount;
                    }
                    foreach ($cashoutdet as $ui) {
                        $acc = (isset($ui->AccCashOut->code_acc)) ? $ui->AccCashOut->code_acc : '';
                        echo '<tr><td style="text-align:center;">' . $acc . '</td>
                    <td style="text-align:center;">' . $ui->AccCoa->code . '</td>
                    <td>' . $ui->description . '</td>
                    <td></td>
                    <td style="text-align: right">' . landa()->rp($ui->amount, $prefix) . ' </td></tr>';

                        $totalk += $ui->amount;
                    }

                    if ($balance < 0) {
                        $totalk+=$balance;
                    } else {
                        $totald+=$balance;
                    }
                    ?>
                </tr>

                <tr>
                    <th colspan="3">Total</th>
                    <th style="text-align: right"><?php echo landa()->rp($totald, $prefix); ?></th>
                    <th style="text-align: right"><?php echo landa()->rp($totalk, $prefix); ?></th>
                </tr>
                <tr>
                    <th colspan="3">Saldo Akhir</th>
                    <th style="text-align: right"><?php echo landa()->rp($totald - $totalk, $prefix); ?></th>
                    <th></th>
                </tr>
            </tbody>
        </table>
        <div>
            <table>
                <tr>
                    <th>Tunai</th>
                    <th> : </th>
                </tr>
                <tr>
                    <th>Bon Sementara</th>
                    <th> : </th>
                </tr>
            </table>
        </div>
    </div>
</div>

<style type="text/css" media="print">   
    body {visibility:hidden;}
    .printableArea{visibility:visible;position: absolute;top:0;left:1px;width: 100%;font-size:11px;}
    table td{
        padding : 0px !important;
    }

</style>

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
