<?php

class RoomBillController extends Controller {

    

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
                'actions' => array('create', 'move', 'extend'),
                'expression' => 'app()->controller->isValidAccess("RoomBill","c")'
            ),
            array('allow', // r
                'actions' => array('index', 'view', 'move', 'extend'),
                'expression' => 'app()->controller->isValidAccess("RoomBill","r")'
            ),
            array('allow', // r
                'actions' => array('index', 'view', 'taxExport'),
                'expression' => 'app()->controller->isValidAccess("TaxExport","r")'
            ),
            array('allow', // u
                'actions' => array('update',),
                'expression' => 'app()->controller->isValidAccess("RoomBill","u")'
            ),
            array('allow', // d
                'actions' => array('delete'),
                'expression' => 'app()->controller->isValidAccess("RoomBill","d")'
            )
        );
    }

//    public function actionMigrasi() {
//        $model = RoomBill::model()->findAll(array('condition' => 'lead_room_bill_id IS NULL'));
//        foreach ($model as $m) {
//            $min = RoomBill::model()->find(array('condition' => 'lead_room_bill_id =0 AND registration_id=' . $m->registration_id . ' AND room_id=' . $m->room_id . ''));
//            $m->lead_room_bill_id = $min->id;
//            $m->save();
//        }
//    }

    public function getBillingChart($id) {
        $model = Room::model()->findByPk($id);
        $roomNumber = $model->number;
        $guestName = $model->Registration->Guest->name;
        $dateCheckIn = date("l Y-m-d h:i:s", strtotime($model->Registration->created));

        $roomBills = RoomBill::model()->findAll(array('condition' => 'room_id=' . $id . ' and is_checkedout=0 and processed=1'));
        $return['detail'] = '';
        $total = 0;
        foreach ($roomBills as $roomBill) {
            echo '<tr class="items">
                        <td style="text-align:center"><a href="#" id="addItem"><i class="brocco-icon-forward"></i></a></td>
                        <td>Room Charge</td>
                        <td style="text-align:center">' . $model->number . '</td>
                        <td>' . date("l Y-m-d H:i:s", strtotime($roomBill->date_bill)) . '</td>                                                        
                        <td style="text-align:center">1</td>                        
                        <td style="text-align:right">' . landa()->rp($roomBill->charge) . '</td>                                                        
                        <td style="text-align:right">' . landa()->rp($roomBill->charge) . '</td>                                                        
                    </tr>';
            $total+= $roomBill->charge;
            $additionBills = RoomBillDet::model()->findAll(array('condition' => 'room_bill_id=' . $roomBill->id));
            foreach ($additionBills as $additionBill) {
                echo '<tr class="items">
                        <td style="text-align:center"><a href="#" id="addItem"><i class="brocco-icon-forward"></i></a></td>
                        <td> &nbsp;&nbsp;&raquo; ' . $additionBill->Additional->name . '</td>
                        <td style="text-align:center">' . $model->number . '</td>
                        <td> &nbsp;&nbsp;&raquo; ' . date("H:i:s", strtotime($additionBill->created)) . '</td>                                                        
                        <td style="text-align:center">' . $additionBill->amount . '</td>                                                        
                        <td style="text-align:right">' . landa()->rp($additionBill->charge) . '</td>                                                        
                        <td style="text-align:right">' . landa()->rp($additionBill->charge) . '</td>                                                        
                    </tr>';
                $total+= $additionBill->charge;
            }
        }

        $grandTotal = $total;
    }

    public function actionGetBilling() {
        if (!empty($_POST['regID'])) {
            $id = $_POST['regID'];
            $model = Room::model()->findByPk($id);
            $return['number'] = $model->number;
            $return['guest_id'] = $model->Registration->guest_user_id;
            $return['name'] = $model->Registration->Guest->name;

            if ($model->Registration->billing_user_id != 0) {
                $return['billTo'] = $model->Registration->billing_user_id;
                $return['billToName'] = '<span>' . $model->Registration->Bill->fullName . '</span>' . '<div><b></b></div>';
            } else {
                $return['billTo'] = "";
                $return['billToName'] = '<span>Please Choose</span>' . '<div><b></b></div>';
            }

//            $return['date_to'] = date("l Y-m-d h:i:s", strtotime($model->Registration->created));
            $return['date_to'] = date("l d-F-Y h:i:s");
            $roomBills = RoomBill::model()->findAll(array('condition' => 'room_id=' . $id . ' and is_checkedout=0 and processed=1'));
            $return['detail'] = '';
            $total = 0;
            foreach ($roomBills as $roomBill) {
                $return['idRoomBill'] = $roomBill->id;
            }
            echo json_encode($return);
        }
    }

    public function actionGetRegistration() {
        if (!empty($_POST['regID'])) {
            $siteConfig = SiteConfig::model()->findByPk(1);
            $id = $_POST['regID'];
            $model = Room::model()->findByPk($id);
            $return['number'] = $model->number;
            $return['guest_id'] = $model->Registration->guest_user_id;
            $return['name'] = $model->Registration->Guest->name;
            $return['customerType'] = $model->Registration->Guest->Roles->name;
            $return['roomType'] = $model->RoomType->name;
            $return['roomBed'] = $model->bed;
            $return['roomFloor'] = $model->floor;
            $return['type'] = ucwords($model->Registration->type);

            $return['date_to'] = date("Y-m-d", strtotime($model->Registration->created));
            $return['reg_code'] = $model->Registration->code;

            $roomBill = RoomBill::model()->find(array('order' => 'date_bill desc', 'condition' => 'registration_id =' . $model->registration_id . ' and room_id=' . $id . '  and is_checkedout=0'));
            $roomBillCurrent = RoomBill::model()->find(array('condition' => 'date_bill="' . $siteConfig->date_system . '" AND registration_id =' . $model->registration_id . ' and room_id=' . $id . ' and is_checkedout=0'));

            if (empty($roomBillCurrent)) {
                echo "extend"; //jika tidak ada room bill hari ini, berarti harus di suruh extend dulu
            } else {
                $return['check_out'] = date("Y-m-d", strtotime('+1 day', strtotime($roomBill->date_bill)));
                $return['date_check_out'] = date("Y-m-d", strtotime('+1 day', strtotime($roomBill->date_bill)));
                $return['pax'] = $roomBillCurrent->pax;
                $return['extrabed'] = $roomBillCurrent->extrabed;
                $return['extrabed_price'] = $roomBillCurrent->extrabed_price;
                $return['fnb_price'] = $roomBillCurrent->fnb_price;
                $return['room_price'] = $roomBillCurrent->room_price;
                $return['charge'] = $roomBillCurrent->charge;
                echo json_encode($return);
            }
        }
    }

    public function actionGetRegister() {
        $data = '';
        if (!empty($_POST['regID'])) {
            $roombill = RoomBill::model()->findAll(array(
                'condition' => 'registration_id IN (' . implode(',', $_POST['regID']) . ') AND lead_room_bill_id=0 AND is_checkedout=0',
                'order' => 'date_bill DESC',
                    )
            );
        } elseif (!empty($_POST['roomID'])) {
            $roombill = RoomBill::model()->findAll(array(
                'condition' => 'room_id IN (' . implode(',', $_POST['roomID']) . ') AND lead_room_bill_id=0 AND is_checkedout=0',
                'order' => 'date_bill DESC',
                    )
            );
        }


        foreach ($roombill as $bill) {
            $checkout = RoomBill::model()->find(array(
                'condition' => 'room_id=' . $bill->room_id,
                'order' => 'date_bill DESC',
                'limit' => '1'
            ));
            $data .= '
                    <tr class="items">
                        <td><i class="icomoon-icon-arrow-right-7"></i>
                            <input type="hidden" id="regId" name="id[]" value="' . $bill->registration_id . '" />
                                <input type="hidden" id="roomNumber" name="roomId[]" value="' . $bill->room_number . '" />
                            <input type="hidden" id="dateIn" name="dateIn[]" value="' . $bill->Registration->date_from . '" />
                            <input type="hidden" id="dateOut" class="dateOut" name="dateOut[]" value="' . date('m/d/Y', strtotime($checkout->date_bill) + 86400) . '" />
                        </td>
                        <td>' . $bill->registration_id . '</td>
                        <td>' . $bill->room_number . '</td>
                        <td>' . $bill->Registration->Guest->name . '</td>
                        <td>' . date('d F Y', strtotime($bill->Registration->date_from)) . '</td>
                        <td>' . date('d F Y', strtotime($checkout->date_bill) + 86400) . '</td>
                        <td class="ext"></td>
                    </tr>  
                    ';
        }
        echo $data;
    }

    public function actionGetAdditional() {
        if (is_numeric($_POST['addID'])) {
            $id = $_POST['addID'];
            $number = (!empty($_POST['roomNumber'])) ? $_POST['roomNumber'] : '-';
            $model = ChargeAdditional::model()->findByPk($id);
            $return['id'] = $model->id;
            $return['name'] = $model->name;
            $return['charge'] = $model->charge;
            $return['discount'] = $model->discount;
            $return['date'] = date("l Y-m-d H:i:s");
            $return['number'] = $number;

            echo json_encode($return);
        }
    }

    public function actionAddByRegister() {
        if (is_numeric($_POST['addID'])) {
            $id = $_POST['addID'];
            $number = (!empty($_POST['roomNumber'])) ? $_POST['roomNumber'] : '-';
            $model = ChargeAdditional::model()->findByPk($id);
            $return['id'] = $model->id;
            $return['name'] = $model->name;
            $return['charge'] = $model->charge;
            $return['discount'] = $model->discount;
            $return['date'] = date("l Y-m-d H:i:s");
            $return['number'] = $number;

            echo json_encode($return);
        }
    }

    public function actionAddRow() {
        $model = ChargeAdditional::model()->findByPk((int) $_POST['additional_id']);
        if (count($model) > 0) {
            if (!empty($_POST['idRoomBill'])) {
                echo '                                                  
                    <tr class="items">
                        <input type="hidden" name="ChargeAdditional[id][]" id="' . $model->id . '" value="' . $model->id . '"/>                        
                        <input type="hidden" name="ChargeAdditional[amount][]" id="detQty" value="' . $_POST['amount'] . '"/>
                        <input type="hidden" name="ChargeAdditional[total][]" id="detTotal" class="detTotal" value="' . $model->charge * $_POST['amount'] . '"/>                                                    
                        <input type="hidden" name="ChargeAdditional[charge][]" id="detCharge"  value="' . $model->charge . '"/>                                                    
                            
                        <td style="text-align:center"><i class="delRow icon-remove-circle" style="cursor:all-scroll;"></i></td>
                        <td> &nbsp;&nbsp;&raquo; ' . $model->name . '</td>
                        <td style="text-align:center">' . $_POST['roomNumber'] . '</td>' .
                //<td> &nbsp;&nbsp;&raquo; ' .// date('H:i:s') . '</td>                                                        
                '<td style="text-align:center">' . $_POST['amount'] . '</td>                        
                        <td style="text-align:right">' . landa()->rp($model->charge) . '</td>                                                        
                        <td style="text-align:right">' . landa()->rp($model->charge * $_POST['amount']) . '</td>                                                        
                    </tr>
                     <tr id="addRow" style="display:none">
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>                       
                    ';
            } else {
                echo '<tr id="addRow" style="display:none">
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>  ';
            }
            echo $_POST['amount'];
        }
    }

    public function cssJs() {
//        cs()->registerScript('', '
//                    ');
    }

