<?php
$this->setPageTitle('Bills | ' . $model->id);

?>

<?php
$this->beginWidget('zii.widgets.CPortlet', array(
    'htmlOptions' => array(
        'class' => ''
    )
));
$this->widget('bootstrap.widgets.TbMenu', array(
    'type' => 'pills',
    'items' => array(
        array('label' => 'Create', 'icon' => 'icon-plus', 'url' => Yii::app()->controller->createUrl('create'), 'linkOptions' => array()),
        array('label' => 'List Data', 'icon' => 'icon-th-list', 'url' => Yii::app()->controller->createUrl('index'), 'linkOptions' => array()),
        array('label' => 'Edit', 'icon' => 'icon-edit', 'url' => Yii::app()->controller->createUrl('update', array('id' => $model->id)), 'active' => true, 'linkOptions' => array()),
    ),
));
$this->endWidget();

$det = '<table style="width:100%;" border="1px solid black">';
$det .= '<tr><td style="text-align:center">Date</td><td style="text-align:center">Description</td><td style="text-align:center">Room</td><td style="text-align:center">Amount</td><td  style="text-align:center">Credit</td><td style="text-align:center">Charge</td><td style="text-align:center">Subtotal</td><tr>';
?>

<?php
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id' => 'sms-form',
    'enableAjaxValidation' => false,
    'method' => 'post',
    'type' => 'horizontal',
    'htmlOptions' => array(
        'enctype' => 'multipart/form-data'
    )
        ));
?>
<div class="box invoice">
    <div class="title clearfix">
        <h4 class="left">
            <span class="blue cut-icon-bookmark"></span>
            <span>Billing Detail</span>                        
        </h4>
        <div class="invoice-info">
            <span class="number"> Invoice #<strong class="red">
                    <?php
                    echo $model->code;
                    ?>
                </strong>
            </span>
        </div> 
    </div>

    <div class="content">   
        <table style="width:100%">
            <tr>
                <td class="span2" style="vertical-align: top">Mr. / Mrs.</td>
                <td style="width:5px;vertical-align: top" colspan="3">: <input value="<?php echo $model->pax_name; ?>" id="pax" type="text" class="span4 guest_reciver" name="guest_reciver" /></td>
            </tr>
            <tr>
                <td class="span2" style="vertical-align: top">Address</td>
                <td style="width:5px;vertical-align: top" colspan="3">: <input value="<?php echo $model->guest_address; ?>" id="guest_address" type="text" class="span4 guest_address" name="guest_address" style="width:90%"/></td>
            </tr>
            <tr>
                <td class="span2" style="vertical-align: top">Company</td>
                <td style="width:5px;vertical-align: top">: <input value="<?php echo $model->guest_company; ?>" id="guest_company" type="text" class="span2 guest_company" name="guest_company" /></td>
                <td class="span2" style="vertical-align: top">Phone</td>
                <td style="width:5px;vertical-align: top">: <input value="<?php echo $model->guest_phone; ?>" id="guest_phone" type="text" class="span2 guest_phone" name="guest_phone" /></td>
            </tr>
            <tr>
                <td style="vertical-align: top">Date Check In</td>
                <td style="vertical-align: top">: <?php echo date("l Y-m-d H:i:s", strtotime($model->arrival_time)); ?></td>
                <td style="vertical-align: top">Date Check Out</td>                                
                <td style="vertical-align: top">: <?php echo date("l Y-m-d H:i:s", strtotime($model->departure_time)); ?></td>
            </tr>                                                     
            <tr>
                <td style="vertical-align: top">Remarks</td>
                <td style="vertical-align: top" colspan="3">: <?php echo CHtml::textArea('description', $model->description, array('style' => 'width:90%')); ?></td>
            </tr>                            
        </table>   

        <table class="table table-striped ">
            <thead>
                <tr>
                    <th style="width: 15px;text-align:center">#</th>
                    <th class="span4" style="text-align:center">Details</th>
                    <th class="span4" style="text-align:center">Room</th>
                    <th class="span3" style="text-align:center">Date</th>                                 
                    <th class="span1" style="text-align:center">Amount</th>                                 
                    <th class="span2" style="text-align:center">Charge</th>                                 
                    <th class="span2" style="text-align:center">Subtotal</th>                                 
                </tr>
            </thead>
            <tbody>     

                <?php
                $bill = new Bill();
                $bill->total = 0;
                $bill->total_dp = 0;
                $room_bill_ids = array();
                $leadRoomBills = array(0);
                $guestUserIds = array();
                $modelRoom = array();
                $billCharge = array();
                $bill_dets = array();
                $return = '';

                $billCharge = BillDet::model()->findAll(array('condition' => 'bill_id=' . $model->id . ' and bill_charge_id <> 0'));

                foreach ($details as $no => $m) {
                    if (empty($m->deposite_id)) //jika bukan deposite
                        $leadRoomBills[] = $m->room_bill_id_leader;
                    else
                        $bill_dets[] = $m->id;

                    $room_bill_ids[] = $m->room_bill_id;
                }

