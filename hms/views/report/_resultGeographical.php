<?php
$bodyCity = '';
$bodyProvince = '';
$bodyCountry = '';
$siteConfig = SiteConfig::model()->findByPk(1);
$province_id = City::model()->find(array('condition' => 'id=' . $siteConfig->city_id));
$selCity = City::model()->findAll(array('condition' => 'province_id=' . $province_id->province_id));
$created = date('Y-m-d', strtotime($_POST['created']));

$geo = ReportGeographical::model()->with('Na')->find(array('condition' => 'Na.date_na <= "' . $created . '"', 'order' => 'Na.date_na desc'));
if (!empty($geo)) {
    $today = json_decode($geo->today_geo, True);
    $month = json_decode($geo->month_geo, True);
    $year = json_decode($geo->year_geo, True);

    $todayPercent = array();
    $monthPercent = array();
    $yearPercent = array();

    foreach ($selCity as $city) {
        $valToday['city']['rno'][$city->id] = (isset($today['city'][$city->id]['rno']) and $geo->Na->date_na == $created ) ? $today['city'][$city->id]['rno'] : 0;
        $valToday['city']['pax'][$city->id] = (isset($today['city'][$city->id]['pax']) and $geo->Na->date_na == $created ) ? $today['city'][$city->id]['pax'] : 0;
        $valToday['city']['rno']['total'] = ((isset($valToday['city']['rno']['total']) and $geo->Na->date_na == $created ) ? $valToday['city']['rno']['total'] : 0) + $valToday['city']['rno'][$city->id];
        $valToday['city']['pax']['total'] = ((isset($valToday['city']['pax']['total']) and $geo->Na->date_na == $created ) ? $valToday['city']['pax']['total'] : 0) + $valToday['city']['pax'][$city->id];

        $valMonth['city']['rno'][$city->id] = (isset($month['city'][$city->id]['rno']) and date("Y-m", strtotime($geo->Na->date_na)) == date("Y-m", strtotime($created)) ) ? $month['city'][$city->id]['rno'] : 0;
        $valMonth['city']['pax'][$city->id] = (isset($month['city'][$city->id]['pax'])and date("Y-m", strtotime($geo->Na->date_na)) == date("Y-m", strtotime($created))) ? $month['city'][$city->id]['pax'] : 0;
        $valMonth['city']['rno']['total'] = ((isset($valMonth['city']['rno']['total']) and date("Y-m", strtotime($geo->Na->date_na)) == date("Y-m", strtotime($created))) ? $valMonth['city']['rno']['total'] : 0) + $valMonth['city']['rno'][$city->id];
        $valMonth['city']['pax']['total'] = ((isset($valMonth['city']['pax']['total']) and date("Y-m", strtotime($geo->Na->date_na)) == date("Y-m", strtotime($created))) ? $valMonth['city']['pax']['total'] : 0) + $valMonth['city']['pax'][$city->id];

        $valYear['city']['rno'][$city->id] = (isset($year['city'][$city->id]['rno']) and date("Y", strtotime($geo->Na->date_na)) == date("Y", strtotime($created))) ? $year['city'][$city->id]['rno'] : 0;
        $valYear['city']['pax'][$city->id] = (isset($year['city'][$city->id]['pax']) and date("Y", strtotime($geo->Na->date_na)) == date("Y", strtotime($created))) ? $year['city'][$city->id]['pax'] : 0;
        $valYear['city']['rno']['total'] = ((isset($valYear['city']['rno']['total']) and date("Y", strtotime($geo->Na->date_na)) == date("Y", strtotime($created))) ? $valYear['city']['rno']['total'] : 0) + $valYear['city']['rno'][$city->id];
        $valYear['city']['pax']['total'] = ((isset($valYear['city']['pax']['total']) and date("Y", strtotime($geo->Na->date_na)) == date("Y", strtotime($created))) ? $valYear['city']['pax']['total'] : 0) + $valYear['city']['pax'][$city->id];
    }

    $selProvince = Province::model()->findAll(array('condition' => 'id <>' . $province_id->province_id));
    foreach ($selProvince as $province) {
        $valToday['province']['rno'][$province->id] = (isset($today['province'][$province->id]['rno']) and $geo->Na->date_na == $created) ? $today['province'][$province->id]['rno'] : 0;
        $valToday['province']['pax'][$province->id] = (isset($today['province'][$province->id]['pax']) and $geo->Na->date_na == $created ) ? $today['province'][$province->id]['pax'] : 0;
        $valToday['province']['rno']['total'] = ((isset($valToday['province']['rno']['total']) and $geo->Na->date_na == $created) ? $valToday['province']['rno']['total'] : 0) + $valToday['province']['rno'][$province->id];
        $valToday['province']['pax']['total'] = ((isset($valToday['province']['pax']['total']) and $geo->Na->date_na == $created) ? $valToday['province']['pax']['total'] : 0) + $valToday['province']['pax'][$province->id];

        $valMonth['province']['rno'][$province->id] = (isset($month['province'][$province->id]['rno']) and date("Y-m", strtotime($geo->Na->date_na)) == date("Y-m", strtotime($created))) ? $month['province'][$province->id]['rno'] : 0;
        $valMonth['province']['pax'][$province->id] = (isset($month['province'][$province->id]['pax']) and date("Y-m", strtotime($geo->Na->date_na)) == date("Y-m", strtotime($created))) ? $month['province'][$province->id]['pax'] : 0;
        $valMonth['province']['rno']['total'] = ((isset($valMonth['province']['rno']['total']) and date("Y-m", strtotime($geo->Na->date_na)) == date("Y-m", strtotime($created))) ? $valMonth['province']['rno']['total'] : 0) + $valMonth['province']['rno'][$province->id];
        $valMonth['province']['pax']['total'] = ((isset($valMonth['province']['pax']['total']) and date("Y-m", strtotime($geo->Na->date_na)) == date("Y-m", strtotime($created))) ? $valMonth['province']['pax']['total'] : 0) + $valMonth['province']['pax'][$province->id];

        $valYear['province']['rno'][$province->id] = (isset($year['province'][$province->id]['rno']) and date("Y", strtotime($geo->Na->date_na)) == date("Y", strtotime($created))) ? $year['province'][$province->id]['rno'] : 0;
        $valYear['province']['pax'][$province->id] = (isset($year['province'][$province->id]['pax']) and date("Y", strtotime($geo->Na->date_na)) == date("Y", strtotime($created))) ? $year['province'][$province->id]['pax'] : 0;
        $valYear['province']['rno']['total'] = ((isset($valYear['province']['rno']['total']) and date("Y", strtotime($geo->Na->date_na)) == date("Y", strtotime($created))) ? $valYear['province']['rno']['total'] : 0) + $valYear['province']['rno'][$province->id];
        $valYear['province']['pax']['total'] = ((isset($valYear['province']['pax']['total']) and date("Y", strtotime($geo->Na->date_na)) == date("Y", strtotime($created))) ? $valYear['province']['pax']['total'] : 0) + $valYear['province']['pax'][$province->id];
    }

    $selNationality = Province::model()->nationalityList;
    foreach ($selNationality as $val => $nationality) {
        if ($val != 'ID') {
            $valToday['nationality']['rno'][$val] = (isset($today['nationality'][$val]['rno']) and $geo->Na->date_na == $created) ? $today['nationality'][$val]['rno'] : 0;
            $valToday['nationality']['pax'][$val] = (isset($today['nationality'][$val]['pax']) and $geo->Na->date_na == $created) ? $today['nationality'][$val]['pax'] : 0;
            $valToday['nationality']['rno']['total'] = ((isset($valToday['nationality']['rno']['total'])and $geo->Na->date_na == $created) ? $valToday['nationality']['rno']['total'] : 0) + $valToday['nationality']['rno'][$val];
            $valToday['nationality']['pax']['total'] = ((isset($valToday['nationality']['pax']['total']) and $geo->Na->date_na == $created) ? $valToday['nationality']['pax']['total'] : 0) + $valToday['nationality']['pax'][$val];

            $valMonth['nationality']['rno'][$val] = (isset($month['nationality'][$val]['rno']) and date("Y-m", strtotime($geo->Na->date_na)) == date("Y-m", strtotime($created))) ? $month['nationality'][$val]['rno'] : 0;
            $valMonth['nationality']['pax'][$val] = (isset($month['nationality'][$val]['pax']) and date("Y-m", strtotime($geo->Na->date_na)) == date("Y-m", strtotime($created))) ? $month['nationality'][$val]['pax'] : 0;
            $valMonth['nationality']['rno']['total'] = ((isset($valMonth['nationality']['rno']['total']) and date("Y-m", strtotime($geo->Na->date_na)) == date("Y-m", strtotime($created))) ? $valMonth['nationality']['rno']['total'] : 0) + $valMonth['nationality']['rno'][$val];
            $valMonth['nationality']['pax']['total'] = ((isset($valMonth['nationality']['pax']['total']) and date("Y-m", strtotime($geo->Na->date_na)) == date("Y-m", strtotime($created))) ? $valMonth['nationality']['pax']['total'] : 0) + $valMonth['nationality']['pax'][$val];

            $valYear['nationality']['rno'][$val] = (isset($year['nationality'][$val]['rno']) and date("Y", strtotime($geo->Na->date_na)) == date("Y", strtotime($created))) ? $year['nationality'][$val]['rno'] : 0;
            $valYear['nationality']['pax'][$val] = (isset($year['nationality'][$val]['pax']) and date("Y", strtotime($geo->Na->date_na)) == date("Y", strtotime($created))) ? $year['nationality'][$val]['pax'] : 0;
            $valYear['nationality']['rno']['total'] = ((isset($valYear['nationality']['rno']['total']) and date("Y", strtotime($geo->Na->date_na)) == date("Y", strtotime($created))) ? $valYear['nationality']['rno']['total'] : 0) + $valYear['nationality']['rno'][$val];
            $valYear['nationality']['pax']['total'] = ((isset($valYear['nationality']['pax']['total']) and date("Y", strtotime($geo->Na->date_na)) == date("Y", strtotime($created))) ? $valYear['nationality']['pax']['total'] : 0) + $valYear['nationality']['pax'][$val];
        }
    }

    $todayTotal = $valToday['city']['rno']['total'] + $valToday['province']['rno']['total'] + $valToday['nationality']['rno']['total'];
    $monthTotal = $valMonth['city']['rno']['total'] + $valMonth['province']['rno']['total'] + $valMonth['nationality']['rno']['total'];
    $yearTotal = $valYear['city']['rno']['total'] + $valYear['province']['rno']['total'] + $valYear['nationality']['rno']['total'];
    ?><h2 align="center">Geographical Origin Report</h2>
    <h3 align="center"><?php echo date('d M Y', strtotime($created)) ?></h3>
    <table class="table  table">
        <thead>
            <tr>
                <th colspan="10"><?php echo strtoupper($province_id->Province->name) ?></th>
            </tr>
            <tr> 
                <th rowspan="2" width="20%">City</th>
                <th colspan="3" width="30%">TODAY</th>
                <th colspan="3" width="30%">MTD</th>
                <th colspan="3">YTD</th>
            </tr>

            <tr>
                <th>RNO</th>
                <th>%</th>
                <th>PAX</th>
                <th>RNO</th>
                <th>%</th>
                <th>PAX</th>
                <th>RNO</th>
                <th>%</th>
                <th>PAX</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($selCity as $city) {
                if ($todayTotal > 0) {
                    $valToday['city']['percent'][$city->id] = number_format((($valToday['city']['rno'][$city->id] / $todayTotal) * 100), 2);
                } else {
                    $valToday['city']['percent'][$city->id] = 0;
                }

                if ($monthTotal > 0) {
                    $valMonth['city']['percent'][$city->id] = number_format((($valMonth['city']['rno'][$city->id] / $monthTotal) * 100), 2);
                } else {
                    $valMonth['city']['percent'][$city->id] = 0;
                }

                if ($yearTotal > 0) {
                    $valYear['city']['percent'][$city->id] = number_format((($valYear['city']['rno'][$city->id] / $yearTotal) * 100), 2);
                } else {
                    $valYear['city']['percent'][$city->id] = 0;
                }

                echo '<tr>
                        <td>' . strtoupper($city->name) . '</td>
                        <td style="text-align:right">' . $valToday['city']['rno'][$city->id] . '</td>
                        <td style="text-align:right">' . $valToday['city']['percent'][$city->id] . '</td>
                        <td style="text-align:right">' . $valToday['city']['pax'][$city->id] . '</td>
                        <td style="text-align:right">' . $valMonth['city']['rno'][$city->id] . '</td>
                        <td style="text-align:right">' . $valMonth['city']['percent'][$city->id] . '</td>
                        <td style="text-align:right">' . $valMonth['city']['pax'][$city->id] . '</td>
                        <td style="text-align:right">' . $valYear['city']['rno'][$city->id] . '</td>
                        <td style="text-align:right">' . $valYear['city']['percent'][$city->id] . '</td>
                        <td style="text-align:right">' . $valYear['city']['pax'][$city->id] . '</td>
                    </tr>';
            }
            ?>
            <tr>
                <td><b>TOTAL</b></td>
                <td style="text-align:right"><?php echo $valToday['city']['rno']['total'] ?></td>
                <td style="text-align:right"><?php echo array_sum($valToday['city']['percent']) ?></td>
                <td style="text-align:right"><?php echo $valToday['city']['pax']['total'] ?></td>
                <td style="text-align:right"><?php echo $valMonth['city']['rno']['total'] ?></td>
                <td style="text-align:right"><?php echo array_sum($valMonth['city']['percent']) ?></td>
                <td style="text-align:right"><?php echo $valMonth['city']['pax']['total'] ?></td>
                <td style="text-align:right"><?php echo $valYear['city']['rno']['total'] ?></td>
                <td style="text-align:right"><?php echo array_sum($valYear['city']['percent']) ?></td>
                <td style="text-align:right"><?php echo $valYear['city']['pax']['total'] ?></td>
            </tr>
        </tbody>
        <thead>
            <tr>
                <th colspan="10">INDONESIA</th>
            </tr>
            <tr> 
                <th rowspan="2" width="20%">PROVINCE</th>
                <th colspan="3" width="30%">TODAY</th>
                <th colspan="3" width="30%">MTD</th>
                <th colspan="3">YTD</th>
            </tr>
            <tr>
                <th>RNO</th>
                <th>%</th>
                <th>PAX</th>
                <th>RNO</th>
                <th>%</th>
                <th>PAX</th>
                <th>RNO</th>
                <th>%</th>
                <th>PAX</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($selProvince as $province) {
                if ($todayTotal > 0) {
                    $valToday['province']['percent'][$province->id] = number_format((($valToday['province']['rno'][$province->id] / $todayTotal) * 100), 2);
                } else {
                    $valToday['province']['percent'][$province->id] = 0;
                }

                if ($monthTotal > 0) {
                    $valMonth['province']['percent'][$province->id] = number_format((($valMonth['province']['rno'][$province->id] / $monthTotal) * 100), 2);
                } else {
                    $valMonth['province']['percent'][$province->id] = 0;
                }

                if ($yearTotal > 0) {
                    $valYear['province']['percent'][$province->id] = number_format((($valYear['province']['rno'][$province->id] / $yearTotal) * 100), 2);
                } else {
                    $valYear['province']['percent'][$province->id] = 0;
                }

                echo '<tr>
                        <td>' . strtoupper($province->name) . '</td>
                        <td style="text-align:right">' . $valToday['province']['rno'][$province->id] . '</td>
                        <td style="text-align:right">' . $valToday['province']['percent'][$province->id] . '</td>
                        <td style="text-align:right">' . $valToday['province']['pax'][$province->id] . '</td>
                        <td style="text-align:right">' . $valMonth['province']['rno'][$province->id] . '</td>
                        <td style="text-align:right">' . $valMonth['province']['percent'][$province->id] . '</td>
                        <td style="text-align:right">' . $valMonth['province']['pax'][$province->id] . '</td>
                        <td style="text-align:right">' . $valYear['province']['rno'][$province->id] . '</td>
                        <td style="text-align:right">' . $valYear['province']['percent'][$province->id] . '</td>
                        <td style="text-align:right">' . $valYear['province']['pax'][$province->id] . '</td>
                    </tr>';
            }
            ?>
            <tr>
                <td><b>TOTAL</b></td>
                <td style="text-align:right"><?php echo $valToday['province']['rno']['total'] ?></td>
                <td style="text-align:right"><?php echo array_sum($valToday['province']['percent']) ?></td>
                <td style="text-align:right"><?php echo $valToday['province']['pax']['total'] ?></td>
                <td style="text-align:right"><?php echo $valMonth['province']['rno']['total'] ?></td>
                <td style="text-align:right"><?php echo array_sum($valMonth['province']['percent']) ?></td>
                <td style="text-align:right"><?php echo $valMonth['province']['pax']['total'] ?></td>
                <td style="text-align:right"><?php echo $valYear['province']['rno']['total'] ?></td>
                <td style="text-align:right"><?php echo array_sum($valYear['province']['percent']) ?></td>
                <td style="text-align:right"><?php echo $valYear['province']['pax']['total'] ?></td>
            </tr>
        </tbody>
        <thead>
            <tr>
                <th colspan="10">WORLD</th>
            </tr>
            <tr> 
                <th rowspan="2" width="20%">COUNTRY</th>
                <th colspan="3" width="30%">TODAY</th>
                <th colspan="3" width="30%">MTD</th>
                <th colspan="3">YTD</th>
            </tr>
            <tr>
                <th>RNO</th>
                <th>%</th>
                <th>PAX</th>
                <th>RNO</th>
                <th>%</th>
                <th>PAX</th>
                <th>RNO</th>
                <th>%</th>
                <th>PAX</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($selNationality as $val => $nationality) {
                if ($val != 'ID') {
                    if ($todayTotal > 0) {
                        $valToday['nationality']['percent'][$val] = number_format((($valToday['nationality']['rno'][$val] / $todayTotal) * 100), 2);
                    } else {
                        $valToday['nationality']['percent'][$val] = 0;
                    }

                    if ($monthTotal > 0) {
                        $valMonth['nationality']['percent'][$val] = number_format((($valMonth['nationality']['rno'][$val] / $monthTotal) * 100), 2);
                    } else {
                        $valMonth['nationality']['percent'][$val] = 0;
                    }

                    if ($yearTotal > 0) {
                        $valYear['nationality']['percent'][$val] = number_format((($valYear['nationality']['rno'][$val] / $yearTotal) * 100), 2);
                    } else {
                        $valYear['nationality']['percent'][$val] = 0;
                    }

                    echo '<tr>
                        <td>' . strtoupper($nationality) . '</td>
                        <td style="text-align:right">' . $valToday['nationality']['rno'][$val] . '</td>
                        <td style="text-align:right">' . $valToday['nationality']['percent'][$val] . '</td>
                        <td style="text-align:right">' . $valToday['nationality']['pax'][$val] . '</td>
                        <td style="text-align:right">' . $valMonth['nationality']['rno'][$val] . '</td>
                        <td style="text-align:right">' . $valMonth['nationality']['percent'][$val] . '</td>
                        <td style="text-align:right">' . $valMonth['nationality']['pax'][$val] . '</td>
                        <td style="text-align:right">' . $valYear['nationality']['rno'][$val] . '</td>
                        <td style="text-align:right">' . $valYear['nationality']['percent'][$val] . '</td>
                        <td style="text-align:right">' . $valYear['nationality']['pax'][$val] . '</td>
                    </tr>';
                }
            }
            ?>
            <tr>
                <td><b>TOTAL</b></td>
                <td style="text-align:right"><?php echo $valToday['nationality']['rno']['total'] ?></td>
                <td style="text-align:right"><?php echo array_sum($valToday['nationality']['percent']) ?></td>
                <td style="text-align:right"><?php echo $valToday['nationality']['pax']['total'] ?></td>
                <td style="text-align:right"><?php echo $valMonth['nationality']['rno']['total'] ?></td>
                <td style="text-align:right"><?php echo array_sum($valMonth['nationality']['percent']) ?></td>
                <td style="text-align:right"><?php echo $valMonth['nationality']['pax']['total'] ?></td>
                <td style="text-align:right"><?php echo $valYear['nationality']['rno']['total'] ?></td>
                <td style="text-align:right"><?php echo array_sum($valYear['nationality']['percent']) ?></td>
                <td style="text-align:right"><?php echo $valYear['nationality']['pax']['total'] ?></td>
            </tr>
        </tbody>
        <tfoot>
            <tr>
                <td><b>GRAND TOTAL</b></td>
                <td style="text-align:right"><?php echo $valToday['city']['rno']['total'] + $valToday['province']['rno']['total'] + $valToday['nationality']['rno']['total'] ?></td>
                <td style="text-align:right"><?php echo number_format(array_sum($valToday['city']['percent']) + array_sum($valToday['province']['percent']) + array_sum($valToday['nationality']['percent']), 2) ?></td>
                <td style="text-align:right"><?php echo $valToday['city']['pax']['total'] + $valToday['province']['pax']['total'] + $valToday['nationality']['pax']['total'] ?></td>
                <td style="text-align:right"><?php echo $valMonth['city']['rno']['total'] + $valMonth['province']['rno']['total'] + $valMonth['nationality']['rno']['total'] ?></td>
                <td style="text-align:right"><?php echo number_format(array_sum($valMonth['city']['percent']) + array_sum($valMonth['province']['percent']) + array_sum($valMonth['nationality']['percent']), 2) ?></td>
                <td style="text-align:right"><?php echo $valMonth['city']['pax']['total'] + $valMonth['province']['pax']['total'] + $valMonth['nationality']['pax']['total'] ?></td>
                <td style="text-align:right"><?php echo $valYear['city']['rno']['total'] + $valYear['province']['rno']['total'] + $valYear['nationality']['rno']['total'] ?></td>
                <td style="text-align:right"><?php echo number_format(array_sum($valYear['city']['percent']) + array_sum($valYear['province']['percent']) + array_sum($valYear['nationality']['percent']), 2) ?></td>
                <td style="text-align:right"><?php echo $valYear['city']['pax']['total'] + $valYear['province']['pax']['total'] + $valYear['nationality']['pax']['total'] ?></td>
            </tr>
        </tfoot>
    </table>
    <?php
} else {
    echo '<div class="well"><h3>No Data Available</h3></div>';
}
?>