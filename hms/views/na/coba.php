<div style="text-align: right">
    <button class="print entypo-icon-printer button" onclick="printGl()" type="button">&nbsp;&nbsp;Print Report</button>
</div>
<div class="na_gl">
    <center><h3>GUEST LEDGER BALANCE</h3></center>
    <center>Date Night Audit : <?php echo date("d-M-Y", strtotime($model->date_na)); ?></center>
    <hr style="border-bottom: 2px solid #bbb !important;border-top: 1px solid #bbb !important;height: 3px">
    <table class="items table table-striped table-hover  table-condensed table-bordered">
        <thead>
            <tr>
                <th class="span1" style="text-align: left">NO</th>
                <th class="span2" style="text-align: left">ROOM NUMBER</th>
                <th class="span3" style="text-align: left">GUEST NAME</th>            
                <th class="span2" style="text-align: right">PREVIOUS</th>            
                <th class="span2" style="text-align: right">TOTAL REVENUE</th>                     
                <th class="span2" style="text-align: right">DEPOSITE</th>                     
                <th class="span2" style="text-align: right">TUNAI</th>                     
                <th class="span2" style="text-align: right">CREDIT CARD</th>                     
                <th class="span2" style="text-align: right">CITY LEDGER</th>                     
                <th class="span2" style="text-align: right">REFUND</th>                     
                <th class="span2" style="text-align: right">GUEST LEDGER</th>            
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
                $roomNumber = '';
                foreach ($filterRoomBill as $fRoomBill) {
                    if ($fRoomBill->RoomBill->registration_id == $r->registration_id) {
                        $roomNumber .= $fRoomBill->RoomBill->room_number . ' , ';
                    }
                }
                $roomNumber = substr($roomNumber, 0, strlen($roomNumber) - 3);

                echo '<tr>';
                echo '<td style="text-align:center">' . $no . '</td>';
                echo '<td style="text-align:left">' . $roomNumber . '</td>';
                echo '<td style="text-align:left">' . $r->Registration->Guest->name . $type . '</td>';
                echo '<td style="text-align:right">' . landa()->rp($r->prev,false) . '</td>';
                echo '<td style="text-align:right">' . landa()->rp($r->charge,false) . '</td>';
                echo '<td style="text-align:right">' . landa()->rp($r->deposite,false) . '</td>';
                echo '<td style="text-align:right">' . landa()->rp($r->tunai,false) . '</td>';
                echo '<td style="text-align:right">' . landa()->rp($r->creditcard,false) . '</td>';
                echo '<td style="text-align:right">' . landa()->rp($r->cityledger,false) . '</td>';
                echo '<td style="text-align:right">' . landa()->rp($r->refund,false) . '</td>';
                echo '<td style="text-align:right">' . landa()->rp($r->balance,false) . '</td>';
                echo '</tr>';
                $no++;
            }
            ?>
            <tr>
                <td colspan="3" style="text-align: right">Total :</td>
                <td style="text-align: right"><?php echo landa()->rp($totPrev,false) ?></td>                             
                <td style="text-align: right"><?php echo landa()->rp($totCharge,false) ?></td>                             
                <td style="text-align: right"><?php echo landa()->rp($totDeposite,false) ?></td>                             
                <td style="text-align: right"><?php echo landa()->rp($totTunai,false) ?></td>                             
                <td style="text-align: right"><?php echo landa()->rp($totCreditCard,false) ?></td>                             
                <td style="text-align: right"><?php echo landa()->rp($totCityLedger,false) ?></td>                             
                <td style="text-align: right"><?php echo landa()->rp($totRefund,false) ?></td>                
                <td style="text-align: right"><?php echo landa()->rp($totBalance,false) ?></td>                             
            </tr> 
        </tbody>
    </table>
    <table>
        <tr>
            <td style="padding: 0px;width: 30%" class="span2">Audit By</td>        
            <td style="padding: 0px">: <?php echo $model->Cashier->name; ?></td>
        </tr>    
        <tr>
            <td style="padding: 0px">Printed Time</td>        
            <td style="padding: 0px">: <?php echo date('l d-M-Y H:i:s', strtotime($model->created)); ?></td>
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
<script type="text/javascript">
        function printGl()
        {
            window.print();
        }
</script>

