<?php

$siteConfig = SiteConfig::model()->findByPk(1);
$settings = json_decode($siteConfig->settings, true);
$detail = '';
$id = 1;
$type_transaction = SiteConfig::model()->getStandartTransactionMalang();
$roomType = RoomType::model()->findAll(array('index'=>'id'));

foreach ($naDet as $na) {
    if ($na->room_bill_id != 0) {
        if ($na->RoomBill->charge != 0) {
            $roomPrice = $na->RoomBill->room_price;
            //kurangi roomprice dengan paket jika jenisroom adalah paket
            if ($na->RoomBill->package_room_type_id != 0) {
                $package = json_decode($roomType[$na->RoomBill->package_room_type_id]['charge_additional_ids']);
                if (!empty($package)) {
                    foreach ($package as $mPackage) {
                        $additional = ChargeAdditional::model()->findByPk($mPackage->id);
                        $roomPrice -= $mPackage->amount * $additional->charge;
                    }
                }
            }
            //check fnb jika 1 orang, maka sisa dikasihkan room
            $fb = $na->RoomBill->pax * $na->RoomBill->fnb_price;
            if ($na->RoomBill->pax == 1) {
                $roomPrice += $na->RoomBill->pax * $na->RoomBill->fnb_price;
            }

            $noID = '0000000' . $id;
            $price = $roomPrice + ($na->RoomBill->extrabed * $na->RoomBill->extrabed_price);
            $detail .= '"TRX' . substr($noID, strlen($noID) - 5, 5) . '"|"' .
                    $na->RoomBill->Registration->code . '"|"' .
                    'ATS' . '"|"' .
                    date('Ymd', strtotime($date)) . '000000"|"' .
                    $type_transaction['ATS'] . '"|"' .
                    $price . '"|"' .
                    '1" ';
            $id++;
            if ($na->RoomBill->package_room_type_id != 0) {
                $package = json_decode($roomType[$na->RoomBill->package_room_type_id]['charge_additional_ids']);
                if (!empty($package)) {
                    foreach ($package as $mPackage) {
                        $additional = ChargeAdditional::model()->findByPk($mPackage->id);
                        $price = $mPackage->amount * $additional->charge;
                        $noID = '0000000' . $id;
                        $detail .= '"TRX' . substr($noID, strlen($noID) - 5, 5) . '"|"' .
                                $na->RoomBill->Registration->code . '"|"' .
                                $additional->type_transaction . '"|"' .
                                date('Ymd', strtotime($date)) . '000000"|"' .
                                $type_transaction[$additional->type_transaction] . '"|"' .
                                $price . '"|"' .
                                '1" ';
                        $id++;
                    }
                }
            }

            if ($na->RoomBill->others_include != '') {
                $others_include = json_decode($na->RoomBill->others_include);
                foreach ($others_include as $key => $mInclude) {
                    $additional = ChargeAdditional::model()->findByPk($key);                    
                    $noID = '0000000' . $id;
                    $detail .= '"TRX' . substr($noID, strlen($noID) - 5, 5) . '"|"' .
                            $na->RoomBill->Registration->code . '"|"' .
                            $additional->type_transaction . '"|"' .
                            date('Ymd', strtotime($date)) . '000000"|"' .
                            $type_transaction[$additional->type_transaction] . '"|"' .
                            $mInclude . '"|"' .
                            '1" ';
                    $id++;
                }
            }
            //breakfast            
            $noID = '0000000' . $id;
            $detail .= '"TRX' . substr($noID, strlen($noID) - 5, 5) . '"|"' .
                    $na->RoomBill->Registration->code . '"|"' .
                    'ATM' . '"|"' .
                    date('Ymd', strtotime($date)) . '000000"|"' .
                    $type_transaction['ATM'] . '"|"' .
                    $fb . '"|"' .
                    '1" ';
            $id++;
        }
    }

    if ($na->bill_charge_id != 0) {
        $billChargeDets = BillChargeDet::model()->findAll(array('condition' => 'bill_charge_id=' . $na->bill_charge_id));
        foreach ($billChargeDets as $billDet) {
            $noID = '0000000' . $id;
            $price = ($billDet->charge * $billDet->amount) - (($billDet->discount / 100) * ($billDet->charge * $billDet->amount));
            $detail .= '"TRX' . substr($noID, strlen($noID) - 5, 5) . '"|"' .
                    $billDet->BillCharge->code . '"|"' .
                    $billDet->Additional->type_transaction . '"|"' .
                    date('Ymd', strtotime($date)) . '000000"|"' .
                    $type_transaction[$billDet->Additional->type_transaction] . '"|"' .
                    $price . '"|"' .
                    '1" ';
            $id++;
        }
    }
}
echo $detail;
?>
