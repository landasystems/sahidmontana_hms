<?php
$this->setPageTitle('View Bill Cashiers  Approval| ID : ' . $model->id);

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
//        array('label' => 'Create', 'icon' => 'icon-plus', 'url' => Yii::app()->controller->createUrl('create'), 'linkOptions' => array()),
        array('label' => 'List Data', 'icon' => 'icon-th-list', 'url' => Yii::app()->controller->createUrl('approving'), 'linkOptions' => array()),
//                array('label'=>'Edit', 'icon'=>'icon-edit', 'url'=>Yii::app()->controller->createUrl('update',array('id'=>$model->id)), 'linkOptions'=>array()),
        //array('label'=>'Pencarian', 'icon'=>'icon-search', 'url'=>'#', 'linkOptions'=>array('class'=>'search-button')),
        array('label' => 'Print', 'icon' => 'icon-print', 'url' => 'javascript:void(0);return false', 'linkOptions' => array('onclick' => 'printDiv();return false;')),
)));
$this->endWidget();
?>

<?php
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id' => 'bill-cashier-form',
    'enableAjaxValidation' => false,
    'method' => 'post',
    'type' => 'horizontal',
    'htmlOptions' => array(
        'enctype' => 'multipart/form-data'
    )
        ));
?>
<div class='printableArea'>
    <style>
        th {
            vertical-align: middle !important;
            text-align: center !important;
        };
    </style>
    <div class="form">
        <legend style="text-align: center">
            <h3 style="padding: 0px">CASHIER SHEET</h3>
            <p class="note" style="font-size: 15px"><?php echo date("l Y-F-d"); ?></p>
        </legend>

        <table class="table ">
            <thead>
                <tr>
                    <th class="span1" rowspan="2">No.</th>
                    <th class="span2" rowspan="2">Type</th>
                    <th class="span2" rowspan="2">Code Transaction</th>                    
                    <th class="span2" colspan="2">Cash</th>
                    <th class="span2" rowspan="2">Guest Ledger</th>
                    <th class="span2" rowspan="2">Credit Card</th>
                    <th class="span2" rowspan="2">C/A</th>                                                        
                    <th class="span2" rowspan="3">Remarks</th>                                                        
                </tr>
                <tr >
                    <th class="span2">Tunai</th>
                    <th class="span2">Refund</th>                                                    
                </tr>                        

            </thead>
            <tbody>
                <?php
                $detail = BillCashierDet::model()->findAll(array('condition' => 'bill_cashier_id=' . $model->id));
                $category = chtml::listData(ChargeAdditionalCategory::model()->findAll(), 'id', 'name');
                $approved = (!empty($model->approved_user_id)) ? $model->Approved->name : '';
                $tot_cash = 0;
                $tot_refund = 0;
                $tot_credit = 0;
                $tot_ca = 0;
                $tot_gl = 0;
                $no = 1;
                foreach ($detail as $det) {
                    if (!empty($det->bill_id)) {
                        //************** guest billing *******************//
                        $bill = Bill::model()->findByPk($det->bill_id);
                        $cash = $bill->cash;
                        $refund = ($bill->total < 0 && $bill->refund > 0) ? $bill->refund : 0;
                        $credit = $bill->cc_charge;
                        $ca = $bill->ca_charge;

                        $tot_cash += $cash;
                        $tot_refund += ($bill->total < 0 && $bill->refund > 0) ? $refund : 0;
                        $tot_credit += $credit;
                        $tot_ca += $ca;

                        $row = '<tr>';
                        $row .= '<td style="text-align:center">' . $no . '</td>';
                        $row .= '<td style="text-align:left">' . 'Guest Checkedout' . '</td>';
                        $row .= '<td style="text-align:left">' . $bill->code . '</td>';
                        $row .= '<td style="text-align:right">' . landa()->rp($cash) . '</td>';
                        $row .= '<td style="text-align:right">' . landa()->rp($refund) . '</td>';
                        $row .= '<td style="text-align:right">' . landa()->rp(0) . '</td>';
                        $row .= '<td style="text-align:right">' . landa()->rp($credit) . '</td>';
                        $row .= '<td style="text-align:right">' . landa()->rp($ca) . '</td>';
                        $row .= '<td style="text-align:left">' . $bill->description . '</td>';
                        $row .= '</tr>';
                        echo $row;
                        $no++;
                    } elseif (!empty($det->deposite_id)) {
                        //************** guest deposite *******************//
                        $dp = Deposite::model()->findByPk($det->deposite_id);
                        if ($dp->dp_by == 'cash') {
                            $cash = $dp->amount;
                            $refund = 0;
                            $credit = 0;
                            $ca = 0;
                        } else {
                            $cash = 0;
                            $refund = 0;
                            $credit = $dp->amount;
                            $ca = 0;
                        }


                        $tot_cash += $cash;
                        $tot_refund += 0;
                        $tot_credit += $credit;
                        $tot_ca += $ca;

                        $row = '<tr>';
                        $row .= '<td style="text-align:center">' . $no . '</td>';
                        $row .= '<td style="text-align:left">' . 'Guest Deposite' . '</td>';
                        $row .= '<td style="text-align:left">' . $dp->code . '</td>';
                        $row .= '<td style="text-align:right">' . landa()->rp($cash) . '</td>';
                        $row .= '<td style="text-align:right">' . landa()->rp($refund) . '</td>';
                        $row .= '<td style="text-align:right">' . landa()->rp(0) . '</td>';
                        $row .= '<td style="text-align:right">' . landa()->rp($credit) . '</td>';
                        $row .= '<td style="text-align:right">' . landa()->rp($ca) . '</td>';
                        $row .= '<td style="text-align:left">' . $dp->description . '</td>';
                        $row .= '</tr>';
                        echo $row;
                        $no++;
                    } elseif (!empty($det->bill_charge_id)) {
                        //************** guest transaction *******************//
                        $trans = BillCharge::model()->findByPk($det->bill_charge_id);
                        $transDet = BillChargeDet::model()->findByAttributes(array('bill_charge_id' => $trans->id));
                        $type = $category[$transDet->Additional->ChargeAdditionalCategory->root];

                        $cash = $trans->cash;
                        $refund = 0;
                        $credit = $trans->cc_charge;
                        $ca = $trans->ca_charge;
                        $gl = $trans->gl_charge;



                        $tot_cash += $cash;
                        $tot_refund += 0;
                        $tot_credit += $credit;
                        $tot_ca += $ca;
                        $tot_gl += $gl;

                        $row = '<tr>';
                        $row .= '<td style="text-align:center">' . $no . '</td>';
                        $row .= '<td style="text-align:left">' . $type . '</td>';
                        $row .= '<td style="text-align:left">' . $trans->code . '</td>';
                        $row .= '<td style="text-align:right">' . landa()->rp($cash) . '</td>';
                        $row .= '<td style="text-align:right">' . landa()->rp($refund) . '</td>';
                        $row .= '<td style="text-align:right">' . landa()->rp($gl) . '</td>';
                        $row .= '<td style="text-align:right">' . landa()->rp($credit) . '</td>';
                        $row .= '<td style="text-align:right">' . landa()->rp($ca) . '</td>';
                        $row .= '<td style="text-align:left">' . $trans->description . '</td>';
                        $row .= '</tr>';
                        echo $row;
                        $no++;
                    }
                }
                ?>


                <tr style="height:40px !important;">
                    <th style="text-align: left !important" colspan="3"><b>TOTAL</b></th>                            
                    <th style="text-align: right !important"><?php echo landa()->rp($tot_cash); ?></th>
                    <th style="text-align: right !important"><?php echo landa()->rp($tot_refund); ?></th>
                    <th style="text-align: right !important"><?php echo landa()->rp($tot_gl); ?></th>
                    <th style="text-align: right !important"><?php echo landa()->rp($tot_credit); ?></th>
                    <th style="text-align: right !important"><?php echo landa()->rp($tot_ca); ?></th>
                    <th style="text-align: right !important"></th>                            
                </tr>

            </tbody>
        </table>  
        <table>
            <tr>
                <td class="span6">Prepared By : <?php echo Yii::app()->user->name; ?></td>
                <td class="span6">Approved By : <?php echo $approved; ?></td>                        
            </tr>
        </table>
    </div>


</div>


<div class="form-actions" style="padding-left: 20px">            
    <button class="btn btn-primary" type="submit" name="approve"><i class="icon-ok icon-white"></i> Approve This Cashier Sheet</button>
    <button class="btn btn-danger" type="submit" name="reject"><i class="icon-remove icon-white"></i> Reject This Cashier Sheet</button>
</div>

<?php $this->endWidget(); ?>
<style type="text/css" media="print">
    body {visibility:hidden;}
    .invoice{visibility:visible} 
    .invoice{margin:0px !important;} 
    .invoice{padding:0px !important;} 
    .invoice{top:0px !important;} 
    .invoice{left:0px !important;} 
    .invoice {overflow-y: visible}

</style>
<script type="text/javascript">
    function printDiv()
    {

        window.print();

    }
</script>
