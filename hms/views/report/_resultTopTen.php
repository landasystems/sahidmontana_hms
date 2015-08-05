<?php

if ($_POST['month'] < 10) {
    $month = '0' . $_POST['month'];
} else {
    $month = $_POST['month'];
}

$year = $_POST['year'];

$role = Roles::model()->findAll(array('condition' => 'prefix = 0'));
$data = ReportGeographical::model()->with('Na')->find(array('condition' => 'month(Na.date_na) = "' . $month . '" and year(Na.date_na)="' . $year . '"', 'order' => 'Na.date_na desc'));
$report = empty($data) ? array() : json_decode($data->top_ten, TRUE);

foreach ($role as $val) {
    $body = '';
    $no = 1;
    if (!isset($report[$val->id]) or empty($report[$val->id])) {
        $body = '<tr>'
                . '<td colspan="5">No Data Available</td>'
                . '</tr>';
    } else {
        arsort($report[$val->id]);

        foreach ($report[$val->id] as $valTop => $o) {
            $user = User::model()->findByPk($valTop);
            $body .= '<tr>
                        <td>' . $no . '</td>
                        <td>' . strtoupper($user->name) . '</td>
                        <td style="text-align:right; width:10%">' . $o['rno'] . '</td>
                        <td style="text-align:right; width:10%">' . number_format($o['revenue']) . '</td>
                        <td style="text-align:right; width:10%"></td>
                    </tr>';
            $no++;
            if ($no == 11) {
                break;
            }
        }
    }
    echo '  <table class="table ">
                <thead>
                    <tr>
                        <th width="35">No</th>
                        <th >' . $val->name . '</th>
                        <th width="300">RNO</th>
                        <th width="300">Revenue</th>
                        <th width="300">Remark</th>
                    </tr>
                </thead>
                <tbody>
                    ' . $body . '
                </tbody>
            </table>
    ';
}
?>