<?php
if (!empty($_POST['year']) && !empty($_POST['month'])) {
    $bed = "''";
    $type = "''";

    if (!empty($_POST['type'])) {
        $type = $_POST['type'];
    }

    if (!empty($_POST['bed'])) {
        $bed = $_POST['bed'];
    }
    ?>
    <style>
        #header-fixed {
            position: fixed;
            top: 0px; 
            display:none;
            background-color:white;
        }
        .lewat {
            width: 100%;
            height: 20px;
            overflow: hidden;
        }
    </style>
    <table class="table table-bordered" id="table-1" style="width: 1252px;">
        <thead>
            <tr>
                <th colspan="3" style="text-align:center;" style="width:75px;">
        <div class="row-fluid">
            <div class="span12">
                Room
            </div>
        </div>
    </th>
    <th colspan="<?php echo $amountDay ?>" style="text-align:center">
    <div class="row-fluid">
        <div class="span8">
            Day
        </div>
        <div class="span4">
            <button <?php
            if ($page == "1") {
                echo 'disabled';
            }
            ?> class="btn btn-primary" id="btnResults" name="yt0" type="button" onclick="pagination(<?php echo $page - 1 . "," . $_POST['month'] . "," . $_POST['year'] . "," . $type . "," . $bed ?>)">
                <i class="icon-arrow-left icon-white"></i> 
                Previous
            </button>
            <button <?php
            if ($page == "3") {
                echo 'disabled';
            }
            ?> class="btn btn-primary" id="btnResults" name="yt0" type="button" onclick="pagination(<?php echo $page + 1 . "," . $_POST['month'] . "," . $_POST['year'] . "," . $type . "," . $bed ?>)">
                Next
                <i class="icon-arrow-right icon-white"></i> 
            </button>
        </div>
    </div>
    </th>
    </tr>
    <tr>
        <th style="text-align:center;width:50px;">Type</th>
        <th style="text-align:center;width:50px;">Number</th>
        <th style="text-align:center;width:50px;">Bed</th>
        <?php
        if ($page == "1") {
            $startCount = 1;
        } else if ($page == "2") {
            $startCount = 11;
        } else {
            $startCount = 21;
        }

        if ($page == "1") {
            $endDate = 10;
        } else if ($page == "2") {
            $endDate = 20;
        } else {
            $endDate = landa()->daysInMonth($_POST['month'], $_POST['year']);
        }

        if (!empty($_POST['year']) && !empty($_POST['month'])) {
            for ($i = $startCount; $i <= $endDate; $i++) {
                $thisDate = ($_POST['month'] . '/' . $i . '/' . $_POST['year']);
                $thisDay = date("l", strtotime($_POST['year'] . "-" . $_POST['month'] . "-" . $i));
                if (strtotime($thisDate) < strtotime($siteConfig->date_system) and $thisDay != 'Sunday') {
                    echo '<th style="width:50px;text-align:center;background:lightgray">' . ($i . '/' . $_POST['month']) . '</th>';
                } elseif ($thisDay == 'Sunday') {
                    echo '<th style="width:50px;text-align:center;background:rgba(255, 110, 110, 1)">' . ($i . '/' . $_POST['month']) . '</th>';
                } else {
                    echo '<th style="width:50px;text-align:center">' . ($i . '/' . $_POST['month']) . '</th>';
                }
            }
        }
        ?>
    </tr>
    </thead>
    <tbody>
        <?php
        $guestName = '';
        $guestPhone = '';
        $dp = '';
        $remarks = '';
        $dateTo = '';
        $dateFrom = '';
        $tempGuestName = '';
        $selisih = 1;
        $roomType = RoomType::model()->findAll(array('condition' => 'is_package=0'));
        foreach ($roomType as $rt) {
            for ($i = $startCount; $i <= $endDate; $i++) {
                $total[$rt->name][$i]['vacant'] = 0;
                $total[$rt->name][$i]['notVacant'] = 0;
            }
        }

        for ($i = $startCount; $i <= $endDate; $i++) {
            $total[$i]['vacant'] = 0;
            $total[$i]['notVacant'] = 0;
        }
        foreach ($modelRoom as $key => $arr) {
            $tdDay = '';
            for ($i = $startCount; $i <= $endDate; $i++) {
                $thisDate = ($_POST['month'] . '/' . $i . '/' . $_POST['year']);
                $thisNumber = $arr->number;
                $reservation_id = '';
                $registration_id = '';
                $rooms = '';
                $total_rooms = '';
                $nextName = '';

                $reservationStatus = isset($mSchedule[$i][$arr->id]->Reservation->status) ? $mSchedule[$i][$arr->id]->Reservation->status : '';

                if (isset($mSchedule[$i][$arr->id]) && $mSchedule[$i][$arr->id]->room_id == $arr->id and $reservationStatus != "cancel") { //jika ada di room schedule
                    $reservation_id = $mSchedule[$i][$arr->id]->reservation_id;
                    $registration_id = $mSchedule[$i][$arr->id]->registration_id;
//                        $status = (date('Y-m-d', strtotime($siteConfig->date_system)) == date('Y-m-d', strtotime($thisDate)) && $mSchedule[$i][$arr->id]->status != 'reservation' && $mSchedule[$i][$arr->id]->status != 'reserved' ) ? $arr->status : $mSchedule[$i][$arr->id]->status;

                    $status = $mSchedule[$i][$arr->id]->status;
//                    $status = ($status == "out of order") ? "out of order" : $status;
                    $status = str_replace(' ', '', $status);

                    $status_hover = (isset($mSchedule[$i][$arr->id]->Reservation->Guest->guestName)) ? $mSchedule[$i][$arr->id]->Reservation->Guest->guestName : ((isset($mSchedule[$i][$arr->id]->Registration->Guest->guestName)) ? $mSchedule[$i][$arr->id]->Registration->Guest->guestName : $mSchedule[$i][$arr->id]->status);
                    $status_hover = ($status_hover == 'reserved') ? 'Reservation (Confirmed)' : $status_hover;
                    $status_hover = ($status_hover == 'reservation') ? 'Reservation (Unconnfirmed)' : $status_hover;

                    if ($registration_id != '') { //jika registrasi
                        $guestName = $mSchedule[$i][$arr->id]->Registration->Guest->guestName;
                        $guestPhone = $mSchedule[$i][$arr->id]->Registration->Guest->phone;
                        $remarks = $mSchedule[$i][$arr->id]->Registration->remarks;
                        $dp = (isset($mSchedule[$i][$arr->id]->Registration->Deposite->amount)) ? landa()->rp($mSchedule[$i][$arr->id]->Registration->Deposite->amount) : '-';
                        if (isset($mSchedule[$i + 1][$arr->id]->Registration->Guest->guestName)) {
                            $nextName = $mSchedule[$i + 1][$arr->id]->Registration->Guest->guestName;
                        }
                        $dateTo = date('l, d-M-Y', strtotime($mSchedule[$i][$arr->id]->Registration->date_to));
                        $dateFrom = date('l, d-M-Y', strtotime($mSchedule[$i][$arr->id]->Registration->date_from));

                        $rooms = $mSchedule[$i][$arr->id]->Registration->roomNumberDet;
                        $total_rooms = $mSchedule[$i][$arr->id]->Registration->roomCount;
                    } elseif ($reservation_id != '') { //jika reservasi
                        $status = $mSchedule[$i][$arr->id]->Reservation->status;
//                    $status = ($status == "out of order") ? "out of order" : $status;
                        $status = str_replace(' ', '', $status);
                        $guestName = $mSchedule[$i][$arr->id]->Reservation->Guest->guestName;
                        $guestPhone = $mSchedule[$i][$arr->id]->Reservation->Guest->phone;
                        $remarks = $mSchedule[$i][$arr->id]->Reservation->remarks;
                        $dp = (isset($mSchedule[$i][$arr->id]->Reservation->Deposite->amount)) ? landa()->rp($mSchedule[$i][$arr->id]->Reservation->Deposite->amount) : '-';
                        if (isset($mSchedule[$i + 1][$arr->id]->Reservation->Guest->guestName)) {
                            $nextName = $mSchedule[$i + 1][$arr->id]->Reservation->Guest->guestName;
                        }
                        $dateTo = date('l, d-M-Y', strtotime($mSchedule[$i][$arr->id]->Reservation->date_to));
                        $dateFrom = date('l, d-M-Y', strtotime($mSchedule[$i][$arr->id]->Reservation->date_from));

                        $rooms = $mSchedule[$i][$arr->id]->Reservation->roomNumberDet;
                        $total_rooms = $mSchedule[$i][$arr->id]->Reservation->roomCount;
                    }

                    if (isset($nextName) and $nextName != '' and $guestName == $nextName) {
                        $selisih +=1;
                    } elseif ($status == "outoforder" and isset($mSchedule[$i + 1][$arr->id]->status)) {
                        $selisih += 1;
                    } else {

                        $tempGuestName = $guestName;
                        $past = '';

                        if (strtotime($thisDate) < strtotime($siteConfig->date_system))
                            $past = '-past';

                        if ($status == 'cancel') {
                            $tdDay .='<td><a href="#" data-toggle="tooltip" title=" ' . ucwords($status_hover) . ' "><div number="' . $thisNumber . '"  status="vacant" reservation="" registration="" date="' . $thisDate . '" class="tombol vacant"></div></a></td>';
//                            $tdDay .='<td colspan="' . $selisih . '" style="background:#e3e3e3;"><div style="background:#353535;width:100%;height:20px;text-align:center;color:white;font-size: 9px;">Out Of Order</div></td>';
                        } else {
                            if (strtotime($thisDate) < strtotime($siteConfig->date_system and $status == "vacant")) {
                                $tdDay .='<td style="background-color:lightgray;"><div number="' . $thisNumber . '"  status="' . $status . '" reservation="" registration="" date="' . $thisDate . '" class="lewat ' . $status . '"></div></td>';
                            } else {
                                $tdDay .='<td  colspan="' . $selisih . '"><a href="#" data-toggle="tooltip" title=" ' . ucwords($status_hover) . ' "><div guest="' . $guestName . '" guestPhone="' . landa()->hp($guestPhone) . '" guestDP="' . $dp . '" remarks="' . $remarks . '" total_rooms="' . $total_rooms . '" rooms="' . $rooms . '" dateTo="' . $dateTo . '" dateFrom="' . $dateFrom . '" number="' . $thisNumber . '" status="' . $status . $past . '" reservation="' . $reservation_id . '" registration="' . $registration_id . '" date="' . $thisDate . '" class="tombol ' . $status . '">' . substr($guestName, 0, 10) . '</div></a></td>';
                            }
                        }
                        $selisih = 1;
                    }
                } else { //jika tidak ada di room schedule
                    if ($arr->status == "out of order" and strtotime($thisDate) >= strtotime($siteConfig->date_system)) {
                        $status = "out of order";
                    } else {
                        $status = "vacant";
                    }

                    //$status = ($arr->status == "out of order") ? "out of order" : "vacant";
                    $status = str_replace(' ', '', $status);
                    $status_hover = $status;

                    $sStatus = '';
                    if (strtotime($thisDate) > strtotime($siteConfig->date_system))
                        $sStatus = $status;

                    if ($status == 'outoforder') {
                        if ($i < $endDate) {
                            $selisih += 1;
                        } else {
                            $tdDay .='<td colspan="' . $selisih . '" style="background:#e3e3e3;"><div style="background:#353535;width:100%;height:20px;text-align:center;color:white;font-size: 9px;">Out Of Order</div></td>';
                            $selisih = 1;
                        }
                    } else {
                        if (strtotime($thisDate) < strtotime($siteConfig->date_system)) {
                            $tdDay .='<td style="background-color:lightgray;"><div number="' . $thisNumber . '"  status="' . $status . '" reservation="" registration="" date="' . $thisDate . '" class="lewat ' . $status . '"></div></td>';
                        } else {
                            $tdDay .='<td><a href="#" data-toggle="tooltip" title=" ' . ucwords($status_hover) . ' "><div number="' . $thisNumber . '"  status="' . $status . '" reservation="" registration="" date="' . $thisDate . '" class="tombol ' . $status . '"></div></a></td>';
                        }
                    }
                    //}
                }

                if ($status == 'vacant') {
                    $total[$i]['vacant'] ++;
                    $total[$arr->RoomType->name][$i]['vacant'] ++;
                } else {
                    $total[$i]['notVacant'] ++;
                    $total[$arr->RoomType->name][$i]['notVacant'] ++;
                }
            }

            echo '
                     <tr>
                        <td style="width:20px;text-align:center">' . $arr->RoomType->name . '</td>
                        <td style="width:20px;text-align:center">' . $arr->number . '</td>
                        <td style="width:20px;text-align:center">' . $arr->bed . '</td>
                        ' . $tdDay . '
                     </tr>';
        }
        echo '<tr style="background:khaki"><td colspan="3">Total Available</td>';
        for ($i = $startCount; $i <= $endDate; $i++) {
            echo '<td>' . $total[$i]['vacant'] . '</td>';
        }
        echo '</tr>';


        echo '<tr><td colspan="3">Total Not Available</td>';
        for ($i = $startCount; $i <= $endDate; $i++) {
            echo '<td>' . $total[$i]['notVacant'] . '</td>';
        }
        echo '</tr>';
        foreach ($roomType as $rt) {
            echo '<tr style="background:khaki"><td colspan="3">Total ' . ucwords($rt->name) . ' Available</td>';
            for ($i = $startCount; $i <= $endDate; $i++) {
                echo '<td>' . $total[$rt->name][$i]['vacant'] . '</td>';
            }
            echo '</tr>';


            echo '<tr><td colspan="3">Total ' . ucwords($rt->name) . ' Not Available</td>';
            for ($i = $startCount; $i <= $endDate; $i++) {
                echo '<td>' . $total[$rt->name][$i]['notVacant'] . '</td>';
            }
        }
        ?>
    </tbody>
    </table>
    <table class="table table-bordered" id="header-fixed" style="width: 1252px;"></table>
    <?php
} else {
    echo '<div class="alert fade in">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <strong>Choose Month and Year </strong> to view room charting in your hotel.
          </div>';
}
?>	
