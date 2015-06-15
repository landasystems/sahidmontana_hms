<table border="0">
    <tbody>
        <?php
        $bill = new Bill();
        $bill->total = 0;
        $bill->total_dp = 0;
        $room_bill_ids = array();
        $leadRoomBills = array();
        $guestUserIds = array();
        $billCharge = array();
        $bill_dets = array();
        $modelRoom = array();
        $return = '';

        foreach ($details as $no => $m) {
            if (empty($m->deposite_id)) //jika bukan deposite
                $leadRoomBills[] = $m->room_bill_id_leader;
            else
                $bill_dets[] = $m->id;

            $room_bill_ids[] = $m->room_bill_id;
            if ($no == 0) {
                $return['date_to'] = '';
            }
        }

//        mencari bill yang di GL kan
        if (empty($leadRoomBills)) {
            $modelBillDet = array();
        } else {
            $modelBillDet = BillDet::model()->findAll(array('with' => 'Bill', 'condition' => 'Bill.gl_room_bill_id IN (' . implode(',', $leadRoomBills) . ')'));
        }

        $billCharge = BillDet::model()->findAll(array('condition' => 'bill_id=' . $model->id . ' and bill_charge_id <> 0'));

        foreach ($modelBillDet as $m) {
            if ($m->room_bill_id_leader == $m->room_bill_id)
                $leadRoomBills[] = $m->room_bill_id;
            $room_bill_ids[] = $m->room_bill_id; // parameter untuk det room yang di gl kan
        }

        $det = '<table class="" border="0" style="width:100%">'
                . '<tr style="border-top: #000 solid 2px;border-bottom: #000 solid 2px;">'
                . '<td id="bill_print"  style="text-align:center"><b>Date</b></td>'
                . '<td id="bill_print"  style="text-align:center"><b>Details</b></td>'
                . '<td  id="bill_print" style="text-align:center"><b>Room</b></td>'
                . '<td  id="bill_print" style="text-align:center"><b>Amount</b></td>'
                . '<td  id="bill_print"  style="text-align:center"><b>Credit</b></td>'
                . '<td  id="bill_print" style="text-align:center"><b>Subtotal</b></td>'
                . '</tr>';

        $siteConfig = SiteConfig::model()->listSiteConfig();
        $content = $siteConfig->report_bill;
        $content = str_replace('{invoice}', $model->code, $content);
        $content = str_replace('{date}', date('d-M-Y, H:i', strtotime($model->created)), $content);
        $content = str_replace('{desc}', $model->description, $content);
        $content = str_replace('{cashier}', strtoupper($model->Cashier->name), $content);
        $content = str_replace('{phone}', ucwords($model->guest_phone), $content);
        $content = str_replace('{guest}', ucwords(strtoupper($model->pax_name)), $content);
        $content = str_replace('{address}', ucwords($model->guest_address), $content);
        $content = str_replace('{departure}', date('d-M-Y, H:i', strtotime($model->departure_time)), $content);
        $content = str_replace('{arrival}', date('d-M-Y, H:i', strtotime($model->arrival_time)), $content);


        $det.= $bill->detDepositeView($bill_dets, true); //deposite
        $det.= $bill->detRoom(array(), $room_bill_ids, true); //roombill
        $det .= $bill->detcharge($billCharge, true); // ambil charge
        //$det.= $bill->detAddCharge($leadRoomBills, true); //mencari transaksi yang di GL kan
        $det.= '<tr id="bill_print"><td id="bill_print" colspan="5" style="text-align:right;" >Total (IDR):</td><td id="bill_print" style="text-align:right;">' . landa()->rp($model->total, false) . '</td></tr>';
        if ($model->cash != 0) {
            $det.= '<tr id="bill_print"><td id="bill_print" colspan="5" style="text-align:right;" >Cash :</td><td id="bill_print" style="text-align:right;">' . landa()->rp($model->cash, false) . '</td></tr>';
        }
        if ($model->cc_charge != 0) {
            $det.= '<tr><td id="bill_print" colspan="5" style="text-align:right;" >Credit :</td><td id="bill_print"  id="bill_print" style="text-align:right;">' . landa()->rp($model->cc_charge, false) . '</td></tr>';
            $det.= '<tr><td id="bill_print" colspan="5" style="text-align:right;" >CC Numb :</td><td id="bill_print"  id="bill_print" style="text-align:right;">' . $model->cc_number . '</td></tr>';
        }

        if ($model->ca_charge != 0 & $model->ca_user_id != 0) {
            $det.= '<tr><td id="bill_print"  colspan="5" style="text-align:right;" >CL (' . $model->BillTo->name . ') :</td><td id="bill_print"  style="text-align:right;">' . landa()->rp($model->ca_charge, false) . '</td></tr>';
        }
        if (!empty($model->gl_room_bill_id)) {
            $det.= '<tr><td id="bill_print"  colspan="5" style="text-align:right;" >Guest Ledger (' . $model->RoomBill->room_number . ') :</td><td id="bill_print"  style="text-align:right;">' . landa()->rp($model->total, false) . '</td></tr>';
        } else {
            $det.= '<tr><td colspan="5" id="bill_print"  style="text-align:right;" >Refund :</td><td id="bill_print"  style="text-align:right;">' . landa()->rp($model->refund, false) . '</td></tr></table>';
        }


        $content = str_replace('{detail}', $det, $content);


        echo $content;
        ?>
    </tbody>
</table>
