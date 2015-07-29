<?php
$a = explode('-', $created);
$start = date('d M Y', strtotime($a[0]));
$end = date('d M Y', strtotime($a[1]));$end = (isset($a[1])) ? date('d/m/Y', strtotime($a[1])) . " 23:59:59" : date('d/m/Y');?>
<a class="btn" href="<?php // echo Yii::app()->controller->createUrl('report/GenerateExcelSalary?created='.str_replace(" ", "" ,$created))  ?>">
    <i class="entypo-icon-list"></i>Export to Excel</a>
<button onclick="js:printDiv();
        return false;" class="btn btn-info"><span class="icon-print"></span>Print this report</button>
<hr>
<div class='printableArea'>
<table style="width: 1000px">
    <tr>
        <td  style="text-align: center" colspan="3"><h2>Laporan Gaji Belum Terbayar</h2>
            <?php echo date('d/m/Y', strtotime($a[0])) . " - " . $end = date('d/m/Y', strtotime($a[1])); ?>
            <hr></td>
    </tr>
</table>


<table class="table table-bordered" style="width: 1000px">
    <thead>
        <tr>
            <th>No</th>
            <th>Nama Proses</th>
            <th>Tanggal Mulai</th>
            <th>Tanggal Selesai</th>
            <th>Nopot</th>
            <th>Jumlah</th>
            <th>Hilang</th>
            <th>Denda</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $no = 0;
        $jumlah=0;
        $tothilang=0;
        $totdenda=0;
        $subtotal=0;
        $group='';
        if (empty($process)) {
            echo'<tr><td colspan="9">Data kosong.</td></tr>';
        } else {
            foreach ($process as $o) {
                $no++;
                if($group != $o->start_user_id){
                    echo'<tr><th colspan="9">Pegawai : ' . $o->StartUser->name . '</th></tr>';
                    $group =  $o->start_user_id;
                }
                echo'<tr>
        <td>' . $no . '</td>
        <td> (SPK ' . ucwords($o->Process->WorkOrder->code) . ')   ' . ucwords($o->Process->name) . '</td>
        <td>' . date('d M Y', strtotime($o->time_start)) . '</td>
        <td>' . date('d M Y', strtotime($o->time_end)) . '</td>
        <td>' . $o->NOPOT->code . '</td>
        <td style="text-align:center">' . $o->NOPOT->qty . '</td>
        <td style="text-align:center">' . $o->loss_qty . '</td>
        <td style="text-align:center">' . landa()->rp($o->loss_charge) . '</td>
        <td style="text-align:center">' . landa()->rp($o->charge) . '</td>
        </tr>';
                $jumlah += $o->NOPOT->qty;
                $tothilang += $o->loss_qty;
                $totdenda += $o->loss_charge;
                $subtotal += $o->charge;
            }
        
        }
        echo '<tr><th colspan="5">TOTAL</th>
            <th style="text-align:center">'.$jumlah.'</th>
            <th style="text-align:center">'.$tothilang.'</th>
            <th style="text-align:center">'.landa()->rp($totdenda).'</th>
            <th style="text-align:center">'.landa()->rp($subtotal).'</th>
            </tr>';
        ?>
    </tbody>
</table>
</div>
<style type="text/css" media="print">
body {visibility:hidden;}
.printableArea{visibility:visible;position: absolute;top:0;left:0;width: 100%;font-size:17px}
</style>
<script type="text/javascript">
    function printDiv()
    {

        window.print();

    }
</script>