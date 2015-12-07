<?php

class BillController extends Controller {

    

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
           
            array('allow', // r
                'actions' => array('index','delete', 'update','view','create'),
                'expression' => 'app()->controller->isValidAccess("Bill","r")'
            ),
            
        );
    }

    public function cssJs() {
        cs()->registerScript('', '                
                        function rp(angka){ 
                            var rupiah = "";
                            var angkarev = angka.toString().split("").reverse().join("");
                            for(var i = 0; i < angkarev.length; i++) if(i%3 == 0) rupiah += angkarev.substr(i,3)+".";
                            return rupiah.split("",rupiah.length-1).reverse().join("");
                        };
                        
                     
                        function pay(){                         
//                            var grandTotal = parseFloat($("#grandTotal").val());
                            var grandTotal = parseFloat($("#grandTotal").val());
                            var totalDeposite = parseFloat($("#totalDeposite").val());
                            
                            var cash = parseFloat($("#cash").val());
                            var credit = parseFloat($("#credit").val());
                            var cl = parseFloat($("#cl").val());
                            var discount = parseFloat($("#discount").val());
                            
                            
                            grandTotal = grandTotal || 0;
                            cash = cash || 0;
                            credit = credit || 0;
                            cl = cl || 0;
                            totalDeposite = totalDeposite || 0;
                            discount = discount || 0;                                                                                                            
                                                                                    
//                            var refund = parseInt(((grandTotal - discount) * -1) + cash + credit + cl + totalDeposite);                            
                            var refund = parseInt((cash + credit + cl + ((grandTotal / 100) * discount))- grandTotal);                            
                            $("#refund").val(refund);
                                            
                        };
                        
                        function clearList(){
                            $(".items").remove();                                       
                            $("#name").html("");
                            $("#date_to").html("");
                            $("#addRow").replaceWith(row);
                            $("#pax").val("");
                            $("#total").html(0);                                       
                            $("#grandTotal").val(0);  
                            $("#totalDeposite").val(0);                                       
                            $("#totalNoDeposite").val(0);                                       
                            $("#cash").val(0);                                       
                            $("#credit").val(0);                                       
                            $("#cc_number").val("");                                                                                                                                                                                 
                            $("#cl").val(0);                                       
                            $("#billedBy").select2("val",0);
                            $("#refund").val(0);     
                            $("#discount").val(0);
                            $("#s2id_registration_id").select2("data", null)
                            $("#s2id_roomId").select2("data", null)
                        }
                                                                     
                        $("#cash").on("input", function() {
                            pay();
                        });                        
                                 
                        $("#credit").on("input", function() {
                            pay();
                        });
                        $("#cl").on("input", function() {
                            pay();
                        });
                        $("#discount").on("input", function() {
                            pay();
                        });                      
                    ');
    }

    public function actionGetBilling() { //checkout by registrasi
        if (isset($_POST['regID']) || isset($_POST['roomId'])) {
            $bill = new Bill();
            $bill->total = 0;
            $bill->total_dp = 0;
            $return['detail'] = '';
            $leadRoomBills = array();
            $room_ids = array();
            $guestUserIds = array();
            $modelRoom = array();

            if (!empty($_POST['regID'])) {
                $modelRoom = RoomBill::model()->findAll(array('condition' => 'lead_room_bill_id=0 AND registration_id IN (' . implode(',', $_POST['regID']) . ') AND is_checkedout=0 ', 'order' => 'room_id'));
            } elseif (!empty($_POST['roomId'])) {
                $modelRoom = RoomBill::model()->findAll(array('condition' => 'lead_room_bill_id=0 AND room_id IN (' . implode(',', $_POST['roomId']) . ') AND is_checkedout=0 ', 'order' => 'room_id'));
            }

            $return['date_to'] = date('Y-m-d H:i');
            foreach ($modelRoom as $no => $m) {

                //---------mencari bill yang ada pada, room sebelumnya (pindah kamar)
                if (!empty($m->moved_room_bill_id)) {
                    $room_move = json_decode($m->moved_room_bill_id);
                    foreach ($room_move as $val) {
                        $leadRoomBills[] = $val;
                    }
                }
                //---------

                $leadRoomBills[] = $m->id;
                $room_ids[] = $m->room_id;
                $guestUserIds[] = $m->Registration->guest_user_id;

                if ($no == 0) {
                    $return['pax'] = $m->Registration->Guest->guestName;
                    $return['guest_phone'] = $m->Registration->Guest->phone;
                    $return['guest_address'] = $m->Registration->Guest->address;
                    $return['guest_company'] = $m->Registration->Guest->company;
                    $return['guest_id'] = $m->Registration->guest_user_id;
                }
                if (strtotime($m->Registration->created) < strtotime($return['date_to']))
                    $return['date_to'] = date('l, d-M-Y H:i', strtotime($m->Registration->created));
            }

            //mencari bill yang di GL kan
            if (empty($leadRoomBills)) {
                $modelBillDet = array();
            } else {
                $modelBillDet = BillDet::model()->findAll(array('with' => 'Bill', 'condition' => 'Bill.gl_room_bill_id IN (' . implode(',', $leadRoomBills) . ')'));
            }
//            $room_bill_ids = array();
            foreach ($modelBillDet as $m) {
                if ($m->room_bill_id_leader == $m->room_bill_id)
                    $leadRoomBills[] = $m->room_bill_id;

//                $room_bill_ids[] = $m->room_bill_id; // parameter untuk det room yang di gl kan
            }

            //belum di fungsikan
            $return['ca'] = '';
            $return['name'] = '';


            $return['detail'].= $bill->detDeposite($guestUserIds); //deposite
            $return['detail'].= $bill->detRoom(array(), $leadRoomBills); //roombill
            $return['detail'].= $bill->detAddCharge($leadRoomBills); //mencari transaksi yang di GL kan

            $return['total'] = landa()->rp($bill->total);
            $return['grandTotal'] = $bill->total;
            $return['totalDeposite'] = $bill->total_dp;
            $return['totalNoDeposite'] = $bill->total + $bill->total_dp;
            $return['detail'].='
                    <tr id="addRow" style="display:none">
                        <td></td>
                        <td></td>                 
                        <td></td>                 
                    </tr>';
            echo json_encode($return);
        }
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id) {
        $model = $this->loadModel($id);
        $mDet = BillDet::model()->findAll(array('condition' => 'bill_id=' . $model->id));

        if (isset($_POST[''])) {
            
        }

        $this->render('view', array(
            'model' => $model,
            'details' => $mDet,
        ));
    }

    public function actionCreate() {
        $this->cssJs();
        $model = new Bill;
        $mRoom = array();
        $siteConfig = SiteConfig::model()->findByPk(1);
        $number = isset($_GET['roomNumber']) ? $_GET['roomNumber'] : '';

//************** save bill checkout **********************
        if (!empty($_POST['roomId']) || !empty($_POST['registration_id'])) {
            if (empty($_POST['roomId'])) { //jika kosong, ambil dari registrasi
                $mRoom = Room::model()->findAll(array('condition' => 'registration_id IN (' . implode($_POST['registration_id'], ',') . ')'));
                foreach ($mRoom as $val)
                    $_POST['roomId'][] = $val->id;
            }

            $model->guest_address = $_POST['guest_address'];
            $model->guest_phone = $_POST['guest_phone'];
            $model->guest_company = $_POST['guest_company'];
            $model->guest_room_ids = json_encode($_POST['roomId']);

            $model->code = SiteConfig::model()->formatting('bill');
            $model->arrival_time = date('Y-m-d H:i', strtotime($_POST['date_to']));
            $model->departure_time = date("Y-m-d H:i");
            $model->description = $_POST['description'];
            $model->cash = (!empty($_POST['cash'])) ? $_POST['cash'] : "";
            $model->cc_number = (!empty($_POST['cc_number'])) ? $_POST['cc_number'] : "";
            $model->cc_charge = (!empty($_POST['credit'])) ? $_POST['credit'] : "";
            $model->ca_user_id = (!empty($_POST['billedBy'])) ? $_POST['billedBy'] : "";
            $model->ca_charge = (!empty($_POST['cl'])) ? $_POST['cl'] : "";
            $model->refund = (!empty($_POST['refund'])) ? $_POST['refund'] : "";
            $model->total = $_POST['grandTotal'];
            $model->discount = $_POST['discount'];
            $model->is_cashier = 0;
            $model->is_na = 0;
            $model->pax_name = (!empty($_POST['guest_reciver'])) ? $_POST['guest_reciver'] : "";
            $model->gl_room_bill_id = (!empty($_POST['gl_room_bill_id'])) ? $_POST['gl_room_bill_id'] : "";

            if ($model->save()) {

                //baru ditambahkan. get bill charge
                
                $billCharge = (isset($_POST['bill_charge_id'])) ? $_POST['bill_charge_id'] : array();
                foreach ($billCharge as $charge) {
                    $billDet = new BillDet();
                    $billDet->bill_id = $model->id;
                    $billDet->bill_charge_id = $charge;
                    $billDet->save();
                }

                //get deposite
                $dp = (isset($_POST['Deposite'])) ? $_POST['Deposite'] : array();
                foreach ($dp as $kunci => $isi) {
                    $billDet = new BillDet();
                    $billDet->bill_id = $model->id;
                    $billDet->deposite_id = $kunci;
                    $billDet->deposite_amount = $isi;
                    $billDet->save();

                    $deposite = Deposite::model()->findByPk($kunci);
                    $deposite->balance_amount -= $isi;
                    $deposite->used_amount += $isi;
                    if ($deposite->balance_amount == $deposite->amount) {
                        $deposite->is_applied = 1;
                        $deposit->is_used = 1;
                        $deposit->used_today = 1;
                    }
                    $deposite->save();
                }

                //menyimpan dari room bill id, ke dalam bill det
                if (empty($_POST['room_bill_id'])) {
                    $roomBills = array();
                } else {
                    $roomBills = RoomBill::model()->findAll(array('condition' => 'id IN (' . implode($_POST['room_bill_id'], ',') . ')', 'order' => 'room_id, lead_room_bill_id'));
                }

                foreach ($roomBills as $roomBill) {
                    $leader = 0;
                    if ($roomBill->lead_room_bill_id == 0)
                        $leader = $roomBill->id;

                    $billDet = new BillDet();
                    $billDet->bill_id = $model->id;
                    $billDet->room_bill_id = $roomBill->id;
                    $billDet->room_bill_id_leader = $leader;
                    $billDet->save();

                    $roomBill->is_checkedout = 1;
                    $roomBill->processed = 1;
                    $roomBill->save();
                }

                //menghapus schedule dan roombill yang gak jadi di checkin
                if (!empty($_POST['roomId'])) {
                    RoomBill::model()->deleteAll('room_id IN (' . implode(',', $_POST['roomId']) . ') and is_na=0');
                    RoomSchedule::model()->deleteAll('(status="occupied" || status="compliment" || status="house use") and room_id IN (' . implode(',', $_POST['roomId']) . ') and DATE(date_schedule) >= date("' . date('Y-m-d', strtotime($siteConfig->date_system)) . '")');
                }

                //mengubah room status jadi dirty
                Room::model()->updateAll(array(
                    'linked_room_id' => 0,
                    'registration_id' => 0,
                    'status' => 'dirty',
                    'status_housekeeping' => 'dirty',
                    'extrabed' => 0,
                    'pax' => 0
                        ), 'id IN (' . implode(',', $_POST['roomId']) . ')');
                
                user()->setFlash('success',"Saved successfully");
                $this->redirect(array('view', 'id' => $model->id));
            }
        }

        $filter = 't.status="occupied" || t.status="house use" || t.status="compliment"';
        $room = Room::model()->findAll(array('condition' => $filter));
        $this->render('create', array(
            'model' => $model,
            'room' => $room,
            'number' => $number,
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id) {
        $this->cssJs();
        $model = $this->loadModel($id);
        $mDet = BillDet::model()->findAll(array('condition' => 'bill_id=' . $model->id));
        if (isset($_POST['description'])) {
            $model->guest_address = $_POST['guest_address'];
            $model->guest_phone = $_POST['guest_phone'];
            $model->guest_company = $_POST['guest_company'];


            $model->description = $_POST['description'];
            $model->cash = (!empty($_POST['cash'])) ? $_POST['cash'] : "";
            $model->cc_number = (!empty($_POST['cc_number'])) ? $_POST['cc_number'] : "";
            $model->cc_charge = (!empty($_POST['credit'])) ? $_POST['credit'] : "";
            $model->ca_user_id = (!empty($_POST['billedBy'])) ? $_POST['billedBy'] : "";
            $model->ca_charge = (!empty($_POST['cl'])) ? $_POST['cl'] : "";
            $model->refund = (!empty($_POST['refund'])) ? $_POST['refund'] : "";
            $model->total = $_POST['grandTotal'];
            $model->discount = $_POST['discount'];
            $model->pax_name = (!empty($_POST['guest_reciver'])) ? $_POST['guest_reciver'] : "";
            $model->gl_room_bill_id = (!empty($_POST['gl_room_bill_id'])) ? $_POST['gl_room_bill_id'] : "";
            $model->save();
            
            user()->setFlash('success',"Saved successfully");
            $this->redirect(array('view', 'id' => $model->id));
        }

        $this->render('update', array(
            'model' => $model,
            'details' => $mDet,
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
            $this->loadModel($id)->delete();

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

        $model = new Bill('search');
        $model->unsetAttributes();  // clear any default values

        if (isset($_GET['Bill'])) {
            $model->attributes = $_GET['Bill'];
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
        $model = Bill::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'bill-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
