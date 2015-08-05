<?php
//from siteconfig        
$settings = json_decode($siteConfig->settings, true);
$roomAcc = (!empty($settings['room_account'])) ? $settings['room_account'] : '';
$foodAcc = 2;
$beverageAcc = 3;
$breakfastDep = 2;
$rateDolar = (!empty($settings['rate'])) ? $settings['rate'] : 0;
$breakfastAcc = (!empty($settings['fb_account'])) ? $settings['fb_account'] : '';
$month_system = date('j', strtotime($siteConfig->date_system));
$initial_food_analysis = json_decode($initialForecast->food_analysis, true);
$initial_food_percover = json_decode($initialForecast->food_percover, true);
$initial_beverage_analysis = json_decode($initialForecast->beverage_analysis, true);
$month_system = date('j', strtotime($siteConfig->date_system));
$date_na = date('Y-m-d', strtotime($siteConfig->date_system));
?>
<center><h3>FOOD & BEVERAGE ANALYSIS</h3></center>
<center>Date Night Audit : <?php echo date("d-M-Y", strtotime($siteConfig->date_system)); ?></center>
<hr>

<table class="items table  table-condensed">
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
            $forecastCover = json_decode($forecast->cover_forecast, true);
        }

        //LOAD DATA FROM YESTERDAY NA (FOOD, COVER & BEVERAGE ANALYS)

        $na_food_thisyear = NaFoodAnalys::model()->with('Na')->find(array('condition' => 'YEAR(Na.date_na) = YEAR("' . $date_na . '")', 'order' => 't.created DESC'));
        $na_food_thismonth = NaFoodAnalys::model()->with('Na')->find(array('condition' => 'MONTH(Na.date_na) = MONTH("' . $date_na . '") and  YEAR(Na.date_na) = YEAR("' . $date_na . '")', 'order' => 't.created DESC'));
        $na_food_lastmonth = NaFoodAnalys::model()->with('Na')->find(array('condition' => 'Na.date_na = DATE("' . $date_na . '" - INTERVAL 1 MONTH)', 'order' => 't.created DESC'));

        $na_foodpercover_thisyear = NaFoodpercoverAnalys::model()->with('Na')->find(array('condition' => 'YEAR(Na.date_na) = YEAR("' . $date_na . '")', 'order' => 't.created DESC'));
        $na_foodpercover_thismonth = NaFoodpercoverAnalys::model()->with('Na')->find(array('condition' => 'MONTH(Na.date_na) = MONTH("' . $date_na . '") and  YEAR(Na.date_na) = YEAR("' . $date_na . '")', 'order' => 't.created DESC'));
        $na_foodpercover_lastmonth = NaFoodpercoverAnalys::model()->with('Na')->find(array('condition' => 'Na.date_na = DATE("' . $date_na . '" - INTERVAL 1 MONTH)', 'order' => 't.created DESC'));

        $na_beverage_thisyear = NaBeverageAnalys::model()->with('Na')->find(array('condition' => 'YEAR(Na.date_na) = YEAR("' . $date_na . '")', 'order' => 't.created DESC'));
        $na_beverage_thismonth = NaBeverageAnalys::model()->with('Na')->find(array('condition' => 'MONTH(Na.date_na) = MONTH("' . $date_na . '") and  YEAR(Na.date_na) = YEAR("' . $date_na . '")', 'order' => 't.created DESC'));
        $na_beverage_lastmonth = NaBeverageAnalys::model()->with('Na')->find(array('condition' => 'Na.date_na = DATE("' . $date_na . '" - INTERVAL 1 MONTH)', 'order' => 't.created DESC'));

        $food_ytd_actual = (!empty($na_food_thisyear)) ? json_decode($na_food_thisyear->ytd_actual, true) : array();
        $food_mtd_actual = (!empty($na_food_thismonth)) ? json_decode($na_food_thismonth->mtd_actual, true) : array();
        $food_lastmonth = (!empty($na_food_lastmonth)) ? json_decode($na_food_lastmonth->mtd_actual, true) : array();

        $foodpercover_ytd_actual = (!empty($na_foodpercover_thisyear)) ? json_decode($na_foodpercover_thisyear->ytd_actual, true) : array();
        $foodpercover_mtd_actual = (!empty($na_foodpercover_thismonth)) ? json_decode($na_foodpercover_thismonth->mtd_actual, true) : array();
        $foodpercover_lastmonth = (!empty($na_foodpercover_lastmonth)) ? json_decode($na_foodpercover_lastmonth->mtd_actual, true) : array();

        $beverage_ytd_actual = (!empty($na_beverage_thisyear)) ? json_decode($na_beverage_thisyear->ytd_actual, true) : array();
        $beverage_mtd_actual = (!empty($na_beverage_thismonth)) ? json_decode($na_beverage_thismonth->mtd_actual, true) : array();
        $beverage_lastmonth = (!empty($na_beverage_lastmonth)) ? json_decode($na_beverage_lastmonth->mtd_actual, true) : array();
        ?>

    <thead>
        <tr>            
            <th colspan="7" rowspan="2" style="text-align: left;">FOOD ANALYSIS </th>   
        </tr>
    </thead>
    <tbody>
        <?php
        $accFood = Account::model()->findByPk($foodAcc);
        $netFood = (($accFood->tax + $accFood->service) / 100) + 1;
