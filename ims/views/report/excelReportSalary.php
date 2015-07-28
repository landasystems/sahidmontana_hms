
<table width="100%">
    <tr>
        <td  style="text-align: center" colspan="3"><h2>SALARY IS PAID REPORT</h2>
            <?php echo date('d/m/Y', strtotime($start)) . " - " . $end = date('d/m/Y', strtotime($end)); ?>
            <hr></td>
    </tr>
</table>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>No</th>
            <th>Tanggal Bayar</th>
            <th>Gaji</th>
            <th>Denda</th>
            <th>Kasbon</th>
            <th>Total Gaji</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $no=0;
        $total_gaji="";
        $total_denda="";
        $total_kasbon="";
        $grand_total="";
        foreach($salary as $o){
            $total_gaji += $o->total;
            $total_denda += $o->total_loss_charge;
            $total_kasbon += $o->other;
            $total = $o->total - $o->total_loss_charge - $o->other;
            $grand_total += $total;
            $no++;
            echo'<tr>
                <td>'.$no.'</td>
                <td>'.date('d F Y', strtotime($o->created)).'</td>
                <td>'.landa()->rp($o->total).'</td>
                <td>'.landa()->rp($o->total_loss_charge).'</td>
                <td>'.landa()->rp($o->other).'</td>
                <td>'.landa()->rp($total).'</td>
            </tr>';
        }
        ?>
    </tbody>
    <tfoot>
        <tr>
            <th colspan="2">Total :</th>
            <th><?php echo landa()->rp($total_gaji); ?></th>
            <th><?php echo landa()->rp($total_denda); ?></th>
            <th><?php echo landa()->rp($total_kasbon); ?></th>
            <th><?php echo landa()->rp($grand_total); ?></th>
            
            
        </tr>
    </tfoot>
</table>