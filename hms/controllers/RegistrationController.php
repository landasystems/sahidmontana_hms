<?php

class RegistrationController extends Controller {

    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = 'main';

    public function filters() {
        return array(
            'accessControl', // perform access control for CRUD operations
        );
    }

    public function accessRules() {
        return array(
            array('allow', // c
                'actions' => array('create'),
                'expression' => 'app()->controller->isValidAccess("Registration","c")'
            ),
            array('allow', // r
                'actions' => array('index', 'view'),
                'expression' => 'app()->controller->isValidAccess("Registration","r")'
            ),
            array('allow', // u
                'actions' => array('update'),
                'expression' => 'app()->controller->isValidAccess("Registration","u")'
            ),
            array('allow', // d
                'actions' => array('delete'),
                'expression' => 'app()->controller->isValidAccess("Registration","d")'
            )
        );
    }

    /**
     * @return array action filters
     */
    public function actionCheckRoom() {
        $sDate = str_replace(" ", "", $_POST['Registration']['date_to']);
        $date = explode('-', $sDate);
        $date1 = explode('/', $date[0]);
        $date1 = $date1[2] . "/" . $date1[1] . "/" . $date1[0];
        $date2 = explode('/', $date[1]);
        $date2 = $date2[2] . "/" . $date2[1] . "/" . $date2[0];
        $start = date("Y/m/d", strtotime($date1));
        $end = date("Y/m/d", strtotime('-1 day', strtotime($date2)));

        if (empty($_POST['Registration']['id']) or $_POST['Registration']['id'] == '0') {
            $criteria = '';
        } else {
            $criteria = 'registration_id != ' . $_POST['Registration']['id'] . ' and ';
        }

        if (empty($_POST['Registration']['reservation_id']) or $_POST['Registration']['reservation_id'] == '0') {
            $criteria .= '';
        } else {
            $criteria .= 'reservation_id != ' . $_POST['Registration']['reservation_id'] . ' and ';
        }


        $warning = '';
        $idNotAva = array();
        $idStatus = array();
        $cek = RoomSchedule::model()->findAll(array('condition' => $criteria . ' date_schedule between "' . $start . '" and "' . $end . '"', 'order' => 'date_schedule asc'));
        if (!empty($cek)) {
            foreach ($cek as $data) {
                if (!isset($idStatus[$data->room_id]['status'])) {
                    $idNotAva[] = $data->room_id;
                    $idStatus[$data->room_id]['status'] = $data->status;
                    $idStatus[$data->room_id]['date'] = $data->date_schedule;
                }
            }

            if (isset($_POST['RegistrationDetail']['room_id'])) {
                for ($i = 0; $i < count($_POST['RegistrationDetail']['room_id']); $i++) {
                    if (in_array($_POST['RegistrationDetail']['room_id'][$i], $idNotAva)) {
                        $warning .= 'Room ' . $_POST['RegistrationDetail']['room_id'][$i] . ' has ' . $idStatus[$_POST['RegistrationDetail']['room_id'][$i]]['status'] . ' on ' . $idStatus[$_POST['RegistrationDetail']['room_id'][$i]]['date'] . ' <br>';
                    }
                }
            }
        }

        echo $warning;
    }

