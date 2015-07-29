<div id='printableArea'>
    <div class="img-polaroid" style="border:none;">
        <style type="text/css" media="print">
            .table td {
                padding: 0px !important;
                margin: 0px !important;

            }
            .table tr{
                padding : 0px !important;
                margin : 0px !important;
            }
            body {font-size:7.5pt;}

        </style>
        <table class="table table-bordered tt">
            <thead>
                <tr>
                    <td  style="text-align: center;border:none" colspan="6"><h4>NERACA SALDO</h4>
                        <h5><?php echo date('d-M-Y', strtotime($start)) . " - " . date('d-M-Y', strtotime($end)); ?></h5>
                        <hr>
                    </td>
                </tr> 
                <tr>
                    <th width="3%" style="text-align:center">KODE</th>
                    <th width="25%" style="text-align:center">REKENING</th>
                    <th width="12%" style="text-align:center">SALDO AWAL</th>
                    <th width="6%" style="text-align:center">DEBIT</th>
                    <th width="6%" style="text-align:center">KREDIT</th>
                    <th width="12%" style="text-align:center">SALDO AKHIR</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <?php
                    $SaldoAkhir = 0;
                    $saldoaw = 0;
                    $saldoak = 0;
                    $debet = 0;
                    $credit = 0;
                    foreach ($accCoa as $b) {
                        $balance = AccCoaDet::model()->beginingBalance(date('Y-m-d', strtotime($start)), $b->id, false);
                        $total = AccCoaDet::model()->totalSaldo($start, $end, $b->id);
                        $SaldoAkhir = $balance + $total->sumDebet - $total->sumCredit;
                        if ($total->sumDebet != 0 || $total->sumCredit != 0 || $balance != 0) {
                            echo'<tr>
			<td width="3%">' . $b->code . '</td>
			<td width="10%">' . $b->name . '</td>
			<td width="10%" style="text-align:right">' . landa()->rp($balance, false, 2) . ' </td>
			<td width="10%" style="text-align:right">' . landa()->rp($total->sumDebet, false, 2) . ' </td>
			<td width="10%" style="text-align:right">' . landa()->rp($total->sumCredit, false, 2) . ' </td>
			<td width="10%" style="text-align:right">' . landa()->rp($SaldoAkhir, false, 2) . ' </td>
                        </tr>';
                        }
                        $saldoaw+=$balance;
                        $saldoak+=$SaldoAkhir;
                        $debet+=$total->sumDebet;
                        $credit+=$total->sumCredit;
                    }
                    ?>
                </tr>
                <tr>
                    <th colspan="2">Total</th>
                    <th style="text-align:right"><?php echo landa()->rp($saldoaw, false, 2); ?></th>
                    <th style="text-align:right"><?php echo landa()->rp($debet, false, 2); ?></th>
                    <th style="text-align:right"><?php echo landa()->rp($credit, false, 2); ?></th>
                    <th style="text-align:right"><?php echo landa()->rp($saldoak, false, 2); ?></th>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<script type="text/javascript">
    function printDiv(divName) {
        var printContents = document.getElementById(divName).innerHTML;
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
    }
</script>
