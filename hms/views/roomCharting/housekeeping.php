<?php
$this->setPageTitle('House Keeping');
$this->breadcrumbs = array(
    'House Keeping',
);
?>

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


<table class="table table-bordered" style="margin-top: 0">
    <thead>
        <tr>
            <th style="text-align:center;vertical-align: middle" rowspan="2" class="span2">Status</th>    
            <th style="text-align:center;vertical-align: middle" rowspan="2" class="span1">Reserved</th>    
            <th style="text-align:center;vertical-align: middle" colspan="3">Room</th>
            <th style="text-align:center;vertical-align: middle" rowspan="2">Guest Name</th>
            <th style="text-align:center;vertical-align: middle" rowspan="2">Date Checkout</th>
            <th style="text-align:center;vertical-align: middle" rowspan="2">Last Update</th>
            <th style="text-align:center;vertical-align: middle" rowspan="2">House Keeper</th>
            <th style="text-align:center;vertical-align: middle" rowspan="2">Remarks</th>
        </tr>
        <tr>
            <th style="text-align:center;vertical-align: middle" >Type</th>
            <th style="text-align:center;vertical-align: middle" >Number</th>
            <th style="text-align:center;vertical-align: middle" >Bed</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if (!empty($modelRoom)) {
            foreach ($modelRoom as $arr) {
                $status = (!empty($arr->status_housekeeping)) ? $arr->status_housekeeping : 'vacant';
                if ($status == 'dirty') {
                    $class = 'label-vd';
                    $name = '';
                    $dateCheckout = '';
                    $array_btn = array('vacant' => 'Vacant', 'dirty' => 'Dirty', 'out of order' => 'Out of Order', 'vacant inspect' => 'Vacant Inspected');
                } elseif ($status == 'occupied') {
                    $class = 'label-o';
                    $name = $arr->Registration->Guest->name;
                    $dateCheckout = $arr->Registration->date_to;
                    $array_btn = array('occupied' => 'Occupied', 'do not disturb' => 'Do Not Disturb', 'occupied no luggage' => 'Occupied No Luggage', 'sleep out' => 'Sleep Out');
                } elseif ($status == 'reserved') {
                    $class = 'label-reserved';
                    $name = '';
                    $dateCheckout = '';
                    $array_btn = array('vacant' => 'Vacant', 'dirty' => 'Dirty', 'out of order' => 'Out of Order', 'vacant inspect' => 'Vacant Inspected');
                } elseif ($status == 'reservation') {
                    $class = 'label-reservation';
                    $name = '';
                    $dateCheckout = '';
                    $array_btn = array('vacant' => 'Vacant', 'dirty' => 'Dirty', 'out of order' => 'Out of Order', 'vacant inspect' => 'Vacant Inspected');
                } elseif ($status == 'out of order') {
                    $class = 'label-ooo';
                    $name = '';
                    $dateCheckout = '';
                    $array_btn = array('vacant' => 'Vacant', 'dirty' => 'Dirty', 'out of order' => 'Out of Order', 'vacant inspect' => 'Vacant Inspected');
                } elseif ($status == 'house use') {
                    $class = 'label-hu';
                    $name = $arr->Registration->Guest->name;
                    $dateCheckout = $arr->Registration->date_to;
                    $array_btn = array('house use' => 'House Use', 'do not disturb' => 'Do Not Disturb', 'occupied no luggage' => 'Occupied No Luggage', 'sleep out' => 'Sleep Out');
                } elseif ($status == 'compliment') {
                    $class = 'compliment';
                    $name = $arr->Registration->Guest->name;
                    $dateCheckout = $arr->Registration->date_to;
                    $array_btn = array('compliment' => 'Compliment', 'do not disturb' => 'Do Not Disturb', 'occupied no luggage' => 'Occupied No Luggage', 'sleep out' => 'Sleep Out');
                } elseif ($status == 'do not disturb') {
                    $class = 'label-dd';
                    $name = $arr->Registration->Guest->name;
                    $dateCheckout = $arr->Registration->date_to;
                    $array_btn = array('occupied' => 'Occupied', 'house use' => 'House Use', 'do not disturb' => 'Do Not Disturb', 'occupied no luggage' => 'Occupied No Luggage', 'sleep out' => 'Sleep Out');
                } elseif ($status == 'occupied no luggage') {
                    $class = 'label-onl';
                    $name = $arr->Registration->Guest->name;
                    $dateCheckout = $arr->Registration->date_to;
                    $array_btn = array('occupied' => 'Occupied', 'do not disturb' => 'Do Not Disturb', 'occupied no luggage' => 'Occupied No Luggage', 'sleep out' => 'Sleep Out');
                } elseif ($status == 'sleep out') {
                    $class = 'label-so';
                    $name = $arr->Registration->Guest->name;
                    $dateCheckout = $arr->Registration->date_to;
                    $array_btn = array('occupied' => 'Occupied', 'do not disturb' => 'Do Not Disturb', 'occupied no luggage' => 'Occupied No Luggage', 'sleep out' => 'Sleep Out');
                } elseif ($status == 'vacant inspect') {
                    $class = 'label-vci';
                    $name = '';
                    $dateCheckout = '';
                    $array_btn = array('vacant' => 'Vacant', 'dirty' => 'Dirty', 'out of order' => 'Out of Order', 'vacant inspect' => 'Vacant Inspected');
                } else {
                    $class = 'label-vc';
                    $name = '';
                    $dateCheckout = '';
                    $array_btn = array('vacant' => 'Vacant', 'dirty' => 'Dirty', 'out of order' => 'Out of Order', 'vacant inspect' => 'Vacant Inspected');
                }

                $houseKeeper = (!empty($arr->modified_user_id) && isset($arr->HouseKeeping->name)) ? $arr->HouseKeeping->name : '';

                $btn_housekeeping = $this->widget('common.extensions.bootstrap.widgets.TbEditableField', array(
                    'type' => 'select',
                    'model' => $arr,
                    'attribute' => 'status_housekeeping',
                    'text' => ucwords($arr->status_housekeeping),
                    'url' => $this->createUrl('roomCharting/updateStatus'),
                    'source' => $array_btn,
                    'success' => 'js: function(response, newValue) {
                            var user = "' . user()->name . '";
                            var now = new Date();
                            var c_outoforder ="slategrey";
                            var c_occupied ="#fcd2c2";
                            var c_onl="#f1d1eb";
                            var c_vacant ="azure";
                            var c_vacantinspect ="#d8f4d2";
                            var c_dirty ="khaki";
                            var c_so ="teal";
                            var c_dd ="indianred";
                            var c_compliment ="saltgrey";
                            var background = $(this).parent().parent().css("background");
                            var bulan = now.getMonth()+1;
                            bulan = "000"+bulan,2;
                            bulan = bulan.substr(bulan.length - 2);
                            tanggal = now.getFullYear()+"-"+bulan+"-"+now.getDate()+" "+now.getHours()+":"+now.getMinutes()+":"+now.getSeconds();
                            var newClass="";
                            if (!response.success)                               
                              $(this).parent().parent().find("td:eq(7)").html(tanggal);
                              $(this).parent().parent().find("td:eq(8)").html(user);
                              
                              if (newValue=="vacant"){                               
                                newClass ="label-vc";
                              }else if (newValue=="dirty"){
                                newClass ="label-vd";
                              }else if (newValue=="out of order"){
                                newClass ="label-ooo";
                              }else if (newValue=="vacant inspect"){
                                newClass ="label-vci";
                              }else if (newValue=="occupied"){
                                newClass ="label-o";
                              }else if (newValue=="occupied no luggage"){
                                newClass ="label-onl";
                              }else if (newValue=="do not disturb"){
                                newClass ="label-dd";
                              }else if (newValue=="sleep out"){
                                newClass ="label-so";
                              }else if (newValue=="house use"){
                                newClass ="label-hu";
                              }else if (newValue=="compliment"){
                                newClass ="compliment";
                              }
                              $(this).parent().attr("class","taro "+newClass);
                            }'
                        ), true);
                $description = $this->widget('common.extensions.bootstrap.widgets.TbEditableField', array(
                    'type' => 'textarea',
                    'model' => $arr,
                    'attribute' => 'description',
                    'url' => $this->createUrl('roomCharting/updateNotes')
                        ), true);

                //cek status booked or not
                $booked = '<span class="label label-vc">No</span>';
                if ($status == 'reservation' || $status == 'reserved') {
                    if (isset($mSchedule[$arr->id])) {
                        if ($mSchedule[$arr->id]['status'] == 'reserved') {
                            $class = 'reserved';
                            $booked = '<span class="label label-info">Yes (Confirmed)</span>';
                            $name = $mSchedule[$arr->id]->Reservation->Guest->name;
                            $regDetail = ReservationDetail::model()->find(array('condition' => 'reservation_id=' . $mSchedule[$arr->id]['reservation_id'] . ' and room_id=' . $mSchedule[$arr->id]['room_id']));
                            $dateCheckout = date('Y-M-d', strtotime($regDetail->Reservation->date_to));
                        } elseif ($mSchedule[$arr->id]['status'] == 'reservation') {
                            $class = 'reservation';
                            $booked = '<span class="label label-info">Yes (Unconfirmed)</span>';
                            $name = $mSchedule[$arr->id]->Reservation->Guest->name;
                            $regDetail = ReservationDetail::model()->find(array('condition' => 'reservation_id=' . $mSchedule[$arr->id]['reservation_id'] . ' and room_id=' . $mSchedule[$arr->id]['room_id']));
                            $dateCheckout = date('Y-M-d', strtotime($regDetail->Reservation->date_to));
                        } else {
                            $booked = '<span class="label label-vc">No</span>';
                        }
                    }
                }

                echo '<tr>                
                        <td style="text-align:center" class="taro ' . $class . '">' . $btn_housekeeping . '</td>
                        <td style="text-align:center">' . $booked . '</td>' .
                '<td>' . $arr->RoomType->name . '</td>
                        <td>' . $arr->number . '</td>
                        <td>' . $arr->bed . '</td>
                        <td>' . $name . '</td>
                        <td>' . $dateCheckout . '</td>
                        <td>' . $arr->modified . '</td>
                        <td>' . $houseKeeper . '</td>
                        <td>' . $description . '</td>
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