    public function actionGetReservation() {
        $id = $_POST['id'];
        $reservation = Reservation::model()->findByPk($id);
        $siteConfig = SiteConfig::model()->findByPk(1);
        $settings = json_decode($siteConfig->settings, true);

        $user = User::model()->findByPk($reservation->guest_user_id);
        $return['group'] = $user->roles_id;
        $return['name'] = $user->name;
        $return['email'] = $user->email;
        $return['city'] = $user->City->id;
        $return['province'] = $user->City->Province->id;
        $return['address'] = $user->address;
        $return['phone'] = $user->phone;
        $return['user_id'] = $user->id;
        $return['sex'] = $user->sex;
        $return['birth'] = $user->birth;
        $return['nationality'] = $user->nationality;
        $return['user_id_number'] = $user->code;
        $return['company'] = $user->company;
        $return['code'] = $user->code;

        if (!empty($reservation->deposite_id)) {
            $return['deposite_code'] = $reservation->Deposite->code;
            $return['deposite_dp_by'] = $reservation->Deposite->dp_by;
            $return['deposite_amount'] = $reservation->Deposite->amount;
            $return['deposite_cc_number'] = $reservation->Deposite->cc_number;
            $return['deposite_description'] = $reservation->Deposite->description;
        }


        $return['package'] = $reservation->package_room_type_id;
        $return['type'] = $reservation->type;
        $return['market_segment'] = $reservation->market_segment_id;
        $return['date_from'] = date('d/m/Y', strtotime($reservation->date_from)) . " - " . date('d/m/Y', strtotime($reservation->date_to));
        $return['billing'] = "0";
        $return['billing_user'] = "<span>Please Choose</span>" . '<abbr class="select2-search-choice-close" style=""></abbr>' . '<div><b></b></div>';
        if (!empty($reservation->billing_user_id)) {
            $return['billing'] = $reservation->billing_user_id;
            $return['billing_user'] = '<span>' . $reservation->Bill->fullName . '</span>' . '<abbr class="select2-search-choice-close" style=""></abbr>' . '<div><b></b></div>';
        }
        $return['billing_note'] = $reservation->billing_note;

        $mDetail = ReservationDetail::model()->findAll(array('condition' => 'reservation_id=' . $reservation->id));
        $myDetail = "";
        foreach ($mDetail as $detail) {
            if ($reservation->package_room_type_id == 0 || $reservation->package_room_type_id == '') {
                $rate = json_decode($detail->Room->RoomType->rate, true);
                $price = 'Min :' . landa()->rp($rate[$reservation->Guest->roles_id]['min']) . '<br> Default : ' .
                        landa()->rp($rate[$reservation->Guest->roles_id]['default']) . '<br> Max :' .
                        landa()->rp($rate[$reservation->Guest->roles_id]['max']);
            } else {
                $ratePakcage = RoomType::model()->findByPk($reservation->package_room_type_id);
                $rate = json_decode($ratePakcage->rate, true);
                $price = 'Min :' . landa()->rp($rate[$reservation->Guest->roles_id]['min']) . '<br> Default : ' .
                        landa()->rp($rate[$reservation->Guest->roles_id]['default']) . '<br> Max :' .
                        landa()->rp($rate[$reservation->Guest->roles_id]['max']);
            }
            $checkbox_others_include = "";
            if ($siteConfig->others_include != "") {
                $others_include = json_decode($siteConfig->others_include);
                $detail_include = json_decode($detail->others_include, true);
                foreach ($others_include as $other) {
                    $tuyul = ChargeAdditional::model()->findByPk($other);
                    $checked = (!empty($detail_include[$other])) ? 'checked' : '';
                    if (count($tuyul) > 0) {//                                                   
                        $val = (isset($detail_include[$other])) ? $detail_include[$other] : $tuyul->charge;
                        $checkbox_others_include.=$this->renderPartial('_oi', array('checked' => $checked, 'id' => $tuyul->id, 'room_id' => $detail->Room->id, 'val' => $val, 'name' => $tuyul->name), true);
                    }
                }
            }
            $id = $detail->room_id;
            $row = '';
            $row .= "<tr class='itemSelected' id='" . $id . "'>";
            $row .= "<td>";
            $row .= '<input type="hidden" value="' . $id . '" name="RegistrationDetail[room_id][]" />';
            $row .= 'No : ' . $detail->Room->number . '<br>';
            $row .= 'Type : ' . $detail->Room->RoomType->name . ' - ' . ucfirst(strtolower($detail->Room->bed)) . '<br>';
            $row .= 'Floor : ' . $detail->Room->floor . '<br>';
            $row .= "</td>";
            $row .= "<td>";
            $row .= '<textarea  style="width:150px" rows="3" name="RegistrationDetail[guest_user_names][]">' . $detail->guest_user_names . '</textarea>';
            $row .= "</td>";
            $row .= "<td>";
            $row .= '<input  style="width:30px" onChange="calculation()" class="pax" type="text" value="' . $detail->pax . '" name="RegistrationDetail[pax][]" />';
            $row .= "</td>";
            $row .= "<td>";
            $row .= "<input type='hidden' name='ori_fnb_price' class='ori_fnb_price' value='" . $detail->Room->RoomType->fnb_charge . "' />";
            $row .= '<div class="input-prepend"><span class="add-on">Rp</span><input  style="width:70px" onChange="calculation()" readOnly class="edit_price fnb_price" type="text" value="' . $detail->fnb_price . '" name="RegistrationDetail[fnb_price][]" /></div>';
            $row .= "</td>";
            $row .= "<td>";
            $row .= '<input  style="width:30px" onChange="calculation()" type="text" class="extrabed" value="' . $detail->extrabed . '" name="RegistrationDetail[extrabed][]" />';
            $row .= "</td>";
            $row .= "<td>";
            $row .= '<div class="input-prepend"><span class="add-on">Rp</span><input  style="width:70px" onChange="calculation()" readOnly class="edit_price extrabed_price" type="text" value="' . $detail->extrabed_price . '" name="RegistrationDetail[extrabed_price][]" /></div>';
            $row .= "</td>";
            $row .= "<td>";
            $row .= $checkbox_others_include;
            $row .= "</td>";
            $row .= "<td>";
            $row .= "<input type='hidden' name='ori_rate' class='ori_rate' value='" . $rate[$reservation->Guest->roles_id]['default'] . "' />";
            $row .= '<div class="input-prepend"><span class="add-on">Rp</span><input style="width:70px" onChange="calculation()" class="room_rate edit_price" readOnly  type="text" value="' . $detail->room_price . '" name="RegistrationDetail[room_price][]" /></div>';
            $row .= "</td>";
            $row .= "<td>";
            $row .= '<div class="input-prepend"><span class="add-on">Rp</span><input style="width:70px" onChange="calculation()" type="text" class="total_rate" value="' . $detail->charge . '" name="RegistrationDetail[charge][]" readonly /></div>';
            $row .= "</td>";
            $row .= "<td>";
            $row .= '<a class="btn btn-small" onClick="$(this).parent().parent().remove();totalRoom(); $(\'button[type="submit"]\').removeAttr(\'disabled\');"  title="Remove" rel="tooltip" "><i class="icon-minus"></i></a>';
            $row .= "</td>";

            $row .= "</tr>";
            $row .= '<tr id="selected" style="display:none"></tr>';
            $myDetail .= $row;
            $include = $detail->others_include;
        }
        $myDetail .= '<tr id="selected" style="display:none">
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>';
        $myDetail .= '<script>' . $this->cssJs() . '</script>';

        $return['detail'] = $myDetail;
        $return['roomSelected'] = count($mDetail);
        $return['otherInclude'] = $include;
        echo json_encode($return);
    }

