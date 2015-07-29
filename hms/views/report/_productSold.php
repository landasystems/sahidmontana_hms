<div style="text-align: right">
    <button class="print entypo-icon-printer button" onclick="printReport()" type="button">&nbsp;&nbsp;Print Report</button>
</div>
<div class="product_sold">
    <?php
    if (!empty($model->charge_additional_category_id)) {
        $category = ChargeAdditionalCategory::model()->findByPk($model->charge_additional_category_id);
        $departementId = ' AND charge_additional_category_id="' . $category->id . '"';
        $departementName = $category->name;
        $category = ChargeAdditionalCategory::model()->findAll(array('condition' => 'id=' . $model->charge_additional_category_id));
    } else {
        $category = ChargeAdditionalCategory::model()->findAll(array('condition' => 'level=1'));
        $departementId = '';
        $departementName = 'All';
    }

    $sDate = $model->created;
    $date = explode('-', $sDate);
    $start = date("Y/m/d", strtotime($date[0]));
    $end = date("Y/m/d", strtotime($date[1])) . " 23:59:59";
    $account = Account::model()->findAll();
    ?>

    <center><h3>PRODUCT SOLD</h3></center>
    <center>Date : <?php echo date('d-M-Y', strtotime($start)) . ' - ' . date('d-M-Y', strtotime($end)) ?> <br>Departement : <?php echo $departementName ?></center>
    <hr>
    <table class="items table table-striped table-bordered  table-condensed">
        <?php
        $qtyAll = 0;
        $totAll = 0;
        foreach ($category as $departement) {
            ?>
            <thead>   
                <tr><th colspan="5" style="height:30px;vertical-align: middle">DEPARTEMENT : <?php echo strtoupper($departement->name); ?></th></tr>
                <tr>
                    <th class="span1" style="text-align: left;vertical-align: middle">No</th>
                    <th class="span5" style="text-align: left;vertical-align: middle">Product Name</th>                                                                                         
                    <th class="span3" style="text-align: center;vertical-align: middle">Sold</th>                            
                    <!--<th class="span5" style="text-align: right;vertical-align: middle">Price</th>-->   
                    <th class="span3" style="text-align: right;vertical-align: middle">Total</th>                                                
                </tr> 
            </thead> 
            <?php
            foreach ($account as $acc) {
                $nomer = 1;
                $total = 0;
                $qty = 0;
//                $detail = BillChargeDet::model()->with('BillCharge', 'Additional')->findAll(array('select' => '*,sum(amount) AS qty', 'group' => 'charge_additional_id', 'order' => 'qty DESC', 'condition' => '(date_format(BillCharge.created,"%Y/%m/%d") between "' . $start . '" AND "' . $end . '") and Additional.account_id=' . $acc->id . '  and BillCharge.charge_additional_category_id=' . $departement->id));
                $NaDetq = NaDet::model()->with('Na')->findAll(array('condition' => '(date_format(Na.date_na,"%Y/%m/%d") between "' . $start . '" AND "' . $end . '") and bill_charge_id != 0'));
                foreach ($NaDetq as $o) {
                    $arrNaDet[] = $o->bill_charge_id;
                }

                $detail = BillChargeDet::model()->with('BillCharge', 'Additional')->findAll(array('select' => '*,sum(amount) AS qty,sum(t.amount*t.charge - ((t.discount/100)* t.amount*t.charge)) as total ', 'group' => 'charge_additional_id', 'order' => 'qty DESC', 'condition' => 'BillCharge.id IN (' . implode(',', $arrNaDet) . ') and Additional.account_id=' . $acc->id . '  and BillCharge.charge_additional_category_id=' . $departement->id));
                if (count($detail) > 0) {
                    ?>
                    <tbody>
                        <tr>              
                            <td></td>
                            <td colspan="3" style="background: aliceblue"><?php echo strtoupper($acc->name); ?></td>
                        </tr>
                        <?php
                        foreach ($detail as $d) {
                            echo '<tr>';
                            echo '<td style="text-align:left">' . $nomer . '</td>';
                            echo '<td>' . $d->Additional->name . '</td>';
                            echo '<td style="text-align:center">' . $d->qty . '</td>';
//                            echo '<td style="text-align:right">' . landa()->rp($d->charge) . '</td>';
                            echo '<td style="text-align:right">' . landa()->rp($d->total) . '</td>';
                            echo '</tr>';
                            $nomer++;
                            $total += $d->total;
                            $qty += $d->qty;
                        }
                        ?>
                        <tr>           
                            <td colspan="2" style="text-align: right;vertical-align: middle">Total  :</td>            
                            <td style="text-align: center;vertical-align: middle"><?php echo $qty; ?></td>             
                            <td  colspan="1" style="text-align: right;vertical-align: middle"><?php echo landa()->rp($total); ?></td>             
                        </tr>                     
                    </tbody>

                    <?php
                    $totAll += $total;
                    $qtyAll += $qty;
                }
            }
            ?>
            <tr><td colspan="4">&nbsp;</td></tr>
            <?php
        }
        ?>
        <thead>  
            <tr>
                <th colspan="2" style="text-align: right;vertical-align: middle">Total All:</th>            
                <th style="text-align: center;vertical-align: middle"><?php echo $qtyAll; ?></th>               
                <th colspan="1" style="text-align: right;vertical-align: middle"><?php echo landa()->rp($totAll); ?></th>               
            </tr>
        </thead>
    </table>
</div>



<style type="text/css">
    @media print
    {
        body {visibility:hidden;}
        .product_sold{visibility: visible;} 
        .product_sold{width: 100%;top: 0px;left: 0px;position: absolute;} 

    }
</style>
<script type="text/javascript">
        function printReport()
        {
            window.print();
        }
</script>

