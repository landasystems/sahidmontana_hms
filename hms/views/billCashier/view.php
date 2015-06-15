<?php
$this->setPageTitle('View Bill Cashiers | ID : ' . $model->id);
$this->breadcrumbs = array(
    'Bill Cashiers' => array('index'),
    $model->id,
);
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
//                array('label'=>'Edit', 'icon'=>'icon-edit', 'url'=>Yii::app()->controller->createUrl('update',array('id'=>$model->id)), 'linkOptions'=>array()),
        //array('label'=>'Pencarian', 'icon'=>'icon-search', 'url'=>'#', 'linkOptions'=>array('class'=>'search-button')),
        array('label' => 'Print', 'icon' => 'icon-print', 'url' => 'javascript:void(0);return false', 'linkOptions' => array('onclick' => 'printDiv();return false;')),
        array('label' => 'Export ke Excel', 'icon' => 'icon-download', 'url' => Yii::app()->controller->createUrl('GenerateExcelCashierSheet?bill_cashier_id=' . $model->id), 'linkOptions' => array('target' => '_blank'), 'visible' => true),
)));
$this->endWidget();
?>
<div class='printableArea'>
    <style>
        th {
            vertical-align: middle !important;
            text-align: center !important;
        }
    </style>
    <div class="form">
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



        <legend style="text-align: center">
            <h3 style="padding: 0px">CASHIER SHEET</h3>
            <p class="note"><?php echo date("D, d F Y", strtotime($model->created)); ?></p>
        </legend>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th class="span1" rowspan="2">No.</th>
                    <th class="span2" rowspan="2">Type</th>
                    <th class="span2" rowspan="2">Code Transaction</th>                    
                    <th class="span2" colspan="2">Cash</th>
                    <th class="span2" rowspan="2">G. Ledger</th>
                    <th class="span2" rowspan="2">C. Card</th>
                    <th class="span2" rowspan="2">C/A</th>                                                        
                    <th class="span2" rowspan="2">Remarks</th>                                                        
                </tr>
                <tr >
                    <th class="span2">Tunai</th>
                    <th class="span2">Refund DP</th>                                                    
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
                        $cash = ($bill->total > 0) ? $bill->cash - $bill->refund : $bill->cash;
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
                        $row .= '<td style="text-align:right">' . landa()->rp($cash, false) . '</td>';
                        $row .= '<td style="text-align:right">' . landa()->rp($refund, false) . '</td>';
                        $row .= '<td style="text-align:right">' . landa()->rp(0, false) . '</td>';
                        $row .= '<td style="text-align:right">' . landa()->rp($credit, false) . '</td>';
                        $row .= '<td style="text-align:right">' . landa()->rp($ca, false) . '</td>';
                        $row .= '<td style="text-align:left">' . $bill->description . '</td>';
                        $row .= '</tr>';
                        echo $row;
                        $no++;
                    } elseif ($det->deposite_id!=0) {
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
                        $tipe = "Guest Deposite";
//                        $tipe = ($dp->is_walk_in_cash == 1) ? "Walk In Cash" : "Guest Deposite";

                        $row = '<tr>';
                        $row .= '<td style="text-align:center">' . $no . '</td>';
                        $row .= '<td style="text-align:left">' . $tipe . '</td>';
                        $row .= '<td style="text-align:left">' . $dp->code . '</td>';
                        $row .= '<td style="text-align:right">' . landa()->rp($cash, false) . '</td>';
                        $row .= '<td style="text-align:right">' . landa()->rp($refund, false) . '</td>';
                        $row .= '<td style="text-align:right">' . landa()->rp(0, false) . '</td>';
                        $row .= '<td style="text-align:right">' . landa()->rp($credit, false) . '</td>';
                        $row .= '<td style="text-align:right">' . landa()->rp($ca, false) . '</td>';
                        $row .= '<td style="text-align:left">' . $dp->description . '</td>';
                        $row .= '</tr>';
                        echo $row;
                        $no++;
                    } elseif ($det->bill_charge_id!=0) {
                        //************** guest transaction *******************//
                        $trans = BillCharge::model()->findByPk($det->bill_charge_id);                        
                        $type = $category[$trans->ChargeAdditionalCategory->root];

                        $cash = ($trans->total > 0) ? $trans->cash - $trans->refund : $trans->cash;
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
                        $row .= '<td style="text-align:right">' . landa()->rp($cash, false) . '</td>';
                        $row .= '<td style="text-align:right">' . landa()->rp($refund, false) . '</td>';
                        $row .= '<td style="text-align:right">' . landa()->rp($gl, false) . '</td>';
                        $row .= '<td style="text-align:right">' . landa()->rp($credit, false) . '</td>';
                        $row .= '<td style="text-align:right">' . landa()->rp($ca, false) . '</td>';
                        $row .= '<td style="text-align:left">' . $trans->description . '</td>';
                        $row .= '</tr>';
                        echo $row;
                        $no++;
                    }
                }
                ?>


                <tr>
                    <th style="text-align: left !important" colspan="3"><b>TOTAL</b></th>                            
                    <th style="text-align: right !important"><?php echo landa()->rp($tot_cash, false); ?></th>
                    <th style="text-align: right !important"><?php echo landa()->rp($tot_refund, false); ?></th>
                    <th style="text-align: right !important"><?php echo landa()->rp($tot_gl, false); ?></th>
                    <th style="text-align: right !important"><?php echo landa()->rp($tot_credit, false); ?></th>
                    <th style="text-align: right !important"><?php echo landa()->rp($tot_ca, false); ?></th>
                    <th style="text-align: right !important"></th>                            
                </tr>

            </tbody>
        </table>  
        <table style="width:100%">
            <tr>
                <td class="span6" style="width:50%">Prepared By : <?php echo $model->Cashier->name; ?></td>
                <td class="span6" style="width:50%">Approved By : <?php echo $approved; ?></td>                        
            </tr>
        </table>


        <?php $this->endWidget(); ?>
        <!--        </div>
            </div>-->

    </div>


</div>
<style type="text/css">
    @media print
    {
        body {visibility:hidden;}
        .printableArea{visibility: visible;}         
        .printableArea{width: 100%;top: 0px;left: 0px;position: absolute;} 
        .printableArea table td,tr,thead,.table,legend{
            margin: 0px;
            padding:3px;
            line-height: 12px;            
            font-size: 10px;        

        }
        .table th, .table td,thead{
            margin: 0px;
            padding:3px;
            line-height: 12px;            
            font-size: 10px;        

        }

    }
</style>
<script type="text/javascript">
    function printDiv()
    {

        window.print();

    }
</script>
