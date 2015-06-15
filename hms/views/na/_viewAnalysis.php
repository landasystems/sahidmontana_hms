<?php
$settings = json_decode($siteConfig->settings, true);
$roomAcc = (!empty($settings['room_account'])) ? $settings['room_account'] : '';
$foodAcc = 2;
$beverageAcc = 3;
$breakfastDep = 2;
$rateDolar = (!empty($settings['rate'])) ? $settings['rate'] : 0;
$breakfastAcc = (!empty($settings['fb_account'])) ? $settings['fb_account'] : '';
?>
<div style="text-align: right">
    <button class="print entypo-icon-printer button" onclick="printDiv('na_analys')" type="button">&nbsp;&nbsp;Print Report</button>
</div>
<div class="na_analys" id="na_analys" style="text-align: center;width: 100%">
    <center><h4>FOOD & BEVERAGE ANALYSIS</h4></center>
    <center>Date Night Audit : <?php echo date("d-M-Y", strtotime($model->date_na)); ?></center>
    <hr style="border-bottom: 2px solid #bbb !important;border-top: 1px solid #bbb !important;height: 3px">

    <table class="tbPrint" style="font-size: 10px;line-height: 1px !important;">
         <thead>
            <tr>            
                <th class="span3 print2" rowspan="2" style="text-align: center;border-bottom:solid #000 2px;border-top:solid #000 2px">ACCOUNT</th>            
                <th class="span2 print2" rowspan="2" style="text-align: center;border-bottom:solid #000 2px;border-top:solid #000 2px">TODAY</th>            
                <th class="print2" colspan="3" style="text-align: center;border-bottom:solid #000 2px;border-top:solid #000 2px">MONTH TO DATE</th>            
                <th class="print2" colspan="2" style="text-align: center;border-bottom:solid #000 2px;border-top:solid #000 2px">YEAR TO DATE</th>                               
            </tr>                        
            <tr>            
                <th class="span2 print2" style="text-align: center;border-bottom:solid #000 2px;border-top:solid #000 2px">ACTUAL</th>            
                <th class="span2 print2" style="text-align: center;border-bottom:solid #000 2px;border-top:solid #000 2px">FORECAST</th>            
                <th class="span2 print2" style="text-align: center;border-bottom:solid #000 2px;border-top:solid #000 2px">LAST MONTH</th>            
                <th class="span2 print2" style="text-align: center;border-bottom:solid #000 2px;border-top:solid #000 2px">ACTUAL</th>                               
                <th class="span2 print2" style="text-align: center;border-bottom:solid #000 2px;border-top:solid #000 2px">FORECAST</th>                               
            </tr>                        
        </thead>

        <?php
        //foodAnalys
        $foodAnalys_today = (!empty($naFoodAnalys)) ? json_decode($naFoodAnalys->today, true) : array();
        $foodAnalys_mtd_actual = (!empty($naFoodAnalys)) ? json_decode($naFoodAnalys->mtd_actual, true) : array();
        $foodAnalys_mtd_forecast = (!empty($naFoodAnalys)) ? json_decode($naFoodAnalys->mtd_forecast, true) : array();
        $foodAnalys_mtd_last_month = (!empty($naFoodAnalys)) ? json_decode($naFoodAnalys->mtd_last_month, true) : array();
        $foodAnalys_ytd_actual = (!empty($naFoodAnalys)) ? json_decode($naFoodAnalys->ytd_actual, true) : array();
        $foodAnalys_ytd_forecast = (!empty($naFoodAnalys)) ? json_decode($naFoodAnalys->ytd_forecast, true) : array();

        //foodPercoverAnalys
        $foodpercoverAnalys_today = (!empty($naFoodpercoverAnalys)) ? json_decode($naFoodpercoverAnalys->today, true) : array();
        $foodpercoverAnalys_mtd_actual = (!empty($naFoodpercoverAnalys)) ? json_decode($naFoodpercoverAnalys->mtd_actual, true) : array();
        $foodpercoverAnalys_mtd_forecast = (!empty($naFoodpercoverAnalys)) ? json_decode($naFoodpercoverAnalys->mtd_forecast, true) : array();
        $foodpercoverAnalys_mtd_last_month = (!empty($naFoodpercoverAnalys)) ? json_decode($naFoodpercoverAnalys->mtd_last_month, true) : array();
        $foodpercoverAnalys_ytd_actual = (!empty($naFoodpercoverAnalys)) ? json_decode($naFoodpercoverAnalys->ytd_actual, true) : array();
        $foodpercoverAnalys_ytd_forecast = (!empty($naFoodpercoverAnalys)) ? json_decode($naFoodpercoverAnalys->ytd_forecast, true) : array();

        //BeverageAnalys
        $beverageAnalys_today = (!empty($naBeverageAnalys)) ? json_decode($naBeverageAnalys->today, true) : array();
        $beverageAnalys_mtd_actual = (!empty($naBeverageAnalys)) ? json_decode($naBeverageAnalys->mtd_actual, true) : array();
        $beverageAnalys_mtd_forecast = (!empty($naBeverageAnalys)) ? json_decode($naBeverageAnalys->mtd_forecast, true) : array();
        $beverageAnalys_mtd_last_month = (!empty($naBeverageAnalys)) ? json_decode($naBeverageAnalys->mtd_last_month, true) : array();
        $beverageAnalys_ytd_actual = (!empty($naBeverageAnalys)) ? json_decode($naBeverageAnalys->ytd_actual, true) : array();
        $beverageAnalys_ytd_forecast = (!empty($naBeverageAnalys)) ? json_decode($naBeverageAnalys->ytd_forecast, true) : array();
        ?>
        <thead>
           
            <tr>  
                
                <th class="print2" colspan="7" style="text-align: left;height: 10px;border-bottom:solid #000 2px !important;border-top:solid #000 2px !important" ><b>FOOD ANALYSIS</b> </th>   
            </tr>
        </thead>
        <?php
        $a0 = $b0 = $c0 = $d0 = $e0 = $f0 = 0;
        $departements = ChargeAdditional::model()->findAll(array('condition' => 'account_id=' . $foodAcc, 'group' => 'charge_additional_category_id'));
        foreach ($departements as $departement) {
            $aa = (isset($foodAnalys_today[$departement->charge_additional_category_id])) ? $foodAnalys_today[$departement->charge_additional_category_id] : 0;
            $bb = (isset($foodAnalys_mtd_actual[$departement->charge_additional_category_id])) ? $foodAnalys_mtd_actual[$departement->charge_additional_category_id] : 0;
            $cc = (isset($foodAnalys_mtd_forecast[$departement->charge_additional_category_id])) ? $foodAnalys_mtd_forecast[$departement->charge_additional_category_id] : 0;
            $dd = (isset($foodAnalys_mtd_last_month[$departement->charge_additional_category_id])) ? $foodAnalys_mtd_last_month[$departement->charge_additional_category_id] : 0;
            $ee = (isset($foodAnalys_ytd_actual[$departement->charge_additional_category_id])) ? $foodAnalys_ytd_actual[$departement->charge_additional_category_id] : 0;
            $ff = (isset($foodAnalys_ytd_forecast[$departement->charge_additional_category_id])) ? $foodAnalys_ytd_forecast[$departement->charge_additional_category_id] : 0;

            echo '<tr>                  
                <td class="print2" style="text-align:left;border-bottom:none;border-top:none">' . $departement->ChargeAdditionalCategory->name . '</td>            
                <td class="print2" style="text-align:right;border-bottom:none;border-top:none">' . landa()->rp($aa, false) . '</td>
                <td class="print2" style="text-align:right;border-bottom:none;border-top:none">' . landa()->rp($bb, false) . '</td>
                <td class="print2" style="text-align:right;border-bottom:none;border-top:none">' . landa()->rp($cc, false) . '</td>
                <td class="print2" style="text-align:right;border-bottom:none;border-top:none">' . landa()->rp($dd, false) . '</td>
                <td class="print2" style="text-align:right;border-bottom:none;border-top:none">' . landa()->rp($ee, false) . '</td>
                <td class="print2" style="text-align:right;border-bottom:none;border-top:none">' . landa()->rp($ff, false) . '</td>
            </tr>';
            $a0+=$aa;
            $b0+=$bb;
            $c0+=$cc;
            $d0+=$dd;
            $e0+=$ee;
            $f0+=$ff;
        }
        ?>

            <tr>            
                <th class="print2" style="text-align: left">TOTAL FOOD</th>            
                <th class="print2" style="text-align:right ">  <?php echo landa()->rp($a0, FALSE); ?></th>            
                <th class="print2" style="text-align:right "><?php echo landa()->rp($b0, false); ?></th>                        
                <th class="print2" style="text-align:right "><?php echo landa()->rp($c0, false); ?></th>                        
                <th class="print2" style="text-align:right "><?php echo landa()->rp($d0, false); ?></th>                        
                <th class="print2" style="text-align:right "><?php echo landa()->rp($e0, false); ?></th>                        
                <th class="print2" style="text-align:right "><?php echo landa()->rp($f0, false); ?></th>            
            </tr> 
        
        <tr><td class="print2" style="text-align:right;border-left:none;border-right:none;" colspan="7" >&nbsp;</td> </tr>
        <thead>
            <tr>  
                
                <th class="print2" colspan="7" style="text-align: left;height: 10px;border-bottom:solid #000 2px !important;border-top:solid #000 2px !important" ><b>FOOD PERCOVER</b> </th>   
            </tr>
        </thead>
        <?php
        $a1 = $b1 = $c1 = $d1 = $e1 = $f1 = 0;
        $departements = ChargeAdditional::model()->findAll(array('condition' => 'account_id=' . $foodAcc, 'group' => 'charge_additional_category_id'));
        foreach ($departements as $departement) {
            $aa = (isset($foodpercoverAnalys_today[$departement->charge_additional_category_id])) ? $foodpercoverAnalys_today[$departement->charge_additional_category_id] : 0;
            $bb = (isset($foodpercoverAnalys_mtd_actual[$departement->charge_additional_category_id])) ? $foodpercoverAnalys_mtd_actual[$departement->charge_additional_category_id] : 0;
            $cc = (isset($foodpercoverAnalys_mtd_forecast[$departement->charge_additional_category_id])) ? $foodpercoverAnalys_mtd_forecast[$departement->charge_additional_category_id] : 0;
            $dd = (isset($foodpercoverAnalys_mtd_last_month[$departement->charge_additional_category_id])) ? $foodpercoverAnalys_mtd_last_month[$departement->charge_additional_category_id] : 0;
            $ee = (isset($foodpercoverAnalys_ytd_actual[$departement->charge_additional_category_id])) ? $foodpercoverAnalys_ytd_actual[$departement->charge_additional_category_id] : 0;
            $ff = (isset($foodpercoverAnalys_ytd_forecast[$departement->charge_additional_category_id])) ? $foodpercoverAnalys_ytd_forecast[$departement->charge_additional_category_id] : 0;

            echo '<tr>                  
                <td class="print2" style="text-align:left;border-bottom:none;border-top:none">' . $departement->ChargeAdditionalCategory->name . '</td>            
                <td class="print2" style="text-align:right;border-bottom:none;border-top:none">' . landa()->rp($aa, false) . '</td>
                <td class="print2" style="text-align:right;border-bottom:none;border-top:none">' . landa()->rp($bb, false) . '</td>
                <td class="print2" style="text-align:right;border-bottom:none;border-top:none">' . landa()->rp($cc, false) . '</td>
                <td class="print2" style="text-align:right;border-bottom:none;border-top:none">' . landa()->rp($dd, false) . '</td>
                <td class="print2" style="text-align:right;border-bottom:none;border-top:none">' . landa()->rp($ee, false) . '</td>
                <td class="print2" style="text-align:right;border-bottom:none;border-top:none">' . landa()->rp($ff, false) . '</td>
            </tr>';
            $a1 += $aa;
            $b1 += $bb;
            $c1 += $cc;
            $d1 += $dd;
            $e1 += $ee;
            $f1 += $ff;
        }
        ?>
        <thead>
            <tr>            
                <th class="print2" style="text-align: left">TTL FOOD/COVER</th>            
                <th class="print2" style="text-align:right ">  <?php echo landa()->rp($a1, FALSE); ?></th>            
                <th class="print2" style="text-align:right "><?php echo landa()->rp($b1, false); ?></th>                        
                <th class="print2" style="text-align:right "><?php echo landa()->rp($c1, false); ?></th>                        
                <th class="print2" style="text-align:right "><?php echo landa()->rp($d1, false); ?></th>                        
                <th class="print2" style="text-align:right "><?php echo landa()->rp($e1, false); ?></th>                        
                <th class="print2" style="text-align:right "><?php echo landa()->rp($f1, false); ?></th>            
            </tr> 
        </thead>
        <tr><td class="print2" style="text-align:right;border-left:none;border-right:none;line-height: 2px;" colspan="7" >&nbsp;</td> </tr>
        <thead>
           
            <tr>  
                
                <th class="print2" colspan="7" style="text-align: left;height: 10px;border-bottom:solid #000 2px !important;border-top:solid #000 2px !important" ><b>AVG FOOD/COVER</b> </th>   
            </tr>
        </thead>
        <?php
        $a = $b = $c = $d = $e = $f = 0;
        $departements = ChargeAdditional::model()->findAll(array('condition' => 'account_id=' . $foodAcc, 'group' => 'charge_additional_category_id'));
        foreach ($departements as $departement) {
            $aa = (($foodpercoverAnalys_today[$departement->charge_additional_category_id] != 0) && ($foodAnalys_today[$departement->charge_additional_category_id] != 0)) ? $foodAnalys_today[$departement->charge_additional_category_id] / $foodpercoverAnalys_today[$departement->charge_additional_category_id] : 0;
            $bb = (($foodpercoverAnalys_mtd_actual[$departement->charge_additional_category_id] != 0) && ($foodAnalys_mtd_actual[$departement->charge_additional_category_id] != 0)) ? $foodAnalys_mtd_actual[$departement->charge_additional_category_id] / $foodpercoverAnalys_mtd_actual[$departement->charge_additional_category_id] : 0;
            $cc = (($foodpercoverAnalys_mtd_forecast[$departement->charge_additional_category_id] != 0) && ($foodAnalys_mtd_forecast[$departement->charge_additional_category_id] != 0)) ? $foodAnalys_mtd_forecast[$departement->charge_additional_category_id] / $foodpercoverAnalys_mtd_forecast[$departement->charge_additional_category_id] : 0;
            $dd = (($foodpercoverAnalys_mtd_last_month[$departement->charge_additional_category_id] != 0) && ($foodAnalys_mtd_last_month[$departement->charge_additional_category_id] != 0) ) ? $foodAnalys_mtd_last_month[$departement->charge_additional_category_id] / $foodpercoverAnalys_mtd_last_month[$departement->charge_additional_category_id] : 0;
            $ee = (($foodpercoverAnalys_ytd_actual[$departement->charge_additional_category_id] != 0) && ($foodAnalys_ytd_actual[$departement->charge_additional_category_id] != 0)) ? $foodAnalys_ytd_actual[$departement->charge_additional_category_id] / $foodpercoverAnalys_ytd_actual[$departement->charge_additional_category_id] : 0;
            $ff = (($foodpercoverAnalys_ytd_forecast[$departement->charge_additional_category_id] != 0) && ($foodAnalys_ytd_forecast[$departement->charge_additional_category_id] != 0)) ? $foodAnalys_ytd_forecast[$departement->charge_additional_category_id] / $foodpercoverAnalys_ytd_forecast[$departement->charge_additional_category_id] : 0;

            echo '<tr>                  
                <td class="print2" style="text-align:left;border-bottom:none;border-top:none">' . $departement->ChargeAdditionalCategory->name . '</td>            
                <td class="print2" style="text-align:right;border-bottom:none;border-top:none">' . landa()->rp($aa, false) . '</td>
                <td class="print2" style="text-align:right;border-bottom:none;border-top:none">' . landa()->rp($bb, false) . '</td>
                <td class="print2" style="text-align:right;border-bottom:none;border-top:none">' . landa()->rp($cc, false) . '</td>
                <td class="print2" style="text-align:right;border-bottom:none;border-top:none">' . landa()->rp($dd, false) . '</td>
                <td class="print2" style="text-align:right;border-bottom:none;border-top:none">' . landa()->rp($ee, false) . '</td>
                <td class="print2" style="text-align:right;border-bottom:none;border-top:none">' . landa()->rp($ff, false) . '</td>
            </tr>';
            $a+=$aa;
            $b+=$bb;
            $c+=$cc;
            $d+=$dd;
            $e+=$ee;
            $f+=$ff;
        }

        $aDiv = ($a1 > 0) ? $a0 / $a1 : 0;
        $bDiv = ($b1 > 0) ? $b0 / $b1 : 0;
        $cDiv = ($c1 > 0) ? $c0 / $c1 : 0;
        $dDiv = ($d1 > 0) ? $d0 / $d1 : 0;
        $eDiv = ($e1 > 0) ? $e0 / $e1 : 0;
        $fDiv = ($f1 > 0) ? $f0 / $f1 : 0;
        ?>        
        <thead>
            <tr>            
                <th style="text-align: left">TTL AVG FOOD/COVER</th>            
                <th style="text-align:right ">  <?php echo landa()->rp($aDiv, FALSE); ?></th>            
                <th style="text-align:right "><?php echo landa()->rp($bDiv, false); ?></th>                        
                <th style="text-align:right "><?php echo landa()->rp($cDiv, false); ?></th>                        
                <th style="text-align:right "><?php echo landa()->rp($dDiv, false); ?></th>                        
                <th style="text-align:right "><?php echo landa()->rp($eDiv, false); ?></th>                        
                <th style="text-align:right "><?php echo landa()->rp($fDiv, false); ?></th>            
            </tr> 
        </thead>
        <tr><td class="print2" style="text-align:right;border-left:none;border-right:none;line-height: 2px;" colspan="7" >&nbsp;</td> </tr>
        <thead>
            
            <tr>  
                
                <th class="print2" colspan="7" style="text-align: left;height: 10px;border-bottom:solid #000 2px !important;border-top:solid #000 2px !important" ><b>BEVERAGE ANALYSIS</b> </th>   
            </tr>
            
        </thead>
        <?php
        $a = $b = $c = $d = $e = $f = 0;
        $departements = ChargeAdditional::model()->findAll(array('condition' => 'account_id=' . $beverageAcc, 'group' => 'charge_additional_category_id'));
        foreach ($departements as $departement) {
            $aa = (isset($beverageAnalys_today[$departement->charge_additional_category_id])) ? $beverageAnalys_today[$departement->charge_additional_category_id] : 0;
            $bb = (isset($beverageAnalys_mtd_actual[$departement->charge_additional_category_id])) ? $beverageAnalys_mtd_actual[$departement->charge_additional_category_id] : 0;
            $cc = (isset($beverageAnalys_mtd_forecast[$departement->charge_additional_category_id])) ? $beverageAnalys_mtd_forecast[$departement->charge_additional_category_id] : 0;
            $dd = (isset($beverageAnalys_mtd_last_month[$departement->charge_additional_category_id])) ? $beverageAnalys_mtd_last_month[$departement->charge_additional_category_id] : 0;
            $ee = (isset($beverageAnalys_ytd_actual[$departement->charge_additional_category_id])) ? $beverageAnalys_ytd_actual[$departement->charge_additional_category_id] : 0;
            $ff = (isset($beverageAnalys_ytd_forecast[$departement->charge_additional_category_id])) ? $beverageAnalys_ytd_forecast[$departement->charge_additional_category_id] : 0;

            echo '<tr>                  
                <td class="print2" style="text-align:left;border-bottom:none;border-top:none">' . $departement->ChargeAdditionalCategory->name . '</td>            
                <td class="print2" style="text-align:right;border-bottom:none;border-top:none">' . landa()->rp($aa, false) . '</td>
                <td class="print2" style="text-align:right;border-bottom:none;border-top:none">' . landa()->rp($bb, false) . '</td>
                <td class="print2" style="text-align:right;border-bottom:none;border-top:none">' . landa()->rp($cc, false) . '</td>
                <td class="print2" style="text-align:right;border-bottom:none;border-top:none">' . landa()->rp($dd, false) . '</td>
                <td class="print2" style="text-align:right;border-bottom:none;border-top:none">' . landa()->rp($ee, false) . '</td>
                <td class="print2" style="text-align:right;border-bottom:none;border-top:none">' . landa()->rp($ff, false) . '</td>
            </tr>';
            $a+=$aa;
            $b+=$bb;
            $c+=$cc;
            $d+=$dd;
            $e+=$ee;
            $f+=$ff;
        }
        ?>
        <thead>
            <tr>            
                <th class="print2" style="text-align: left">TOTAL BEVERAGE</th>            
                <th class="print2" style="text-align:right ">  <?php echo landa()->rp($a, FALSE); ?></th>            
                <th  class="print2"style="text-align:right "><?php echo landa()->rp($b, false); ?></th>                        
                <th class="print2" style="text-align:right "><?php echo landa()->rp($c, false); ?></th>                        
                <th class="print2" style="text-align:right "><?php echo landa()->rp($d, false); ?></th>                        
                <th class="print2" style="text-align:right "><?php echo landa()->rp($e, false); ?></th>                        
                <th class="print2" style="text-align:right "><?php echo landa()->rp($f, false); ?></th>            
            </tr> 
        </thead>
    </table>
    <br>
    <table style="width: 100%">
        <tr>
            <td style="padding: 0px;width: 30%;font-size: 10px" class="span2">Audit By</td>        
            <td style="padding: 0px;font-size: 10px">: <?php echo $model->Cashier->name; ?></td>
        </tr>    
        <tr>
            <td style="padding: 0px;font-size: 10px">Printed Time</td>        
            <td style="padding: 0px;font-size: 10px">: <?php echo date('l d-M-Y H:i:s', strtotime($model->created)); ?></td>
        </tr>    
    </table>

</div>

<style type="text/css">
    @media print
    {
        body {visibility:hidden;}
        .na_analys{visibility: visible;} 
        .na_analys{width: 100%;top: 0px;left: 0px;position: absolute;font-size: 11px !important} 
        .na_analys{-webkit-print-color-adjust:exact;}      

        table.tbPrint2 td, table.tbPrint2 th {
            border: solid #000 2px;
        }
        .tbPrint2 td{
            background: #e8edff; 
            border-bottom: none ;
            border-left: none;
            border-right: none;
            color: #669;
            border-top: none;
        }
/*        .print2 {
            padding: 3px;
            line-height: 10px;
            font-size: 12px;
            vertical-align: middle;
            word-spacing: 1.1pt;
            letter-spacing: 4pt;
            color: #000;
        }*/

    }
</style>
