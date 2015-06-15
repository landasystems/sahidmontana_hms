<?php

if (!isset($thisDate))
    $thisDate = '';
if (!isset($thisNumber))
    $thisNumber = '';
if (!isset($reservation_id))
    $reservation_id = '';




if ($status == 'occupied') {
    echo '<a class="btn btn-danger btn-mini dropdown-toggle" data-toggle="dropdown" href="#"><span class="caret"></span></a>
                                        <ul class="dropdown-menu">
<li><a href="roomBill/charge?date=' . $thisDate . '&number=' . $thisNumber . '">Additional Charge</a></li>
<li><a href="roomBill/extend?date=' . $thisDate . '&number=' . $thisNumber . '">Extend</a></li>
<li><a href="room/UpdateLinkedRoom/' . $thisNumber . '?date=' . $thisDate . '&number=' . $thisNumber . '">Linked Room</a></li>

<li><a href="#">Move Room</a></li>
<li><a href="bill/create?date=' . $thisDate . '&number=' . $thisNumber . '">Guest Bill</a></li>
                                        </ul>';
} elseif ($status == 'reservation') {
    echo '<a class="btn btn-info btn-mini dropdown-toggle" data-toggle="dropdown" href="#"><span class="caret"></span></a>
                                        <ul class="dropdown-menu">
<li><a href="reservation/confirm/' . $reservation_id . '.html">Confirm Reservation</a></li>
<li><a href="reservation/cancel/' . $reservation_id . '.html">Cancel Reservation</a></li>
                                        </ul>';
} elseif ($status == 'reserved') {
    echo '<a class="btn btn-primary btn-mini dropdown-toggle" data-toggle="dropdown" href="#"><span class="caret"></span></a>
                                        <ul class="dropdown-menu">';
    if (strtotime($thisDate) == strtotime(date('m/d/Y')))
        echo '<li><a href="registration/create/' . $reservation_id . '.html">Registration</a></li>';
    echo '<li><a href="reservation/cancel/' . $reservation_id . '.html">Cancel Reservation</a></li>
                                        </ul>';
} elseif ($status == 'dirty') {
    echo '<a class="btn btn-warning btn-mini dropdown-toggle" data-toggle="dropdown" href="#"><span class="caret"></span></a>
                                        <ul class="dropdown-menu">
<li><a href="room/changeToVacant/' . $thisNumber . '.html?date=' . $thisDate . '">Change to Vacant</a></li>
                                        </ul>';
} elseif ($status == 'outoforder') {
    echo '<a class="btn btn-inverse btn-mini dropdown-toggle" data-toggle="dropdown" href="#"><span class="caret"></span></a>
                                        <ul class="dropdown-menu">
                                        </ul>';
} elseif ($status == 'vacant') {
    if (strtotime($thisDate) > strtotime(date('Ymd'))) {
        echo '<a class="btn btn-success btn-mini dropdown-toggle" data-toggle="dropdown" href="#"><span class="caret"></span></a>
                                  <ul class="dropdown-menu">                                                                            
                                      <li><a href="reservation/create?date=' . $thisDate . '&number=' . $thisNumber . '">Reservation</a></li>
                                  </ul>
                                    ';
    } elseif (strtotime($thisDate) < strtotime(date('Ymd'))) {
        echo '<a class="btn btn-success btn-mini dropdown-toggle" data-toggle="dropdown" href="#"><span class="caret"></span></a>';
    } elseif (strtotime($thisDate) == strtotime(date('m/d/Y'))) {
        echo '<a class="btn btn-success btn-mini dropdown-toggle" data-toggle="dropdown" href="#"><span class="caret"></span></a>
                <ul class="dropdown-menu">                                      
                    <li><a href="registration/create?date=' . $thisDate . '&number=' . $thisNumber . '">Registration</a></li>
                    <li><a href="reservation/create?date=' . $thisDate . '&number=' . $thisNumber . '">Reservation</a></li>
                </ul>
                ';
    }
}
?>