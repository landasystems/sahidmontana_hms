<?php
$a = explode('-', $created);
$start = date('d M Y', strtotime($a[0]));
$end = date('d M Y', strtotime($a[1]));
?>
<table width="100%">
    <tr>
        <td  style="text-align: center" colspan="3"><h2>SELL REPORT</h2>
            <?php echo date('d/m/Y', strtotime($a[0])) . " - " . $end = date('d/m/Y', strtotime($a[1])); ?>
            <hr></td>
    </tr>   
    <tr>
        <td width="10%">Departement</td>
        <td width="5px">:</td>
        <td><?php echo $departement; ?></td>
    </tr>   
   
</table>

<table class="table table-bordered">
    <thead>
        <tr>            
            <th >Date</th>
            <th >Code</th>
            <th >Departement</th>
            <th >Subtotal</th>
            <th >PPN</th>
            <th >Discount</th>
            <th >Other Cost</th>
            <th >Total</th>            
            <th style="text-align: center">View Detail</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $grandTotal = 0;
        foreach ($buy as $o) {
            $subtotal = (isset($o->subtotal)) ? $o->subtotal : 0;
            $ppn = (isset($o->ppn)) ? $o->ppn : 0;
            $discount = (isset($o->discount)) ? $o->discount : 0;
            $other = (isset($o->other)) ? $o->other : 0;
            $total = $subtotal + $ppn - $discount + $other;
            $grandTotal = $grandTotal + $total;
            $payment = (isset($o->payment)) ? $o->payment : 0;
            echo '<tr>
                    <td>'.date('d M Y', strtotime($o->created)).'</td>
                    <td>'.$o->code.'</td>
                    <td>' . $o->Departement->name . '</td>
                    <td>'.landa()->rp($subtotal).'</td>
                    <td>'.landa()->rp($ppn).'</td>
                    <td>'.landa()->rp($discount).'</td>
                    <td>'.landa()->rp($other).'</td>
                    <td>'.landa()->rp($total).'</td>                    
                    <td style="text-align:center">
                    <a class="btn btn-small view" target="_blank" title="View Detail" rel="tooltip" href="../sell/'.$o->id.'"><i class="icon-eye-open"></i></a>
                    </td>
                <tr>';                        
        }
        ?>
    </tbody>
    <tfoot>
        <tr>
            <th colspan="7">Total Sell</th>            
            <th colspan="2"><?php echo landa()->rp($grandTotal) ?></th>
                    
        </tr>
    </tfoot>
</table>