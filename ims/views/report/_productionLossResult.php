<?php
$a = explode('-', $created);
$start = date('d M Y', strtotime($a[0]));
$end = date('d M Y', strtotime($a[1]));
?>
<!--<a class="btn" href="<?php // echo Yii::app()->controller->createUrl('report/GenerateExcelProductionLoss?created='.str_replace(" ", "" ,$created)) ?>">-->
    <!--<i class="entypo-icon-list"></i>Export to Excel</a>-->
<button onclick="js:printDiv();return false;" class="btn btn-info"><span class="icon-print"></span>Print this report</button>
<hr>
<div class='printableArea'>
<table style="width: 1000px">
    <tr>
        <td  style="text-align: center" colspan="3"><h2>Laporan Jumlah Barang Hilang</h2>
            <?php echo date('d/M/Y', strtotime($a[0])) . " - " . $end = date('d/M/Y', strtotime($a[1])); ?>
            <hr></td>
    </tr>
</table>

<table class="table table-bordered" style="width: 1000px">
    <thead>
        <tr>            
            <th class="span1">No</th>
            <th >SPK</th>
            <th >NOPOT</th>
            <th >Nama Proses</th>
            <th >Mulai Pengerjaan</th>
            <th >Selesai Pengerjaan</th>
            <th >Start </th>
            <th >Dari</th>
            <th >Ke</th>
           
        </tr>
    </thead>
    <tbody>
        <?php
        $no = 0;
        $total_loss="";
        foreach ($lossReport as $o) {
            $total_loss += $o->loss_qty;
            $no++;
            echo '<tr>
                    <td>' . $no . '</td>
                    <td>' . $o->Process->WorkOrder->code . '</td>
                    <td>' . $o->NOPOT->code. '</td>
                    <td>' . $o->Process->name . '</td>
                    <td>' . date('d M Y', strtotime($o->time_start)) . '</td>
                    <td>' . date('d M Y', strtotime($o->time_end)) . '</td>
                    <td>' .$o->StartUser->name . '</td>
                    <td>' .$o->EndUser->name . '</td>
                    <td>' . $o->loss_qty . '</td>                    
                   
                </tr>';
        }
        ?>
    
    </tbody>
    <tfoot>
        <tr>
            <th colspan="8" style="text-align: right;padding-right: 15px">Total Loss :</th>            
            <th colspan="1"><?php echo  $total_loss ?></th>
        </tr>
    </tfoot>
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