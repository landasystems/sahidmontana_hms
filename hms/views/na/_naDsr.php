<?php
//from siteconfig        
$settings = json_decode($siteConfig->settings, true);
$roomAcc = (!empty($settings['room_account'])) ? $settings['room_account'] : '';
$foodAcc = 2;
$beverageAcc = 3;
$breakfastDep = 2;
$rateDolar = (!empty($settings['rate'])) ? $settings['rate'] : 1;
$breakfastAcc = (!empty($settings['fb_account'])) ? $settings['fb_account'] : '';
$month_system = date('j', strtotime($siteConfig->date_system));
$date_na = date('Y-m-d', strtotime($siteConfig->date_system));
$initial_dsr = json_decode($initialForecast->dsr, true);
$initial_statistic = json_decode($initialForecast->statistik, true);
?>
<center><div class="row-fluid">
        <div class="span3" style="text-align: center">
            <?php
            $img = Yii::app()->landa->urlImg('site/', $siteConfig->client_logo, param('id'));
            echo '<img src="' . $img['small'] . '" class="" style="width:40%"/>';
            ?>
        </div>
        <div class="span5" style="">
            <br>
            <center><h3>DAILY SALES REPORT</h3></center>
            <center>Date Night Audit : <?php echo date("l, d-M-Y", strtotime($siteConfig->date_system)); ?> 
                <br/>
                Printed Time : <?php echo date('l, d-M-Y H:i'); ?>
            </center>
        </div>
        <div class="span4">
            <table>
                <tr>
                    <td style="padding: 2px">Rate Dollar</td>
                    <td style="padding: 2px">:</td>
                    <td style="padding: 2px"><?php echo landa()->rp($rateDolar) ?></td>
                </tr>
                <tr>
                    <td style="padding: 2px">Weather</td>
                    <td style="padding: 2px">:</td>
                    <td style="padding: 2px"><input type="text" placeholder="Type weather now . ." name="weather"></td>
                </tr>
                <tr>
                    <td style="padding: 2px">Event</td>
                    <td style="padding: 2px">:</td>
                    <td style="padding: 2px"><input type="text" placeholder="Type event here . ." name="event"></td>
                </tr>
            </table>


        </div>
    </div></center>


