<?php
$this->setPageTitle('Room Listing / ' . date('d F Y'));

$siteConfig = SiteConfig::model()->findByPk(1);
?>

<style>
    .label-reservation{
        background-color: #4AC3FF;
    }
    .dropdown-menu>li>a{
        padding:3px;
    }
</style>

<?php
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id' => 'room-stay-form',
    'enableAjaxValidation' => false,
    'method' => 'post',
    'type' => 'horizontal',
    'htmlOptions' => array(
        'enctype' => 'multipart/form-data'
    )
        ));
?>

<div class="well">

    <div class="row-fluid">
        <div class="span12">
            <?php
            $type = CHtml::listData(RoomType::model()->findAll(), 'id', 'name');
            $bed = Room::model()->getBedList();
            ?>
            <?php
            $roomUser = Room::model()->findAll(array('condition' => 'status="occupied" or status="house use" or status="compliment"'));
            $listUser = array();
            foreach ($roomUser as $userRegistered) {
                $listUser[$userRegistered->Registration->guest_user_id] = $userRegistered->Registration->Guest->guestName;
            }
            ?>

            Registered By : 
            <?php
            $this->widget(
                    'bootstrap.widgets.TbSelect2', array(
                'asDropDownList' => true,
                'name' => 'user',
                'value' => (!empty($_POST['user'])) ? $_POST['user'] : '',
                'data' => array(0 => 'Please Choose') + $listUser,
                'options' => array(
                    "placeholder" => 'Please Choose',
                    "allowClear" => false,
                    'width' => '135px',
                ),
                    )
            );
            ?>
            &nbsp;&nbsp;&nbsp;&nbsp;
            Status: <?php
            echo CHtml::dropDownList('status', (!empty($_POST['status'])) ? $_POST['status'] : '', array(
                'vacant inspect' => 'Vacant Inpected', 'vacant' => 'Vacant', 'dirty' => 'Dirty', 'reservation' => 'Reservation Unconfirmed', 'reserved' => 'Reservation Confirmed', 'occupied' => 'Occupied', 'do not disturb' => 'Do Not Disturb', 'sleep out' => 'Sleep Out', 'occupied no luggage' => 'Occupied No Luggage', 'house use' => 'House Use', 'out of order' => 'Out of Order'
                    ), array('style' => 'width:135px', 'empty' => 'Please Choose'));
            ?>&nbsp;&nbsp;            
            Room Type : <?php echo CHtml::dropDownList('type', (!empty($_POST['type'])) ? $_POST['type'] : '', $type, array('style' => 'width:135px', 'empty' => 'Please Choose')); ?>&nbsp;&nbsp;&nbsp;&nbsp;            
            Room Bed : <?php echo CHtml::dropDownList('bed', (!empty($_POST['bed'])) ? $_POST['bed'] : '', $bed, array('style' => 'width:135px', 'empty' => 'Please Choose')); ?>&nbsp;&nbsp;&nbsp;&nbsp;

            <?php
            $this->widget('bootstrap.widgets.TbButton', array(
                'buttonType' => 'submit',
                'type' => 'primary',
                'icon' => 'search white',
                'label' => 'Filter',
            ));
            ?>

        </div>         
    </div>
</div>
<hr/>

