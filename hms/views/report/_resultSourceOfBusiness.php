<table class="table table-bordered">
    <thead>
        <tr>
            <th rowspan="2" width="200">SEGMENT</th>
            <th colspan="4">MONTH TO DATE</th>
            <th colspan="4">YEAR TO DATE</th>
        </tr>
        <tr>
            <th>Room Nights</th>
            <th>No. Of Guest</th>
            <th>Average Room Rate</th>
            <th>Room Revenue</th>
            <th>Room Nights</th>
            <th>No. Of Guest</th>
            <th>Average Room Rate</th>
            <th>Room Revenue</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if ($_POST['month'] < 10) {
            $month = '0' . $_POST['month'];
        } else {
            $month = $_POST['month'];
        }
        $year = $_POST['year'];

        $select = ReportGeographical::model()->with('Na')->find(array('condition' => 'month(Na.date_na) = "' . $month . '" and year(Na.date_na)="' . $year . '"', 'order' => 'Na.date_na desc'));
        if (!empty($select)) {
            $sobMonth = empty($select) ? array() : json_decode($select->sob_month, TRUE);
            $sobYear = empty($select) ? array() : json_decode($select->sob_year, TRUE);
        } else {
            $select = ReportGeographical::model()->with('Na')->find(array('condition' => 'month(Na.date_na) <= "' . $month . '" and year(Na.date_na)="' . $year . '"', 'order' => 'Na.date_na desc'));
            $sobMonth = array();
            $sobYear = empty($select) ? array() : json_decode($select->sob_year, TRUE);
        }

        if (!empty($select)) {
            $marketSegment = MarketSegment::model()->findAll(array('condition' => 'parent_id = 0'));
            $month = array();
            $year = array();
            foreach ($marketSegment as $val) {
                $month['rno'][$val->id] = 0;
                $month['pax'][$val->id] = 0;
                $month['average'][$val->id] = 0;
                $month['revenue'][$val->id] = 0;

                $year['rno'][$val->id] = 0;
                $year['pax'][$val->id] = 0;
                $year['average'][$val->id] = 0;
                $year['revenue'][$val->id] = 0;
                echo '<tr>
                        <td style="text-align:center;"><b>' . $val->name . '</b></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                    </tr>';
                $descendants = $val->descendants()->findAll();
                foreach ($descendants as $valChild) {
                    $month[$val->id][$valChild->id]['rno'] = isset($sobMonth[$valChild->id]['rno']) ? $sobMonth[$valChild->id]['rno'] : 0;
                    $month[$val->id][$valChild->id]['revenue'] = isset($sobMonth[$valChild->id]['revenue']) ? $sobMonth[$valChild->id]['revenue'] : 0;
                    $month[$val->id][$valChild->id]['pax'] = isset($sobMonth[$valChild->id]['pax']) ? $sobMonth[$valChild->id]['pax'] : 0;
                    if ($month[$val->id][$valChild->id]['rno'] == 0) {
                        $month[$val->id][$valChild->id]['average'] = 0;
                    } else {
                        $month[$val->id][$valChild->id]['average'] = $month[$val->id][$valChild->id]['revenue'] / $month[$val->id][$valChild->id]['rno'];
                    }

                    $year[$val->id][$valChild->id]['rno'] = isset($sobYear[$valChild->id]['rno']) ? $sobYear[$valChild->id]['rno'] : 0;
                    $year[$val->id][$valChild->id]['revenue'] = isset($sobYear[$valChild->id]['revenue']) ? $sobYear[$valChild->id]['revenue'] : 0;
                    $year[$val->id][$valChild->id]['pax'] = isset($sobYear[$valChild->id]['pax']) ? $sobYear[$valChild->id]['pax'] : 0;
                    if ($year[$val->id][$valChild->id]['rno'] == 0) {
                        $year[$val->id][$valChild->id]['average'] = 0;
                    } else {
                        $year[$val->id][$valChild->id]['average'] = $year[$val->id][$valChild->id]['revenue'] / $year[$val->id][$valChild->id]['rno'];
                    }

                    echo '<tr>
                        <td>' . $valChild->name . '</td>
                            <td style="text-align:right;">' . $month[$val->id][$valChild->id]['rno'] . '</td>
                            <td style="text-align:right;">' . $month[$val->id][$valChild->id]['pax'] . '</td>
                            <td style="text-align:right;">' . number_format($month[$val->id][$valChild->id]['average']) . '</td>
                            <td style="text-align:right;">' . number_format($month[$val->id][$valChild->id]['revenue']) . '</td>
                            <td style="text-align:right;">' . $year[$val->id][$valChild->id]['rno'] . '</td>
                            <td style="text-align:right;">' . $year[$val->id][$valChild->id]['pax'] . '</td>
                            <td style="text-align:right;">' . number_format($year[$val->id][$valChild->id]['average']) . '</td>
                            <td style="text-align:right;">' . number_format($year[$val->id][$valChild->id]['revenue']) . '</td>
                    </tr>';

                    $month['rno'][$val->id] += $month[$val->id][$valChild->id]['rno'];
                    $month['pax'][$val->id] += $month[$val->id][$valChild->id]['pax'];
                    $month['average'][$val->id] += $month[$val->id][$valChild->id]['average'];
                    $month['revenue'][$val->id] += $month[$val->id][$valChild->id]['revenue'];

                    $year['rno'][$val->id] += $year[$val->id][$valChild->id]['rno'];
                    $year['pax'][$val->id] += $year[$val->id][$valChild->id]['pax'];
                    $year['average'][$val->id] += $year[$val->id][$valChild->id]['average'];
                    $year['revenue'][$val->id] += $year[$val->id][$valChild->id]['revenue'];
                }
                echo '<tr>
                        <td><b>SUB TOTAL ' . $val->name . '</b></td>
                            <td style="text-align:right">' . $month['rno'][$val->id] . '</td>
                            <td style="text-align:right">' . $month['pax'][$val->id] . '</td>
                            <td style="text-align:right">' . number_format($month['average'][$val->id]) . '</td>
                            <td style="text-align:right">' . number_format($month['revenue'][$val->id]) . '</td>
                            <td style="text-align:right">' . $year['rno'][$val->id] . '</td>
                            <td style="text-align:right">' . $year['pax'][$val->id] . '</td>
                            <td style="text-align:right">' . number_format($year['average'][$val->id]) . '</td>
                            <td style="text-align:right">' . number_format($year['revenue'][$val->id]) . '</td>
                    </tr>';
            }
            echo '<tr>
                        <td><b>TOTAL</b></td>
                            <td style="text-align:right">' . array_sum($month['rno']) . '</td>
                            <td style="text-align:right">' . array_sum($month['pax']) . '</td>
                            <td style="text-align:right">' . number_format(array_sum($month['average'])) . '</td>
                            <td style="text-align:right">' . number_format(array_sum($month['revenue'])) . '</td>
                            <td style="text-align:right">' . array_sum($year['rno']) . '</td>
                            <td style="text-align:right">' . array_sum($year['pax']) . '</td>
                            <td style="text-align:right">' . number_format(array_sum($year['average'])) . '</td>
                            <td style="text-align:right">' . number_format(array_sum($year['revenue'])) . '</td>
                    </tr>';
        } else {
            echo '<tr>
                    <td colspan="9">No Data Available</td>
                  </tr>';
        }
        ?>
    </tbody>
</table>