//        echo $netFood;
        ?>
        <?php
        $foodToday = 0;
        $foodActualMonth = 0;
        $foodMTD = 0;
        $foodLastMonth = 0;
        $foodActualYear = 0;
        $foodYTD = 0;
        foreach ($category as $departement) {
            $totalNetFood[$departement->id] = 0;
            $totalNetBeverage [$departement->id] = 0;
        }
        //nyari total net food        
        $today = 0;
        $forecastNow = 0;
        $forecastYear = 0;
        $roomOccupied = 0;
        $compliment = 0;
        $houseuse = 0;
        $totalRoomRate = 0;
        foreach ($roomBills as $data) {
            //roomBill         
            if ($data->charge != 0 && $data->is_checkedout == 0 && $data->is_na == 0 || ($data->is_checkedout == 1 && $data->date_bill == $siteConfig->date_system)) {
                $pax = (!empty($data->pax)) ? $data->pax : 0;
                $fnb_price = (!empty($data->fnb_price)) ? $data->fnb_price : 0;
                $totalNetFood[$breakfastDep] += $pax * $fnb_price;

//                if ($data->package_room_type_id != 0) {
//                    $package = json_decode($roomType[$data->package_room_type_id]['charge_additional_ids']);
//                    if (!empty($package)) {
//                        foreach ($package as $mPackage) {
//                            if ($foodAcc == $additional[$mPackage->id]['account_id'])
//                                $totalNetFood[$additional[$mPackage->id]['charge_additional_category_id']] += $mPackage->amount * $mPackage->charge;
//                            if ($beverageAcc == $additional[$mPackage->id]['account_id'])
//                                $totalNetBeverage[$additional[$mPackage->id]['charge_additional_category_id']] += $mPackage->amount * $mPackage->charge;
//                        }
//                    }
//                }

                if ($data->others_include != '') {
                    $others_include = json_decode($data->others_include);
                    foreach ($others_include as $key => $mInclude) {
                        $tuyul = ChargeAdditional::model()->findByPk($key);
                        if ($foodAcc == $tuyul->account_id)
                            $totalNetFood[$tuyul->charge_additional_category_id] += $mInclude;
                        if ($beverageAcc == $tuyul->account_id)
                            $totalNetBeverage[$tuyul->charge_additional_category_id] += $mInclude;
                    }
                }
            }
        }

        //transaction / account                 
        foreach ($billCharge as $charge) {
            $billChargeDet = BillChargeDet::model()->findAll(array('condition' => 'bill_charge_id=' . $charge->id . ' and deposite_id=0'));
            foreach ($billChargeDet as $chargeDet) {
                if ($chargeDet->Additional->account_id == $foodAcc) {
                    $totalNetFood[$chargeDet->Additional->charge_additional_category_id] += $chargeDet->netTotal;
                } elseif ($chargeDet->Additional->account_id == $beverageAcc) {
                    $totalNetBeverage[$chargeDet->Additional->charge_additional_category_id] += $chargeDet->netTotal;
                }
            }
        }


        //---------------------------------------------------------------


        $departements = ChargeAdditional::model()->findAll(array('condition' => 'account_id=' . $foodAcc, 'group' => 'charge_additional_category_id'));
        foreach ($departements as $departement) {
            $hasilFood = $totalNetFood[$departement->charge_additional_category_id] / $netFood;
            $food_mtd_ac = (isset($food_mtd_actual[$departement->charge_additional_category_id])) ? $food_mtd_actual[$departement->charge_additional_category_id] + $hasilFood : $hasilFood;
            $food_mtd_la = (isset($food_lastmonth[$departement->charge_additional_category_id])) ? $food_lastmonth[$departement->charge_additional_category_id] : 0;
            $food_ytd_ac = (isset($food_ytd_actual[$departement->charge_additional_category_id])) ? $food_ytd_actual[$departement->charge_additional_category_id] + $hasilFood : $hasilFood;

            $forecastYear = 0;
            $forecastNow = 0;
            for ($i = 1; $i < 13; $i++) {
                if ($i == date('n', strtotime($siteConfig->date_system))) {
                    $forecastNow = isset($masterForecast[$foodAcc][$departement->charge_additional_category_id][date('n', strtotime($siteConfig->date_system))]) ? $masterForecast[$foodAcc][$departement->charge_additional_category_id][date('n', strtotime($siteConfig->date_system))] : 0;
                } elseif ($i < date('n', strtotime($siteConfig->date_system))) {
                    $forecastYear += isset($masterForecast[$foodAcc][$departement->charge_additional_category_id][$i]) ? $masterForecast[$foodAcc][$departement->charge_additional_category_id][$i] : 0;
                }
            }

            $forecastNow = ($forecastNow / date('t', strtotime($siteConfig->date_system))) * date('d', strtotime($siteConfig->date_system));
            $forecastYear += $forecastNow;
            $value = $totalNetFood[$departement->charge_additional_category_id] / $netFood;
//            $value = $totalNetFood[$departement->charge_additional_category_id] / $netFood;




            //input initial forecast            
            $food_mtd_ac+= (isset($initial_food_analysis[$departement->charge_additional_category_id]['monthToDate'])) ? $initial_food_analysis[$departement->charge_additional_category_id]['monthToDate'] : 0;
            $food_mtd_la+= (isset($initial_food_analysis[$departement->charge_additional_category_id]['lastMonth'][$month_system])) ? $initial_food_analysis[$departement->charge_additional_category_id]['lastMonth'][$month_system] : 0;
            $food_ytd_ac+= (isset($initial_food_analysis[$departement->charge_additional_category_id]['yearToDate'])) ? $initial_food_analysis[$departement->charge_additional_category_id]['yearToDate'] : 0;

            //input to array
            $foodAnalys[$departement->charge_additional_category_id]['today'] = $value;
            $foodAnalys[$departement->charge_additional_category_id]['mtd_actual'] = $food_mtd_ac;
            $foodAnalys[$departement->charge_additional_category_id]['mtd_forecast'] = $forecastNow;
            $foodAnalys[$departement->charge_additional_category_id]['lastmonth'] = $food_mtd_la;
            $foodAnalys[$departement->charge_additional_category_id]['ytd_actual'] = $food_ytd_ac;
            $foodAnalys[$departement->charge_additional_category_id]['ytd_forecast'] = $forecastYear;
            ?>
            <tr>  
                <?php echo '
                <input type="hidden" name="food_analys_today[' . $departement->charge_additional_category_id . ']" value="' . $value . '" />                 
                <input type="hidden" name="food_analys_mtd_actual[' . $departement->charge_additional_category_id . ']" value="' . $food_mtd_ac . '" />                     
                <input type="hidden" name="food_analys_mtd_forecast[' . $departement->charge_additional_category_id . ']" value="' . $forecastNow . '" />
                <input type="hidden" name="food_analys_mtd_last_month[' . $departement->charge_additional_category_id . ']" value="' . $food_mtd_la . '" />                    
                <input type="hidden" name="food_analys_ytd_actual[' . $departement->charge_additional_category_id . ']" value="' . $food_ytd_ac . '" />                      
                <input type="hidden" name="food_analys_ytd_forecast[' . $departement->charge_additional_category_id . ']" value="' . $forecastYear . '" />
                '; ?>
                <td style = "text-align: left"><?php echo $departement->ChargeAdditionalCategory->name; ?></td>            
                <td style = "text-align:right "><?php echo landa()->rp($value, false); ?></td>
                <td style = "text-align:right "><?php echo landa()->rp($food_mtd_ac, false); ?></td>
                <td style = "text-align:right "><?php echo landa()->rp($forecastNow, false); ?></td>
                <td style = "text-align:right "><?php echo landa()->rp($food_mtd_la, false); ?></td>
                <td style = "text-align:right "><?php echo landa()->rp($food_ytd_ac, false); ?></td>
                <td style = "text-align:right "><?php echo landa()->rp($forecastYear, false); ?></td>
            </tr>
            <?php
            $foodToday += $hasilFood;
            $foodActualMonth += $food_mtd_ac;
            $foodMTD += $forecastNow;
            $foodLastMonth += $food_mtd_la;
            $foodActualYear += $food_ytd_ac;
            $foodYTD += $forecastYear;
            ?>
        <?php } ?>
        <tr style="font-weight: bold;background: khaki">            
            <th style="text-align: left">TOTAL FOOD</th>            
            <th style="text-align:right ">  <?php echo landa()->rp($foodToday, FALSE); ?></th>            
            <th style="text-align:right "><?php echo landa()->rp($foodActualMonth, false); ?></th>                        
            <th style="text-align:right "><?php echo landa()->rp($foodMTD, false); ?></th>                        
            <th style="text-align:right "><?php echo landa()->rp($foodLastMonth, false); ?></th>                        
            <th style="text-align:right "><?php echo landa()->rp($foodActualYear, false); ?></th>                        
            <th style="text-align:right "><?php echo landa()->rp($foodYTD, false); ?></th>            
        </tr>  
        <?php
        //input to array
        $foodAnalys['total_today'] = $foodToday;
        $foodAnalys['total_mtd_actual'] = $foodActualMonth;
        $foodAnalys['total_mtd_forecast'] = $foodMTD;
        $foodAnalys['total_lastmonth'] = $foodLastMonth;
        $foodAnalys['total_ytd_actual'] = $foodActualYear;
        $foodAnalys['total_ytd_forecast'] = $foodYTD;
        ?>
    </tbody>
    <tr>            
        <th colspan="7">&nbsp;</th>   
    </tr>

    <thead>
        <tr>            
            <th colspan="7" rowspan="2" style="text-align: left;">FOOD PER COVER </th>   
        </tr>
    </thead>
    <tbody>
        <?php
        $accFood = Account::model()->findByPk($foodAcc);
        $netFood = (($accFood->tax + $accFood->service) / 100) + 1;
        ?>
        <?php
        $foodToday = 0;
        $foodActualMonth = 0;
        $foodMTD = 0;
        $foodLastMonth = 0;
        $foodActualYear = 0;
        $foodYTD = 0;
        foreach ($category as $departement) {
            $totalPercover[$departement->id] = 0;
        }
        foreach ($roomBills as $data) {
            //roomBill         
            if ($data->charge != 0 && $data->is_checkedout == 0 && $data->is_na == 0 || ($data->is_checkedout == 1 && $data->date_bill == $siteConfig->date_system)) {
                $pax = (!empty($data->pax)) ? $data->pax : 0;
                $totalPercover[$breakfastDep] += $pax;
//                if ($data->package_room_type_id != 0) {
//                    $package = json_decode($roomType[$data->package_room_type_id]['charge_additional_ids']);
//                    if (!empty($package)) {
//                        foreach ($package as $mPackage) {
//                            if ($foodAcc == $additional[$mPackage->id]['account_id'])
//                                $totalPercover[$additional[$mPackage->id]['charge_additional_category_id']] += $pax;
//                        }
//                    }
//                }

                if ($data->others_include != '') {
                    $others_include = json_decode($data->others_include);
                    foreach ($others_include as $key => $mInclude) {
                        $tuyul = ChargeAdditional::model()->findByPk($key);
                        if ($foodAcc == $tuyul->account_id)
                            $totalPercover[$tuyul->charge_additional_category_id] += $pax;
                    }
                }
            }
        }

        //transaction / account                 
        foreach ($billCharge as $charge) {
            $totalPercover[$charge->charge_additional_category_id] += $charge->cover;
        }

        //---------------------------------------------------------------        
        foreach ($departements as $departement) {
            $hasilFood = $totalPercover[$departement->charge_additional_category_id];
            $food_mtd_ac = (isset($foodpercover_mtd_actual[$departement->charge_additional_category_id])) ? $foodpercover_mtd_actual[$departement->charge_additional_category_id] + $hasilFood : $hasilFood;
            $food_mtd_la = (isset($foodpercover_lastmonth[$departement->charge_additional_category_id])) ? $foodpercover_lastmonth[$departement->charge_additional_category_id] : 0;
            $food_ytd_ac = (isset($foodpercover_ytd_actual[$departement->charge_additional_category_id])) ? $foodpercover_ytd_actual[$departement->charge_additional_category_id] + $hasilFood : $hasilFood;

            $forecastYear = 0;
            $forecastNow = 0;
            for ($i = 1; $i < 13; $i++) {
                if ($i == date('n', strtotime($siteConfig->date_system))) {
                    $forecastNow = isset($forecastCover[$departement->charge_additional_category_id][date('n', strtotime($siteConfig->date_system))]) ? $forecastCover[$departement->charge_additional_category_id][date('n', strtotime($siteConfig->date_system))] : 0;
                } elseif ($i < date('n', strtotime($siteConfig->date_system))) {
                    $forecastYear += isset($forecastCover[$departement->charge_additional_category_id][$i]) ? $forecastCover[$departement->charge_additional_category_id][$i] : 0;
                }
            }

            $forecastNow = ($forecastNow / date('t', strtotime($siteConfig->date_system))) * date('d', strtotime($siteConfig->date_system));
            $forecastYear += $forecastNow;
            $value = $totalPercover[$departement->charge_additional_category_id];

            //input initial forecast            
            $food_mtd_ac+= (isset($initial_food_percover[$departement->charge_additional_category_id]['monthToDate'])) ? $initial_food_percover[$departement->charge_additional_category_id]['monthToDate'] : 0;
            $food_mtd_la+= (isset($initial_food_percover[$departement->charge_additional_category_id]['lastMonth'][$month_system])) ? $initial_food_percover[$departement->charge_additional_category_id]['lastMonth'][$month_system] : 0;
            $food_ytd_ac+= (isset($initial_food_percover[$departement->charge_additional_category_id]['yearToDate'])) ? $initial_food_percover[$departement->charge_additional_category_id]['yearToDate'] : 0;

            //input to array
            $foodCover[$departement->charge_additional_category_id]['today'] = $value;
            $foodCover[$departement->charge_additional_category_id]['mtd_actual'] = $food_mtd_ac;
            $foodCover[$departement->charge_additional_category_id]['mtd_forecast'] = $forecastNow;
            $foodCover[$departement->charge_additional_category_id]['lastmonth'] = $food_mtd_la;
            $foodCover[$departement->charge_additional_category_id]['ytd_actual'] = $food_ytd_ac;
            $foodCover[$departement->charge_additional_category_id]['ytd_forecast'] = $forecastYear;
            ?>
            <tr >  
                <?php echo '
                <input type="hidden" name="food_percover_today[' . $departement->charge_additional_category_id . ']" value="' . $value . '" />                 
                <input type="hidden" name="food_percover_mtd_actual[' . $departement->charge_additional_category_id . ']" value="' . $food_mtd_ac . '" />                     
                <input type="hidden" name="food_percover_mtd_forecast[' . $departement->charge_additional_category_id . ']" value="' . $forecastNow . '" />
                <input type="hidden" name="food_percover_mtd_last_month[' . $departement->charge_additional_category_id . ']" value="' . $food_mtd_la . '" />                    
                <input type="hidden" name="food_percover_ytd_actual[' . $departement->charge_additional_category_id . ']" value="' . $food_ytd_ac . '" />                      
                <input type="hidden" name="food_percover_ytd_forecast[' . $departement->charge_additional_category_id . ']" value="' . $forecastYear . '" />
                '; ?>
                <td style = "text-align: left"><?php echo $departement->ChargeAdditionalCategory->name; ?></td>            
                <td style = "text-align:right "><?php echo landa()->rp($value, false); ?></td>
                <td style = "text-align:right "><?php echo landa()->rp($food_mtd_ac, false); ?></td>
                <td style = "text-align:right "><?php echo landa()->rp($forecastNow, false); ?></td>
                <td style = "text-align:right "><?php echo landa()->rp($food_mtd_la, false); ?></td>
                <td style = "text-align:right "><?php echo landa()->rp($food_ytd_ac, false); ?></td>
                <td style = "text-align:right "><?php echo landa()->rp($forecastYear, false); ?></td>
            </tr>
            <?php
            $foodToday += $hasilFood;
            $foodActualMonth += $food_mtd_ac;
            $foodMTD += $forecastNow;
            $foodLastMonth += $food_mtd_la;
            $foodActualYear += $food_ytd_ac;
            $foodYTD += $forecastYear;
            ?>
        <?php } ?>
        <tr style="font-weight: bold;background: khaki">            
            <th style="text-align: left">TTL  FOOD/COVER</th>            
            <th style="text-align:right ">  <?php echo landa()->rp($foodToday, FALSE); ?></th>            
            <th style="text-align:right "><?php echo landa()->rp($foodActualMonth, false); ?></th>                        
            <th style="text-align:right "><?php echo landa()->rp($foodMTD, false); ?></th>                        
            <th style="text-align:right "><?php echo landa()->rp($foodLastMonth, false); ?></th>                        
            <th style="text-align:right "><?php echo landa()->rp($foodActualYear, false); ?></th>                        
            <th style="text-align:right "><?php echo landa()->rp($foodYTD, false); ?></th>            
        </tr>  
        <?php
        //input to array
        $foodCover['total_today'] = $foodToday;
        $foodCover['total_mtd_actual'] = $foodActualMonth;
        $foodCover['total_mtd_forecast'] = $foodMTD;
        $foodCover['total_lastmonth'] = $foodLastMonth;
        $foodCover['total_ytd_actual'] = $foodActualYear;
        $foodCover['total_ytd_forecast'] = $foodYTD;
        ?>
        <tr>            
            <th colspan="7">&nbsp;</th>   
        </tr>
    </tbody>
    <thead>
        <tr>            
            <th colspan="7" rowspan="2" style="text-align: left;">AVERAGE FOOD PER COVER </th>   
        </tr>
    </thead>
    <tbody>       
        <?php
        foreach ($departements as $departement) {
            $value = ($foodCover[$departement->charge_additional_category_id]['today'] != 0) ? $foodAnalys[$departement->charge_additional_category_id]['today'] / $foodCover[$departement->charge_additional_category_id]['today'] : 0;
            $food_mtd_ac = ($foodCover[$departement->charge_additional_category_id]['mtd_actual'] != 0) ? $foodAnalys[$departement->charge_additional_category_id]['mtd_actual'] / $foodCover[$departement->charge_additional_category_id]['mtd_actual'] : 0;
            $forecastNow = ($foodCover[$departement->charge_additional_category_id]['mtd_forecast'] != 0) ? $foodAnalys[$departement->charge_additional_category_id]['mtd_forecast'] / $foodCover[$departement->charge_additional_category_id]['mtd_forecast'] : 0;
            $food_mtd_la = ($foodCover[$departement->charge_additional_category_id]['lastmonth'] != 0) ? $foodAnalys[$departement->charge_additional_category_id]['lastmonth'] / $foodCover[$departement->charge_additional_category_id]['lastmonth'] : 0;
            $food_ytd_ac = ($foodCover[$departement->charge_additional_category_id]['ytd_actual'] != 0) ? $foodAnalys[$departement->charge_additional_category_id]['ytd_actual'] / $foodCover[$departement->charge_additional_category_id]['ytd_actual'] : 0;
            $forecastYear = ($foodCover[$departement->charge_additional_category_id]['ytd_forecast'] != 0) ? $foodAnalys[$departement->charge_additional_category_id]['ytd_forecast'] / $foodCover[$departement->charge_additional_category_id]['ytd_forecast'] : 0;
            ?>
            <tr >  
                <td style = "text-align: left"><?php echo $departement->ChargeAdditionalCategory->name; ?></td>            
                <td style = "text-align:right "><?php echo landa()->rp($value, false); ?></td>
                <td style = "text-align:right "><?php echo landa()->rp($food_mtd_ac, false); ?></td>
                <td style = "text-align:right "><?php echo landa()->rp($forecastNow, false); ?></td>
                <td style = "text-align:right "><?php echo landa()->rp($food_mtd_la, false); ?></td>
                <td style = "text-align:right "><?php echo landa()->rp($food_ytd_ac, false); ?></td>
                <td style = "text-align:right "><?php echo landa()->rp($forecastYear, false); ?></td>
            </tr>      
        <?php } ?>
        <?php
        $foodToday = ($foodCover['total_today'] != 0) ? $foodAnalys['total_today'] / $foodCover['total_today'] : 0;
        $foodActualMonth = ($foodCover['total_mtd_actual'] != 0) ? $foodAnalys['total_mtd_actual'] / $foodCover['total_mtd_actual'] : 0;
        $foodMTD = ($foodCover['total_mtd_forecast'] != 0) ? $foodAnalys['total_mtd_forecast'] / $foodCover['total_mtd_forecast'] : 0;
        $foodLastMonth = ($foodCover['total_lastmonth'] != 0) ? $foodAnalys['total_lastmonth'] / $foodCover['total_lastmonth'] : 0;
        $foodActualYear = ($foodCover['total_ytd_actual'] != 0) ? $foodAnalys['total_ytd_actual'] / $foodCover['total_ytd_actual'] : 0;
        $foodYTD = ($foodCover['total_ytd_forecast'] != 0) ? $foodAnalys['total_ytd_forecast'] / $foodCover['total_ytd_forecast'] : 0;
        ?>
        <tr style="font-weight: bold;background: khaki">            
            <th style="text-align: left">TTL AVG FOOD/COVER</th>            
            <th style="text-align:right ">  <?php echo landa()->rp($foodToday, FALSE); ?></th>            
            <th style="text-align:right "><?php echo landa()->rp($foodActualMonth, false); ?></th>                        
            <th style="text-align:right "><?php echo landa()->rp($foodMTD, false); ?></th>                        
            <th style="text-align:right "><?php echo landa()->rp($foodLastMonth, false); ?></th>                        
            <th style="text-align:right "><?php echo landa()->rp($foodActualYear, false); ?></th>                        
            <th style="text-align:right "><?php echo landa()->rp($foodYTD, false); ?></th>            
        </tr>  
        <tr>            
            <th colspan="7">&nbsp;</th>   
        </tr>
    </tbody>

    <thead>
        <tr>            
            <th colspan="7" rowspan="2" style="text-align: LEFT;">BEVERAGE ANALYSIS </th>   
        </tr>
    </thead>
    <tbody>
        <?php
        $accBeverage = Account::model()->findByPk($beverageAcc);
        $netBeverage = (($accBeverage->tax + $accBeverage->service) / 100) + 1;
        $bevToday = 0;
        $bevActualMonth = 0;
        $bevMTD = 0;
        $bevLastMonth = 0;
        $bevActualYear = 0;
        $bevYTD = 0;
        ?>
        <?php
        $departements = ChargeAdditional::model()->findAll(array('condition' => 'account_id=' . $beverageAcc, 'group' => 'charge_additional_category_id'));
        foreach ($departements as $departement) {
            $hasilBeverage = $totalNetBeverage[$departement->charge_additional_category_id] / $netBeverage;
            $beverage_mtd_ac = (isset($beverage_mtd_actual[$departement->charge_additional_category_id])) ? $beverage_mtd_actual[$departement->charge_additional_category_id] + $hasilBeverage : $hasilBeverage;
            $beverage_mtd_la = (isset($beverage_lastmonth[$departement->charge_additional_category_id])) ? $beverage_lastmonth[$departement->charge_additional_category_id] : 0;
            $beverage_ytd_ac = (isset($beverage_ytd_actual[$departement->charge_additional_category_id])) ? $beverage_ytd_actual[$departement->charge_additional_category_id] + $hasilBeverage : $hasilBeverage;
            $forecastYear = 0;
            $forecastNow = 0;
            for ($i = 1; $i < 13; $i++) {
                if ($i == date('n', strtotime($siteConfig->date_system))) {
                    $forecastNow = isset($masterForecast[$beverageAcc][$departement->charge_additional_category_id][date('n', strtotime($siteConfig->date_system))]) ? $masterForecast[$beverageAcc][$departement->charge_additional_category_id][date('n', strtotime($siteConfig->date_system))] : 0;
                } elseif ($i < date('n', strtotime($siteConfig->date_system))) {
                    $forecastYear += isset($masterForecast[$beverageAcc][$departement->charge_additional_category_id][$i]) ? $masterForecast[$beverageAcc][$departement->charge_additional_category_id][$i] : 0;
                }
            }

            $forecastNow = ($forecastNow / date('t', strtotime($siteConfig->date_system))) * date('d', strtotime($siteConfig->date_system));
            $forecastYear += $forecastNow;



            //input initial forecast            
            $beverage_mtd_ac+= (isset($initial_beverage_analysis[$departement->charge_additional_category_id]['monthToDate'])) ? $initial_beverage_analysis[$departement->charge_additional_category_id]['monthToDate'] : 0;
            $beverage_mtd_la+= (isset($initial_beverage_analysis[$departement->charge_additional_category_id]['lastMonth'][$month_system])) ? $initial_beverage_analysis[$departement->charge_additional_category_id]['lastMonth'][$month_system] : 0;
            $beverage_ytd_ac+= (isset($initial_beverage_analysis[$departement->charge_additional_category_id]['yearToDate'])) ? $initial_beverage_analysis[$departement->charge_additional_category_id]['yearToDate'] : 0;

            $bevToday += $hasilBeverage;
            $bevActualMonth += $beverage_mtd_ac;
            $bevMTD += $forecastNow;
            $bevLastMonth += $beverage_mtd_la;
            $bevActualYear += $beverage_ytd_ac;
            $bevYTD += $forecastYear;
            $value = $totalNetBeverage[$departement->charge_additional_category_id] / $netBeverage;
            ?>
            <tr >        
                <?php echo '
                <input type="hidden" name="beverage_analys_today[' . $departement->charge_additional_category_id . ']" value="' . $value . '" />                 
                <input type="hidden" name="beverage_analys_mtd_actual[' . $departement->charge_additional_category_id . ']" value="' . $beverage_mtd_ac . '" />                     
                <input type="hidden" name="beverage_analys_mtd_forecast[' . $departement->charge_additional_category_id . ']" value="' . $forecastNow . '" />
                <input type="hidden" name="beverage_analys_mtd_last_month[' . $departement->charge_additional_category_id . ']" value="' . $beverage_mtd_la . '" />                    
                <input type="hidden" name="beverage_analys_ytd_actual[' . $departement->charge_additional_category_id . ']" value="' . $beverage_ytd_ac . '" />                      
                <input type="hidden" name="beverage_analys_ytd_forecast[' . $departement->charge_additional_category_id . ']" value="' . $forecastYear . '" />
                '; ?>
                <td style = "text-align: left"><?php echo $departement->ChargeAdditionalCategory->name; ?></td>            
                <td style = "text-align:right "><?php echo landa()->rp($value, false); ?></td>
                <td style = "text-align:right "><?php echo landa()->rp($beverage_mtd_ac, false); ?></td>
                <td style = "text-align:right "><?php echo landa()->rp($forecastNow, false); ?></td>
                <td style = "text-align:right "><?php echo landa()->rp($beverage_mtd_la, false); ?></td>
                <td style = "text-align:right "><?php echo landa()->rp($beverage_ytd_ac, false); ?></td>
                <td style = "text-align:right "><?php echo landa()->rp($forecastYear, false); ?></td>
            </tr>
        <?php } ?>
        <tr style="font-weight: bold;background: khaki">            
            <th style="text-align: left">TOTAL BEVERAGE</th>            
            <th style="text-align:right ">  <?php echo landa()->rp($bevToday, FALSE); ?></th>            
            <th style="text-align:right "><?php echo landa()->rp($bevActualMonth, false); ?></th>                        
            <th style="text-align:right "><?php echo landa()->rp($bevMTD, false); ?></th>                        
            <th style="text-align:right "><?php echo landa()->rp($bevLastMonth, false); ?></th>                        
            <th style="text-align:right "><?php echo landa()->rp($bevActualYear, false); ?></th>                        
            <th style="text-align:right "><?php echo landa()->rp($bevYTD, false); ?></th>            
        </tr> 
    </tbody>
</table>
