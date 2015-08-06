<?php

class ReservationController extends Controller {

    

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
                'expression' => 'app()->controller->isValidAccess("Reservation","c")'
            ),
            array('allow', // r
                'actions' => array('index', 'view'),
                'expression' => 'app()->controller->isValidAccess("Reservation","r")'
            ),
            array('allow', // u
                'actions' => array('update'),
                'expression' => 'app()->controller->isValidAccess("Reservation","u")'
            ),
            array('allow', // d
                'actions' => array('delete'),
                'expression' => 'app()->controller->isValidAccess("Reservation","d")'
            )
        );
    }

    public function actionCheckRoom() {
        $sDate = str_replace(" ", "", $_POST['Reservation']['date_from']);
        $date = explode('-', $sDate);
        $date1 = explode('/', $date[0]);
        $date1 = $date1[2] . "/" . $date1[1] . "/" . $date1[0];
        $date2 = explode('/', $date[1]);
        $date2 = $date2[2] . "/" . $date2[1] . "/" . $date2[0];
        $start = date("Y/m/d", strtotime($date1));
        $end = date("Y/m/d", strtotime('-1 day', strtotime($date2)));

        if (empty($_POST['Reservation']['id']) or $_POST['Reservation']['id'] == '0') {
            $criteria = '';
        } else {
            $criteria = 'reservation_id != ' . $_POST['Reservation']['id'] . ' and ';
        }

        $warning = '';
        $idNotAva = array();
        $idStatus = array();
        $cek = RoomSchedule::model()->with('Reservation')->findAll(array('condition' => $criteria . ' date_schedule between "' . $start . '" and "' . $end . '"', 'order' => 'date_schedule asc'));
        if (!empty($cek)) {
            foreach ($cek as $data) {
                $statusReservation = isset($data->Reservation->status) ? $data->Reservation->status : '';
                if (!isset($idStatus[$data->room_id]['status']) and $statusReservation != "cancel") {
                    $idNotAva[] = $data->room_id;
                    $idStatus[$data->room_id]['status'] = $data->status;
                    $idStatus[$data->room_id]['date'] = $data->date_schedule;
                    $idStatus[$data->room_id]['statusReservation'] = isset($data->Reservation->status) ? $data->Reservation->status : '';
                }
            }

            for ($i = 0; $i < count($_POST['ReservationDetail']['room_id']); $i++) {
                if (in_array($_POST['ReservationDetail']['room_id'][$i], $idNotAva)) {
                    $warning .= 'Room ' . $_POST['ReservationDetail']['room_id'][$i] . ' has ' . $idStatus[$_POST['ReservationDetail']['room_id'][$i]]['status'] . ' on ' . $idStatus[$_POST['ReservationDetail']['room_id'][$i]]['date'] . ' <br>';
                }
            }
        }

        echo $warning;
    }

    public function actionGetPackage() {
        $id = $_POST['Reservation']['package_room_type_id'];
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
            $rate = array('rate' => $guest[$group]['default'], 'fnb' => $roomPackage->fnb_charge, 'charge' => $guest[$group]['roomRate'], 'rate' => $guest[$group]['default']);
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

    public function actionSelectReservation() {
        $model = Reservation::model()->findByPk($_POST['id']);
        $disabled = ($model->status == 'registered' or $model->status == 'cancel' or (strtotime($model->date_to) < strtotime(date("Y-m-d")))) ? true : false;
        $id = ($model->status != 'registered') ? '<input type="hidden" name="cancelId" value="' . $model->id . '" />' : '';
        $array = ($model->status != 'registered') ? array('reservation' => 'Reservation', 'reserved' => 'Reserved', 'cancel' => 'Cancel', 'noshow' => 'No Show') : array('reservation' => 'Reservation', 'reserved' => 'Reserved', 'registered' => 'Registered', 'cancel' => 'Cancel', 'noshow' => 'No Show');

        echo '<div class="row-fluid">
                    <div class="span3">
                        Status
                    </div>
                    <div class="span1" style="width:10px">:</div>
                    <div class="span8" style="text-align:left"> 
                        
                       ' . $id . CHtml::dropDownList('cancelStatus', $model->status, $array, array('label' => false, 'disabled' => $disabled)) .
        '</div>
                </div>        
                <div class="row-fluid">
                    <div class="span3">
                        Reservation Number
                    </div>
                    <div class="span1" style="width:10px">:</div>
                    <div class="span8" style="text-align:left">'
        . CHtml::textField('cancelReservationNumber', $model->code, array('style' => 'margin-bottom:5px', 'class' => 'span12', 'readOnly' => true)) .
        '</div>
                </div>        
                <div class="row-fluid">
                    <div class="span3">
                        Guest Name
                    </div>
                    <div class="span1" style="width:10px">:</div>
                    <div class="span8" style="text-align:left">'
        . CHtml::textField('cancelGuestName', $model->Guest->name, array('style' => 'margin-bottom:5px', 'class' => 'span12', 'readOnly' => true)) .
        '</div>
                </div>        
                <div class="row-fluid">
                    <div class="span3">
                        Reason if Cancel
                    </div>
                    <div class="span1" style="width:10px">:</div>
                    <div class="span8" style="text-align:left">'
        . Chtml::textArea('cancelReason', $model->reason_of_cancel, array('style' => 'margin-bottom:5px;
                    height:70px', 'class' => 'span12', 'label' => false, 'disabled' => $disabled)) .
        '</div>
                </div>';
    }

    public function actionConfirm($id) {
        $schedule = RoomSchedule::model()->updateAll(array('status' => 'reserved'), 'reservation_id=' . $id);
        $schedule = Reservation::model()->updateAll(array('status' => 'reserved'), 'id=' . $id);
        $this->redirect(array('index'));
    }

    public function actionGetRoom() {
        $sDate = str_replace(" ", "", $_POST['Reservation']['date_from']);
        $type = $_POST['roomType'];
        $floor = $_POST['floor'];
        $bed = $_POST['bed'];
        $package = $_POST['Reservation']['package_room_type_id'];

//        $date = explode('-', $sDate);
//        $start = date("Y/m/d", strtotime($date[0]));
//        $end = date("Y/m/d", strtotime('-1 day', strtotime($date[1])));
        $date = explode('-', $sDate);
        $date1 = explode('/', $date[0]);
        $date1 = $date1[2] . "/" . $date1[1] . "/" . $date1[0];
        $date2 = explode('/', $date[1]);
        $date2 = $date2[2] . "/" . $date2[1] . "/" . $date2[0];
        $start = date("Y/m/d", strtotime($date1));
        $end = date("Y/m/d", strtotime('-1 day', strtotime($date2)));

        $filter = 't.status!="out of order" and t.id not in (select acca_room_schedule.room_id from acca_room_schedule where status<>"vacant" and date_schedule between "' . $start . '" and "' . $end . '")';
        if (!empty($type))
            $filter .= ' and t.room_type_id=' . $type . '';
        if (!empty($floor))
            $filter .= ' and t.floor=' . $floor . '';
        if (!empty($bed))
            $filter .= ' and t.bed="' . strtolower($bed) . '"';

        $roomType = RoomType::model()->findAll(array('condition' => 'is_package=0'));
        foreach ($roomType as $rt) {
            $arrRoomType[$rt->id]['KING'] = 0;
            $arrRoomType[$rt->id]['TWIN'] = 0;
        }

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

            $return .=' <tr class="item" id="' . $value->id . '">
                                <td class="span1" style="text-align:center">' . $value->number . '</td>
                                <td class="span3">' . ucwords($value->RoomType->name) . '</td>
                                <td class="span1" style="text-align:center">' . $value->floor . '</td>
                               <td class="span2">' . ucwords($value->bed) . '</td>
                                <td class="span3">' . $price .
                    '</td>
                                <td style="width:30px"><a class="btn btn-small btn-add" taro="' . $value->id . '" title="Add" rel="tooltip" "><i class="icon-plus"></i></a></td>
                            </tr>';
        }
        $return .='<tr id="addRow" style="display:none">
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
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

        echo json_encode($dataq);
    }

    public function cssJs() {
        return '          
                $(".btn-add").click(function(){
                    var tambahan = "&id=" + $(this).attr("taro") + "&package=" + $("#Reservation_package_room_type_id").val() + "&usertype=" + $("#roles").val();
                    var postData = $("#reservation-form").serialize()+ tambahan;
                    $.ajax({
                        url:"' . url("reservation/addRoom") . '",
                        data:postData,
                        type:"post",
                        success:function(data){
                            obj = JSON.parse(data);                            
                            $("#"+obj.id).remove();                              
                            if($("#"+obj.id).length )
                            {
                                totalRoom(); 
                            }else{
                               $("#selected").replaceWith(obj.row); 
                               totalRoom(); 
                            }
                             calculation();
                        }
                    });
                });

                function removeSelect(id){                                                                
                   $("#"+id).parent().remove();
                   totalRoom(); 
                };
                                                            
                ';
    }

    public function actionAddRoom() {
        $usertype = $_POST['usertype'];
        $package = $_POST['package'];
        $type = $_POST['Reservation']['type'];
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
        $row .= '<input type="hidden" value="' . $id . '" name="ReservationDetail[room_id][]" class="room_id"/>';
        $row .= 'No : ' . $room->number . '<br>';
        $row .= 'Type : ' . $room->RoomType->name . ' - ' . ucfirst(strtolower($room->bed)) . '<br>';
        $row .= 'Floor : ' . $room->floor . '<br>';
        $row .= "</td>";
        $row .= "<td>";
        $row .= '<textarea style="width:150px" rows="3" name="ReservationDetail[guest_user_names][]"></textarea>';
        $row .= "</td>";
        $row .= "<td>";
        $row .= '<input style="width:30px" onChange="calculation()" class="pax" type="text" value="' . $pax . '" name="ReservationDetail[pax][]" />';
        $row .= "</td>";
        $row .= "<td>";
        $row .= "<input type='hidden' name='ori_fnb_price' class='ori_fnb_price' value='" . $fnb_price . "' />";
        $row .= '<div class="input-prepend"><span class="add-on">Rp</span><input style="width:70px" onChange="calculation()" readOnly class="edit_price fnb_price" type="text" value="' . $fnb_price . '" name="ReservationDetail[fnb_price][]" /></div>';
        $row .= "</td>";
        $row .= "<td>";
        $row .= '<input style="width:30px" onChange="calculation()" type="text" class="extrabed" value="0" name="ReservationDetail[extrabed][]" />';
        $row .= "</td>";
        $row .= "<td>";
        $row .= '<div class="input-prepend"><span class="add-on">Rp</span><input style="width:70px" onChange="calculation()" readOnly class="edit_price extrabed_price" type="text" value="' . $exbed . '" name="ReservationDetail[extrabed_price][]" /></div>';
        $row .= "</td>";
        $row .= "<td>";
        $row .= $checkbox_others_include_sys;
        $row .= '<span class="pckg">' . $checkbox_others_include . '</span></td>';
        $row .= "<td>";
        $row .= "<input type='hidden' name='ori_rate' class='ori_rate' value='" . $rate[$usertype]['roomRate'] . "' />";
        $row .= '<div class="input-prepend"><span class="add-on">Rp</span><input style="width:70px" onChange="calculation()" class="room_rate edit_price" readOnly  type="text" value="' . $rate[$usertype]['roomRate'] . '" name="ReservationDetail[room_price][]" /></div>';
        $row .= "</td>";
        $row .= "<td>";
        $row .= '<div class="input-prepend"><span class="add-on">Rp</span><input style="width:70px" onChange="calculation()" type="text" class="total_rate" value="" name="ReservationDetail[charge][]" readonly /></div>';
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

    public function js() {
        Cs()->registerScript('', $this->cssJs());
        Cs()->registerScript('', '
            $(".addUser").on("click", function() {
                var add = $("#addUser").val();
                addUser(add);
            });
            $(".removeSelect").on("click", function() {
               $(this).parent().parent().remove();
                $(\'button[type="submit"]\').removeAttr(\'disabled\');
            });    
            

             $(".others_include").click(function(event) {  //on click                     
                    calculation();
                });
                
             $(".additional").change(function(event) {  //on change                                     
                calculation();
             });
             $(".editOtherInclude").change(function(event) {  //on change                                     
                $("."+$(this).attr("kode")).val(this.value); 
                calculation();
             });
                
             $("#all_others").click(function(event) {  //on click 
                if(this.checked) { // check select status
                    $(".others_include").prop("checked", true);
                }else{
                    $(".others_include").prop("checked", false);
                }
                calculation();
            });

            
            $("#myTab a").click(function(e) {
                    e.preventDefault();
                    $(this).tab("show");
                });                
            function addUser(add){                
                if (add=="1"){      
                    $("#addUser").val(0);
                    $("#guestInfo").show();                    
                }else{
                    $("#guestInfo").hide();
                    $("#addUser").val(1);                 
                    $("#sex").val("");
                    $("#birth").val("");
                    $("#company").val("");
                    $("#nationality").val("");
                    $("#group").val("");
                    $("#name").val("");
                    $("#province_guest").val(0);
                    $("#city_guest").val(0);
                    $("#address").val("");
                    $("#phone").val("");                    
                    $("#userNumber").val("");                    
                }
                
            }
        ');
    }

//    public function actionView($id) {
//        $this->layout = "mainWide";
//        $model = $this->loadModel($id);
//        $mDetail = ReservationDetail::model()->findAll(array('condition' => 'reservation_id=' . $id));
//        $myDetail = ReservationDetail::model()->findByAttributes(array('reservation_id' => $id));
//        $modelDp = Deposite::model()->findByPk($model->deposite_id);
//        if (empty($modelDp))
//            $modelDp = new Deposite();
//        $this->render('view', array(
//            'model' => $model,
//            'mDetail' => $mDetail,
//            'myDetail' => $myDetail,
//            'modelDp' => $modelDp,
//        ));
//    }
    
    public function actionView($id) {
        cs()->registerScript('read', '
            $("form input, form textarea, form select").each(function(){
                $(this).prop("disabled", true);
            });');
        $_GET['v'] = true;
        $this->actionUpdate($id);
    }

    public function actionCreate() {
        $siteConfig = SiteConfig::model()->findByPk(1);
        $settings = json_decode($siteConfig->settings, true);
        $model = new Reservation;
        $modelDp = new Deposite;
        $modelDp->code = SiteConfig::model()->formatting('deposite');
        $this->js();
        $this->layout = "mainWide";
        if (isset($_POST['Reservation'])) {
            if (!empty($_POST['ReservationDetail']['room_id'])) {
                //$model->guest_user_id = 0;
                $model->attributes = $_POST['Reservation'];
                $model->guest_user_id = $_POST['id'];
                $model->code = SiteConfig::model()->formatting('reservation', FALSE);
                if (empty($_POST['id'])) { // new guest
                    $user = new User;
                    $user->scenario = 'notAllow';
                    $user->username = '';
                    $user->password = '';
                    $user->name = (!empty($_POST['name'])) ? $_POST['name'] : '';
                    $user->roles_id = (!empty($_POST['group'])) ? $_POST['group'] : '';
                    $user->enabled = 1;
                    $user->city_id = $_POST['city_guest'];
                    $user->address = (!empty($_POST['address'])) ? $_POST['address'] : '';
                    $user->phone = (!empty($_POST['phone'])) ? $_POST['phone'] : '';
                    $user->email = (!empty($_POST['email'])) ? $_POST['email'] : '';
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
                    $user->city_id = $_POST['city_guest'];
                    $user->address = (!empty($_POST['address'])) ? $_POST['address'] : '';
                    $user->phone = (!empty($_POST['phone'])) ? $_POST['phone'] : '';
                    $user->email = (!empty($_POST['email'])) ? $_POST['email'] : '';
                    $user->code = (!empty($_POST['userNumber'])) ? $_POST['userNumber'] : '';
                    $company = (!empty($_POST['company'])) ? $_POST['company'] : '';
                    //$other = json_encode(array('company' => $company));
                    $user->company = $company;
                    $user->birth = (!empty($_POST['birth'])) ? date('Y/m/d', strtotime($_POST['birth'])) : '';
                    $user->sex = (!empty($_POST['sex'])) ? $_POST['sex'] : '';
                    $user->nationality = (!empty($_POST['nationality'])) ? $_POST['nationality'] : '';
                    $user->save();
                }

//                $sDate = $_POST['Reservation']['date_from'];
//                $date = explode('-', $sDate);
                $sDate = str_replace(" ", "", $_POST['Reservation']['date_from']);
                $date = explode('-', $sDate);
                $date1 = explode('/', $date[0]);
                $date1 = $date1[2] . "/" . $date1[1] . "/" . $date1[0];
                $date2 = explode('/', $date[1]);
                $date2 = $date2[2] . "/" . $date2[1] . "/" . $date2[0];
                $start = date("Y/m/d", strtotime($date1));
                $end = date("Y/m/d", strtotime('-1 day', strtotime($date2)));
                $model->date_from = date("Y/m/d", strtotime($date1));
                $model->date_to = date("Y/m/d", strtotime($date2));
                $model->status = 'reservation';

                if ($model->guest_user_id != 0 and !empty($model->guest_user_id)) {
                    if ($model->save()) {

                        if ($_POST['Deposite']['amount'] > 0) {
                            $modelDp->attributes = $_POST['Deposite'];
                            $modelDp->code = SiteConfig::model()->formatting('deposite', false);
                            $modelDp->guest_user_id = $model->guest_user_id;
                            $modelDp->used_amount = 0;
                            $modelDp->is_used = 0;
                            $modelDp->used_today = 0;
                            $modelDp->balance_amount = $modelDp->amount - $modelDp->used_amount;
                            if ($modelDp->save()) {
                                $model->deposite_id = $modelDp->id;
                                $model->save();
                            }
                        }

                        for ($i = 0; $i < count($_POST['ReservationDetail']['room_id']); $i++) {
                            //update detail
                            $mDet = new ReservationDetail;
                            $mDet->reservation_id = $model->id;
                            $mDet->room_id = $_POST['ReservationDetail']['room_id'][$i];
                            $mDet->extrabed = $_POST['ReservationDetail']['extrabed'][$i];
                            $mDet->extrabed_price = $_POST['ReservationDetail']['extrabed_price'][$i];
                            $mDet->pax = $_POST['ReservationDetail']['pax'][$i];
                            $mDet->room_price = $_POST['ReservationDetail']['room_price'][$i];
                            $mDet->fnb_price = $_POST['ReservationDetail']['fnb_price'][$i];
                            $mDet->charge = $_POST['ReservationDetail']['charge'][$i];

                            if (!empty($_POST['others_include'][$mDet->room_id])) {
                                $mDet->others_include = json_encode($_POST['others_include'][$mDet->room_id]);
                            }
                            $mDet->guest_user_names = $_POST['ReservationDetail']['guest_user_names'][$i];
                            $mDet->save();

//                        update schedule
                            $startTime = strtotime($date1);
                            $endTime = strtotime("-1 day", strtotime($date2));

                            // Loop between timestamps, 24 hours at a time
                            for ($t = $startTime; $t <= $endTime; $t = $t + 86400) {
                                $mSchedule = new RoomSchedule;
                                $mSchedule->room_id = $mDet->room_id;
                                $mSchedule->date_schedule = date('Y/m/d', $t);
                                $mSchedule->status = "reservation";
                                $mSchedule->reservation_id = $model->id;
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
        $model->code = SiteConfig::model()->formatting('reservation');
        $this->render('create', array(
            'model' => $model,
            'modelDp' => $modelDp,
        ));
    }

    public function actionUpdate($id) {
        $siteConfig = SiteConfig::model()->findByPk(1);
        $settings = json_decode($siteConfig->settings, true);
        $this->layout = "mainWide";
        $model = $this->loadModel($id)->with('Guest', 'Guest.City', 'Guest.City.province', 'Bill', 'Bill.Roles');
        $mDetail = ReservationDetail::model()->with('Room', 'Room.RoomType')->findAll(array('condition' => 'reservation_id=' . $id));
        $this->js();

        if (!empty($model->deposite_id)) {
            $modelDp = Deposite::model()->findByPk($model->deposite_id);
        } else {
            $modelDp = new Deposite;
            $modelDp->code = SiteConfig::model()->formatting('deposite');
        }

        if (isset($_POST['cancel'])) {
            $model->attributes = $_POST['Reservation'];
            $status = ($model->status == 'cancel' || $model->status == 'noshow') ? 'vacant' : $model->status;

            if ($status == 'vacant') {
                RoomSchedule::model()->deleteAll(array('condition' => 'reservation_id=' . $model->id));
            } else {
                RoomSchedule::model()->updateAll(array('status' => $status), 'reservation_id=' . $model->id);
            }

            $model->save();
            $this->redirect(array('index'));
        }



        if (isset($_POST['Reservation'])) {
            if (!empty($_POST['ReservationDetail']['room_id'])) {

                $model->attributes = $_POST['Reservation'];
                $model->guest_user_id = $_POST['id'];
//                $sDate = $_POST['Reservation']['date_from'];
//                $date = explode('-', $sDate);
                $sDate = str_replace(" ", "", $_POST['Reservation']['date_from']);
                $date = explode('-', $sDate);
                $date1 = explode('/', $date[0]);
                $date1 = $date1[2] . "/" . $date1[1] . "/" . $date1[0];
                $date2 = explode('/', $date[1]);
                $date2 = $date2[2] . "/" . $date2[1] . "/" . $date2[0];
                $start = date("Y/m/d", strtotime($date1));
                $end = date("Y/m/d", strtotime('-1 day', strtotime($date2)));
                $model->date_from = date("Y/m/d", strtotime($date1));
                $model->date_to = date("Y/m/d", strtotime($date2));


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

                //-----------------------------

                if ($model->save()) {
                    ReservationDetail::model()->deleteAll(array('condition' => 'reservation_id=' . $model->id));
                    RoomSchedule::model()->deleteAll(array('condition' => 'reservation_id=' . $model->id));
                    if ($_POST['Deposite']['amount'] > 0) {
                        $modelDp->attributes = $_POST['Deposite'];
                        if ($modelDp->isNewRecord == true)
                            $modelDp->code = SiteConfig::model()->formatting('deposite', false);
                        $modelDp->guest_user_id = $model->guest_user_id;
                        $modelDp->used_amount = 0;
                        $modelDp->is_used = 0;
                        $modelDp->used_today = 0;
                        $modelDp->balance_amount = $modelDp->amount - $modelDp->used_amount;
                        if ($modelDp->save()) {
                            $model->deposite_id = $modelDp->id;
                            $model->save();
                        }
                    }

                    for ($i = 0; $i < count($_POST['ReservationDetail']['room_id']); $i++) {
                        //update detail
                        $mDet = new ReservationDetail;
                        $mDet->reservation_id = $model->id;
                        $mDet->room_id = $_POST['ReservationDetail']['room_id'][$i];
                        $mDet->extrabed = $_POST['ReservationDetail']['extrabed'][$i];
                        $mDet->extrabed_price = $_POST['ReservationDetail']['extrabed_price'][$i];
                        $mDet->pax = $_POST['ReservationDetail']['pax'][$i];
                        $mDet->room_price = $_POST['ReservationDetail']['room_price'][$i];
                        $mDet->fnb_price = $_POST['ReservationDetail']['fnb_price'][$i];
                        $mDet->charge = $_POST['ReservationDetail']['charge'][$i];
                        $mDet->others_include = '';
                        if (!empty($_POST['others_include'][$mDet->room_id])) {
                            $mDet->others_include = json_encode($_POST['others_include'][$mDet->room_id]);
                        }
                        $mDet->guest_user_names = $_POST['ReservationDetail']['guest_user_names'][$i];
                        $mDet->save();

//                        update schedule
                        $startTime = strtotime($date1);
                        $endTime = strtotime("-1 day", strtotime($date2));

                        // Loop between timestamps, 24 hours at a time
                        for ($t = $startTime; $t <= $endTime; $t = $t + 86400) {
                            $mSchedule = new RoomSchedule;
                            $mSchedule->room_id = $mDet->room_id;
                            $mSchedule->date_schedule = date('Y/m/d', $t);
                            $mSchedule->status = "reservation";
                            $mSchedule->reservation_id = $model->id;
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
            $model = $this->loadModel($id);
            if (!empty($model->deposite_id)) {
                $modelDp = Deposite::model()->findByPk($model->deposite_id);
                if (!empty($modelDp)) {
                    if ($modelDp->is_used == 0)
                        $modelDp->delete();
                }
            }
            $model->delete();
            ReservationDetail::model()->deleteAll('reservation_id=' . $id);
            RoomSchedule::model()->deleteAll('reservation_id=' . $id);

            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if (!isset($_GET['ajax']))
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
        } else
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    }

    /**
     * Lists all models.
     */
    public function actionIndex() {

        $model = new Reservation('search');
        $model->unsetAttributes();  // clear any default values

        if (isset($_POST['cancel'])) {
            if (!empty($_POST['cancelId'])) {
                $cancel = Reservation::model()->findByPk($_POST['cancelId']);
                $cancel->status = $_POST['cancelStatus'];
                if ($_POST['cancelStatus'] == 'cancel') {
                    $cancel->reason_of_cancel = $_POST['cancelReason'];
                } else {
                    $cancel->reason_of_cancel = '';
                }
                $cancel->save();

                //update schedule
                $status = ($cancel->status == 'cancel' || $cancel->status == 'noshow') ? 'vacant' : $cancel->status;
                RoomSchedule::model()->updateAll(array('status' => $status), 'reservation_id=' . $cancel->id);
            }
            $this->redirect(array('index'));
        }


        if (isset($_GET['Reservation'])) {
            $model->attributes = $_GET['Reservation'];
            $model->date_from = (!empty($model->date_from)) ? date('Y-m-d', strtotime($model->date_from)) : '';
            $model->date_to = (!empty($model->date_to)) ? date('Y-m-d', strtotime($model->date_to)) : '';
        }

        $this->render('index', array(
            'model' => $model,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new Reservation('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Reservation']))
            $model->attributes = $_GET['Reservation'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id) {
        $model = Reservation::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'reservation-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