    public function actionGetRoom() {
        $sDate = str_replace(" ", "", $_POST['Registration']['date_to']);
        $type = $_POST['roomType'];
        $floor = $_POST['floor'];
        $bed = $_POST['bed'];
        $package = $_POST['Registration']['package_room_type_id'];

        $roomType = RoomType::model()->findAll(array('condition' => 'is_package=0'));
        foreach ($roomType as $rt) {
            $arrRoomType[$rt->id]['KING'] = 0;
            $arrRoomType[$rt->id]['TWIN'] = 0;
        }
        $date = explode('-', $sDate);
        $date1 = explode('/', $date[0]);
        $date1 = $date1[2] . "/" . $date1[1] . "/" . $date1[0];
        $date2 = explode('/', $date[1]);
        $date2 = $date2[2] . "/" . $date2[1] . "/" . $date2[0];
        $start = date("Y/m/d", strtotime($date1));
        $end = date("Y/m/d", strtotime('-1 day', strtotime($date2)));

        $filter = 't.status!="out of order" and t.status!="dirty" and t.status!="occupied" and t.status!="compliment" and t.status!="house use" and t.id not in (select acca_room_schedule.room_id from acca_room_schedule where status<>"vacant"  and date_schedule between "' . $start . '" and "' . $end . '")';
        if (!empty($type))
            $filter .= ' and t.room_type_id=' . $type . '';
        if (!empty($floor))
            $filter .= ' and t.floor=' . $floor . '';
        if (!empty($bed))
            $filter .= ' and t.bed="' . strtolower($bed) . '"';


        $data = Room::model()->with('RoomSchedule')->
                findAll(array('condition' => $filter));
        $return = "";
        $y = 0;
        foreach ($data as $value) {
            $arrRoomType[$value->room_type_id][$value->bed] ++;
            $arrType[$value->room_type_id] = 'ok';
            if ($package == 0) {
                $rate = json_decode($value->RoomType->rate, true);
                $price = 'Min :' . landa()->rp($rate[$_POST['roles']]['min']) . '<br> Default : ' .
                        landa()->rp($rate[$_POST['roles']]['default']) . '<br> Max :' .
                        landa()->rp($rate[$_POST['roles']]['max']);
            } else {
                $package_model = RoomType::model()->findByPk($package);
                $rate = json_decode($package_model->rate, true);
                $price = 'Min :' . landa()->rp($rate[$_POST['roles']]['min']) . '<br> Default : ' .
                        landa()->rp($rate[$_POST['roles']]['default']) . '<br> Max :' .
                        landa()->rp($rate[$_POST['roles']]['max']);
            }
            $y = 1;
            $status_housekeeping = ($value->status_housekeeping == "vacant inspect") ? '<span class="label label-info">' . $value->status_housekeeping . '</span>' : '<span class="label label-important">' . $value->status_housekeeping . '</span>';
            $return .=' <tr class="item" id="' . $value->id . '">
                                <td class="span1" style="text-align:center">' . $value->number . '</td>
                                <td class="span3">' . ucwords($value->RoomType->name) . '</td>
                                <td class="span1" style="text-align:center">' . $value->floor . '</td>
                               <td class="span2">' . ucwords($value->bed) . '</td>
                                <td class="span3">' . $price . '</td>
                                <td class="span1" style="text-align:center">' . $status_housekeeping . '</td>
                                <td style="width:30px;text-align:center"><a class="btn btn-small btn-add" taro="' . $value->id . '" title="Add" rel="tooltip" "><i class="icon-plus"></i></a></td>
                            </tr>';
        }
        $return .='<tr id="addRow" style="display:none">
                               
                    </tr>';
        $return .= '<script>' . $this->cssJs() . '</script>';
        $statistik = '';
        $totalAll = 0;
        $totalKing = 0;
        $totalTwin = 0;
        foreach ($roomType as $rt) {
            if (isset($arrType[$rt->id])) {
                $all = $arrRoomType[$rt->id]['KING'] + $arrRoomType[$rt->id]['TWIN'];
                $totalAll += $all;
                $totalKing += $arrRoomType[$rt->id]['KING'];
                $totalTwin += $arrRoomType[$rt->id]['TWIN'];
                $statistik .= '<tr  class="item"><td>' . $rt->description . '</td><td>' . $arrRoomType[$rt->id]['KING'] . '</td><td>' . $arrRoomType[$rt->id]['TWIN'] . '</td><td>' . $all . '</td></tr>';
            }
        }
        $statistik .= '<tr style="background:lightsteelblue"  class="item"><td><b>TOTAL<b></td><td><b>' . $totalKing . '</b></td><td><b>' . $totalTwin . '</b></td><td><b>' . $totalAll . '</b></td></tr>';
        $statistik .= '<tr id="statistik" style="display:none"></tr>';
        if ($y == 0)
            $return = '<tr id="addRow"><td colspan="6"> No record available</td></tr>';
        $dataq['room'] = $return;
        $dataq['statistik'] = $statistik;
        $dataq['date_start'] = $date[0];
        $dataq['date_end'] = $date[1];

        echo json_encode($dataq);
    }