<hr> 
<table class="items table table-bordered table-condensed">
    <thead>
        <tr>            
            <th class="span3" rowspan="2" style="text-align: center">ACCOUNT</th>            
            <th class="span2" rowspan="2" style="text-align: center">TODAY</th>            
            <th colspan="3" style="text-align: center">MONTH TO DATE</th>            
            <th colspan="2" style="text-align: center">YEAR TO DATE</th>                               
        </tr>                        
        <tr>            
            <th class="span2" style="text-align: center">ACTUAL</th>            
            <th class="span2" style="text-align: center">FORECAST</th>            
            <th class="span2" style="text-align: center">LAST MONTH</th>            
            <th class="span2" style="text-align: center">ACTUAL</th>                               
            <th class="span2" style="text-align: center">FORECAST</th>                               
        </tr>                        
    </thead>
    <tbody>
        <?php
        if (!empty($forecast)) {
            $masterForecast = json_decode($forecast->forecast, true);
        }
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

        $totalDay = 0;
        $totalMtdActual = 0;
        $totalMtdForecast = 0;
        $totalMtdLastMonth = 0;
        $totalYtdActual = 0;
        $totalYtdForecast = 0;
        foreach ($category as $departement) {
            $totalNetFood[$departement->id] = 0;
            $totalNetBeverage [$departement->id] = 0;
        }


        //LOAD DATA FROM YESTERDAY NA (DSR & STATISTIK)

        $na_thisyear = NaDsr::model()->with('Na')->find(array('condition' => 'YEAR(Na.date_na) = YEAR("' . $date_na . '")', 'order' => 't.created DESC'));
        $na_thismonth = NaDsr::model()->with('Na')->find(array('condition' => 'MONTH(Na.date_na) = MONTH("' . $date_na . '") and YEAR(Na.date_na) = YEAR("' . $date_na . '")', 'order' => 't.created DESC'));
        $na_lastmonth = NaDsr::model()->with('Na')->find(array('condition' => 'Na.date_na = DATE("' . $date_na . '" - INTERVAL 1 MONTH)', 'order' => 't.created DESC'));

        $na_statistic_thisyear = NaStatistic::model()->with('Na')->find(array('condition' => 'YEAR(Na.date_na) = YEAR("' . $date_na . '")', 'order' => 't.created DESC'));
        $na_statistic_thismonth = NaStatistic::model()->with('Na')->find(array('condition' => 'MONTH(Na.date_na) = MONTH("' . $date_na . '") and YEAR(Na.date_na) = YEAR("' . $date_na . '")', 'order' => 't.created DESC'));
        $na_statistic_lastmonth = NaStatistic::model()->with('Na')->find(array('condition' => 'Na.date_na = DATE("' . $date_na . '" - INTERVAL 1 MONTH)', 'order' => 't.created DESC'));

        $ytd_actual = (!empty($na_thisyear)) ? json_decode($na_thisyear->ytd_actual, true) : array();
        $mtd_actual = (!empty($na_thismonth)) ? json_decode($na_thismonth->mtd_actual, true) : array();
        $lastmonth = (!empty($na_statistic_lastmonth)) ? json_decode($na_lastmonth->mtd_actual, true) : array();

        $other_ytd_actual = (!empty($na_statistic_thisyear)) ? json_decode($na_statistic_thisyear->ytd_actual, true) : array();
        $other_mtd_actual = (!empty($na_statistic_thismonth)) ? json_decode($na_statistic_thismonth->mtd_actual, true) : array();
        $other_lastmonth = (!empty($na_statistic_lastmonth)) ? json_decode($na_statistic_lastmonth->mtd_actual, true) : array();

        $roomOccupied = $roomTodayStatus['occupied'];
        $compliment = $roomTodayStatus['compliment'];
        $houseuse = $roomTodayStatus['house use'];

        foreach ($account as $acc) {
//            trace($acc);
            $today = 0;
            $forecastNow = 0;
            $forecastYear = 0;

//            $totalRoomRate = 0;
            $numberOfGuest = 0;
            foreach ($roomBills as $data) {
                //roomBill         
                if ($data->is_checkedout == 0 && $data->is_na == 0 || ($data->is_checkedout == 1 && $data->date_bill == $siteConfig->date_system)) {
                    $numberOfGuest += $data->pax;
//                    $roomOccupied++;
                    $pax = (!empty($data->pax)) ? $data->pax : 0;
                    $extrabed = (!empty($data->extrabed)) ? $data->extrabed : 0;
                    $extrabed_price = (!empty($data->extrabed_price)) ? $data->extrabed_price : 0;
                    $fnb_price = (!empty($data->fnb_price)) ? $data->fnb_price : 0;
//                    $totalRoomRate += $data->room_price + ($extrabed_price * $extrabed);
                    if ($acc->id == $breakfastAcc) {
                        $today += $pax * $fnb_price;
                        $totalNetFood[$breakfastDep] += $pax * $fnb_price;
                    }

                    if ($acc->id == $roomAcc) {
                        $today += $extrabed * $extrabed_price;
                        $today += $data->room_price;
                    }

//                    if ($data->package_room_type_id != 0) {
//                        $package = json_decode($roomType[$data->package_room_type_id]['charge_additional_ids']);
//                        if (!empty($package)) {
//                            foreach ($package as $mPackage) {
//                                if ($acc->id == $additional[$mPackage->id]['account_id']) {
//                                    $today += $mPackage->amount * $mPackage->charge;
//                                }
//                                if ($acc->id == $additional[$mPackage->id]['account_id'] && $foodAcc == $additional[$mPackage->id]['account_id']) {
//                                    $totalNetFood[$additional[$mPackage->id]['charge_additional_category_id']] += $mPackage->amount * $mPackage->charge;
//                                }
//                                if ($acc->id == $additional[$mPackage->id]['account_id'] && $beverageAcc == $additional[$mPackage->id]['account_id']) {
//                                    $totalNetBeverage[$additional[$mPackage->id]['charge_additional_category_id']] += $mPackage->amount * $mPackage->charge;
//                                }
//                            }
//                        }
//                    }

                    if ($data->others_include != '') {
                        $others_include = json_decode($data->others_include);
                        foreach ($others_include as $key => $mInclude) {
                            $tuyul = ChargeAdditional::model()->findByPk($key);
                            if ($acc->id == $tuyul->account_id) {
                                $today += $mInclude;
                            }
                            if ($acc->id == $tuyul->account_id && $foodAcc == $tuyul->account_id) {
                                $totalNetFood[$tuyul->charge_additional_category_id] += $mInclude;
                            }
                            if ($acc->id == $tuyul->account_id && $beverageAcc == $tuyul->account_id) {
                                $totalNetBeverage[$tuyul->charge_additional_category_id] += $mInclude;
                            }
                        }
                    }
                }
            }


            //forecast
            if (!empty($forecast)) {
                $dep = ChargeAdditional::model()->findAll(array('condition' => 'account_id=' . $acc->id, 'group' => 'charge_additional_category_id'));
                if (count($dep) > 0) {
                    foreach ($masterForecast[$acc->id] as $key => $msF) {
                        for ($i = 1; $i < 13; $i++) {
                            if ($i == date('n', strtotime($siteConfig->date_system))) {
                                $forecastNow += $masterForecast[$acc->id][$key][$i];
                            } elseif ($i < date('n', strtotime($siteConfig->date_system))) {
                                $forecastYear += $masterForecast[$acc->id][$key][$i];
                            }
                        }
                    }
                } else {
                    for ($i = 1; $i < 13; $i++) {
                        if ($i == date('n', strtotime($siteConfig->date_system))) {
                            $forecastNow = $masterForecast[$acc->id][date('n', strtotime($siteConfig->date_system))];
                        } elseif ($i < date('n', strtotime($siteConfig->date_system))) {
                            $forecastYear += $masterForecast[$acc->id][$i];
                        }
                    }
                }

                $forecastNow = ($forecastNow / date('t', strtotime($siteConfig->date_system))) * date('d', strtotime($siteConfig->date_system));
                $forecastYear += $forecastNow;
            }

            //transaction / account                 
            foreach ($billCharge as $charge) {
                $detCharge = BillChargeDet::model()->findAll(array('condition' => 'bill_charge_id=' . $charge->id . ' and deposite_id=0'));
                foreach ($detCharge as $det) {
                    $totCharge = $det->netTotal;
                    if ($acc->id == $det->Additional->account_id) {
                        $today += $totCharge;
                    }
                    if ($acc->id == $det->Additional->account_id && $foodAcc == $det->Additional->account_id) {
                        $totalNetFood[$det->Additional->charge_additional_category_id] += $totCharge;
                    }
                    if ($acc->id == $det->Additional->account_id && $beverageAcc == $det->Additional->account_id) {
                        $totalNetBeverage[$det->Additional->charge_additional_category_id] += $totCharge;
                    }
                }
            }



            $net = (($acc->tax + $acc->service) / 100) + 1;
            $netService = ($acc->service != 0) ? (($acc->service) / 100) + 1 : 0;
            $netTax = ($acc->tax != 0) ? (($acc->tax) / 100) + 1 : 0;

            $mtd_ac = (isset($mtd_actual[$acc->id])) ? $mtd_actual[$acc->id] + $today + ($initial_dsr[$acc->id]['monthToDate'] * $net) : $today + ($initial_dsr[$acc->id]['monthToDate'] * $net);
            $mtd_la = (isset($lastmonth[$acc->id])) ? $lastmonth[$acc->id] + ($initial_dsr[$acc->id]['lastMonth'][$month_system] * $net) : 0 + ($initial_dsr[$acc->id]['lastMonth'][$month_system] * $net );
            $ytd_ac = (isset($ytd_actual[$acc->id])) ? $ytd_actual[$acc->id] + $today + ($initial_dsr[$acc->id]['yearToDate'] * $net) : ($initial_dsr[$acc->id]['yearToDate'] * $net) + $today;

            $netDay += $today / $net;
            $netMtdActual += ($mtd_ac / $net);
            $netMtdForecast += $forecastNow;
            $netMtdLastMonth += $mtd_la / $net;
            $netYtdActual += $ytd_ac / $net;
            $netYtdForecast += $forecastYear;

            $serviceDay += ($acc->service != 0) ? ($acc->service / 100) * ($today / $net) : 0;
            $serviceMtdActual += ($acc->service != 0) ? ($acc->service / 100) * ($mtd_ac / $net) : 0;
            $serviceMtdForecast += ($acc->service != 0) ? ($acc->service / 100) * ($forecastNow) : 0;
            $serviceMtdLastMonth += ($acc->service != 0) ? ($acc->service / 100) * ($mtd_la / $net) : 0;
            $serviceYtdActual += ($acc->service != 0) ? ($acc->service / 100) * ($ytd_ac / $net) : 0;
            $serviceYtdForecast += ($acc->service != 0) ? ($acc->service / 100) * ($forecastYear) : 0;

            $taxDay += ($acc->tax != 0) ? ($acc->tax / 100) * ($today / $net) : 0;
            $taxMtdActual += ($acc->tax != 0) ? ($acc->tax / 100) * ($mtd_ac / $net) : 0;
            $taxMtdForecast += ($acc->tax != 0) ? ($acc->tax / 100) * ($forecastNow) : 0;
            $taxMtdLastMonth += ($acc->tax != 0) ? ($acc->tax / 100) * ($mtd_la / $net) : 0;
            $taxYtdActual += ($acc->tax != 0) ? ($acc->tax / 100) * ($ytd_ac / $net) : 0;
            $taxYtdForecast += ($acc->tax != 0) ? ($acc->tax / 100) * ($forecastYear) : 0;

            $totalDay += $today;
            $totalMtdActual +=$mtd_ac;
            $totalMtdForecast += $forecastNow;
            $totalMtdLastMonth += $mtd_la;
            $totalYtdActual += $ytd_ac;
            $totalYtdForecast += $forecastYear;

            if ($acc->id == $roomAcc) {
                $totalRoomRate = $netDay;
            }
//            echo $totalRoomRate . '---';
            //fill data to array
            $totalNetEachDsr[$acc->id]['today'] = $today / $net;
            $totalNetEachDsr[$acc->id]['mtd_actual'] = $mtd_ac / $net;
            $totalNetEachDsr[$acc->id]['mtd_forecast'] = $forecastNow;
            $totalNetEachDsr[$acc->id]['lastmonth'] = $mtd_la / $net;
            $totalNetEachDsr[$acc->id]['ytd_actual'] = $ytd_ac / $net;
            $totalNetEachDsr[$acc->id]['ytd_forecast'] = $forecastYear;

            echo '
                <tr>
                <td>
                <input type="hidden" name="today[' . $acc->id . ']" value="' . $today . '" />                 
                <input type="hidden" name="mtd_actual[' . $acc->id . ']" value="' . $mtd_ac . '" />                     
                <input type="hidden" name="mtd_forecast[' . $acc->id . ']" value="' . $forecastNow . '" />
                <input type="hidden" name="mtd_last_month[' . $acc->id . ']" value="' . $mtd_la . '" />                    
                <input type="hidden" name="ytd_actual[' . $acc->id . ']" value="' . $ytd_ac . '" />                      
                <input type="hidden" name="ytd_forecast[' . $acc->id . ']" value="' . $forecastYear . '" />
                ' . strtoupper($acc->name) . '
                </td>
                <td style="text-align:right"><input type="hidden" name="todayNet[' . $acc->id . ']" value="' . str_replace(",", "", number_format(($today / $net), 0)) . '">' . landa()->rp($today / $net, false) . '</td>
                <td style="text-align:right">' . landa()->rp($mtd_ac / $net, false) . '</td>
                <td style="text-align:right">' . landa()->rp($forecastNow, false) . '</td>
                <td style="text-align:right">' . landa()->rp($mtd_la / $net, false) . '</td>
                <td style="text-align:right">' . landa()->rp($ytd_ac / $net, false) . '</td>
                <td style="text-align:right">' . landa()->rp($forecastYear, false) . '</td>
                </tr>
            ';
        }
        ?>

        <tr style="font-weight: bold;background: khaki">            
            <td style="text-align: left">NET SALES</td>            
            <td style="text-align:right ">  <?php echo landa()->rp($netDay, false); ?> </td>            
            <td style="text-align:right "><?php echo landa()->rp($netMtdActual, false); ?></td>                                 
            <td style="text-align:right "> <?php echo landa()->rp($netMtdForecast, false); ?></td>            
            <td style="text-align:right "><?php echo landa()->rp($netMtdLastMonth, false); ?></td>            
            <td style="text-align:right "> <?php echo landa()->rp($netYtdActual, false); ?></td>                        
            <td style="text-align:right "> <?php echo landa()->rp($netYtdForecast, false); ?></td>            
        </tr>       
        <?php
        //fill data to array
        $totalNetEachDsr['net']['today'] = $netDay;
        $totalNetEachDsr['net']['mtd_actual'] = $netMtdActual;
        $totalNetEachDsr['net']['mtd_forecast'] = $netMtdForecast;
        $totalNetEachDsr['net']['lastmonth'] = $netMtdLastMonth;
        $totalNetEachDsr['net']['ytd_actual'] = $netYtdActual;
        $totalNetEachDsr['net']['ytd_forecast'] = $netYtdForecast;
        ?>

        <tr>
            <td>SERVICE CHARGE</td>
            <td style="text-align: right"><input type="hidden" name="servisDay" value="<?php echo str_replace(",", "", number_format($serviceDay, 0)) ?>"><?php echo landa()->rp($serviceDay, false); ?></td>
            <td style="text-align: right"><?php echo landa()->rp($serviceMtdActual, false); ?> </td>            
            <td style="text-align: right"><?php echo landa()->rp($serviceMtdForecast, false); ?></td>            
            <td style="text-align: right"> <?php echo landa()->rp($serviceMtdLastMonth, false); ?></td>            
            <td style="text-align: right"> <?php echo landa()->rp($serviceYtdActual, false); ?></td>            
            <td style="text-align: right"> <?php echo landa()->rp($serviceYtdForecast, false); ?></td>
        </tr>
        <tr>
            <td>TAX</td>
            <td style="text-align: right"><input type="hidden" name="tax" value="<?php echo str_replace(",", "", number_format($taxDay, 0)) ?>"><?php echo landa()->rp($taxDay, false); ?></td>            
            <td style="text-align: right"><?php echo landa()->rp($taxMtdActual, false); ?></td>                                    
            <td style="text-align: right"><?php echo landa()->rp($taxMtdForecast, false); ?></td>
            <td style="text-align: right"><?php echo landa()->rp($taxMtdLastMonth, false); ?> </td>
            <td style="text-align: right"><?php echo landa()->rp($taxYtdActual, false); ?></td>            
            <td style="text-align: right"> <?php echo landa()->rp($taxYtdForecast, false); ?></td>
        </tr>

        <tr style="font-weight: bold;background: khaki">            
            <th style="text-align: left">GROSS SALES</th>            
            <th style="text-align:right "><input type="hidden" name="grossSales" value="<?php echo $totalDay ?>"><?php echo landa()->rp($totalDay, FALSE); ?></th>            
            <th style="text-align:right "><?php echo landa()->rp($totalMtdActual, false); ?></th>                        
            <th style="text-align:right "><?php echo landa()->rp($totalMtdForecast + $serviceMtdForecast + $taxMtdForecast, false); ?></th>                        
            <th style="text-align:right "><?php echo landa()->rp($totalMtdLastMonth, false); ?></th>                        
            <th style="text-align:right "><?php echo landa()->rp($totalYtdActual, false); ?></th>                        
            <th style="text-align:right ">
                <?php echo landa()->rp($totalYtdForecast + $serviceYtdForecast + $taxYtdForecast, false); ?>
                <input type="hidden" name="Na[global_cash]" value="<?php echo $netDay ?>" />
                <input type="hidden" name="Na[global_cc]" value="0" />
                <input type="hidden" name="Na[global_gl]" value="0" />            
                <input type="hidden" name="Na[global_cl]" value="0" />
                <input type="hidden" name="Na[global_total]" value="<?php echo $totalDay ?>" />
            </th>            
        </tr>  


    </tbody>
    <thead>
        <tr>            
            <th colspan="7" rowspan="2" style="text-align: left;height: 50px">STATISTIC </th>   
        </tr>
    </thead>
    <?php
