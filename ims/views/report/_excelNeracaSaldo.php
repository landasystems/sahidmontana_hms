<table width="100%">
    <tr>
        <td  style="text-align: center" colspan="6"><h2>NERACA SALDO</h2>
            <h3><?php echo date('d-M-Y', strtotime($start)) . " - " . date('d-M-Y', strtotime($end)); ?></h3>
            <hr></td>
    </tr>   
</table>
<table class="table table-bordered table" border="1">
    <thead>
        <tr> 

            <th width="10%" style="text-align:center">No. Rekening</th>
            <th width="30%" style="text-align:center">Nama Rekening</th>
            <th width="15%" style="text-align:center">Saldo Awal</th>
            <th width="15%" style="text-align:center">Debet</th>
            <th width="15%" style="text-align:center">Credit</th>
            <th width="15%" style="text-align:center">Saldo Akhir</th>
        </tr>
        <tr class="table-bordered">
            <?php
            $SaldoAkhir = 0;
            $saldoaw = 0;
            $saldoak = 0;
            $debet = 0;
            $credit = 0;
            foreach ($accCoa as $b) {
                $balance = AccCoaDet::model()->beginingBalance(date('Y-m-d', strtotime('-1 day', strtotime($start))), $b->id);
//                $SaldoAkhir = AccCoaDet::model()->beginingBalance(date('Y-m-d', strtotime($end)), $b->id);
                $total = AccCoaDet::model()->totalSaldo($start, $end, $b->id);
                if ($total->sumDebet != 0 || $total->sumCredit != 0 || $balance != 0) {
                    $SaldoAkhir = $balance + $total->sumDebet - $total->sumCredit;
                    echo'<tr>
			<td width="10%">' . $b->code . '</td>
			<td>' . $b->name . '</td>
			<td style="text-align:right">' . $balance . ' </td>
			<td style="text-align:right">' . $total->sumDebet . ' </td>
			<td style="text-align:right">' . $total->sumCredit . ' </td>
			<td style="text-align:right">' . $SaldoAkhir . ' </td>
                        </tr>';
                }
                $saldoaw+=$balance;
                $saldoak+=$SaldoAkhir;
                $debet+=$total->sumDebet;
                $credit+=$total->sumCredit;
            }
            ?>
        </tr>
    </thead>
    <tfoot>
        <tr>
            <th colspan="3">Total</th>
            <th style="text-align:right"><?php echo $debet; ?></th>
            <th style="text-align:right"><?php echo $credit; ?></th>
            <th style="text-align:right"></th>
        </tr>
    </tfoot>
</table>