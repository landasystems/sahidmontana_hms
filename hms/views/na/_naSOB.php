<?php
//from siteconfig        
$settings = json_decode($siteConfig->settings, true);
$roomAcc = (!empty($settings['room_account'])) ? $settings['room_account'] : '';
$initial_food_analysis = json_decode($initialForecast->food_analysis, true);
?>
<center><h3>SOURCES OF BUSINESS</h3></center>
<center>Date Night Audit : <?php echo date("d-M-Y", strtotime($siteConfig->date_system)); ?></center>
<hr>

<table class="items table  table-condensed">
    <thead>
        <tr>            
            <th rowspan="2" style="text-align: center">SEGMENT</th>            
            <th colspan="5" style="text-align: center">TODAY</th>            
            <th colspan="5" style="text-align: center">MONTH TO DATE</th>            
            <th colspan="5" style="text-align: center">YEAR TO DATE</th>                               
        </tr>                        
        <tr>            
            <th  style="text-align: center">Room Nights</th>            
            <th  style="text-align: center">% OCC</th>            
            <th  style="text-align: center">No. Of Guest</th>            
            <th  style="text-align: center">Avg. Room Rate</th>                               
            <th  style="text-align: center">Room Revenue</th>     

            <th  style="text-align: center">Room Nights</th>            
            <th  style="text-align: center">% OCC</th>            
            <th  style="text-align: center">No. Of Guest</th>            
            <th  style="text-align: center">Avg. Room Rate</th>                               
            <th  style="text-align: center">Room Revenue</th> 

            <th  style="text-align: center">Room Nights</th>            
            <th  style="text-align: center">% OCC</th>            
            <th  style="text-align: center">No. Of Guest</th>            
            <th  style="text-align: center">Avg. Room Rate</th>                               
            <th  style="text-align: center">Room Revenue</th>                               
        </tr>                        
    </thead>
    <tbody>
        <?php
        foreach ($marketSegment as $market) {
            $roomNightToday[$market->id] = 0;
            $numberOfGuestToday[$market->id] = 0;
        }

        foreach ($roomBills as $r) {
            if ($r->is_checkedout == 0) {
                $roomNightToday[$r->Registration->MarketSegment->id]++;
                $numberOfGuestToday[$r->Registration->MarketSegment->id] += $r->pax;
            }
        }


        $root = '';
        $nameRoot = '';
        foreach ($marketSegment as $market) {
            if ($root != $market->root && $root != '') {
                echo '<tr>';
                echo '<td><b>SUB TOTAL ' . strtoupper($nameRoot) . '</b></td>';
                echo '<td></td>';
                echo '<td></td>';
                echo '<td></td>';
                echo '<td></td>';
                echo '<td></td>';
                echo '<td></td>';
                echo '<td></td>';
                echo '<td></td>';
                echo '<td></td>';
                echo '<td></td>';
                echo '<td></td>';
                echo '<td></td>';
                echo '<td></td>';
                echo '<td></td>';
                echo '<td></td>';
                echo '</tr>';
            }
            ?>
            <tr>
                <td><?php echo $market->NestedNameSob ?></td>
                <td><?php echo ($market->level != 1) ? $roomNightToday[$market->id] : ''; ?></td>
                <td></td>
                <td><?php echo ($market->level != 1) ? $numberOfGuestToday[$market->id] : ''; ?></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <?php
            $root = $market->root;
            if ($market->root == $market->id)
                $nameRoot = $market->name;
        }
        echo '<tr>';
        echo '<td><b>SUB TOTAL ' . strtoupper($nameRoot) . '</b></td>';
        echo '<td></td>';
        echo '<td></td>';
        echo '<td></td>';
        echo '<td></td>';
        echo '<td></td>';
        echo '<td></td>';
        echo '<td></td>';
        echo '<td></td>';
        echo '<td></td>';
        echo '<td></td>';
        echo '<td></td>';
        echo '<td></td>';
        echo '<td></td>';
        echo '<td></td>';
        echo '<td></td>';
        echo '</tr>';
        ?>
    </tbody>
</table>