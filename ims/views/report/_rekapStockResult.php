<div class='printableArea'>

<table width="100%">
    <tr>
        <td  style="text-align: center" colspan="2"><h2>REKAP KARTU STOCK REPORT</h2>
            <?php echo date('d-M-Y', strtotime($start))." - ". date('d-M-Y', strtotime($end)); ?>
            <hr></td>
    </tr>   
</table>
<table class="table table-bordered table">
    <thead>
        <tr> 
            
            <th width="10%" rowspan="2"><h4 align="center">No. Akun</h3></th>
            <th width="30%" rowspan="2"><h4 align="center">Nama Supplier</h3></th>
            <th width="15%" rowspan="2"><h4 align="center">Saldo Awal</h3></th>
            <th width="30%" colspan="2"><h4 align="center">Mutasi</h3></th>
            <th width="15%" rowspan="2"><h4 align="center">Saldo Akhir</h3></th>
        </tr>
        <tr>
            <th width="15%"><h4 align="center">Debet</h3></th>
            <th width="15%"><h4 align="center">Credit</h3></th>
        </tr>

        <tr class="table-bordered">
            <?php
            $tbalance="";
            $tdebet="";
            $tcredit="";
            $takhir="";
            $SaldoAkhir="";
            foreach ($supplier as $b) {
                $balance = AccCoaSub::model()->total($type, $end, $start, $b->id);
//                $SaldoAkhir = AccCoaSub::model()->beginingBalanceAr(date('Y-m-d', strtotime($end)), $b->id);
                $saldoawal = AccCoaSub::model()->saldoKartu($type, $b->id, $start);
                $salakhir = AccCoaSub::model()->saldoKartu($type, $b->id, $end);
                $tbalance += $saldoawal;
                $takhir += $salakhir;
                $tdebet += $balance->sumDebet;
                $tcredit += $balance->sumCredit;
                if($balance->sumDebet != 0 or $balance->sumCredit != 0 or $saldoawal != 0 or $salakhir !=0){
                     echo'<tr>
			<td width="10%">' . $b->code . '</td>
			<td>' . $b->name . '</td>
			<td>' . landa()->rp($saldoawal). ',- </td>
			<td>' . landa()->rp($balance->sumDebet). ',- </td>
			<td>' . landa()->rp($balance->sumCredit). ',- </td>
			<td>' . landa()->rp($salakhir) . ',- </td>
                        </tr>';
                }
            }
            ?>
        </tr>
    </thead>
    <footer>
        <tr>
            <th colspan="2">Saldo Total</th>
            <th><?php echo landa()->rp($tbalance);?></th>
            <th><?php echo landa()->rp($tdebet);?></th>
            <th><?php echo landa()->rp($tcredit);?></th>
            <th><?php echo landa()->rp($takhir);?></th>
        </tr>
    </footer>
</table>
 </div>

<style type="text/css" media="print">
    body {visibility:hidden;}
    .printableArea{visibility:visible;position: absolute;top:0;left:0px;width: 100%;font-size:17px}
    table{width: 100%}
</style>
<script type="text/javascript">
    function printDiv()
    {

        window.print();

    }
</script>