//    echo $roomOccupied.'--';
    $roomOutoforder = array_filter($room, function($room) {
        return $room['status'] == 'out of order';
    });
    $roomVacant = count($room) - $roomOccupied - $compliment - count($roomOutoforder);
    $outoforder = count($roomOutoforder);
    $roomAvailable = count($room) - count($roomOutoforder);
    $roomTotal = count($room);
    $occupancy = ($roomAvailable == 0) ? 0 : ($roomOccupied / $roomAvailable) * 100;
    $doubleOccupancy = ($roomOccupied == 0) ? 0 : (($numberOfGuest - $roomOccupied) / $roomOccupied) * 100;
    $avgRoomRate = ($roomOccupied == 0) ? 0 : $totalRoomRate / $roomOccupied;
//    echo $avgRoomRate.'aa';
    $avgRoomRateDolar = ($rateDolar == 0) ? 0 : $avgRoomRate / $rateDolar;
    $salesCoofient = ($netDay == 0 || $totalRoomRate == 0) ? 0 : ($netDay / ($totalRoomRate) ) * 100;
    $otherForcast = array('room occupied' => $roomOccupied,
        'house use' => $houseuse,
        'compliment' => $compliment,
        'vacant room' => $roomVacant,
        'out of order' => $outoforder,
        'room available' => $roomAvailable,
        'avg room rate rupiah' => $avgRoomRate,
        'avg room rate dolar' => $avgRoomRateDolar,
        'percentage of occupancy' => $occupancy,
        'percentage of double occupancy' => $doubleOccupancy,
        'number of guest' => $numberOfGuest,
        'sales coeficient' => $salesCoofient);
