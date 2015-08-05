<style>
    th {
        vertical-align: middle !important;
    }
</style>
<center><h3>EXPECTED GUEST ARRIVAL / DEPARTURE <?php echo strtoupper(date("d-M-Y", strtotime('+1 days', strtotime($siteConfig->date_system)))); ?></h3></center>
<center>Date Night Audit : <?php echo date("d-M-Y", strtotime($siteConfig->date_system)); ?></center>
<hr>
<table class="items table table-striped table-hover  table-condensed">
    <thead>
        <tr>
            <th colspan="7" style="text-align: center">ARRIVAL</th>
            <th colspan="3" style="text-align: center">DEPARTURE</th>
        </tr>                                                   
    </thead>

    <tr>
        <td colspan="7" style="margin:0px;padding: 0px">
            <table style="width:100%">

                <tr>
                    <th class="span2" style="text-align: center">Room</th>
                    <th class="span2" style="text-align: center">Name</th>            
                    <th class="span1" style="text-align: center">Arrival</th>                                                     
                    <th class="span1" style="text-align: center">Departure</th>    
                    <th class="span1" style="text-align: center">Nights</th>                        
                    <th class="span1" style="text-align: center">Rooms</th> 
                    <th class="span2" style="text-align: center">Remarks</th>                                                   
                </tr> 

                <?php
                $totRoomArrival = 0;
                $totNightArrival = 0;
                foreach ($expectedArrival as $arrival) {
                    echo '<tr>';
                    echo '<td style="text-align:center">' . $arrival->room . '</td>';
                    echo '<td style="text-align:left">' . $arrival->Guest->guestName . '</td>';
                    echo '<td style="text-align:left">' . date('M-d', strtotime($arrival->date_from)) . '</td>';
                    echo '<td style="text-align:left">' . date('M-d', strtotime($arrival->date_to)) . '</td>';
                    echo '<td style="text-align:center">' . $arrival->totalNight . '</td>';
                    echo '<td style="text-align:center">' . $arrival->totalRoom . '</td>';
                    echo '<td style="text-align:left">' . $arrival->remarks . '</td>';
                    echo '</tr>';
                    $totRoomArrival += $arrival->totalRoom;
                    $totNightArrival += $arrival->totalNight;
                }
                ?>
                <tr>
                    <th colspan="4">Total EA</th>
                    <th style="text-align:center"><?php echo $totNightArrival; ?></th>
                    <th style="text-align: center"><?php echo $totRoomArrival; ?></th>
                    <th></th>
                </tr>

            </table>
        </td>
        <td colspan="3" style="margin:0px;padding: 0px">
            <table style="width:100%">

                <tr>           
                    <th class="span2" style="text-align: center">Room</th>                                               
                    <th class="span2" style="text-align: center">Name</th>                        
                    <th class="span1" style="text-align: center">Rooms</th>                                  
                </tr> 


                <?php      
                foreach ($expectedDeparture as $dep) {
                    $registration[] = $dep->registration_id;                    
                }
                if (!isset($registration))
                    $registration = array();
                $uniqRegistration = array_unique($registration);
                $totRoomDeparture = 0;
                foreach ($uniqRegistration as $reg) {
                    $regId = $reg;
                    $filterDeparture = array_filter($expectedDeparture, function($expectedDeparture) use($regId) {
                                return $expectedDeparture['registration_id'] == $regId;
                            });
                    if (count($filterDeparture) > 0) {
                        $room = '';
                        $noROom = 0;
                        $name = '';
                        foreach ($filterDeparture as $departure) {
                            $room .= $departure->room_number . ', ';
                            $name = $departure->Registration->Guest->guestName;
                            $noROom++;
                        }
                        $room = substr($room, 0, strlen($room) - 1);
                        echo '<tr>';
                        echo '<td style="text-align:center">' . $room . '</td>';
                        echo '<td style="text-align:left">' . $name . '</td>';
                        echo '<td style="text-align:center">' . $noROom . '</td>';
                        echo '</tr>';
                        $totRoomDeparture += $noROom;
                    }
                }
                ?>
                <tr>
                    <th colspan="2">Total ED</th>
                    <th style="text-align: center"><?php echo $totRoomDeparture; ?></th>

                </tr>
            </table>
        </td>
    </tr>        

</table>
<table>
    <tr>
        <td style="padding: 0px" class="span2">Audit By</td>        
        <td style="padding: 0px">: <?php echo User()->name; ?></td>
    </tr>    
    <tr>
        <td style="padding: 0px">Printed Time</td>        
        <td style="padding: 0px">: <?php echo date('l d-M-Y H:i:s'); ?></td>
    </tr>    
</table>