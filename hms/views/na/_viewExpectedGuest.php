<style>
    th {
        vertical-align: middle !important;
    }
</style>
<div style="text-align: right">
    <button class="print entypo-icon-printer button" onclick="printDiv('na_expected')" type="button">&nbsp;&nbsp;Print Report</button>
</div>
<div id="na_expected" class="na_expected" style="width: 100%">
    <center><h4>EXPECTED GUEST ARRIVAL / DEPARTURE <?php echo strtoupper(date("d-M-Y", strtotime('+1 days', strtotime($model->date_na)))); ?></h4></center>
    <center>Date Night Audit : <?php echo date("d-M-Y", strtotime($model->date_na)); ?></center>
    <hr style="border-bottom: 2px solid #bbb !important;border-top: 1px solid #bbb !important;height: 3px">
    <table class="tbPrint" style="font-size: 10px;line-height: 10px !important;">
        <thead>
            <tr>
                <th class="print2" colspan="7" style="text-align: center;border-bottom:solid #000 2px !important;border-top:solid #000 2px">ARRIVAL</th>
                <th class="print2" colspan="3" style="text-align: center;border-bottom:solid #000 2px !important;border-top:solid #000 2px">DEPARTURE</th>
            </tr>                                                   
        </thead>

        <tr>
            <td colspan="7" style="margin:0px;padding: 3px;vertical-align: top">
                <table style="width:100%">

                    <tr>
                        <th class="span2 print2" style="text-align: center">Room</th>
                        <th class="span3 print2" style="text-align: center">Name</th>            
                        <th class="span1 print2" style="text-align: center">Arrival</th>                                                     
                        <th class="span1 print2" style="text-align: center">Departure</th>    
                        <th class="span1 print2" style="text-align: center">Nights</th>                        
                        <th class="span1 print2" style="text-align: center">Rooms</th> 
                        <th class="span2 print2" style="text-align: center">Remarks</th>                                                   
                    </tr> 

                    <?php
                    $totRoomArrival = 0;
                    $totNightArrival = 0;
                    foreach ($expectedArrival as $arrival) {
                        if (isset($arrival->Reservation->room)) {
                            echo '<tr>';
                            echo '<td class="print2" style="text-align:center;border-bottom:none;border-top:none">' . $arrival->Reservation->room . '</td>';
                            echo '<td class="print2" style="text-align:left;border-bottom:none;border-top:none">' . $arrival->Reservation->Guest->guestName . '</td>';
                            echo '<td class="print2" style="text-align:left;border-bottom:none;border-top:none">' . date('M-d', strtotime($arrival->Reservation->date_from)) . '</td>';
                            echo '<td class="print2" style="text-align:left;border-bottom:none;border-top:none">' . date('M-d', strtotime($arrival->Reservation->date_to)) . '</td>';
                            echo '<td class="print2" style="text-align:center;border-bottom:none;border-top:none">' . $arrival->Reservation->totalNight . '</td>';
                            echo '<td class="print2" style="text-align:center;border-bottom:none;border-top:none">' . $arrival->Reservation->totalRoom . '</td>';
                            echo '<td class="print2" style="text-align:left;border-bottom:none;border-top:none">' . $arrival->Reservation->remarks . '</td>';
                            echo '</tr>';
                            $totRoomArrival += $arrival->Reservation->totalRoom;
                            $totNightArrival += $arrival->Reservation->totalNight;
                        }
                    }
                    ?>
                    <tr>
                        <th colspan="4">Total EA</th>
                        <th class="print2" style="text-align:center"><?php echo $totNightArrival; ?></th>
                        <th class="print2" style="text-align: center"><?php echo $totRoomArrival; ?></th>
                        <th></th>
                    </tr>

                </table>
            </td>
            <td colspan="3" style="margin:0px;padding: 3px">
                <table style="width:100%">

                    <tr>           
                        <th class="span2 print2" style="text-align: center">Room</th>                                               
                        <th class="span3 print2" style="text-align: center">Name</th>                        
                        <th class="span1 print2" style="text-align: center">Rooms</th>                                  
                    </tr> 


                    <?php
                    foreach ($expectedDeparture as $dep) {
                        $registration[] = $dep->RoomBill->registration_id;
                    }
                    $totRoomDeparture = 0;
                    if (!isset($registration))
                        $registration = array();
                    $uniqRegistration = array_unique($registration);

                    foreach ($uniqRegistration as $reg) {
                        $room = '';
                        $noROom = 0;
                        $name = '';
                        foreach ($expectedDeparture as $departure) {
                            if ($reg == $departure->RoomBill->registration_id) {
                                $room .= $departure->RoomBill->room_number . ' , ';
                                $name = $departure->RoomBill->Registration->Guest->guestName;
                                $noROom++;
                            }
                        }
                        $room = substr($room, 0, strlen($room) - 1);
                        echo '<tr>';
                        echo '<td class="print2" style="text-align:center;border-bottom:none;border-top:none">' . $room . '</td>';
                        echo '<td class="print2" style="text-align:left;border-bottom:none;border-top:none">' . $name . '</td>';
                        echo '<td class="print2" style="text-align:center;border-bottom:none;border-top:none">' . $noROom . '</td>';
                        echo '</tr>';
                        $totRoomDeparture += $noROom;
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
    <br>
    <table>
        <tr>
            <td style="padding: 0px;font-size: 10px" class="span2">Audit By</td>        
            <td style="padding: 0px;font-size: 10px">: <?php echo User()->name; ?></td>
        </tr>    
        <tr>
            <td style="padding: 0px;font-size: 10px">Printed Time</td>        
            <td style="padding: 0px;font-size: 10px">: <?php echo date('l d-M-Y H:i:s'); ?></td>
        </tr>    
    </table>
</div>
<style type="text/css">
    @media print
    {
        body {visibility:hidden;}
        .na_expected{visibility: visible;} 
        .na_expected{width: 100%;top: 0px;left: 0px;position: absolute;font-size: 9px !important}
    }
</style>