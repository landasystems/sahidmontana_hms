<?php
$category = ChargeAdditionalCategory::model()->findAll(array('condition' => 'level=1'));
$departementId = '';
$departementName = 'All';
$account = Account::model()->findAll();
$productSold = (isset($naProductSold->product_sold)) ? json_decode($naProductSold->product_sold, true) : array();
?>
<div style="text-align: right">
    <button class="print entypo-icon-printer button" onclick="printDiv('na_product_sold')" type="button">&nbsp;&nbsp;Print Report</button>
</div>
<div class="na_product_sold" id="na_product_sold" style="text-align: center;width: 100%">
    <center><h3>PRODUCT SOLD</h3></center>
    <center>Date Night Audit : <?php echo date("d-M-Y", strtotime($siteConfig->date_system)); ?></center>
    <hr style="border-bottom: 2px solid #bbb !important;border-top: 1px solid #bbb !important;height: 3px">
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
                if (isset($productSold[$departement->id][$acc->id])) {
                    ?>
                    <tbody>
                        <tr>              
                            <td></td>
                            <td colspan="5" style="background: aliceblue"><?php echo strtoupper($acc->name); ?></td>
                        </tr>
                        <?php
                        foreach ($productSold[$departement->id][$acc->id] as $d) {
                            echo '<tr>';
                            echo '<td style="text-align:center"></td>';
                            echo '<td style="text-align:left">' . $nomer . '</td>';
                            echo '<td>' . $d['name'] . '</td>';
                            echo '<td style="text-align:center">' . $d['amount'] . '</td>';
                            echo '<td style="text-align:right">' . landa()->rp($d['netCharge'], false) . '</td>';
                            echo '<td style="text-align:right">' . landa()->rp($d['netTotal'], false) . '</td>';
                            echo '</tr>';
                            $nomer++;
                            $total += $d['netTotal'];
                            $qty += $d['amount'];
                            
                            
                        }                        
                        ?>
                        <tr>
                            <td></td>            
                            <td colspan="2" style="text-align: right;vertical-align: middle">Total  :</td>            
                            <td style="text-align: center;vertical-align: middle"><?php echo $qty; ?></td>             
                            <td  colspan="2" style="text-align: right;vertical-align: middle"><?php echo landa()->rp($total); ?></td>             
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
                <th colspan="2" style="text-align: right;vertical-align: middle"><?php echo landa()->rp($totAll); ?></th>               
            </tr>
        </thead>
    </table>
<br>    
    <table>
        <tr>
            <td style="padding: 0px;width: 30%" class="span2">Audit By</td>        
            <td style="padding: 0px">: <?php echo $model->Cashier->name; ?></td>
        </tr>    
        <tr>
            <td style="padding: 0px">Printed Time</td>        
            <td style="padding: 0px">: <?php echo date('l d-M-Y H:i:s', strtotime($model->created)); ?></td>
        </tr>    
    </table>
</div>

<style type="text/css">
    .noborder{
        border: 0px !important;
    }
    @media print
    {
        body {visibility:hidden;}
        .na_product_sold{visibility: visible;} 
        .na_product_sold{width: 100%;top: 0px;left: 0px;position: absolute;font-size: 9px !important} 

    }


</style>