//    echo $netDay.'--'.$totalRoomRate.'---'.$totalRoomRate/$net;
    $masterOtherForecast = (!empty($forecast)) ? json_decode($forecast->other_forecast, true) : array();
    ?>
    <tbody>
        <?php
        foreach ($otherForcast as $key => $value) {
            if ($key == 'avg room rate rupiah') {
                //month to date
                $bagi = $totalNetEachOther['room occupied']['mtd_actual'];
                $nilai = $totalNetEachDsr[$roomAcc]['mtd_actual'];
                $other_mtd_ac = ($bagi == 0) ? 0 : $nilai / $bagi;

                //last month
                $bagi = $totalNetEachOther['room occupied']['lastmonth'];
                $nilai = $totalNetEachDsr[$roomAcc]['lastmonth'];
                $other_mtd_la = ($bagi == 0) ? 0 : $nilai / $bagi;

                //year to date
                $bagi = $totalNetEachOther['room occupied']['ytd_actual'];
                $nilai = $totalNetEachDsr[$roomAcc]['ytd_actual'];
                $other_ytd_ac = ($bagi == 0) ? 0 : $nilai / $bagi;


                $forecastOtherYearOcc = 0;
                for ($i = 1; $i < 13; $i++) {
                    if ($i == date('n', strtotime($siteConfig->date_system))) {
                        $forecastOtherNowOcc = $masterOtherForecast['room occupied'][date('n', strtotime($siteConfig->date_system))];
                        $forecastOtherNowOcc = ($forecastOtherNowOcc / date('t', strtotime($siteConfig->date_system))) * date('d', strtotime($siteConfig->date_system));
                    } elseif ($i < date('n', strtotime($siteConfig->date_system))) {
                        $forecastOtherYearOcc += $masterOtherForecast['room occupied'][$i];
                    }
                }
                $forecastOtherNow = ($forecastOtherNowOcc != 0) ? $totalNetEachDsr[$roomAcc]['mtd_forecast'] / $forecastOtherNowOcc : 0;
                $other_ytd = (($forecastOtherYearOcc + $forecastOtherNowOcc) != 0) ? $totalNetEachDsr[$roomAcc]['ytd_forecast'] / ($forecastOtherYearOcc + $forecastOtherNowOcc) : 0;
            } elseif ($key == 'avg room rate dolar') {
                $other_mtd_ac = $totalNetEachOther['avg room rate rupiah']['mtd_actual'] / $rateDolar;
                $other_mtd_la = $totalNetEachOther['avg room rate rupiah']['lastmonth'] / $rateDolar;
                $other_ytd_ac = $totalNetEachOther['avg room rate rupiah']['ytd_actual'] / $rateDolar;
                $forecastOtherNow = $totalNetEachOther['avg room rate rupiah']['mtd_forecast'] / $rateDolar;
                $other_ytd = $totalNetEachOther['avg room rate rupiah']['ytd_forecast'] / $rateDolar;
            } elseif ($key == 'percentage of occupancy') {
                $other_mtd_ac = (isset($totalNetEachOther['room available']['mtd_actual']) && $totalNetEachOther['room available']['mtd_actual'] != 0) ? $totalNetEachOther['room occupied']['mtd_actual'] / $totalNetEachOther['room available']['mtd_actual'] * 100 : 0;
                $other_mtd_la = (isset($totalNetEachOther['room available']['lastmonth']) && $totalNetEachOther['room available']['lastmonth'] != 0) ? $totalNetEachOther['room occupied']['lastmonth'] / $totalNetEachOther['room available']['lastmonth'] * 100 : 0;
                $other_ytd_ac = (isset($totalNetEachOther['room available']['ytd_actual']) && $totalNetEachOther['room available']['ytd_actual'] != 0) ? $totalNetEachOther['room occupied']['ytd_actual'] / $totalNetEachOther['room available']['ytd_actual'] * 100 : 0;
                $forecastOtherNow = (isset($totalNetEachOther['room available']['mtd_forecast']) && $totalNetEachOther['room available']['mtd_forecast'] != 0) ? $totalNetEachOther['room occupied']['mtd_forecast'] / $totalNetEachOther['room available']['mtd_forecast'] * 100 : 0;
                $other_ytd = (isset($totalNetEachOther['room available']['ytd_forecast']) && $totalNetEachOther['room available']['ytd_forecast'] != 0) ? $totalNetEachOther['room occupied']['ytd_forecast'] / $totalNetEachOther['room available']['ytd_forecast'] * 100 : 0;
            } elseif ($key == 'percentage of double occupancy') {
                $other_mtd_ac_nog = (isset($other_mtd_actual['number of guest'])) ? $other_mtd_actual['number of guest'] + $otherForcast['number of guest'] + $initial_statistic['number of guest']['monthToDate'] : $otherForcast['number of guest'] + $initial_statistic['number of guest']['monthToDate'];
                $other_mtd_la_nog = (isset($other_lastmonth['number of guest'])) ? $other_lastmonth['number of guest'] + $initial_statistic['number of guest']['lastMonth'][$month_system] : $initial_statistic['number of guest']['lastMonth'][$month_system];
                $other_ytd_ac_nog = (isset($other_ytd_actual['number of guest'])) ? $other_ytd_actual['number of guest'] + $otherForcast['number of guest'] + $initial_statistic['number of guest']['yearToDate'] + $initial_statistic['number of guest']['monthToDate'] : $otherForcast['number of guest'] + $initial_statistic['number of guest']['yearToDate'] + $initial_statistic['number of guest']['monthToDate'];
                $forecastOtherYear = 0;
                for ($i = 1; $i < 13; $i++) {
                    if ($i == date('n', strtotime($siteConfig->date_system))) {
                        $forecastOtherNow = $masterOtherForecast['number of guest'][date('n', strtotime($siteConfig->date_system))];
                        $forecastOtherNow = ($forecastOtherNow / date('t', strtotime($siteConfig->date_system))) * date('d', strtotime($siteConfig->date_system));
                    } elseif ($i < date('n', strtotime($siteConfig->date_system))) {
                        $forecastOtherYear += $masterOtherForecast['number of guest'][$i];
                    }
                }
                $other_ytd_nog = $forecastOtherNow + $forecastOtherYear;

                $other_mtd_ac = ($totalNetEachOther['room occupied']['mtd_actual'] != 0) ? ($other_mtd_ac_nog - $totalNetEachOther['room occupied']['mtd_actual']) / $totalNetEachOther['room occupied']['mtd_actual'] * 100 : 0;
                $other_mtd_la = ($totalNetEachOther['room occupied']['lastmonth'] != 0) ? ($other_mtd_la_nog - $totalNetEachOther['room occupied']['lastmonth']) / $totalNetEachOther['room occupied']['lastmonth'] * 100 : 0;
                $other_ytd_ac = ( $totalNetEachOther['room occupied']['ytd_actual'] != 0) ? ($other_ytd_ac_nog - $totalNetEachOther['room occupied']['ytd_actual']) / $totalNetEachOther['room occupied']['ytd_actual'] * 100 : 0;
                $forecastOtherNow = ( $totalNetEachOther['room occupied']['mtd_forecast'] != 0) ? ($forecastOtherNow - $totalNetEachOther['room occupied']['mtd_forecast']) / $totalNetEachOther['room occupied']['mtd_forecast'] * 100 : 0;
                $other_ytd = ($totalNetEachOther['room occupied']['ytd_forecast'] != 0) ? ($other_ytd_nog - $totalNetEachOther['room occupied']['ytd_forecast']) / $totalNetEachOther['room occupied']['ytd_forecast'] * 100 : 0;
            } elseif ($key == 'sales coeficient') {
                $other_mtd_ac = ($totalNetEachDsr[$roomAcc]['mtd_actual'] != 0) ? $totalNetEachDsr['net']['mtd_actual'] / $totalNetEachDsr[$roomAcc]['mtd_actual'] * 100 : 0;
                $other_mtd_la = ($totalNetEachDsr[$roomAcc]['lastmonth'] != 0) ? $totalNetEachDsr['net']['lastmonth'] / $totalNetEachDsr[$roomAcc]['lastmonth'] * 100 : 0;
                $other_ytd_ac = ( $totalNetEachDsr[$roomAcc]['ytd_actual'] != 0) ? $totalNetEachDsr['net']['ytd_actual'] / $totalNetEachDsr[$roomAcc]['ytd_actual'] * 100 : 0;
                $forecastOtherNow = ($totalNetEachDsr[$roomAcc]['mtd_forecast'] != 0) ? $totalNetEachDsr['net']['mtd_forecast'] / $totalNetEachDsr[$roomAcc]['mtd_forecast'] * 100 : 0;
                $other_ytd = ($totalNetEachDsr[$roomAcc]['ytd_forecast'] != 0) ? $totalNetEachDsr['net']['ytd_forecast'] / $totalNetEachDsr[$roomAcc]['ytd_forecast'] * 100 : 0;
            } else {
                $other_mtd_ac = (isset($other_mtd_actual[$key])) ? $other_mtd_actual[$key] + $value + $initial_statistic[$key]['monthToDate'] : $value + $initial_statistic[$key]['monthToDate'];
                $other_mtd_la = (isset($other_lastmonth[$key])) ? $other_lastmonth[$key] + $initial_statistic[$key]['lastMonth'][$month_system] : $initial_statistic[$key]['lastMonth'][$month_system];
                $other_ytd_ac = (isset($other_ytd_actual[$key])) ? $other_ytd_actual[$key] + $value + $initial_statistic[$key]['yearToDate'] : $value + $initial_statistic[$key]['yearToDate'];
                $forecastOtherYear = 0;
                for ($i = 1; $i < 13; $i++) {
                    if ($i == date('n', strtotime($siteConfig->date_system))) {
                        $forecastOtherNow = $masterOtherForecast[$key][date('n', strtotime($siteConfig->date_system))];
                        $forecastOtherNow = ($forecastOtherNow / date('t', strtotime($siteConfig->date_system))) * date('d', strtotime($siteConfig->date_system));
                    } elseif ($i < date('n', strtotime($siteConfig->date_system))) {
                        $forecastOtherYear += $masterOtherForecast[$key][$i];
                    }
                }
                $other_ytd = $forecastOtherNow + $forecastOtherYear;
            }

            //fill data to array
            $totalNetEachOther[$key]['today'] = $value;
            $totalNetEachOther[$key]['mtd_actual'] = $other_mtd_ac;
            $totalNetEachOther[$key]['mtd_forecast'] = $forecastOtherNow;
            $totalNetEachOther[$key]['lastmonth'] = $other_mtd_la;
            $totalNetEachOther[$key]['ytd_actual'] = $other_ytd_ac;
            $totalNetEachOther[$key]['ytd_forecast'] = $other_ytd;

            echo '<tr>                  
                <input type="hidden" name="statistic_today[' . $key . ']" value="' . $value . '" />                 
                <input type="hidden" name="statistic_mtd_actual[' . $key . ']" value="' . $other_mtd_ac . '" />                     
                <input type="hidden" name="statistic_mtd_forecast[' . $key . ']" value="' . $forecastOtherNow . '" />
                <input type="hidden" name="statistic_mtd_last_month[' . $key . ']" value="' . $other_mtd_la . '" />                    
                <input type="hidden" name="statistic_ytd_actual[' . $key . ']" value="' . $other_ytd_ac . '" />                      
                <input type="hidden" name="statistic_ytd_forecast[' . $key . ']" value="' . $other_ytd . '" />
                ';
            //prosentase, tidak perlu menggunakan function rupiah
            if ($key == 'percentage of occupancy' || $key == 'avg room rate dolar' || $key == 'percentage of double occupancy' || $key == 'sales coeficient') {
                if ($key == 'sales coeficient') {
                    $key = 'sales coefficient';
                } elseif ($key == 'percentage of occupancy') {
                    $key = '% of occupancy';
                } elseif ($key == 'percentage of double occupancy') {
                    $key = '% of double occupancy';
                }
                echo'            
                <td style="text-align: left">' . ucwords($key) . '</td>            
                <td style="text-align:right ">' . landa()->rp($value, false, 2) . '</td>            
                <td style="text-align:right ">' . landa()->rp($other_mtd_ac, false, 2) . '</td>                                 
                <td style="text-align:right ">' . landa()->rp($forecastOtherNow, false, 2) . '</td>            
                <td style="text-align:right ">' . landa()->rp($other_mtd_la, false, 2) . '</td>            
                <td style="text-align:right ">' . landa()->rp($other_ytd_ac, false, 2) . '</td>                        
                <td style="text-align:right ">' . landa()->rp($other_ytd, false, 2) . '</td>            
                ';
            } elseif ($key == 'avg room rate rupiah') {
                echo'            
                <td style="text-align: left">' . ucwords($key) . '</td>            
                <td style="text-align:right ">' . landa()->rp($value, false, 0) . '</td>            
                <td style="text-align:right ">' . landa()->rp($other_mtd_ac, false, 0) . '</td>                                 
                <td style="text-align:right ">' . landa()->rp($forecastOtherNow, false, 0) . '</td>            
                <td style="text-align:right ">' . landa()->rp($other_mtd_la, false, 0) . '</td>            
                <td style="text-align:right ">' . landa()->rp($other_ytd_ac, false, 0) . '</td>                        
                <td style="text-align:right ">' . landa()->rp($other_ytd, false, 0) . '</td>            
                ';
            } else {
                echo'            
                <td style="text-align: left">' . ucwords($key) . '</td>            
                <td style="text-align:right ">' . landa()->rp($value, false) . '</td>            
                <td style="text-align:right ">' . landa()->rp($other_mtd_ac, false) . '</td>                                 
                <td style="text-align:right ">' . landa()->rp($forecastOtherNow, false) . '</td>            
                <td style="text-align:right ">' . landa()->rp($other_mtd_la, false) . '</td>            
                <td style="text-align:right ">' . landa()->rp($other_ytd_ac, false) . '</td>                        
                <td style="text-align:right ">' . landa()->rp($other_ytd, false) . '</td>            
                ';
            }
            echo '</tr> ';
        }
        ?>
    </tbody>
</table>