//        mencari bill yang di GL kan
                $modelBillDet = BillDet::model()->findAll(array('with' => 'Bill', 'condition' => 'Bill.gl_room_bill_id IN (' . implode(',', $leadRoomBills) . ')'));
                foreach ($modelBillDet as $m) {
                    if ($m->room_bill_id_leader == $m->room_bill_id)
                        $leadRoomBills[] = $m->room_bill_id;
                    $room_bill_ids[] = $m->room_bill_id; // parameter untuk det room yang di gl kan
                }

                $return.= $bill->detDepositeView($bill_dets); //deposite
                $return.= $bill->detRoom(array(), $room_bill_ids); //roombill
                //$return.= $bill->detAddCharge($leadRoomBills); //mencari transaksi yang di GL kan
                $return.= $bill->detcharge($billCharge);
                echo $return;
                ?>

                <tr>
                    <td colspan="6" style="text-align: right">
                        <b>Grand Total :</b>                                         
                        <input type="hidden" name="totalDeposite" id="totalDeposite" value="<?php echo $bill->total_dp; ?>" />
                        <input type="hidden" name="grandTotal" id="grandTotal" value="<?php echo $model->total; ?>" />
                        <input type="hidden" name="totalNoDeposite" id="totalNoDeposite" value="<?php echo $model->total - $bill->total_dp; ?>" />

                    </td>
                    <td style="text-align:right"><?php echo landa()->rp($model->total); ?></td>                                                        
                </tr>

                <tr>          
                    <td colspan="3" style="text-align: right">
                        <b>Guest Ledger :</b>                
                    </td>
                    <td colspan="2" style="text-align:right">
                        <?php
                        $roomBill = RoomBill::model()->findAll(array('condition' => 'is_checkedout=0 and lead_room_bill_id=0', "order" => "room_number Asc"));
                        $data = array(0 => 'Please Choose') + CHtml::listData($roomBill, 'id', 'fullRoom');
                        $this->widget(
                                'bootstrap.widgets.TbSelect2', array(
                            'asDropDownList' => true,
                            'name' => 'gl_room_bill_id',
                            'data' => $data,
                            'options' => array(
                                'allowClear' => true,
                                "placeholder" => 'Please Choose',
                                'width' => '85%',
                            ),
                                )
                        );
                        ?>                      
                    </td>
                    <td style="text-align: right">
                        <b>Cash :</b>                
                    </td>
                    <td style="text-align:right">
                        <div class="input-prepend"><span class="add-on">Rp</span>
                            <input <?php echo (!empty($billTo)) ? 'disabled' : ''; ?> value="<?php echo $model->cash; ?>" style="width:100px;" id="cash" name="cash"  type="text" class="angka">
                        </div>                        
                    </td>                                                        
                </tr> 
                <tr >
                    <td colspan="3" style="text-align: right">
                        <b>Credit Card Number :</b>                        
                    </td>
                    <td colspan="2" style="text-align:right">
                        <input <?php echo (!empty($billTo)) ? 'disabled' : ''; ?> value="<?php echo $model->cc_number; ?>" style="width:90%" id="cc_number" name="cc_number"  type="text">
                    </td>
                    <td style="text-align: right">
                        <b>Credit Card :</b>                        
                    </td>
                    <td style="text-align:right">
                        <div class="input-prepend"><span class="add-on">Rp</span>
                            <input <?php echo (!empty($billTo)) ? 'disabled' : ''; ?> value="<?php echo $model->cc_charge; ?>" style="width:100px;" id="credit" name="credit"  type="text" class="angka">
                        </div>
                    </td>                                                        
                </tr>
                <tr>
                    <td colspan="3" style="text-align: right">
                        <b>City Ledger Name:</b>                        
                    </td>
                    <td colspan="2" style="text-align:right">
                        <?php
                        $datauser = CHtml::listData(User::model()->listUsers('guest'), 'id', 'fullName');
                        $this->widget(
                                'bootstrap.widgets.TbSelect2', array(
                            'asDropDownList' => true,
                            'value' => (!empty($billTo)) ? $billTo : '',
                            'name' => 'billedBy',
                            'data' => array(0 => 'Please Choose') + $datauser,
                            'options' => array(
                                "placeholder" => 'Please Choose',
                                "allowClear" => true,
                                "width" => '100%',
                        )));
                        ?>
                    </td>   
                    <td style="text-align: right">
                        <b>City Ledger :</b>                        
                    </td>
                    <td style="text-align:right">
                        <div class="input-prepend"><span class="add-on">Rp</span>
                            <input style="width:100px;" id="cl" name="cl"  type="text" class="angka" value="<?php echo $model->ca_charge; ?>">
                        </div>
                    </td>                                                        
                </tr> 
                <tr>
                    <td colspan="6" style="text-align: right">
                        <b>Discount :</b>                        
                    </td>
                    <td style="text-align:right">
                        <div class="input-prepend">
                            <span class="add-on">Rp</span>
                            <input class="angka" style="width:100px;" id="discount" name="discount"  type="text" value="<?php echo $model->discount; ?>">

                        </div>
                    </td>                                                        
                </tr>
                <tr>                    
                    <td colspan="6" style="text-align: right">
                        <b>Refund</b>                        
                    </td>
                    <td style="text-align:right">
                        <div class="input-prepend"><span class="add-on">Rp</span>
                            <input style="width:100px;" id="refund" name="refund" ReadOnly type="text" class="angka" value="<?php echo $model->refund; ?>">                            
                        </div>
                    </td>                                                        
                </tr>

            </tbody>
        </table>  
    </div>
    <div class="form-actions">
        <button class="btn btn-primary" id="submit" type="submit" name="room"><i class="icon-ok icon-white"></i> Save</button>
    </div>
</div> 
<?php
$this->endWidget();
$this->renderPartial('js');
?>

