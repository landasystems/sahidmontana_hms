<?php
//$a = explode('-', $_POST['AccCoaSub']['created']);
//$start = date('d M Y', strtotime($a[0]));
//$end = date('d M Y', strtotime($a[1]));
?>
<div class='printableArea'>
    <table width="100%">
        <tr>
            <td></td>
            <td></td>
            <td  style="text-align: center" colspan="2"><h2>KARTU STOCK REPORT</h2>
                <?php echo $start . " - " . $end; ?>
                <hr></td>
        </tr>   
        <?php
        $acc = Product::model()->findByPk($as_id);
        ?>

        <tr>
            <td width="10%">Nama Akun</td>
            <td width="5px">:</td>
            <td><?php echo $acc->name; ?> </td>
            <td></td>
            <td></td>
            <td align="right"><?php // echo 'Kode Akun = ' . $acc->code;   ?></td>    
        </tr> 

    </table>

    <table class="table table-bordered" style="border-collapse: separate" border="1">
        <thead>
            <tr>
                <th colspan="2" width="5%" style="text-align: center">Date</th>
                <th>Description</th>
                <th width="30%">Reff</th>
                <th width="20%">Debet</th>
                <th width="20%">Credit</th>
                <th width="20%">Saldo</th>
            </tr>

            <tr>
                <?php
                $balance = AccCoaSub::model()->saldoKartu($type, $acc->id, $start);
                ?>
                <th></th>
                <th></th>
                <th>Saldo Awal</th>
                <th></th>
                <th></th>
                <th></th>
                <th style="text-align: right;"><?php echo landa()->rp($balance); ?></th>
            </tr>
        </thead>
        <?php
        $i = 0;
        $monthYear = "";
        $saldo = 0;
        foreach ($accCoaSub as $a) {
            $sDate = ($monthYear == date('M Y', strtotime($a->date_coa))) ? "" : date('M Y', strtotime($a->date_coa));
            $monthYear = date('M Y', strtotime($a->date_coa));
            $dk = $a->debet - $a->credit;
            $i += $dk;
            $saldo = $balance + $i;

            echo'<tr>
			<td>' . $sDate . '</td>
                        <td>' . date('d', strtotime($a->date_coa)) . '</td>
			<td>' . $a->description . '</td>
			<td>' . $a->code . '</td>
			<td name="deb">' . landa()->rp($a->debet, false) . ',- </td>
			<td name="cred">' . landa()->rp($a->credit, false) . ',- </td>
			<td name="tdeb">' . landa()->rp($saldo, false) . ',- </td>
			</tr>';
        }
        ?>
        <tfoot>
            <tr>
                <th colspan="6">Saldo Akhir</th>
                <th style="text-align: right;"><?php echo landa()->rp($saldo, false) . ',-' ?></th>
            </tr>
        </tfoot>
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
