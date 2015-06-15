<?php
$a = explode('-', $created);
$start = date('d M Y', strtotime($a[0]));
$end = date('d M Y', strtotime($a[1]));
?>
<table width="100%">
    <tr>
        <td  style="text-align: center" colspan="3"><h2>GUEST IN HOUSE</h2>
            <?php echo date('d/m/Y', strtotime($a[0])) . " - " . $end = date('d/m/Y', strtotime($a[1])); ?>
            <hr></td>
    </tr>   


</table>
<table class="responsive table table-bordered" width="100%">
    <thead>
        <tr>
            <th rowspan="2">No</th>
            <th colspan="2">Room</th>
            <th rowspan="2">Registration Number</th>
            <th rowspan="2">Guest Name</th>
            <th rowspan="2">Address</th>
            <th rowspan="2">Market Segment</th>
            <th rowspan="2">Arrival</th>
            <th rowspan="2">Departure</th>
            <th rowspan="2">Rate Charge</th>
        </tr>
        <tr>
            <th width="50">#</th>
            <th width="50">TYPE</th>
        </tr>
    </thead>
    <?php
    $no = 1;
    $reg[] = array();
    foreach ($roomBill as $s) {
        if (!in_array($s->Registration->id, $reg)) {
            echo '
            <tr>
            <td>' . $no . '</td>
            <td>' . $s->Room->number . '</td>
            <td>' . $s->Room->RoomType->name . '</td>
            <td>' . $s->Registration->code . '</td>
            <td>' . $s->Registration->Guest->name . '</td>
            <td>' . $s->Registration->Guest->City->name . '</td>
            <td>' . $s->Registration->MarketSegment->name . '</td>
            <td>' . date('d M Y', strtotime($s->Registration->date_from)) . '</td>
            <td>' . date('d M Y', strtotime($s->Registration->date_to)) . '</td>
            <td>' . landa()->rp($s->charge) . '</td>
            ';
            $no++;
            $reg[] = $s->Registration->id;
        }
    }
    ?>
</table>