    public function actionGetListGuest() {
        $name = $_GET['term'];
        $guestName = User::model()->with('Roles')->findAll(array('condition' => 'Roles.is_allow_login=0 and t.name like "%' . $name . '%"'));
        $source = array();
        foreach ($guestName as $val) {
            if (empty($val->company)) {
                $name = $val->name;
            } else {
                $name = $val->name . ' ( ' . $val->company . ' )';
            }
            $source[] = array(
                'item_id' => $val->id,
                'label' => $name,
                'value' => $val->name,
            );
        }
        echo CJSON::encode($source);
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionGetPackage() {
        $id = $_POST['Registration']['package_room_type_id'];
        $group = $_POST['group'];
        if ($id != 0) {
            $roomPackage = RoomType::model()->findByPk($id);
            $package = json_decode($roomPackage->charge_additional_ids);
            $guest = json_decode($roomPackage->rate, TRUE);
            $listPackage = '';
            $total = 0;
            $arr = array();
            $rate = array();
            $value = array();
            $rate = array('fnb' => $roomPackage->fnb_charge, 'charge' => $guest[$group]['roomRate'], 'rate' => $guest[$group]['default']);
            if (!empty($package)) {
                foreach ($package as $data) {
                    $additional = ChargeAdditional::model()->findByPk($data->id);
                    $nmPackage = '[ ' . $data->amount . ' x  @' . landa()->rp($data->charge, false) . '] ' . $additional->name . '<br>';
                    $listPackage .= $nmPackage;
                    $totOi = ($data->amount * $data->charge );
                    //$rate = $guest->1;
                    $arr[] = array('id' => $additional->id, 'name' => $nmPackage, 'total' => $totOi, 'pax' => $roomPackage->pax);
                    $total += $totOi;
                }
            }

            $ret = '<div class="alert alert-info fade in">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        <strong>Detail Package : </strong><br>     
                        <div class="content_package">
                        ' . $listPackage . ' 
                            ------------------------------------------------------------------------------<br>
                            Total Detail Package : ' . landa()->rp($total) . '
                            <input type="hidden" id="addPackage" name="addPackage" value="' . $total . '"/>
                            <input type="hidden" id="detPackage" name="detPackage" value=\'' . json_encode($arr) . '\'/>
                            <input type="hidden" id="pricePackage" name="pricePackage" value=\'' . json_encode($rate) . '\'/>
                        </div>
                    </div>';
            echo $ret;
        }
    }

    public function actionView($id) {
        cs()->registerScript('read', '
            $("form input, form textarea, form select").each(function(){
                $(this).prop("disabled", true);
            });');
        $_GET['v'] = true;
        $this->actionUpdate($id);
    }

    public function actionAddRoom() {
        $usertype = $_POST['usertype'];
        $package = $_POST['package'];
        $type = $_POST['Registration']['type'];
        $id = $_POST['id'];
        $room = Room::model()->findByPk($id);
        $siteConfig = SiteConfig::model()->findByPk(1);
        $settings = json_decode($siteConfig->settings, true);
        $fnb = (!empty($settings['fb_charge'])) ? $settings['fb_charge'] : 0;
        $exbed = (!empty($settings['extrabed_charge'])) ? $settings['extrabed_charge'] : 0;
        $checkbox_others_include = "";
        $checkbox_others_include_sys = "";
        $fnb_price = $room->RoomType->fnb_charge;
        $pax = 2;
        if ($siteConfig->others_include != "") {
            $others_include = json_decode($siteConfig->others_include);
            foreach ($others_include as $other) {
                $tuyul = ChargeAdditional::model()->findByPk($other);
                if (count($tuyul) > 0) {
                    $val = (isset($_POST["_" . $tuyul->id])) ? $_POST["_" . $tuyul->id] : 0;
                    $checkbox_others_include_sys.= $this->renderPartial('_oi', array('checked' => '', 'id' => $tuyul->id, 'room_id' => $id, 'val' => $val, 'name' => $tuyul->name), true);
                }
            }
        }
        if ($package == 0) {
            $rate = json_decode($room->RoomType->rate, true);
            $price = 'Min :' . landa()->rp($rate[$usertype]['min']) . '<br> Default : ' .
                    landa()->rp($rate[$usertype]['default']) . '<br> Max :' .
                    landa()->rp($rate[$usertype]['max']);
        } else {
            //menambahkan centangan, klo package di pilih
            $package_model = RoomType::model()->findByPk($package);
            $arrPackage = json_decode($package_model->charge_additional_ids);
            if (!empty($arrPackage)) {
                foreach ($arrPackage as $data) {
                    $additional = ChargeAdditional::model()->findByPk($data->id);
                    $nmPackage = '[ ' . $data->amount . ' x  @' . landa()->rp($data->charge, false) . '] ' . $additional->name . '<br>';
                    $totOi = ($data->amount * $data->charge );
                    $checkbox_others_include.= $this->renderPartial('_oi', array('checked' => ($type == "regular") ? 'checked' : '', 'id' => $additional->id, 'room_id' => $id, 'val' => $totOi, 'name' => $nmPackage), true);
                }
            }

            $rate = json_decode($package_model->rate, true);
            $price = 'Min :' . landa()->rp($rate[$usertype]['min']) . '<br> Default : ' .
                    landa()->rp($rate[$usertype]['default']) . '<br> Max :' .
                    landa()->rp($rate[$usertype]['max']);
            $fnb_price = $package_model->fnb_charge;
            $pax = $package_model->pax;
        }

        if ($type == "regular") {
            
        } else {
            $total = 0;
            $pax = 0;
            $exbed = 0;
            $rate[$usertype]['roomRate'] = 0;
        }
        $row = '';
        $row .= "<tr class='itemSelected' id='" . $id . "'>";
        $row .= "<td>";
        $row .= '<input type="hidden" value="' . $id . '" name="RegistrationDetail[room_id][]" class="room_id"/>';
        $row .= 'No : ' . $room->number . '<br>';
        $row .= 'Type : ' . $room->RoomType->name . ' - ' . ucfirst(strtolower($room->bed)) . '<br>';
        $row .= 'Floor : ' . $room->floor . '<br>';
        $row .= "</td>";
        $row .= "<td>";
        $row .= '<textarea style="width:150px" rows="3" name="RegistrationDetail[guest_user_names][]"></textarea>';
        $row .= "</td>";
        $row .= "<td>";
        $row .= '<input style="width:30px" onChange="calculation()" class="pax" type="text" value="' . $pax . '" name="RegistrationDetail[pax][]" />';
        $row .= "</td>";
        $row .= "<td>";
        $row .= "<input type='hidden' name='ori_fnb_price' class='ori_fnb_price' value='" . $fnb_price . "' />";
        $row .= '<div class="input-prepend"><span class="add-on">Rp</span><input style="width:70px" onChange="calculation()" readOnly class="edit_price fnb_price" type="text" value="' . $fnb_price . '" name="RegistrationDetail[fnb_price][]" /></div>';
        $row .= "</td>";
        $row .= "<td>";
        $row .= '<input style="width:30px" onChange="calculation()" type="text" class="extrabed" value="0" name="RegistrationDetail[extrabed][]" />';
        $row .= "</td>";
        $row .= "<td>";
        $row .= '<div class="input-prepend"><span class="add-on">Rp</span><input style="width:70px" onChange="calculation()" readOnly class="edit_price extrabed_price" type="text" value="' . $exbed . '" name="RegistrationDetail[extrabed_price][]" /></div>';
        $row .= "</td>";
        $row .= "<td>";
        $row .= $checkbox_others_include_sys;
        $row .= '<span class="pckg">' . $checkbox_others_include . '</span></td>';
        $row .= "<td>";
        $row .= "<input type='hidden' name='ori_rate' class='ori_rate' value='" . $rate[$usertype]['roomRate'] . "' />";
        $row .= '<div class="input-prepend"><span class="add-on">Rp</span><input style="width:70px" onChange="calculation()" class="room_rate edit_price" readOnly  type="text" value="' . $rate[$usertype]['roomRate'] . '" name="RegistrationDetail[room_price][]" /></div>';
        $row .= "</td>";
        $row .= "<td>";
        $row .= '<div class="input-prepend"><span class="add-on">Rp</span><input style="width:70px" onChange="calculation()" type="text" class="total_rate" value="" name="RegistrationDetail[charge][]" readonly /></div>';
        $row .= "</td>";
        $row .= "<td>";
        $row .= '<a class="btn btn-small" onClick="$(this).parent().parent().remove();totalRoom(); $(\'button[type="submit"]\').removeAttr(\'disabled\');"  title="Remove" rel="tooltip" "><i class="icon-minus"></i></a>';
        $row .= "</td>";
        $row .= "</tr>";
        $row .= '<tr id="selected" style="display:none"></tr>';

        $value['row'] = $row;
        $value['id'] = $id;
        $value['status_housekeeping'] = $room->status_housekeeping;
        echo json_encode($value);
    }

    public function cssJs() {
        return '
                $(".btn-add").click(function(){
                    var tambahan = "&id=" + $(this).attr("taro") + "&package=" + $("#Registration_package_room_type_id").val() + "&usertype=" + $("#roles").val() + "&type=" + $("#Registration_type").val();
                    var postData = $("#registration-form").serialize()+ tambahan;
                    $.ajax({
                        url:"' . url("registration/addRoom") . '",
                        data:postData,
                        type:"post",
                        success:function(data){
                            obj = JSON.parse(data);   
                            if (obj.status_housekeeping !="vacant inspect"){
                                var r = confirm("Room status is not Vacant Inspect. Are you sure to continue?");
                                if (r == true) {
                                    $("#"+obj.id).remove();                              
                                    if($("#"+obj.id).length )
                                    {}else{
                                       $("#selected").replaceWith(obj.row);                     
                                    }
                                    totalRoom(); 
                                }
                            }else{                                                        
                                $("#"+obj.id).remove();                              
                                if($("#"+obj.id).length )
                                {}else{
                                   $("#selected").replaceWith(obj.row);                     
                                }
                                totalRoom();
                            }
                            calculation();
                        }
                    });
                });
                
                
                
                $(".removeSelect").on("click", function() {
                    $(this).parent().parent().remove();
                    totalRoom();
                     $(\'button[type="submit"]\').removeAttr(\'disabled\');
                });                                   

                function removeSelect(id){                                                                
                   $("#"+id).parent().remove();
                   totalRoom();
                };                                               
            ';
    }

    public function js() {
        Cs()->registerScript('', $this->cssJs());
        Cs()->registerScript('', '
            $(".others_include").click(function(event) {  //on click                   
                    calculation();
                });

            $("#myTab a").click(function(e) {
                    e.preventDefault();
                    $(this).tab("show");
                });                 
             $(".editOtherInclude").change(function(event) {  //on change                                     
                $("."+$(this).attr("kode")).val(this.value); 
                calculation();
             });
            function seachBy(){         
                        $("#newGuestCheck").prop("checked")==true;
                        if ($("#seachBy").prop("checked")==true){
                            $("#newGuest").show();
                            $("#reservation").hide();                            
                            if ($("#newGuestCheck").prop("checked")==true){
                            disEn("0");
                            }else{
                            disEn("1");
                            }
                        }else{
                            $("#newGuest").hide();
                            $("#contact").hide();
                            $("#reservation").show();
                            disEn("1");
                        }                        
                    }
            $(".removeSelect").on("click", function() {
              $(this).parent().parent().remove();
               $(\'button[type="submit"]\').removeAttr(\'disabled\');
            });   
            
            $("#group").on("change", function() {
                $("#roles").val(this.value);             
            });          
            

            function newGuest(){   
                        $("#Deposite_code").val("");
                        if ($("#newGuestCheck").prop("checked")==true){                            
                            disEn("0");
                            $("#contact").hide();                            
                        }else{
                            $("#contact").show();                            
                            disEn("1");
                        }
                    }                   
            

            $("#all_others").click(function(event) {  //on click 
                if(this.checked) { // check select status
                    $(".others_include").prop("checked", true);
                }else{
                    $(".others_include").prop("checked", false);
                }
                calculation();
            });
                       
            
            function disEn(state){                
                if (state=="1"){    
//                    $("#group").val("");
                    $("#company").val("");
                    $("#birth").val("");
                    $("#nationality").val("");
                    $("#name").val("");
                    $("#userNumber").val("");                    
//                    $("#province_guest").val(0);
//                    $("#city_guest").val(0);
                    $("#address").val("");
                    $("#phone").val("");                      
//                    $("#Registration_billing_note").val("");                      
//                    $("#Registration_dp").val("");                      
//                    $("#Registration_billing_user_id").val("0");                         
//                    $("#s2id_Registration_billing_user_id .select2-choice").html("<span>Please Choose</span><abbr class=\'select2-search-choice-close\'></abbr><div><b></b></div>");
//                    $(".itemSelected").remove();
                    
                }else{  
//                    $("#group").val("");
                    $("#name").val("");                    
                    $("#userNumber").val("");
//                    $("#province_guest").val(0);
//                    $("#city_guest").val(0);
                    $("#address").val("");
                    $("#phone").val("");    
                    $("#company").val("");
                    $("#birth").val("");
                    $("#nationality").val("");
//                    $("#Registration_billing_user_id").val("0");   
//                    $("#Registration_billing_note").val("");     
//                    $("#Registration_dp").val("");                      
//                    $(".itemSelected").remove();
//                    $("#s2id_Registration_billing_user_id .select2-choice").html("<span>Please Choose</span><abbr class=\'select2-search-choice-close\'></abbr><div><b></b></div>");
                }
                
            }
        ');
    }

    public function actionCreate() {
        cs()->registerScript('wide', '$(".landaMin").trigger("click");');
        $model = new Registration;
        $modelDp = new Deposite;
        $modelDp->code = SiteConfig::model()->formatting('deposite');
        $this->js();
        $siteConfig = SiteConfig::model()->findByPk(1);
        $settings = json_decode($siteConfig->settings, true);

        if (isset($_POST['Registration'])) {
//            $this->redirect($_POST['RegistrationDetail']['room_id'][0]);
//            $this->redirect($_POST['group']);

            if (!empty($_POST['RegistrationDetail']['room_id']) && !empty($_POST['group'])) {
                $model->attributes = $_POST['Registration'];
                $model->guest_user_id = $_POST['id'];
                $reservation = Reservation::model()->findByPk($model->reservation_id);
                $model->approval_user_id = (!empty($reservation)) ? $reservation->approval_user_id : '';
                $model->code = SiteConfig::model()->formatting('registration', FALSE);
                $by = "user";
                if (!empty($_POST['seachBy'])) { //from walk in     
                    if (empty($_POST['id'])) { // new guest                        
                        $user = new User;
                        $user->scenario = 'notAllow';
                        $user->username = '';
                        $user->password = '';
                        $user->name = (!empty($_POST['name'])) ? $_POST['name'] : '';
                        $user->roles_id = (!empty($_POST['group'])) ? $_POST['group'] : '';
                        $user->enabled = 1;
                        $user->email = (!empty($_POST['email'])) ? $_POST['email'] : '';
                        $user->city_id = $_POST['city_guest'];
                        $user->address = (!empty($_POST['address'])) ? $_POST['address'] : '';
                        $user->phone = (!empty($_POST['phone'])) ? $_POST['phone'] : '';
                        $user->code = (!empty($_POST['userNumber'])) ? $_POST['userNumber'] : '';
                        $company = (!empty($_POST['company'])) ? $_POST['company'] : '';
                        //$other = json_encode(array('company' => $company));
                        $user->company = $company;
                        $user->birth = (!empty($_POST['birth'])) ? date('Y/m/d', strtotime($_POST['birth'])) : '';
                        $user->sex = (!empty($_POST['sex'])) ? $_POST['sex'] : '';
                        $user->nationality = (!empty($_POST['nationality'])) ? $_POST['nationality'] : '';
                        $user->save();
                        $model->guest_user_id = $user->id;
                    } else {
                        $user = User::model()->findByPk($model->guest_user_id);
                        $user->scenario = 'notAllow';
                        $user->name = (!empty($_POST['name'])) ? $_POST['name'] : '';
                        $user->roles_id = (!empty($_POST['group'])) ? $_POST['group'] : '';
                        $user->enabled = 1;
                        $user->email = (!empty($_POST['email'])) ? $_POST['email'] : '';
                        $user->city_id = $_POST['city_guest'];
                        $user->address = (!empty($_POST['address'])) ? $_POST['address'] : '';
                        $user->phone = (!empty($_POST['phone'])) ? $_POST['phone'] : '';
                        $user->code = (!empty($_POST['userNumber'])) ? $_POST['userNumber'] : '';
                        $company = (!empty($_POST['company'])) ? $_POST['company'] : '';
                        //$other = json_encode(array('company' => $company));
                        $user->company = $company;
                        $user->birth = (!empty($_POST['birth'])) ? date('Y/m/d', strtotime($_POST['birth'])) : '';
                        $user->sex = (!empty($_POST['sex'])) ? $_POST['sex'] : '';
                        $user->nationality = (!empty($_POST['nationality'])) ? $_POST['nationality'] : '';
                        $user->save();
                    }
                    $model->reservation_id = "";
//note : default guest_user_id is from walk in and load from user
                } else {
// from reservation
                    $by = "reservation";
                    if (!empty($model->Reservation->deposite_id)) {
                        $modelDp = Deposite::model()->findByPk($model->Reservation->deposite_id);
                    }
                }
                if (!empty($model->guest_user_id)) {
                    $sDate = str_replace(" ", "", $_POST['Registration']['date_to']);
                    $date = explode('-', $sDate);
                    $date1 = explode('/', $date[0]);
                    $date1 = $date1[2] . "/" . $date1[1] . "/" . $date1[0];
                    $date2 = explode('/', $date[1]);
                    $date2 = $date2[2] . "/" . $date2[1] . "/" . $date2[0];
                    $start = date("Y/m/d", strtotime($date1));
                    $end = date("Y/m/d", strtotime('-1 day', strtotime($date2)));
//                    $sDate = $_POST['Registration']['date_to'];
//                    $date = explode('-', $sDate);
                    $model->date_from = date("Y/m/d", strtotime($date1));
                    $model->date_to = date("Y/m/d", strtotime($date2));
                    if ($model->save()) {
//update deposite is_used =1 yg usernya sama                    
                        Deposite::model()->updateAll(array('is_used' => 1, 'used_today' => 1), 'guest_user_id=' . $model->guest_user_id . ' and is_applied=0');

                        if ($_POST['Deposite']['amount'] > 0) {
                            $modelDp->attributes = $_POST['Deposite'];
                            if ($modelDp->isNewRecord == true)
                                $modelDp->code = SiteConfig::model()->formatting('deposite', false);
                            $modelDp->guest_user_id = $model->guest_user_id;
                            $modelDp->used_amount = 0;
                            $modelDp->is_used = 1;
                            $modelDp->used_today = 1;
                            $modelDp->balance_amount = $modelDp->amount - $modelDp->used_amount;
                            if ($modelDp->save()) {
                                $model->deposite_id = $modelDp->id;
                                $model->save();
                            }
                        }

                        for ($i = 0; $i < count($_POST['RegistrationDetail']['room_id']); $i++) {
//update detail
                            $mDet = new RegistrationDetail;
                            $mDet->registration_id = $model->id;
                            $mDet->room_id = $_POST['RegistrationDetail']['room_id'][$i];
                            $mDet->charge = $_POST['RegistrationDetail']['charge'][$i];
                            $mDet->extrabed = $_POST['RegistrationDetail']['extrabed'][$i];
                            $mDet->pax = $_POST['RegistrationDetail']['pax'][$i];
                            $mDet->extrabed_price = $_POST['RegistrationDetail']['extrabed_price'][$i];
                            $mDet->room_price = $_POST['RegistrationDetail']['room_price'][$i];
                            $mDet->fnb_price = $_POST['RegistrationDetail']['fnb_price'][$i];

                            if (!empty($_POST['others_include'][$mDet->room_id])) {
                                $mDet->others_include = json_encode($_POST['others_include'][$mDet->room_id]);
                            }

                            $mDet->guest_user_names = $_POST['RegistrationDetail']['guest_user_names'][$i];
                            $mDet->save();

//update guest room
                            $room = Room::model()->findByPk($mDet->room_id);
                            $room->registration_id = $model->id;
                            $room->status = ($model->type == "regular") ? 'occupied' : $model->type;
                            $room->status_housekeeping = ($model->type == "regular") ? 'occupied' : $model->type;
                            $room->extrabed = $_POST['RegistrationDetail']['extrabed'][$i];
                            $room->pax = $_POST['RegistrationDetail']['pax'][$i];
                            $room->save();

//                      update reservation
                            if ($by == "reservation") {
                                $reservation = Reservation::model()->findByPk($model->reservation_id);
                                $reservation->status = "registered";
                                $reservation->save();
//delete schedule from reservation
                                RoomSchedule::model()->deleteAll('reservation_id=' . $model->reservation_id);
                            }

                            $startTime = strtotime($date1);
                            $endTime = strtotime("-1 day", strtotime($date2));

// Loop between timestamps, 24 hours at a time
                            $b = 0;
                            $lead_room_bill_id = 0;
                            for ($a = $startTime; $a <= $endTime; $a = $a + 86400) {
//add to room bill              
                                $mBill = new RoomBill;
                                $mBill->room_id = $mDet->room_id;
                                $mBill->charge = $mDet->charge;
                                $mBill->room_number = $mDet->Room->number;
                                $mBill->date_bill = date('Y/m/d', $a);
                                $mBill->registration_id = $model->id;
                                $mBill->extrabed = $_POST['RegistrationDetail']['extrabed'][$i];
                                $mBill->pax = $_POST['RegistrationDetail']['pax'][$i];
                                $mBill->extrabed_price = $_POST['RegistrationDetail']['extrabed_price'][$i];
                                $mBill->room_price = $_POST['RegistrationDetail']['room_price'][$i];
                                $mBill->fnb_price = $_POST['RegistrationDetail']['fnb_price'][$i];
                                $mBill->package_room_type_id = $model->package_room_type_id;
                                $mBill->processed = 0;
                                $mBill->lead_room_bill_id = $lead_room_bill_id;

//                            if ($b == 0) {
//                                $mBill->processed = 1; // first day is true, else is false                                
//                            } else {
//                                $mBill->processed = 0;
//                            }
                                $mBill->others_include = $mDet->others_include;
                                $mBill->save();
                                if ($b == 0) //set lead room bill
                                    $lead_room_bill_id = $mBill->id;
                                $b++;
//add to schedule
                                $mSchedule = new RoomSchedule;
                                $mSchedule->room_id = $mDet->room_id;
                                $mSchedule->date_schedule = date('Y/m/d', $a);
                                $mSchedule->status = ($model->type == "regular") ? 'occupied' : $model->type;
                                $mSchedule->registration_id = $model->id;
                                $mSchedule->room_bill_id = $mBill->id;
                                if (!$mSchedule->save())
                                    throw new CHttpException(404, 'The requested page does not exist.');
                            }
                        }
                        $this->redirect(array('view', 'id' => $model->id));
                    }
                }else {
                    Yii::app()->user->setFlash('error', "<b>Guest information</b> cannot be blank");
                }
            }
        }
        $model->code = SiteConfig::model()->formatting('registration');
        $this->render('create', array(
            'model' => $model,
            'modelDp' => $modelDp,
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id) {
        $model = $this->loadModel($id)->with('Guest', 'Guest.City', 'Guest.City.province', 'Bill');
        $this->js();

        if ($model->is_na == 1 and !isset($_GET['v'])) // jika sudah di NA registrasi tidak bisa di update
            throw new CHttpException(500, 'Registrasi already Night Audited, cannot be update.');

        $siteConfig = SiteConfig::model()->findByPk(1);
        $settings = json_decode($siteConfig->settings, true);
        cs()->registerScript('wide', '$(".landaMin").trigger("click");');

        if (!empty($model->deposite_id)) {
            $modelDp = Deposite::model()->findByPk($model->deposite_id);
        } else {
            $modelDp = new Deposite;
            $modelDp->code = SiteConfig::model()->formatting('deposite');
        }
        $mDetail = RegistrationDetail::model()->with('Room', 'Room.RoomType')->findAll(array('condition' => 't.registration_id=' . $id));
        if (isset($_POST['Registration'])) {
            if (!empty($_POST['RegistrationDetail']['room_id'])) {
                $reservation_id = $model->reservation_id;
                $model->attributes = $_POST['Registration'];
                $model->guest_user_id = $_POST['id'];
                $model->reservation_id = $reservation_id;
//                $sDate = $_POST['Registration']['date_to'];
//                $date = explode('-', $sDate);
                $sDate = str_replace(" ", "", $_POST['Registration']['date_to']);
                $date = explode('-', $sDate);
                $date1 = explode('/', $date[0]);
                $date1 = $date1[2] . "/" . $date1[1] . "/" . $date1[0];
                $date2 = explode('/', $date[1]);
                $date2 = $date2[2] . "/" . $date2[1] . "/" . $date2[0];
                $start = date("Y/m/d", strtotime($date1));
                $end = date("Y/m/d", strtotime('-1 day', strtotime($date2)));
                $model->date_from = date("Y/m/d", strtotime($date1));
                $model->date_to = date("Y/m/d", strtotime($date2));

                //$user = User::model()->findByPk($model->guest_user_id);
                $user = User::model()->findByPk($_POST['id']);
                $user->scenario = 'notAllow';
                $user->name = (!empty($_POST['name'])) ? $_POST['name'] : '';
                $user->roles_id = (!empty($_POST['group'])) ? $_POST['group'] : '';
                $user->enabled = 1;
                $user->email = (!empty($_POST['email'])) ? $_POST['email'] : '';
                $user->city_id = $_POST['city_guest'];
                $user->address = (!empty($_POST['address'])) ? $_POST['address'] : '';
                $user->phone = (!empty($_POST['phone'])) ? $_POST['phone'] : '';
                $user->code = (!empty($_POST['userNumber'])) ? $_POST['userNumber'] : '';
                $company = (!empty($_POST['company'])) ? $_POST['company'] : '';
                //$other = json_encode(array('company' => $company));
                $user->company = $company;
                $user->birth = (!empty($_POST['birth'])) ? date('Y/m/d', strtotime($_POST['birth'])) : '';
                $user->sex = (!empty($_POST['sex'])) ? $_POST['sex'] : '';
                $user->nationality = (!empty($_POST['nationality'])) ? $_POST['nationality'] : '';
                $user->save();

                if ($model->save()) {
                    //menampung transaksi yang sudah di GL kan di room bill
                    //$gl_bill_old = BillCharge::model()->findAll(array('condition' => 'gl_room_bill_id<>0 AND registration_id=' . $model->id));

                    RegistrationDetail::model()->deleteAll(array('condition' => 'registration_id=' . $model->id));
                    RoomSchedule::model()->deleteAll(array('condition' => 'registration_id=' . $model->id));
                    RoomBill::model()->deleteAll(array('condition' => 'registration_id=' . $model->id));
                    Room::model()->updateAll(array('registration_id' => '0', 'status' => 'vacant', 'status_housekeeping' => 'vacant inspect', 'extrabed' => '0', 'pax' => '0'), 'registration_id=' . $model->id);

                    if ($_POST['Deposite']['amount'] > 0) {
                        $modelDp->attributes = $_POST['Deposite'];
                        if ($modelDp->isNewRecord == true)
                            $modelDp->code = SiteConfig::model()->formatting('deposite', false);
                        $modelDp->guest_user_id = $model->guest_user_id;
                        //$modelDp->guest_user_id = $_POST['id'];

                        $modelDp->used_amount = 0;
                        $modelDp->is_used = 1;
                        $modelDp->used_today = 1;

                        $modelDp->balance_amount = $modelDp->amount - $modelDp->used_amount;
                        if ($modelDp->save()) {
                            $model->deposite_id = $modelDp->id;
                            $model->save();
                        }
                    }


                    for ($i = 0; $i < count($_POST['RegistrationDetail']['room_id']); $i++) {
//update detail
                        $mDet = new RegistrationDetail;
                        $mDet->registration_id = $model->id;
                        $mDet->room_id = $_POST['RegistrationDetail']['room_id'][$i];
                        $mDet->guest_user_names = $_POST['RegistrationDetail']['guest_user_names'][$i];
                        $mDet->charge = $_POST['RegistrationDetail']['charge'][$i];
                        $mDet->extrabed = $_POST['RegistrationDetail']['extrabed'][$i];
                        $mDet->pax = $_POST['RegistrationDetail']['pax'][$i];
                        $mDet->extrabed_price = $_POST['RegistrationDetail']['extrabed_price'][$i];
                        $mDet->room_price = $_POST['RegistrationDetail']['room_price'][$i];
                        $mDet->fnb_price = $_POST['RegistrationDetail']['fnb_price'][$i];
                        $mDet->others_include = '';
                        if (!empty($_POST['others_include'][$mDet->room_id])) {
                            $mDet->others_include = json_encode($_POST['others_include'][$mDet->room_id]);
                        }
                        $mDet->save();

//update guest room
                        $room = Room::model()->findByPk($mDet->room_id);
                        $room->registration_id = $model->id;
                        $room->status = ($model->type == "regular") ? 'occupied' : $model->type;
                        $room->status_housekeeping = ($model->type == "regular") ? 'occupied' : $model->type;
                        $room->extrabed = $_POST['RegistrationDetail']['extrabed'][$i];
                        $room->pax = $_POST['RegistrationDetail']['pax'][$i];
                        $room->save();

                        $startTime = strtotime($date1);
                        $endTime = strtotime("-1 day", strtotime($date2));

// Loop between timestamps, 24 hours at a time
                        $b = 0;
                        $lead_room_bill_id = 0;
                        for ($a = $startTime; $a <= $endTime; $a = $a + 86400) {
//add to room bill
                            $mBill = new RoomBill;
                            $mBill->room_id = $mDet->room_id;
                            $mBill->charge = $mDet->charge;
                            $mBill->room_number = $mDet->Room->number;
                            $mBill->date_bill = date('Y/m/d', $a);
                            $mBill->registration_id = $model->id;
                            $mBill->extrabed = $_POST['RegistrationDetail']['extrabed'][$i];
                            $mBill->pax = $_POST['RegistrationDetail']['pax'][$i];
                            $mBill->extrabed_price = $_POST['RegistrationDetail']['extrabed_price'][$i];
                            $mBill->room_price = $_POST['RegistrationDetail']['room_price'][$i];
                            $mBill->fnb_price = $_POST['RegistrationDetail']['fnb_price'][$i];
                            $mBill->package_room_type_id = $model->package_room_type_id;
                            $mBill->processed = 0;
                            $mBill->lead_room_bill_id = $lead_room_bill_id;
//                            if ($b == 0) {
//                                $mBill->processed = 1; // first day is true, else is false                                
//                            } else {
//                                $mBill->processed = 0;
//                            }
                            $mBill->others_include = $mDet->others_include;
                            $mBill->save();
                            if ($b == 0) { //set lead room bill
                                $lead_room_bill_id = $mBill->id;
                                //gl lama di taruh di bill charge yang baru, karena sudah terdelete sebelumnya terkena proses edit
//                                foreach ($gl_bill_old as $arr) {
//                                    if ($arr->RoomBill->room_id == $mBill->room_id) {
//                                        $arr->gl_room_bill_id = $mBill->id;
//                                        $arr->save();
//                                    }
//                                }
                            }
                            $b++;
//add to schedule
                            $mSchedule = new RoomSchedule;
                            $mSchedule->room_id = $mDet->room_id;
                            $mSchedule->date_schedule = date('Y/m/d', $a);
                            $mSchedule->status = ($model->type == "regular") ? 'occupied' : $model->type;
                            $mSchedule->registration_id = $model->id;
                            $mSchedule->room_bill_id = $mBill->id;
                            if (!$mSchedule->save())
                                throw new CHttpException(404, 'The requested page does not exist.');
                        }
                    }
                    $this->redirect(array('view', 'id' => $model->id));
                }else {
                    Yii::app()->user->setFlash('error', "<b>Guest information</b> cannot be blank");
                }
            }
        }

        $this->render('update', array(
            'model' => $model,
            'mDetail' => $mDetail,
            'modelDp' => $modelDp,
        ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id) {
        if (Yii::app()->request->isPostRequest) {
// we only allow deletion via POST request
            $check = RoomBill::model()->findAll(array('condition' => 'is_na=1 and registration_id=' . $id));
            if (empty($check)) {
                $model = $this->loadModel($id);
                if (!empty($model->deposite_id))
                    $modelDp = Deposite::model()->findByPk($model->deposite_id)->delete();
                $model->delete();
                RegistrationDetail::model()->deleteAll(array('condition' => 'registration_id=' . $id));
                RoomSchedule::model()->deleteAll(array('condition' => 'registration_id=' . $id));
                RoomBill::model()->deleteAll(array('condition' => 'registration_id=' . $id));
                Room::model()->updateAll(array('registration_id' => '0', 'status' => 'vacant inspect', 'status_housekeeping' => 'vacant inspect', 'extrabed' => '0', 'pax' => '0'), 'registration_id=' . $id);

// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
                if (!isset($_GET['ajax']))
                    $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
            }else {
                throw new CHttpException(403, 'Sorry!  Registration data has been NA or guest already checkout.');
            }
        } else
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    }

    /**
     * Lists all models.
     */
    public function actionIndex() {

        $model = new Registration('search');
        $model->unsetAttributes();  // clear any default values

        if (isset($_GET['Registration'])) {
            $model->attributes = $_GET['Registration'];
        }



        $this->render('index', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id) {
        $model = Registration::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'registration-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
