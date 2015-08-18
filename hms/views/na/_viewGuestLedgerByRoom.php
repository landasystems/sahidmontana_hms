<div style="text-align: right">
    <button class="print entypo-icon-printer button" onclick="printDiv('na_gl')" type="button">&nbsp;&nbsp;Print Report</button>
</div>
<div class="na_gl" id="na_gl">
    <center><h4>GUEST LEDGER BALANCE</h4></center>
    <center>Date Night Audit : <?php echo date("d-M-Y", strtotime($model->date_na)); ?></center>
    <hr style="border-bottom: 2px solid #bbb !important;border-top: 1px solid #bbb !important;height: 3px">
    <table class="tbPrint" style="font-size: 10px;line-height: 10px !important;">
        <thead>
            <tr>
                <th class="span1 judul print2" style="text-align: center;border-bottom:solid #000 2px !important;border-top:solid #000 2px">NO</th>
                <th class="span3 judul print2" style="text-align: center;border-bottom:solid #000 2px !important;border-top:solid #000 2px">ROOM NUMBER</th>
                <th class="span3 judul print2" style="text-align: center;border-bottom:solid #000 2px !important;border-top:solid #000 2px">GUEST NAME</th>            
                <th class="span2 judul print2" style="text-align: center;border-bottom:solid #000 2px !important;border-top:solid #000 2px">COMPANY</th>            
                <th class="span2 judul print2" style="text-align: center;border-bottom:solid #000 2px !important;border-top:solid #000 2px">PREVIOUS</th>            
                <th class="span2 judul print2" style="text-align: center;border-bottom:solid #000 2px !important;border-top:solid #000 2px">TOTAL REVENUE</th>                     
                <th class="span2 judul print2" style="text-align: center;border-bottom:solid #000 2px !important;border-top:solid #000 2px">DEPOSITE</th>                     
                <th class="span2 judul print2" style="text-align: center;border-bottom:solid #000 2px !important;border-top:solid #000 2px">CASH</th>                     
                <th class="span2 judul print2" style="text-align: center;border-bottom:solid #000 2px !important;border-top:solid #000 2px">CREDIT CARD</th>                     
                <th class="span2 judul print2" style="text-align: center;border-bottom:solid #000 2px !important;border-top:solid #000 2px">CITY LEDGER</th>                     
                <th class="span2 judul print2" style="text-align: center;border-bottom:solid #000 2px !important;border-top:solid #000 2px">REFUND</th>                     
                <th class="span2 judul print2" style="text-align: center;border-bottom:solid #000 2px !important;border-top:solid #000 2px">GUEST LEDGER</th>            
            </tr>                        
        </thead>
        <tbody>
            <?php
            $no = 1;
            $totPrev = 0;
            $totCharge = 0;
            $totBalance = 0;
            $totDeposite = 0;
            $totTunai = 0;
            $totCreditCard = 0;
            $totCityLedger = 0;
            $totRefund = 0;


            foreach ($naGl as $r) {
                $type = ($r->charge == 0) ? '' : '';
                $totPrev += $r->prev;
                $totCharge += $r->charge;
                $totBalance += $r->balance;
                $totDeposite += $r->deposite;
                $totTunai += $r->tunai;
                $totCreditCard += $r->creditcard;
                $totCityLedger += $r->cityledger;
                $totRefund += $r->refund;

                $filterRoomBill = array_filter($naDet, function($naDet) {
                    return $naDet['room_bill_id'] != 0;
                });

                if ($r->room_number == "") {
                    $roomNumber = '';
                    $arrRoomNumber = array();
                    foreach ($filterRoomBill as $fRoomBill) {
                        if ($fRoomBill->RoomBill->registration_id == $r->registration_id && !in_array($fRoomBill->RoomBill->room_number, $arrRoomNumber)) {
                            $roomNumber .= $fRoomBill->RoomBill->room_number . ' , ';
                            array_push($arrRoomNumber, $fRoomBill->RoomBill->room_number);
                        }
                    }
                    $roomNumber = substr($roomNumber, 0, strlen($roomNumber) - 3);
                    $guestName = (isset($r->Registration->Guest->guestName)) ? $r->Registration->Guest->guestNam : '';
                    $company = (isset($r->Registration->Guest->company)) ? strtoupper($r->Registration->Guest->company) : '';
                } else {
                    $roomNumber = $r->room_number;
                    if (isset($r->Guest->guestName)) { //jika ada guest user id , belum checkout
                        $guestName = $r->Guest->guestName;
                        $company = strtoupper($r->Guest->company);
                    } else { //jika tidak ada, berarti sudah checkout
                        $guestName = $r->Bill->pax_name;
                        $company = '';
                    }
                }

                echo '<tr>';
                echo '<td class="isi print2" style="text-align:center;border-bottom:none;border-top:none">' . $no . '</td>';
                echo '<td class="isi print2" style="text-align:left;border-bottom:none;border-top:none">' . $roomNumber . '</td>';
                echo '<td class="isi print2" style="text-align:left;border-bottom:none;border-top:none">' . $guestName . '</td>';
                echo '<td class="isi print2" style="text-align:left;border-bottom:none;border-top:none">' . $company . '</td>';
                echo '<td class="isi print2" style="text-align:right;border-bottom:none;border-top:none">' . landa()->rp($r->prev, false) . '</td>';
                echo '<td class="isi print2" style="text-align:right;border-bottom:none;border-top:none">' . landa()->rp($r->charge, false) . '</td>';
                echo '<td class="isi print2" style="text-align:right;border-bottom:none;border-top:none">' . landa()->rp($r->deposite, false) . '</td>';
                echo '<td class="isi print2" style="text-align:right;border-bottom:none;border-top:none">' . landa()->rp($r->tunai, false) . '</td>';
                echo '<td class="isi print2" style="text-align:right;border-bottom:none;border-top:none">' . landa()->rp($r->creditcard, false) . '</td>';
                echo '<td class="isi print2" style="text-align:right;border-bottom:none;border-top:none">' . landa()->rp($r->cityledger, false) . '</td>';
                echo '<td class="isi print2" style="text-align:right;border-bottom:none;border-top:none">' . landa()->rp($r->refund, false) . '</td>';
                echo '<td class="isi print2" style="text-align:right;border-bottom:none;border-top:none">' . landa()->rp($r->balance, false) . '</td>';
                echo '</tr>';
                $no++;
            }
            ?>
            <tr>
                <td colspan="4" style="text-align: right">Total :</td>
                <td style="text-align: right"><?php echo landa()->rp($totPrev, false) ?></td>                             
                <td style="text-align: right"><?php echo landa()->rp($totCharge, false) ?></td>                             
                <td style="text-align: right"><?php echo landa()->rp($totDeposite, false) ?></td>                             
                <td style="text-align: right"><?php echo landa()->rp($totTunai, false) ?></td>                             
                <td style="text-align: right"><?php echo landa()->rp($totCreditCard, false) ?></td>                             
                <td style="text-align: right"><?php echo landa()->rp($totCityLedger, false) ?></td>                             
                <td style="text-align: right"><?php echo landa()->rp($totRefund, false) ?></td>                
                <td style="text-align: right"><?php echo landa()->rp($totBalance, false) ?></td>                             
            </tr> 
        </tbody>
    </table>
    <br>
    <table>
        <tr>
            <td style="padding: 0px;width: 30%;font-size: 10px" class="span2">Audit By</td>        
            <td style="padding: 0px;font-size: 10px">: <?php echo $model->Cashier->name; ?></td>
        </tr>    
        <tr>
            <td style="padding: 0px;font-size: 10px">Printed Time</td>        
            <td style="padding: 0px;font-size: 10px">: <?php echo date('l d-M-Y H:i:s', strtotime($model->created)); ?></td>
        </tr>    
    </table>
</div>

<style type="text/css">

    @media print
    {
        body {visibility:hidden;}
        .na_gl{visibility: visible;} 
        .na_gl{width: 100%;top: 0px;left: 0px;position: absolute;font-size: 9px !important} 

    }
</style>
