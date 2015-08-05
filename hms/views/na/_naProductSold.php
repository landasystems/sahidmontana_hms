<?php
$category = ChargeAdditionalCategory::model()->findAll(array('condition' => 'level=1'));
$departementId = '';
$departementName = 'All';
$account = Account::model()->findAll();
?>

<center><h3>PRODUCT SOLD</h3></center>
<center>Date Night Audit : <?php echo date("d-M-Y", strtotime($siteConfig->date_system)); ?></center>
<hr>
<table class="items table table-striped   table-condensed">
    <?php
    $qtyAll = 0;
    $totAll = 0;
    foreach ($category as $departement) {
        ?>
        <thead>   
            <tr><th colspan="6" style="height:30px;vertical-align: middle">DEPARTEMENT : <?php echo strtoupper($departement->name); ?></th></tr>
            <tr>
                <th>&nbsp;&nbsp;</th>
                <th class="span1" style="text-align: left;vertical-align: middle">No</th>
                <th class="span5" style="text-align: left;vertical-align: middle">Product Name</th>                                                                                         
                <th class="span3" style="text-align: center;vertical-align: middle">Sold</th>                            
                <th class="span5" style="text-align: right;vertical-align: middle">Price</th>   
                <th class="span3" style="text-align: right;vertical-align: middle">Total</th>                                                
            </tr> 
        </thead> 
        <?php
        foreach ($account as $acc) {
            $nomer = 1;
            $total = 0;
            $qty = 0;
            $detail = BillChargeDet::model()->with('BillCharge', 'Additional')->findAll(array('select' => '*,sum(amount) AS qty', 'group' => 'charge_additional_id', 'order' => 'qty DESC', 'condition' => 'is_na=0 and is_cashier=1 and Additional.account_id=' . $acc->id . '  and Additional.charge_additional_category_id=' . $departement->id));
            if (count($detail) > 0) {
                ?>
                <tbody>
                    <tr>              
                        <td></td>
                        <td colspan="5" style="background: aliceblue"><?php echo strtoupper($acc->name); ?></td>
                    </tr>
                    <?php
                    foreach ($detail as $d) {
                        echo '<tr>';
                        echo '<td style="text-align:center"></td>';
                        echo '<td style="text-align:left">' . $nomer . '</td>';
                        echo '<td>' . $d->Additional->name . '</td>';
                        echo '<td style="text-align:center">' . $d->qty . '</td>';
                        echo '<td style="text-align:right">' . landa()->rp($d->netCharge, false) . '</td>';
                        echo '<td style="text-align:right">' . landa()->rp($d->netTotal, false) . '</td>';
                        echo '</tr>';
                        $nomer++;
                        $total += $d->netTotal;
                        $qty += $d->qty;

                        echo '<input type="hidden" name="productSold[' . $departement->id . '][' . $acc->id . ']['.$d->Additional->id.'][id]" value="' . $d->Additional->id . '" />';
                        echo '<input type="hidden" name="productSold[' . $departement->id . '][' . $acc->id . ']['.$d->Additional->id.'][name]" value="' . $d->Additional->name . '" />';
                        echo '<input type="hidden" name="productSold[' . $departement->id . '][' . $acc->id . ']['.$d->Additional->id.'][amount]" value="' . $d->qty . '" />';
                        echo '<input type="hidden" name="productSold[' . $departement->id . '][' . $acc->id . ']['.$d->Additional->id.'][netCharge]" value="' . $d->netCharge . '" />';
                        echo '<input type="hidden" name="productSold[' . $departement->id . '][' . $acc->id . ']['.$d->Additional->id.'][netTotal]" value="' . $d->netTotal . '" />';
                    }
                    ?>
                    <tr>
                        <td></td>            
                        <td colspan="2" style="text-align: right;vertical-align: middle">Total  :</td>            
                        <td style="text-align: center;vertical-align: middle"><?php echo $qty; ?></td>             
                        <td  colspan="2" style="text-align: right;vertical-align: middle"><?php echo landa()->rp($total, false); ?></td>             
                    </tr>                     
                </tbody>

                <?php
                $totAll += $total;
                $qtyAll += $qty;
            }
        }
        ?>
        <tr><td colspan="6">&nbsp;</td></tr>
        <?php
    }
    ?>


    <thead>  
        <tr>
            <th colspan="3" style="text-align: right;vertical-align: middle">Total All:</th>            
            <th style="text-align: center;vertical-align: middle"><?php echo $qtyAll; ?></th>               
            <th colspan="2" style="text-align: right;vertical-align: middle"><?php echo landa()->rp($totAll, false); ?></th>               
        </tr>
    </thead>
</table>

<table>
    <tr>
        <td style="padding: 0px" class="span2">Audit By</td>        
        <td style="padding: 0px">: <?php echo User()->name; ?></td>
    </tr>    
    <tr>
        <td style="padding: 0px">Printed Time</td>        
        <td style="padding: 0px">: <?php echo date('l d-M-Y H:i:s'); ?></td>
    </tr>    
</table>