//    public function actionCharge() {
//        $this->cssJs();
//        $model = new RoomBill;
////        $filter = 't.id in (select acca_room_schedule.room_id from acca_room_schedule where status="occupied" and date_schedule = CURRENT_DATE() GROUP BY acca_room_schedule.room_id)';
//        $filter = 't.status="occupied" or t.status="compliment" or t.status="house use"';
//
//        $room = Room::model()->
//                findAll(array('condition' => $filter));
//
//        if (!empty($_POST['idRoomBill'])) {
//            for ($i = 0; $i < count($_POST['ChargeAdditional']['id']); $i++) {
//                $mInDet = new RoomBillDet;
//                $mInDet->room_bill_id = $_POST['idRoomBill'];
//                $mInDet->charge_additional_id = $_POST['ChargeAdditional']['id'][$i];
//                $mInDet->amount = $_POST['ChargeAdditional']['amount'][$i];
//                $mInDet->charge = $_POST['ChargeAdditional']['charge'][$i];
//                $mInDet->save();
//            }
//            Yii::app()->user->setFlash('success', '<strong>Welldone! </strong> Transaction success added.');
//            $this->redirect(url('roomBill/charge'));
//        }
//        $this->render('charge', array(
//            'model' => $model,
//            'room' => $room,
//        ));
//    }

    public function actionExtend() {
        $this->cssJs();
        $model = new RoomBill;
        $filter = 't.status="occupied" or t.status="compliment" or t.status="house use"';

        $room = Room::model()->findAll(array('condition' => $filter));
        $number = isset($_GET['roomNumber']) ? $_GET['roomNumber'] : '';


        if (isset($_POST['yt0'])) {
//            $roomBill = RoomBill::model()->find(array('condition' => 'registration_id IN (' . implode(',', $_POST['registration_id']) . ') and is_checkedout=0'));
            foreach ($_POST['roomId'] as $val => $id) {
                if (isset($_POST['dateOut'][$val])) {
                    $curentDate = date('m/d/Y', strtotime($_POST['dateOut'][$val]));
                    $extendDate = date('m/d/Y', strtotime('-1 day', strtotime($_POST['extend'])));
                    $start = date("Y-m-d", strtotime($curentDate));
                    $end = date("Y-m-d", strtotime($extendDate));

                    //pengecekan jika bentrok dengan room schedule (Reservasi)
                    $filter = 't.room_id =' . $_POST['roomId'][$val] . ' and t.date_schedule between ("' . $start . '" and "' . $end . '") and t.status <>"vacant"';
                    $data = RoomSchedule::model()->findAll(array('condition' => $filter)); 
                    if (empty($data)) {
                                        } else {
                        foreach ($data as $o) {
                            if (!empty($o->reservation_id)) {
                                $id = $o->reservation_id;
                            }
                        }
                        Yii::app()->user->setFlash('error', '<strong>Sorry! </strong> This room is reserved in arange date. Please edit room in this reservation first.  <a target="_blank" href="' . url('reservation/update/' . $id) . '">Edit Reservation</a> ');
                    }

                    $roomBill = RoomBill::model()->find(array(
                        'condition' => 'room_id=' . $id . ' and is_checkedout=0'
                    ));
                    $lead = RoomBill::model()->find(array(
                        'condition' => 'lead_room_bill_id=0 AND room_id=' . $id . ' and is_checkedout=0'
                    ));
                    $attributes = $roomBill->getAttributes();
                    if ($extendDate >= $curentDate) {
                        while (strtotime($curentDate) <= strtotime($extendDate)) {
                            $newRoomBill = new RoomBill;
                            $newRoomBill->attributes = $attributes;
                            unset($newRoomBill->id);
                            $newRoomBill->date_bill = date('Y-m-d', strtotime($curentDate));
                            $newRoomBill->registration_id = $roomBill->registration_id;
                            $newRoomBill->room_number = $_POST['roomId'][$val];
                            $newRoomBill->room_id = $roomBill->room_id;
                            $newRoomBill->moved_room_bill_id = $roomBill->moved_room_bill_id;
                            $newRoomBill->processed = 0;
                            $newRoomBill->is_checkedout = 0;
                            $newRoomBill->is_na = 0;
                            $newRoomBill->na_id = NULL;
                            $newRoomBill->lead_room_bill_id = $lead->id;
                            $newRoomBill->save();

                            $newSchedule = new RoomSchedule;
                            $newSchedule->room_bill_id = $newRoomBill->id;
                            $newSchedule->room_id = $roomBill->room_id;
                            $newSchedule->date_schedule = date('Y-m-d', strtotime($curentDate));
                            $newSchedule->status = "occupied";
                            $newSchedule->registration_id = $roomBill->registration_id;
                            $newSchedule->save();
                            $curentDate = date('m/d/Y', strtotime("+1 day", strtotime($curentDate)));
                        }
                        Yii::app()->user->setFlash('success', '<strong>Success! </strong> Room has been extended.');
//                    $this->redirect(url('roomBill/charge'));
                    } else {
                        Yii::app()->user->setFlash('error', '<strong>Ups! </strong> Date extend must more than current checkout date.');
                    }
                }
            }
        }

        $this->render('extend', array(
            'model' => $model,
            'room' => $room,
            'number' => $number,
        ));
    }

    public function actionMove() {
        $this->cssJs();
        $model = new RoomBill;
        $number = isset($_GET['roomNumber']) ? $_GET['roomNumber'] : '';

        if (!empty($_POST['roomId'])) {
            if (!empty($_POST['room_selected'])) {
                $roomOld = Room::model()->findByPk($_POST['roomId']);
                $roomNew = Room::model()->findByPk($_POST['room_selected']);

                //tranver detail old room to new room                
                $roomNew->registration_id = $roomOld->registration_id;
                $roomNew->pax = $roomOld->pax;
                $roomNew->status = $roomOld->status;
                $roomNew->status_housekeeping = $roomOld->status_housekeeping;
                $roomNew->save();
                $roomOld->registration_id = 0;
                $roomOld->pax = 0;
                $roomOld->status = 'dirty';
                $roomOld->status_housekeeping = 'dirty';
                $roomOld->save();

                $date_move = $_POST['date_move'];
                $date_check_out = $_POST['date_check_out'];
                $room_id = $_POST['roomId'];
                $price = $_POST['price'];
                $fb = $_POST['fb'];
                $eb = $_POST['eb'];
                $other = (isset($_POST['others_include'])) ? json_encode($_POST['others_include']) : '';
                $room_selected = $_POST['room_selected'];

                $start = date("Y/m/d", strtotime($date_move));
                $end = date("Y/m/d", strtotime($date_check_out));

                //find moved room id 
                $moved_room_bill_id = array();
                $not_moved = RoomBill::model()->findAll(array('condition' => 'registration_id =' . $roomNew->registration_id . '  and room_id=' . $room_id . ' and is_na=1'));
                foreach ($not_moved as $value) {
                    $value->is_checkedout = 1;
                    $value->save();
                }

                //add detail to registration
                $detailReg = RegistrationDetail::model()->findByAttributes(array('registration_id' => $roomNew->registration_id, 'room_id' => $_POST['roomId']));
                $regDetail = RegistrationDetail::model()->findByAttributes(array('registration_id' => $roomNew->registration_id, 'room_id' => $_POST['room_selected']));
                if (empty($regDetail)) {
                    $regDetail = new RegistrationDetail;
                }
                $regDetail->registration_id = $detailReg->registration_id;
                $regDetail->room_id = $_POST['room_selected'];
                $regDetail->charge = ($detailReg->charge - $detailReg->room_price) + $price;
                $regDetail->guest_user_names = $detailReg->guest_user_names;
                $regDetail->extrabed = $detailReg->extrabed;
                $regDetail->pax = $detailReg->pax;
                $regDetail->room_price = $price;
                $regDetail->extrabed_price = $detailReg->extrabed_price;
                $regDetail->others_include = $detailReg->others_include;
                $regDetail->save();

                //update old reg detail is moved
                RegistrationDetail::model()->updateAll(
                        array('is_moved' => 1), 'room_id=' . $roomOld->id . ' and registration_id=' . $roomNew->registration_id);

                //update room bill 
                $lead_room_bill_id = 0;
                for ($a = strtotime($start); $a <= strtotime($end); $a = $a + 86400) {
                    $mBill = RoomBill::model()->find(array('condition' => 'registration_id=' . $roomNew->registration_id . ' and room_id=' . $room_id . ' and date_bill="' . date('Y-m-d', ($a)) . '"'));
                    if (!empty($mBill)) {
                        //mencari lead yang lama, dari bill yang lama
                        $oldLead = ($mBill->lead_room_bill_id == 0) ? $mBill->id : $mBill->lead_room_bill_id;

                        $mBill->room_id = $room_selected;
                        $mBill->charge = $regDetail->charge;
                        $mBill->room_price = $price;
                        $mBill->room_number = $roomNew->number;
                        $mBill->extrabed_price = $eb;
                        $mBill->fnb_price = $fb;
                        $mBill->others_include = $other;
                        $mBill->date_bill = date('Y-m-d', ($a));
                        $mBill->lead_room_bill_id = $lead_room_bill_id;
                        $arrTemp = json_decode($mBill->moved_room_bill_id); //menyusun room bill
                        $arrTemp[] = $oldLead;
                        $mBill->moved_room_bill_id = json_encode($arrTemp);
                        $mBill->save();

                        if ($lead_room_bill_id == 0)
                            $lead_room_bill_id = $mBill->id;

                        $roomSchedule = RoomSchedule::model()->find(array('condition' => 'registration_id=' . $roomNew->registration_id . ' and  room_id=' . $room_id . ' and date_schedule="' . date('Y-m-d', ($a)) . '"'));
                        if (!empty($roomSchedule)) {
                            $roomSchedule->room_bill_id = $mBill->id;
                            $roomSchedule->room_id = $room_selected;
                            $roomSchedule->save();
                        }
                    }
                }

                Yii::app()->user->setFlash('success', '<strong>Welldone! </strong>Room success moved to Room ' . $room_selected);
            } else {
                Yii::app()->user->setFlash('error', '<strong>Error! </strong>No room selected to move.');
            }
        }
        $filter = 't.status="occupied" or t.status="compliment" or t.status="house use"';
        $room = Room::model()->
                findAll(array('condition' => $filter));
        $this->render('move', array(
            'model' => $model,
            'room' => $room,
            'number' => $number,
        ));
    }

    public function actionEditPaxExtrabed() {
        $siteConfig = SiteConfig::model()->findByPk(1);
        $model = new RoomBill;
        $filter = 't.is_checkedout=0 AND t.date_bill="' . $siteConfig->date_system . '" and Room.status= "occupied" ';
        $room = RoomBill::model()->with('Room')->findAll(array('condition' => $filter));

        if (!empty($_POST['roomId'])) {
            foreach ($_POST['room_id'] as $key => $val) {
                $room_id = $_POST['room_id'][$key];
                $pax = $_POST['pax'][$key];
                $fnb_price = $_POST['fnb_price'][$key];
                $extrabed = $_POST['extrabed'][$key];
                $extrabed_price = $_POST['extrabed_price'][$key];
                $room_rate = $_POST['room_rate'][$key];
                $charge = $_POST['total_rate'][$key];

//                mengambil other include yang tercentang
                if (empty($_POST['others_include'][$val]))
                    $others_include = json_encode(array());
                else
                    $others_include = json_encode($_POST['others_include'][$val]);

                //mencari registrasi id dari room bill
                $roomBill = RoomBill::model()->find(array('condition' => 'is_checkedout=0 AND lead_room_bill_id=0 AND room_id=' . $val));
                $registration_id = $roomBill->registration_id;

                Room::model()->updateAll(array('pax' => $pax, 'extrabed' => $extrabed), 'id=' . $room_id);
                RoomBill::model()->updateAll(
                        array('others_include' => $others_include, 'fnb_price' => $fnb_price, 'pax' => $pax, 'extrabed' => $extrabed, 'extrabed_price' => $extrabed_price, 'room_price' => $room_rate, 'charge' => $charge), 'room_id=' . $room_id . ' and is_na=0 and date_bill >= "' . $siteConfig->date_system . '" AND registration_id=' . $registration_id);
                RegistrationDetail::model()->updateAll(
                        array('fnb_price' => $fnb_price, 'pax' => $pax, 'extrabed' => $extrabed, 'extrabed_price' => $extrabed_price, 'room_price' => $room_rate, 'charge' => $charge), 'room_id=' . $room_id . ' and registration_id=' . $registration_id);
            }
            Yii::app()->user->setFlash('success', '<strong>Welldone! </strong>Pax and Extrabed successfully edited.');
        }


        $this->render('editPaxExtrabed', array(
            'model' => $model,
            'room' => $room,
        ));
    }

    public function actionDetChangeCharge() {
        $data = '';
//        $roombill = array();
        $siteConfig = SiteConfig::model()->findByPk(1);

        if (!empty($_POST['regID'])) {
            $roombill = RoomBill::model()->findAll(array(
                'condition' => 'date_bill="' . $siteConfig->date_system . '" AND registration_id IN (' . implode(',', $_POST['regID']) . ') AND is_checkedout=0',
                    )
            );
        } elseif (!empty($_POST['roomID'])) {
            $roombill = RoomBill::model()->findAll(array(
                'condition' => 'date_bill="' . $siteConfig->date_system . '" AND room_id IN (' . implode(',', $_POST['roomID']) . ') AND is_checkedout=0',
                    )
            );
        }

        if ($siteConfig->others_include != "") {
            $others_include_sys = json_decode($siteConfig->others_include);
        }



        foreach ($roombill as $val) {
            $checkbox_others_include = "";
            $checkbox_others_include_sys = "";
            //meretrieve roombill other packagenya 
            $after = array();
            if (empty($val->others_include))
                $arrOi = array();
            else
                $arrOi = json_decode($val->others_include);

            foreach ($arrOi as $oi => $oiPrice) {
                $chargeAdd = ChargeAdditional::model()->findByPk($oi);
                if (!empty($chargeAdd)) {
                    $after[] = $oi;
                    $checkbox_others_include.= $this->renderPartial('/registration/_oi', array('checked' => 'checked', 'id' => $chargeAdd->id, 'room_id' => $val->room_id, 'val' => $oiPrice, 'name' => $chargeAdd->name), true);
                }
            }

            //meretrieve default other include dari sistem
            foreach ($others_include_sys as $other) {
                $chargeAdd = ChargeAdditional::model()->findByPk($other);
                if (!empty($chargeAdd) && !in_array($other, $after)) {
                    $checkbox_others_include_sys.= $this->renderPartial('/registration/_oi', array('checked' => '', 'id' => $chargeAdd->id, 'room_id' => $val->room_id, 'val' => $chargeAdd->charge, 'name' => $chargeAdd->name), true);
                }
            }

            $data .= '<tr class="items" id="' . $val->room_id . '">
                        <td>
                            <input type="hidden" name="room_id[]" value="' . $val->room_id . '" class="room_id"/>
                            No : ' . $val->Room->number . '<br/>
                            Type : ' . $val->Room->RoomType->name . '<br/>
                            Floor : ' . $val->Room->floor . '
                        </td>
                        <td><input onchange="calculation()" value="' . $val->pax . '" class="angka pax" type="text" value="" name="pax[]" style="width:20px" maxlength="1"/></td>   
                        <td><div class="input-prepend"><span class="add-on">Rp</span><input onchange="calculation()" value="' . $val->fnb_price . '" class="angka fnb_price"  style="width:70px"  type="text" value="" name="fnb_price[]" /></div></td>
                        <td><input onchange="calculation()" value="' . $val->extrabed . '" class="angka extrabed" type="text" value="" name="extrabed[]" style="width:20px" maxlength="1"/></td>
                        <td><div class="input-prepend"><span class="add-on">Rp</span><input onchange="calculation()" value="' . $val->extrabed_price . '" class="angka extrabed_price" type="text" value="" name="extrabed_price[]" /></div></td>                                
                        <td>' . $checkbox_others_include_sys . '<span class="pckg">' . $checkbox_others_include . '</span></td>
                        <td><div class="input-prepend"><span class="add-on">Rp</span><input onchange="calculation()" value="' . $val->room_price . '" class="angka room_rate" type="text" value="" name="room_rate[]" /></div></td>                                
                        <td><div class="input-prepend"><span class="add-on">Rp</span><input onchange="calculation()" style="width:70px" type="text" class="total_rate" name="total_rate[]" readonly=""></div></td>
                    </tr>
                    <tr id="addRow" style="display:none">
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr> ';
        }
        echo $data;
    }

    public function actionTaxExport() {
        $model = new RoomBill;
        if (!empty($_POST['RoomBill'])) {
            $model->attributes = $_POST['RoomBill'];
        }
        $this->render('taxExport', array(
            'model' => $model,
        ));
    }

    public function actionCreateZip() {

        $zip = new ZipArchive;
        $res = $zip->open('/var/www/tarom/test.zip', ZipArchive::CREATE);
        if ($res === TRUE) {
            $zip->addFromString('test.txt', 'file content goes here');
            $zip->close();
            echo 'ok';
        } else {
            echo 'failed';
        }
    }

    public function actionGenerateTax() {
        if (!empty($_GET['date'])) {
            $date = $_GET['date'];
            $na = Na::model()->find(array('condition' => 'date_na="' . date('Y-m-d', strtotime($date)) . '"'));
            $naDet = array();
            if (!empty($na))
                $naDet = NaDet::model()->findAll(array('condition' => 'na_id=' . $na->id));
        } else {
            $naDet = array();
            $date = date('Y-m-d');
        }
        $kodeArea = SiteConfig::model()->getKodeAreaMalang();
        $siteConfig = SiteConfig::model()->listSiteConfig();
        $npwp = $siteConfig->npwp;
        $name = 'T' . $kodeArea . '_' . $npwp . '_' . date('Ymd', strtotime($date)) . '000000';

        Yii::app()->request->sendFile($name . '.txt', $this->renderPartial('_generateTaxExport', array(
                    'naDet' => $naDet,
                    'date' => $date
                        ), 'text/plain')
        );
    }

    public function actionGetRoom() {
        if (!empty($_POST['roomId'])) {
            $date_move = $_POST['date_move'];
            $date_check_out = $_POST['date_check_out'];
            $room = Room::model()->findByPk($_POST['roomId']);
            $roles = $room->Registration->Guest->roles_id;
            $type = $_POST['roomType'];
            $floor = (!empty($_POST['floor'])) ? $_POST['floor'] : '';
            $bed = $_POST['bed'];
            $start = date("Y/m/d", strtotime($date_move));
            $end = date("Y/m/d", strtotime($date_check_out));
            echo $this->renderPartial('_selectRoomMove', array('date_move' => $date_check_out, 'date_check_out' => $date_check_out, 'roles' => $roles, 'type' => $type, 'floor' => $floor, 'bed' => $bed, 'start' => $start, 'end' => $end));
        }
    }

    public function loadModel($id) {
        $model = RoomBill::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'room-bill-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