<table class="table table-bordered">
    <thead>
        <tr>            
            <th style="text-align:center;vertical-align: middle" rowspan="2" class="span2">Status</th>
            <th style="text-align:center;vertical-align: middle" rowspan="2" class="span1">RSV</th>
            <th style="text-align:center;vertical-align: middle" colspan="3">Room</th>
            <th style="text-align:center;vertical-align: middle" rowspan="2">Registered / Reserved By</th>
            <th style="text-align:center;vertical-align: middle" rowspan="2">Guest Name</th>
            <th style="text-align:center;vertical-align: middle" rowspan="2">Room Charge</th>            
            <th style="text-align:center;vertical-align: middle" rowspan="2">Pax</th>            
            <th style="text-align:center;vertical-align: middle" rowspan="2">EB</th>  
            <th style="text-align:center;vertical-align: middle" rowspan="2">BF</th>
            <th style="text-align:center;vertical-align: middle" rowspan="2">Other Include</th>
            <th style="text-align:center;vertical-align: middle" rowspan="2">Arrival</th>
            <th style="text-align:center;vertical-align: middle" rowspan="2">Depart</th>
            <th style="text-align:center;vertical-align: middle" rowspan="2">Remarks</th>
        </tr>
        <tr>
            <th style="text-align:center;vertical-align: middle" >Type</th>
            <th style="text-align:center;vertical-align: middle" >No</th>
            <th style="text-align:center;vertical-align: middle" >Bed</th>

        </tr>
    </thead>
    <tbody>
        <?php
        if (!empty($modelRoom)) {
            foreach ($modelRoom as $arr) {
                $status = (!empty($arr->status_housekeeping)) ? $arr->status_housekeeping : 'vacant';
                $name = '';
                $note = '';
                $guestName = '';
                $charge = '';
                $bf = '';
                $arival = '';
                $txtOther = '';
                $regDetail = array();
                $others_include = '';

                if ($status == 'dirty') {
                    $class = 'label-vd';
                } elseif ($status == 'occupied') {
                    $class = 'label-o';
                    if (isset($arr->Registration->guest_user_id)) {
                        $detail = User::model()->getDetailGuest($arr->Registration->guest_user_id);
                        $name = '<a href="#" onclick="js:bootbox.alert(\' ' . $detail . ' \')">' . $arr->Registration->Guest->guestName . '</a>';
                        $regDetail = RegistrationDetail::model()->find(array('condition' => 'registration_id=' . $arr->registration_id . ' and room_id=' . $arr->id));
                        $guestName = $this->widget('common.extensions.bootstrap.widgets.TbEditableField', array(
                            'type' => 'textarea',
                            'model' => $regDetail,
                            'attribute' => 'guest_user_names',
                            'url' => $this->createUrl('roomCharting/updateGuestNames'), //$endpoint, //url for submit data
                            'placement' => 'left'
                                ), true);
                        $roomBill = RoomBill::model()->find(array('condition' => 'registration_id=' . $regDetail->registration_id . ' and room_id=' . $regDetail->room_id . ' and date_bill="' . $siteConfig->date_system . '"'));
                        $charge = (empty($roomBill)) ? landa()->rp($regDetail->charge, false) : landa()->rp($roomBill->charge, false);
                        $bf = (empty($roomBill)) ? 0 : landa()->rp($roomBill->fnb_price, false);
                        $arival = date('d M', strtotime($regDetail->Registration->date_from));
                        
                        if (isset($roomBill) and $roomBill->others_include != '') {
                        $others_include = json_decode($roomBill->others_include);
                        foreach ($others_include as $key => $mInclude) {
                            //mencari nama charge
                            $tuyul = ChargeAdditional::model()->findByPk($key);
                            $txtOther .= $tuyul->name . ' : ' . landa()->rp($mInclude, false) . '<br>';
                        }
                        }
                    }
                } elseif ($status == 'reserved') {
                    $class = 'label-reserved';
                    $detail = User::model()->getDetailGuest($arr->Registration->guest_user_id);
                    $name = '<a href="#" onclick="js:bootbox.alert(\' ' . $detail . ' \')">' . $arr->Registration->Guest->guestName . '</a>';
                    $regDetail = ReservationDetail::model()->find(array('condition' => 'reservation_id=' . $arr->reservation_id . ' and room_id=' . $arr->id));
                    $guestName = $this->widget('common.extensions.bootstrap.widgets.TbEditableField', array(
                        'type' => 'textarea',
                        'model' => $regDetail,
                        'attribute' => 'guest_user_names',
                        'url' => $this->createUrl('roomCharting/updateGuestReservation'), //$endpoint, //url for submit data
                        'placement' => 'left'
                            ), true);
                    $roomBill = RoomBill::model()->find(array('condition' => 'registration_id=' . $regDetail->registration_id . ' and room_id=' . $regDetail->room_id . ' and date_bill="' . $siteConfig->date_system . '"'));
                    $charge = (empty($roomBill)) ? landa()->rp($regDetail->charge, false) : landa()->rp($roomBill->charge, false);
                    $bf = (empty($roomBill)) ? 0 : landa()->rp($roomBill->fnb_price, false);
                    $arival = date('d M', strtotime($regDetail->Reservation->date_from));
                } elseif ($status == 'reservation') {
                    $class = 'label-reservation';
                    $detail = User::model()->getDetailGuest($arr->Registration->guest_user_id);
                    $name = '<a href="#" onclick="js:bootbox.alert(\' ' . $detail . ' \')">' . $arr->Registration->Guest->guestName . '</a>';
                    $regDetail = ReservationDetail::model()->find(array('condition' => 'reservation_id=' . $arr->reservation_id . ' and room_id=' . $arr->id));
                    $guestName = $this->widget('common.extensions.bootstrap.widgets.TbEditableField', array(
                        'type' => 'textarea',
                        'model' => $regDetail,
                        'attribute' => 'guest_user_names',
                        'url' => $this->createUrl('roomCharting/updateGuestReservation'), //$endpoint, //url for submit data
                        'placement' => 'left'
                            ), true);
                    $charge = landa()->rp($regDetail->charge, false);
                    $bf = (empty($roomBill)) ? 0 : landa()->rp($roomBill->fnb_price, false);
                    $arival = date('d M', strtotime($regDetail->Reservation->date_from));
                } elseif ($status == 'out of order') {
                    $class = 'label-ooo';
                } elseif ($status == 'house use') {
                    $class = 'label-hu';
                    $detail = User::model()->getDetailGuest($arr->Registration->guest_user_id);
                    $name = '<a href="#" onclick="js:bootbox.alert(\' ' . $detail . ' \')">' . $arr->Registration->Guest->guestName . '</a>';
                    $regDetail = RegistrationDetail::model()->find(array('condition' => 'registration_id=' . $arr->registration_id . ' and room_id=' . $arr->id));
                    $guestName = $this->widget('common.extensions.bootstrap.widgets.TbEditableField', array(
                        'type' => 'textarea',
                        'model' => $regDetail,
                        'attribute' => 'guest_user_names',
                        'url' => $this->createUrl('roomCharting/updateGuestNames'), //$endpoint, //url for submit data
                        'placement' => 'left'
                            ), true);
                    $charge = landa()->rp($regDetail->charge, false);
                    $bf = (empty($roomBill)) ? 0 : landa()->rp($roomBill->fnb_price, false);
                    $arival = date('d M', strtotime($regDetail->Registration->date_from));
                } elseif ($status == 'compliment') {
                    $class = 'compliment';
                    $detail = User::model()->getDetailGuest($arr->Registration->guest_user_id);
                    $name = '<a href="#" onclick="js:bootbox.alert(\' ' . $detail . ' \')">' . $arr->Registration->Guest->guestName . '</a>';
                    $regDetail = RegistrationDetail::model()->find(array('condition' => 'registration_id=' . $arr->registration_id . ' and room_id=' . $arr->id));
                    $guestName = $this->widget('common.extensions.bootstrap.widgets.TbEditableField', array(
                        'type' => 'textarea',
                        'model' => $regDetail,
                        'attribute' => 'guest_user_names',
                        'url' => $this->createUrl('roomCharting/updateGuestNames'), //$endpoint, //url for submit data
                        'placement' => 'left'
                            ), true);
                    $charge = landa()->rp($regDetail->charge, false);
                    $bf = (empty($roomBill)) ? 0 : landa()->rp($roomBill->fnb_price, false);
                    $arival = date('d M', strtotime($regDetail->Registration->date_from));
                } elseif ($status == 'do not disturb') {
                    $class = 'label-dd';
                    $detail = User::model()->getDetailGuest($arr->Registration->guest_user_id);
                    $name = '<a href="#" onclick="js:bootbox.alert(\' ' . $detail . ' \')">' . $arr->Registration->Guest->guestName . '</a>';
                    $regDetail = RegistrationDetail::model()->find(array('condition' => 'registration_id=' . $arr->registration_id . ' and room_id=' . $arr->id));
                    $guestName = $this->widget('common.extensions.bootstrap.widgets.TbEditableField', array(
                        'type' => 'textarea',
                        'model' => $regDetail,
                        'attribute' => 'guest_user_names',
                        'url' => $this->createUrl('roomCharting/updateGuestNames'), //$endpoint, //url for submit data
                        'placement' => 'left'
                            ), true);
                    $charge = landa()->rp($regDetail->charge, false);
                    $bf = (empty($roomBill)) ? 0 : landa()->rp($roomBill->fnb_price, false);
                    $arival = date('d M', strtotime($regDetail->Registration->date_from));
                } elseif ($status == 'occupied no luggage') {
                    $class = 'label-onl';
                    $detail = User::model()->getDetailGuest($arr->Registration->guest_user_id);
                    $name = '<a href="#" onclick="js:bootbox.alert(\' ' . $detail . ' \')">' . $arr->Registration->Guest->guestName . '</a>';
                    $regDetail = RegistrationDetail::model()->find(array('condition' => 'registration_id=' . $arr->registration_id . ' and room_id=' . $arr->id));
                    $guestName = $this->widget('common.extensions.bootstrap.widgets.TbEditableField', array(
                        'type' => 'textarea',
                        'model' => $regDetail,
                        'attribute' => 'guest_user_names',
                        'url' => $this->createUrl('roomCharting/updateGuestNames'), //$endpoint, //url for submit data
                        'placement' => 'left'
                            ), true);
                    $charge = landa()->rp($regDetail->charge, false);
                    $bf = (empty($roomBill)) ? 0 : landa()->rp($roomBill->fnb_price, false);
                    $arival = date('d M', strtotime($regDetail->Registration->date_from));
                } elseif ($status == 'sleep out') {
                    $class = 'label-so';
                    $detail = User::model()->getDetailGuest($arr->Registration->guest_user_id);
                    $name = '<a href="#" onclick="js:bootbox.alert(\' ' . $detail . ' \')">' . $arr->Registration->Guest->guestName . '</a>';
                    $regDetail = RegistrationDetail::model()->find(array('condition' => 'registration_id=' . $arr->registration_id . ' and room_id=' . $arr->id));
                    $guestName = $this->widget('common.extensions.bootstrap.widgets.TbEditableField', array(
                        'type' => 'textarea',
                        'model' => $regDetail,
                        'attribute' => 'guest_user_names',
                        'url' => $this->createUrl('roomCharting/updateGuestNames'), //$endpoint, //url for submit data
                        'placement' => 'left'
                            ), true);
                    $charge = landa()->rp($regDetail->charge, false);
                    $bf = (empty($roomBill)) ? 0 : landa()->rp($roomBill->fnb_price, false);
                    $arival = date('d M', strtotime($regDetail->Registration->date_from));
                } elseif ($status == 'vacant inspect') {
                    $class = 'label-vci';
                } else {
                    $class = 'label-vc';
                }


                $note = $this->widget('common.extensions.bootstrap.widgets.TbEditableField', array(
                    'type' => 'textarea',
                    'model' => $arr,
                    'attribute' => 'description',
                    'url' => $this->createUrl('roomCharting/updateNotes'), //$endpoint, //url for submit data
                    'placement' => 'left'
                        ), true);

                //cek status booked or not
                $is_booked = 0;
                $booked = '<span class="label label-vc">No</span>';
                if ($status == 'reservation' || $status == 'reserved') {
                    if (isset($mSchedule[$arr->id])) {
                        if ($mSchedule[$arr->id]['status'] == 'reserved') {
                            $class = 'label-reserved';
                            $is_booked = 1;
                            $booked = '<span class="label label-info">Yes (Confirmed)</span>';
                            $detail = User::model()->getDetailGuest($mSchedule[$arr->id]->Reservation->guest_user_id);
                            $name = '<a href="#" onclick="js:bootbox.alert(\' ' . $detail . ' \')">' . $mSchedule[$arr->id]->Reservation->Guest->guestName . '</a>';
                            $regDetail = ReservationDetail::model()->find(array('condition' => 'reservation_id=' . $mSchedule[$arr->id]['reservation_id'] . ' and room_id=' . $mSchedule[$arr->id]['room_id']));
                            $guestName = $this->widget('common.extensions.bootstrap.widgets.TbEditableField', array(
                                'type' => 'textarea',
                                'model' => $regDetail,
                                'attribute' => 'guest_user_names',
                                'url' => $this->createUrl('roomCharting/updateGuestReservation'), //$endpoint, //url for submit data
                                'placement' => 'left'
                                    ), true);
                            $charge = landa()->rp($regDetail->charge, false);
                            $bf = (empty($roomBill)) ? 0 : landa()->rp($roomBill->fnb_price, false);
                            $arival = date('d M', strtotime($regDetail->Reservation->date_from));
                        } elseif ($mSchedule[$arr->id]['status'] == 'reservation') {
                            $is_booked = 1;
                            $class = 'label-reservation';
                            $booked = '<span class="label label-info">Yes (Unconfirmed)</span>';
                            $detail = User::model()->getDetailGuest($mSchedule[$arr->id]->Reservation->guest_user_id);
                            $name = '<a href="#" onclick="js:bootbox.alert(\' ' . $detail . ' \')">' . $mSchedule[$arr->id]->Reservation->Guest->guestName . '</a>';
                            $regDetail = ReservationDetail::model()->find(array('condition' => 'reservation_id=' . $mSchedule[$arr->id]['reservation_id'] . ' and room_id=' . $mSchedule[$arr->id]['room_id']));
                            $guestName = $this->widget('common.extensions.bootstrap.widgets.TbEditableField', array(
                                'type' => 'textarea',
                                'model' => $regDetail,
                                'attribute' => 'guest_user_names',
                                'url' => $this->createUrl('roomCharting/updateGuestReservation'), //$endpoint, //url for submit data
                                'placement' => 'left'
                                    ), true);
                            $charge = landa()->rp($regDetail->charge, false);
                            $arival = date('d M', strtotime($regDetail->Reservation->date_from));
                            $bf = (empty($roomBill)) ? 0 : landa()->rp($roomBill->fnb_price, false);
                        }
                    }
                }

                //mencari departure
                if ($is_booked == 1) {
                    $departure = date('d M', strtotime($regDetail->Reservation->date_to));
                } else {
                    if (!empty($regDetail)) {
                        $max = RoomBill::model()->find(array('order' => 'date_bill DESC', 'condition' => 'room_id=' . $regDetail->room_id . ' and registration_id=' . $regDetail->registration_id));
                        if (isset($max))
                            $departure = date("d M", strtotime('+1 day', strtotime($max->date_bill)));
                        else
                            $departure = '';
                    } else {
                        $departure = '';
                    }
                }

                $link = '';
                $status = strtolower($status);
                if ($status == "vacant" || $status == "vacant inspect") {
                    $link .= '<a href="../reservation/create.html?roomNumber=' . $arr->number . '&date=' . date("m/d/Y", strtotime($siteConfig->date_system)) . '" target="_blank" class="btn btn-mini blue entypo-icon-book" data-toggle="tooltip" title="Reservation"></a>';
                    $link .= '<a href="../registration/create.html?roomNumber=' . $arr->number . '&date=' . date("m/d/Y", strtotime($siteConfig->date_system)) . '" target="_blank" class="btn btn-mini blue entypo-icon-feather" data-toggle="tooltip" title="Registration"></a>';
                } elseif ($status == "reservation") {
                    $link .= '<a href="../registration/create.html?reservationId=' . $regDetail->reservation_id . '" target="_blank" class="btn btn-mini blue entypo-icon-feather" data-toggle="tooltip" title="Registration"></a>';
                    $link .= '<a href="../reservation/update/' . $regDetail->reservation_id . '.html?changeStatus=reserved" target="_blank" class="btn btn-mini blue entypo-icon-flag" data-toggle="tooltip" title="Confirm Reservation"></a>';
                    $link .= '<a href="../reservation/update/' . $regDetail->reservation_id . '.html?changeStatus=cancel" target="_blank" class="btn btn-mini blue entypo-icon-reload-CCW" data-toggle="tooltip" title="Cancel Reservation"></a>';
                } elseif ($status == "reserved") {
                    $link .= '<a href="../registration/create.html?reservationId=' . $regDetail->reservation_id . '" target="_blank" class="btn btn-mini blue entypo-icon-feather" data-toggle="tooltip" title="Registration"></a>';
                    $link .= '<a href="../reservation/update/' . $regDetail->reservation_id . '.html?changeStatus=cancel" target="_blank" class="btn btn-mini blue entypo-icon-reload-CCW" data-toggle="tooltip" title="Cancel Reservation"></a>';
                } elseif ($status == "out of order") {
                } elseif ($status == "dirty") {
                    
                } else {
                    $link .= '<a href="../bill/create.html?roomNumber=' . $arr->number . '" target="_blank" class="btn btn-mini blue entypo-icon-alert" data-toggle="tooltip" title="Checked Out"></a>';
                    $link .= '<a href="../roomBill/extend.html?roomNumber=' . $arr->number . '" target="_blank" class="btn btn-mini blue entypo-icon-creative-commons" data-toggle="tooltip" title="Extend"></a>';
                    $link .= '<a href="../roomBill/move.html?roomNumber=' . $arr->number . '" target="_blank" class="btn btn-mini blue entypo-icon-google-circles" data-toggle="tooltip" title="Move Room"></a>';
                }


                echo '<tr>
                        <td style="text-align:center" class="' . $class . '"><span class="taro">' . ucwords($status) . '</span><br>
                            ' . $link . '                            
                        </td>
                        <td style="text-align:center">' . $booked . '</td>
                        <td>' . $arr->RoomType->name . '</td>
                        <td style="text-align:center">' . $arr->number . '</td>
                        <td>' . $arr->bed . '</td>
                        
                        <td>' . $name . '</td>
                        <td>' . $guestName . '</td>
                        <td style="text-align:right">' . $charge . '</td>
                        <td style="text-align:center">' . $arr->pax . '</td>
                        <td style="text-align:center">' . $arr->extrabed . '</td>
                        <td style="text-align:right">' . $bf . '</td>
                        <td style="text-align:left">' . $txtOther . '</td>
                        <td style="text-align:right">' . $arival . '</td>
                        <td style="text-align:right">' . $departure . '</td>
                        <td style="text-align:right">' . $note . '</td>
                  </tr>';
            }
        }
        ?>
    </tbody>
</table>


<?php $this->endWidget(); ?>
<div>Status Color Information : </div>
<span class="label label-vci">Vacant Inspected</span>
<span class="label label-vc">Vacant</span>
<span class="label label-vd">Dirty</span>
<span class="label reservation">Reservation Unconfirmed</span>
<span class="label reserved">Reservation Confirmed</span>
<span class="label label-o">Occupied</span>
<span class="label label-onl">Occupied No Luggage</span>
<span class="label label-dd">Do Not Disturb</span>
<span class="label label-so">Sleep Out</span>
<span class="label label-ooo">Out of Order</span>
<span class="label label-hu">House Use</span>
<span class="label compliment">Compliment</span>