<?php
$settings = json_decode($siteConfig->settings, true);
$roomAcc = (!empty($settings['room_account'])) ? $settings['room_account'] : '';
$foodAcc = 2;
$beverageAcc = 3;
$breakfastDep = 2;
$rateDolar = (!empty($settings['rate'])) ? $settings['rate'] : 0;
$breakfastAcc = (!empty($settings['fb_account'])) ? $settings['fb_account'] : '';
$arrOtherForecast = Forecast::model()->otherForcast();
foreach ($arrOtherForecast as $dep) {
    $temp[$dep] = 0;
}
?>
<div style="text-align: right">
    <button class="print entypo-icon-printer button" onclick="printDiv('na_cc')" type="button">&nbsp;&nbsp;Print Report</button>
</div>
<div class="na_cc" id="na_cc">
    <center><table width="100%" style="font-size: 11px;line-height: 11px !important;">
            <tr>
                <td style="text-align: center;width: 33%"><?php
                    $img = Yii::app()->landa->urlImg('site/', $siteConfig->client_logo, 1);
                    echo '<img src="' . $img['small'] . '" class="cc logo" width="100"';
                    ?></td>
                <td style="text-align: center;width: 33%"> 
            <center><h4>DAILY SALES REPORT</h4></center>
            </td>
            <td style="text-align: center;width: 33%;padding-left: 70px">
                <table>                   
                    <tr>
                        <td style="padding: 2px">Day</td>
                        <td style="padding: 2px">:</td>
                        <td style="padding: 2px"><?php echo date("l", strtotime($model->date_na)); ?></td>
                    </tr>
                    <tr>
                        <td style="padding: 2px">Date</td>
                        <td style="padding: 2px">:</td>
                        <td style="padding: 2px"><?php echo date("d-M-Y", strtotime($model->date_na)); ?></td>
                    </tr>
                    <tr>
                        <td style="padding: 2px">Weather</td>
                        <td style="padding: 2px">:</td>
                        <td style="padding: 2px"><?php echo ucwords(strtolower($model->weather)) ?></td>
                    </tr>
                    <tr>
                        <td style="padding: 2px">Event</td>
                        <td style="padding: 2px">:</td>
                        <td style="padding: 2px"><?php echo ucwords(strtolower($model->event)) ?></td>
                    </tr>
                </table>
            </td>
            </tr>
            <tr>
                <td style="border-top: 2px solid #bbb;font-size: 10px" colspan="2">Print Out : <?php echo date("d-M-Y H:i:s", strtotime($model->created)); ?>&nbsp; By : <?php echo $model->Cashier->name; ?></td>
                <td style="text-align: right;border-top: 2px solid #bbb;font-size: 10px">$ : <?php echo landa()->rp($model->rate_dollar); ?></td>
            </tr>

        </table> </center>
    <hr style="margin-top:0px;border-top: 1px solid #bbb !important;height: 3px">

    <table class="tbPrint" style="font-size: 10px;line-height: 1px !important;">
        <thead>
            <tr>            
                <th class="span3 print2" rowspan="2" style="text-align: center;border-bottom:solid #000 2px !important;border-top:solid #000 2px">ACCOUNT</th>            
                <th class="span2 print2" rowspan="2" style="text-align: center;border-bottom:solid #000 2px !important;border-top:solid #000 2px">TODAY</th>            
                <th class="print2" colspan="3" style="text-align: center;border-bottom:solid #000 2px !important;border-top:solid #000 2px">MONTH TO DATE</th>            
                <th class="print2" colspan="2" style="text-align: center;border-bottom:solid #000 2px !important;border-top:solid #000 2px">YEAR TO DATE</th>                               
            </tr>                        
            <tr>            
                <th class="span2 print2" style="text-align: center;border-bottom:solid #000 2px !important;border-top:solid #000 2px">ACTUAL</th>            
                <th class="span2 print2" style="text-align: center;border-bottom:solid #000 2px !important;border-top:solid #000 2px">FORECAST</th>            
                <th class="span2 print2" style="text-align: center;border-bottom:solid #000 2px !important;border-top:solid #000 2px">LAST MONTH</th>            
                <th class="span2 print2" style="text-align: center;border-bottom:solid #000 2px !important;border-top:solid #000 2px">ACTUAL</th>                               
                <th class="span2 print2" style="text-align: center;border-bottom:solid #000 2px !important;border-top:solid #000 2px">FORECAST</th>                               
            </tr>                        
        </thead>
        <tbody>
            <?php
            $today = (!empty($naDsr)) ? json_decode($naDsr->today, true) : array();
            $mtd_actual = (!empty($naDsr)) ? json_decode($naDsr->mtd_actual, true) : array();
            $mtd_forecast = (!empty($naDsr)) ? json_decode($naDsr->mtd_forecast, true) : array();
            $mtd_last_month = (!empty($naDsr)) ? json_decode($naDsr->mtd_last_month, true) : array();
            $ytd_actual = (!empty($naDsr)) ? json_decode($naDsr->ytd_actual, true) : array();
            $ytd_forecast = (!empty($naDsr)) ? json_decode($naDsr->ytd_forecast, true) : array();
            $taxService = (!empty($naDsr)) ? json_decode($naDsr->tax_service, true) : array();

            //statistic
            $statistic_today = (!empty($naStatistic)) ? json_decode($naStatistic->today, true) : $temp;
            $statistic_mtd_actual = (!empty($naStatistic)) ? json_decode($naStatistic->mtd_actual, true) : $temp;
            $statistic_mtd_forecast = (!empty($naStatistic)) ? json_decode($naStatistic->mtd_forecast, true) : $temp;
            $statistic_mtd_last_month = (!empty($naStatistic)) ? json_decode($naStatistic->mtd_last_month, true) : $temp;
            $statistic_ytd_actual = (!empty($naStatistic)) ? json_decode($naStatistic->ytd_actual, true) : $temp;
            $statistic_ytd_forecast = (!empty($naStatistic)) ? json_decode($naStatistic->ytd_forecast, true) : $temp;

            $totalDay = 0;
            $totalMtdActual = 0;
            $totalMtdForecast = 0;
            $totalMtdLastMonth = 0;
            $totalYtdActual = 0;
            $totalYtdForecast = 0;

            $netDay = 0;
            $netMtdActual = 0;
            $netMtdForecast = 0;
            $netMtdLastMonth = 0;
            $netYtdActual = 0;
            $netYtdForecast = 0;

            $serviceDay = 0;
            $serviceMtdActual = 0;
            $serviceMtdForecast = 0;
            $serviceMtdLastMonth = 0;
            $serviceYtdActual = 0;
            $serviceYtdForecast = 0;

            $taxDay = 0;
            $taxMtdActual = 0;
            $taxMtdForecast = 0;
            $taxMtdLastMonth = 0;
            $taxYtdActual = 0;
            $taxYtdForecast = 0;

            foreach ($account as $acc) {
                $net = (isset($taxService[$acc->id]['tax'])) ? (($taxService[$acc->id]['tax'] + $taxService[$acc->id]['service']) / 100) + 1 : 0;
                $netService = (isset($taxService[$acc->id]['tax'])) ? (($taxService[$acc->id]['service']) / 100) + 1 : 0;
                $netTax = (isset($taxService[$acc->id]['tax'])) ? (($taxService[$acc->id]['tax']) / 100) + 1 : 0;

                $vToday = (isset($today[$acc->id])) ? $today[$acc->id] : 0;
                $vMtd_actual = (isset($mtd_actual[$acc->id])) ? $mtd_actual[$acc->id] : 0;
                $vMtd_Forecast = (isset($mtd_forecast[$acc->id])) ? $mtd_forecast[$acc->id] : 0;
                $vMtd_last_month = (isset($mtd_last_month[$acc->id])) ? $mtd_last_month[$acc->id] : 0;
                $vYtd_actual = (isset($ytd_actual[$acc->id])) ? $ytd_actual[$acc->id] : 0;
                $vYtd_forecast = (isset($ytd_forecast[$acc->id])) ? $ytd_forecast[$acc->id] : 0;

                echo '
                <tr>
                <td class="print2" style="text-align:left;border-bottom:none;border-top:none;">              
                ' . strtoupper($acc->name) . '
                </td>
                <td class="print2" style="text-align:right;border-bottom:none;border-top:none;">' . landa()->rp($vToday / $net, false) . '</td>
                <td class="print2" style="text-align:right;border-bottom:none;border-top:none;">' . landa()->rp($vMtd_actual / $net, false) . '</td>
                <td class="print2" style="text-align:right;border-bottom:none;border-top:none;">' . landa()->rp($vMtd_Forecast, false) . '</td>
                <td class="print2" style="text-align:right;border-bottom:none;border-top:none;">' . landa()->rp($vMtd_last_month / $net, false) . '</td>
                <td class="print2" style="text-align:right;border-bottom:none;border-top:none;">' . landa()->rp($vYtd_actual / $net, false) . '</td>
                <td class="print2" style="text-align:right;border-bottom:none;border-top:none;">' . landa()->rp($vYtd_forecast, false) . '</td>
                </tr>
            ';
                $tax = (isset($taxService[$acc->id]['tax'])) ? $taxService[$acc->id]['tax'] : 0;
                $service = (isset($taxService[$acc->id]['service'])) ? $taxService[$acc->id]['service'] : 0;

                $totalDay += $vToday;
                $totalMtdActual += $vMtd_actual;
                $totalMtdForecast += $vMtd_Forecast;
                $totalMtdLastMonth += $vMtd_last_month;
                $totalYtdActual += $vYtd_actual;
                $totalYtdForecast += $vYtd_forecast;

                $netDay += $vToday / $net;
                $netMtdActual += $vMtd_actual / $net;
                $netMtdForecast += $vMtd_Forecast;
                $netMtdLastMonth += $vMtd_last_month / $net;
                $netYtdActual += $vYtd_actual / $net;
                $netYtdForecast += $vYtd_forecast;

                $serviceDay += ($service / 100) * ($vToday / $net);
                $serviceMtdActual += ($service / 100) * ($vMtd_actual / $net);
                $serviceMtdForecast += ($service / 100) * ($vMtd_Forecast );
                $serviceMtdLastMonth += ($service / 100) * ($vMtd_last_month / $net);
                $serviceYtdActual += ($service / 100) * ($vYtd_actual / $net);
                $serviceYtdForecast += ($service / 100) * ($vYtd_forecast );

                $taxDay += ($tax / 100) * ($vToday / $net);
                $taxMtdActual += ($tax / 100) * ($vMtd_actual / $net);
                $taxMtdForecast += ($tax / 100) * ($vMtd_Forecast );
                $taxMtdLastMonth += ($tax / 100) * ($vMtd_last_month / $net );
                $taxYtdActual += ($tax / 100) * ($vYtd_actual / $net);
                $taxYtdForecast += ($tax / 100) * ($vYtd_forecast );
            }
            ?>
        </tbody>
        <thead>
            <tr>            
                <th class="print2" style="text-align: left;">NET SALES</th>            
                <th class="print2" style="text-align: right;"><?php echo landa()->rp($netDay, false); ?></th>            
                <th class="print2" style="text-align: right;"><?php echo landa()->rp($netMtdActual, false); ?></th>            
                <th class="print2" style="text-align: right;"><?php echo landa()->rp($netMtdForecast, false); ?></th>            
                <th class="print2" style="text-align: right;"><?php echo landa()->rp($netMtdLastMonth, false); ?></th>                               
                <th class="print2" style="text-align: right;"><?php echo landa()->rp($netYtdActual, false); ?></th>                               
                <th class="print2" style="text-align: right;"><?php echo landa()->rp($netYtdForecast, false); ?></th>            
            </tr>        
        </thead>
        <tbody>
            <tr>
                <td class="print2" style="text-align:left;border-bottom:none;border-top:none">SERVICE CHARGE</td>
                <td class="print2" style="text-align:right;border-bottom:none;border-top:none"><?php echo landa()->rp($serviceDay, false); ?></td>
                <td class="print2" style="text-align:right;border-bottom:none;border-top:none"><?php echo landa()->rp($serviceMtdActual, false); ?></td>                
                <td class="print2" style="text-align:right;border-bottom:none;border-top:none" ><?php echo landa()->rp($serviceMtdForecast, false); ?></td>                                
                <td class="print2" style="text-align:right;border-bottom:none;border-top:none"><?php echo landa()->rp($serviceMtdLastMonth, false); ?></td>                                
                <td class="print2" style="text-align:right;border-bottom:none;border-top:none"><?php echo landa()->rp($serviceYtdActual, false); ?></td>                                
                <td class="print2" style="text-align:right;border-bottom:none;border-top:none"><?php echo landa()->rp($serviceYtdForecast, false); ?></td>
            </tr>
            <tr>
                <td class="print2" style="text-align:left;border-bottom:none;border-top:none">TAX</td>
                <td class="print2" style="text-align:right;border-bottom:none;border-top:none"><?php echo landa()->rp($taxDay, false); ?></td>
                <td class="print2" style="text-align:right;border-bottom:none;border-top:none"><?php echo landa()->rp($taxMtdActual, false); ?></td>                
                <td class="print2" style="text-align:right;border-bottom:none;border-top:none"><?php echo landa()->rp($taxMtdForecast, false); ?></td>                
                <td class="print2" style="text-align:right;border-bottom:none;border-top:none"><?php echo landa()->rp($taxMtdLastMonth, false); ?></td>                
                <td class="print2" style="text-align:right;border-bottom:none;border-top:none"><?php echo landa()->rp($taxYtdActual, false); ?></td>                
                <td class="print2" style="text-align:right;border-bottom:none;border-top:none"><?php echo landa()->rp($taxYtdForecast, false); ?></td>
            </tr>
        </tbody>
        <thead>
            <tr>            
                <th style="text-align: left" class="print2">GROSS SALES</th>            
                <th style="text-align: right" class="print2"><?php echo landa()->rp($totalDay, false); ?></th>            
                <th style="text-align: right" class="print2"><?php echo landa()->rp($totalMtdActual, false); ?></th>                                   
                <th style="text-align: right" class="print2"><?php echo landa()->rp($totalMtdForecast + $serviceMtdForecast + $taxMtdForecast, false); ?></th>                                                     
                <th style="text-align: right" class="print2"><?php echo landa()->rp($totalMtdLastMonth, false); ?></th>                                                     
                <th style="text-align: right" class="print2"><?php echo landa()->rp($totalYtdActual, false); ?></th>                                                     
                <th style="text-align: right" class="print2"><?php echo landa()->rp($totalYtdForecast + $serviceYtdForecast + $taxYtdForecast, false); ?></th>            
            </tr> 
            </thead>
            <tbody>
            <tr><td class="print2" style="text-align:right;border-left:none;border-right:none;" colspan="7" >&nbsp;</td> </tr>
            <tr>  

                <th class="print2" colspan="7" style="text-align: left;height: 10px;border-bottom:solid #000 2px !important;border-top:solid #000 2px !important" ><b>STATISTIC</b> </th>   
            </tr>
            </tbody>
        


        <?php
        $arrOtherForecast = Forecast::model()->otherForcast();
        foreach ($arrOtherForecast as $statistic) {
            if ($statistic == 'percentage of occupancy' || $statistic == 'avg room rate dolar' || $statistic == 'percentage of double occupancy' || $statistic == 'sales coeficient') {
                $key = $statistic;
                if ($key == 'sales coeficient') {
                    $key = 'sales coefficient';
                } elseif ($key == 'percentage of occupancy') {
                    $key = '% of occupancy';
                } elseif ($key == 'percentage of double occupancy') {
                    $key = '% of double occupancy';
                }
                echo '
                <tr>
                <td class="print2" style="text-align:left;border-bottom:none;border-top:none">              
                ' . strtoupper($key) . '
                </td>
                <td class="print2" style="text-align:right;border-bottom:none;border-top:none">' . landa()->rp($statistic_today[$statistic], false, 2) . '</td>
                <td class="print2" style="text-align:right;border-bottom:none;border-top:none">' . landa()->rp($statistic_mtd_actual[$statistic], false, 2) . '</td>
                <td class="print2" style="text-align:right;border-bottom:none;border-top:none">' . landa()->rp($statistic_mtd_forecast[$statistic], false, 2) . '</td>
                <td class="print2" style="text-align:right;border-bottom:none;border-top:none">' . landa()->rp($statistic_mtd_last_month[$statistic], false, 2) . '</td>
                <td class="print2" style="text-align:right;border-bottom:none;border-top:none">' . landa()->rp($statistic_ytd_actual[$statistic], false, 2) . '</td>
                <td class="print2" style="text-align:right;border-bottom:none;border-top:none">' . landa()->rp($statistic_ytd_forecast[$statistic], false, 2) . '</td>
                </tr>
            ';
            } elseif ($statistic == 'avg room rate rupiah') {

                echo '
                <tr>
                <td class="print2" style="text-align:left;border-bottom:none;border-top:none">              
                ' . strtoupper($statistic) . '
                </td>
                <td class="print2" style="text-align:right;border-bottom:none;border-top:none">' . landa()->rp($statistic_today[$statistic], false, 0) . '</td>
                <td class="print2" style="text-align:right;border-bottom:none;border-top:none">' . landa()->rp($statistic_mtd_actual[$statistic], false, 0) . '</td>
                <td class="print2" style="text-align:right;border-bottom:none;border-top:none">' . landa()->rp($statistic_mtd_forecast[$statistic], false, 0) . '</td>
                <td class="print2" style="text-align:right;border-bottom:none;border-top:none">' . landa()->rp($statistic_mtd_last_month[$statistic], false, 0) . '</td>
                <td class="print2" style="text-align:right;border-bottom:none;border-top:none">' . landa()->rp($statistic_ytd_actual[$statistic], false, 0) . '</td>
                <td class="print2" style="text-align:right;border-bottom:none;border-top:none">' . landa()->rp($statistic_ytd_forecast[$statistic], false, 0) . '</td>
                </tr>
            ';
            } else {
                echo '
                <tr>
                <td class="print2" style="text-align:left;border-bottom:none;border-top:none">              
                ' . strtoupper($statistic) . '
                </td>
                <td class="print2" style="text-align:right;border-bottom:none;border-top:none">' . landa()->rp($statistic_today[$statistic], false) . '</td>
                <td class="print2" style="text-align:right;border-bottom:none;border-top:none">' . landa()->rp($statistic_mtd_actual[$statistic], false) . '</td>
                <td class="print2" style="text-align:right;border-bottom:none;border-top:none">' . landa()->rp($statistic_mtd_forecast[$statistic], false) . '</td>
                <td class="print2" style="text-align:right;border-bottom:none;border-top:none">' . landa()->rp($statistic_mtd_last_month[$statistic], false) . '</td>
                <td class="print2" style="text-align:right;border-bottom:none;border-top:none">' . landa()->rp($statistic_ytd_actual[$statistic], false) . '</td>
                <td class="print2" style="text-align:right;border-bottom:none;border-top:none">' . landa()->rp($statistic_ytd_forecast[$statistic], false) . '</td>
                </tr>
            ';
            }
        }
        ?>
        <tr>            
            <th colspan="7" rowspan="2" class="" style="text-align: center;border-bottom:none;border-left:none;border-right:none;border-top:solid #000 2px"> </th>   
        </tr>
    </table>
    <center>
        <table width="100%"  style="font-size: 10px;line-height: 9px !important;border: none"> 
            <tr>                
                <td class="print2"  style="text-align: center;font-size: 10px;">Prepared By</td>
                <td class="print2"  style="text-align: center;font-size: 10px">Approved By</td>
                <td class="print2"  style="text-align: center;font-size: 10px">Acknowledged By</td>
            </tr>
            <tr>                
                <td class="print2"  style="text-align: center;font-size: 10px"><br/><br><b>Income Audit</b></td>
                <td class="print2"  style="text-align: center;font-size: 10px"><br/><br><b>Chief Accounting</b></td>
                <td class="print2"  style="text-align: center;font-size: 10px"><br/><br><b>General Manager</b></td>
            </tr>
        </table>
    </center>

</div>

<style type="text/css">
    @media print
    {
        .na_cc .cc{width: 120px}        
        /*body {visibility:hidden;}*/
        .na_cc{visibility: visible;} 
        .na_cc{width: 100%;top: 0px;left: 0px;position: absolute;font-size: 9px !important} 

        .logo{
            width: 150px !important;
            height: 80px;
        }
        table.tbPrint td, table.tbPrint th {
             border: none; 
        }
        

    }
</style>

