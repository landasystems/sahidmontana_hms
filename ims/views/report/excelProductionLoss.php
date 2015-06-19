
<table class="table table-bordered">
     <tr>
        <td  style="text-align: center" colspan="9"><h2>PRODUCTION LOSS REPORT</h2>
            <?php echo date('d/m/Y', strtotime($start)) . " - " . $end = date('d/m/Y', strtotime($end)); ?>
            <hr></td>
    </tr>
    <thead>
        <tr>            
            <th >No</th>
            <th >SPK</th>
            <th >NOPOT</th>
            <th >Nama Proses</th>
            <th >Mulai Pengerjaan</th>
            <th >Selesai Pengerjaan</th>
            <th >Start </th>
            <th >End</th>
            <th >Loss</th>
           
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