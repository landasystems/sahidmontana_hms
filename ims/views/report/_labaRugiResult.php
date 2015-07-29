<?php
//trace($balance);
$tanggal = "";
$tanggal = $start . " - " . $end;
?>
<div class='printableArea'>
    <table width="100%">

    </table>


    <table class="table table-bordered table-striped table" border="1">
        <thead>
            <tr>
                <td style="text-align: center;border:none" colspan="4"><h2>LAPORAN LABA/RUGI</h2>
                    <?php echo $tanggal; ?>
                    <hr>
                </td>
            </tr> 
            <tr>
                <th width="5%">No</th>
                <th width="55%">Keterangan</th>
                <th width="20%">Saldo</th>
                <th width="20%">Total</th>
            </tr>
        <thead>
            <tr>
                <td><h4>I</h4></td>
                <td colspan="3"><H4>PENDAPATAN</h4></td>
<?php
$tpend = 0;
$ttotal = 0;
$tpeng = 0;

$pendapatan = array();
$beban = array();
$bal = array();

foreach ($accCoa as $b) {
    $lr = AccCoaDet::model()->listProfitLoss(date('Y-m-d', strtotime($start)), date('Y-m-d', strtotime($end)));
    $balance = (isset($lr[$b->id])) ? $lr[$b->id]['sumCredit'] - $lr[$b->id]['sumDebet'] : 0;

    if ($balance != 0 || $b->type = "general") {
        if ($b->group == "receivable") {
            $pendapatan[$b->id] = $b;
            $tpend += $balance;
        } else {
            $beban[$b->id] = $b;
            $tpeng += $balance;
        }
        $bal[$b->id] = $balance;
    }
}

foreach (array_reverse($accCoa) as $b) {
    if (isset($bal[$b->parent_id])) {
        $bal[$b->parent_id] += $bal[$b->id];
    }
}

foreach ($pendapatan as $b) {
    if ($b->level <= $viewType) {
        $balance = $bal[$b->id];
        if (($balance != 0 && !empty($balance)) || ($viewType == $b->level && $balance != 0)) {
            $balance = ($viewType == $b->level || $b->type == 'detail') ? landa()->rp($balance, false) : '';
            echo'<tr><td></td><td>'
            . $b->spaceName . '</td><td style="text-align:right>' . $balance . '</td><td></td>' .
            '</tr>';
        }
    }
    $tpend += $balance;
}
?>

            </tr>
            <tr>
                <td colspan="3"><b>Total Pendapatan Kotor</b></td>
                <td><?php echo landa()->rp($tpend, false) . ',-'; ?></td>
            </tr>

            <tr>
                <td><h4>II</h4></td>
                <td colspan="3"><h4>BIAYA</h4></td>
<?php
foreach ($beban as $b) {
    if ($b->level <= $viewType) {
        $balance = $bal[$b->id];
        if (($balance != 0 && !empty($balance)) || ($viewType == $b->level && $balance != 0)) {
            $balance = ($viewType == $b->level || $b->type == 'detail') ? landa()->rp($balance, false) : '';
            echo'<tr><td></td><td>'
            . $b->spaceName . '</td><td style="text-align:right">' . $balance . '</td><td></td>' .
            '</tr>';
        }
    }
    $tpeng += $balance;
}
?>
            </tr>
            <tr>
                <td colspan="3"><b>Total Biaya</b></td>
                <td><?php echo landa()->rp($tpeng, false) . ',-'; ?></td>
            </tr>

            <tr>
                <th width="10%" colspan="3">
<?php
$ttotal = $tpend + $tpeng;
if ($ttotal >= 0)
    echo 'Laba';
else
    echo 'Rugi';
?></th>
                <th ><?php echo landa()->rp($ttotal, false) . ',-'; ?></th>
            </tr>
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
