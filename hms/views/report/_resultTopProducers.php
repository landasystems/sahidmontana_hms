<?php
$role = Roles::model()->findAll(array('condition' => 'prefix = 0'));
$data = ReportGeographical::model()->with('Na')->find(array('condition' => 'year(Na.date_na)="' . $_POST['year'] . '"', 'order' => 'Na.date_na Desc'));
$report = empty($data) ? array() : json_decode($data->top_producer, TRUE);
foreach ($role as $val) {
    ?>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th rowspan="2">No.</th>
                <th rowspan="2" width="200"><?php echo $val->name ?></th>
                <th rowspan="2" width="100">Phone No</th>
                <th colspan="12" style="text-align:center">Month</th>
                <th rowspan="2" width="100">Jumlah</th>
            </tr>
            <tr>
                <?php
                $total = array();
                $month = array('Jan', 'Feb', 'March', 'April', 'May', 'June', 'July', 'Aug', 'Sept', 'Oct', 'Nov', 'Dec');
                for ($i = 0; $i < 12; $i++) {
                    $total[$i] = 0;
                    echo '<th>' . $month[$i] . '</th>';
                }
                ?>
            </tr>
        </thead>
        <tbody>
            <?php
            $producers = User::model()->findAll(array('condition' => 'roles_id=' . $val->id));
            $no = 1;
            $jumlah = array();
            foreach ($producers as $valProducers) {
                $jumlah[$valProducers->id] = 0;
                ?>
                <tr>
                    <td><?php echo $no++; ?></td>
                    <td><?php echo strtoupper($valProducers->name) ?></td>
                    <td><?php echo $valProducers->phone ?></td>
                    <?php
                    $bln = 1;
                    for ($i = 0; $i < 12; $i++) {
                        if ($bln < 10) {
                            $bulan = '0' . $bln;
                        } else {
                            $bulan = $bulan;
                        }

                        $nilai = isset($report[$bulan][$valProducers->id]) ? $report[$bulan][$valProducers->id]['rno'] : 0;
                        $total[$i] += $nilai;
                        $jumlah[$valProducers->id] += $nilai;
                        echo '<td>' . $nilai . '</td>';
                        $bln++;
                    }
                    ?>
                    <td><?php echo $jumlah[$valProducers->id] ?></td>
                </tr>
                <?php
            }
            ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" style="text-align: center">Total</td>
                <?php
                for ($i = 0; $i < 12; $i++) {
                    echo '<td>' . $total[$i] . '</td>';
                }
                ?>
                <td><?php echo array_sum($jumlah) ?></td>
            </tr>
        </tfoot>
    </table>
    <?php
}
?>