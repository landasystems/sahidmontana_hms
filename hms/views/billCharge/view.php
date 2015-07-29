<?php
$category = ChargeAdditionalCategory::model()->findAll(array('index' => 'id'));
$id = $model->charge_additional_category_id;
$this->setPageTitle('View Bill Charges | ID : ' . $model->id);
$this->breadcrumbs = array(
    'Bill Charges' => array('index'),
    $model->id,
);
$departement = '';
?>

<?php
$this->beginWidget('zii.widgets.CPortlet', array(
    'htmlOptions' => array(
        'class' => ''
    )
));
//$print = ($model->is_temp == 0) ? "printDiv();" : "alert('Sorry! Transaction not paid yet. Please save it first.');";
$print = "printDiv();";
$this->widget('bootstrap.widgets.TbMenu', array(
    'type' => 'pills',
    'items' => array(
        array('label' => 'Create', 'icon' => 'icon-plus', 'url' => Yii::app()->controller->createUrl('create'), 'linkOptions' => array()),
        array('label' => 'List Data', 'icon' => 'icon-th-list', 'url' => Yii::app()->controller->createUrl('index'), 'linkOptions' => array()),
        array('label' => 'Edit', 'icon' => 'icon-edit', 'url' => Yii::app()->controller->createUrl('update', array('id' => $model->id)), 'linkOptions' => array()),
        //array('label'=>'Pencarian', 'icon'=>'icon-search', 'url'=>'#', 'linkOptions'=>array('class'=>'search-button')),        
        array('label' => 'Print', 'icon' => 'icon-print', 'url' => 'javascript:void(0);return false', 'linkOptions' => array('onclick' => $print . 'return false;')),
)));
$this->endWidget();
?>
<?php
foreach (Yii::app()->user->getFlashes() as $key => $message) {
    echo '<div class="taro alert alert-' . $key . '">' . $message . '</div>';
}
?>


<div class="form">
    <?php
    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id' => 'bill-charge-form',
        'enableAjaxValidation' => false,
        'method' => 'post',
        'type' => 'horizontal',
        'htmlOptions' => array(
            'enctype' => 'multipart/form-data'
        )
    ));
    ?>
    <fieldset>
        <div class="box gradient invoice">
            <div class="title clearfix">
                <h4 class="left">
                    <span class="blue cut-icon-bookmark"></span>
                    <span>Cash Carge</span>                        
                </h4>
                <div class="invoice-info">
                    <span class="number"> <strong class="red">
                            <?php
                            echo $model->code;
                            ?>                            
                        </strong></span><br>
                    <span class="data gray"><?php echo date('d M Y', strtotime($model->created)) ?></span>
                </div> 
            </div>

            <div class="content">   
                <table style="width:100%">
                    <tr>
                        <td style="width:150px">Departement </td>
                        <td  style="width:5px">:</td>
                        <td >
                            <?php
                            $departement = Chtml::listdata(ChargeAdditionalCategory::model()->findAll(), 'id', 'name');
                            echo CHtml::dropDownList('BillCharge[charge_additional_category_id]', $model->charge_additional_category_id, $departement, array('id' => 'departement', 'class' => 'span3', 'disabled' => true, 'empty' => 'Please Choose',
                                'ajax' => array(
                                    'type' => 'POST',
                                    'url' => url('billCharge/selectDepartement'),
                                    'data' => array('departement_id' => 'js:this.value'),
                                    'success' => 'function(data) {
                                        $("#content_table").html(data);
                                    }
                        ',
                            )));
                            ?>
                        </td>                                
                    </tr>                                                     
                    <tr>
                        <td >Date </td>
                        <td  style="width:5px">:</td>
                        <?php
                        $date = (!empty($model->created)) ? date('l Y-F-d H:i:s', strtotime($model->created)) : date('l Y-F-d H:i:s');
                        ?>
                        <td ><?php echo $date; ?></td>                                
                    </tr>  <tr>
                        <td >Cover </td>
                        <td  style="width:5px">:</td>                       
                        <td ><?php echo Chtml::textField('BillCharge[cover]', $model->cover, array('disabled' => true, 'class' => 'span3')); ?></td>                                
                    </tr>                           
                    <tr>
                        <td >Note </td>
                        <td  style="width:5px">:</td>
                        <td >
                            <?php echo Chtml::textArea('BillCharge[description]', $model->description, array('disabled' => true, 'style' => 'width:100%')); ?>
                        </td>                                
                    </tr>                            
                </table>                     


                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th style="width: 15px;text-align:center">#</th>
                            <th class="span4" style="text-align:center">Item Name</th>                             
                            <th class="span1" style="text-align:center">Amount</th>                                 
                            <th class="span2" style="text-align:center">Charge</th>                                 
                            <th class="span1" style="text-align:center">Discount</th>                                 
                            <th class="span2" style="text-align:center">Subtotal</th>                                 
                        </tr>
                    </thead>
                    <tbody>     


                        <?php
                        if ($model->isNewRecord == FALSE) {
                            $details = BillChargeDet::model()->findAll(array('condition' => 'bill_charge_id=' . $model->id));
                            $i = 1;
                            foreach ($details as $detail) {
                                if ($detail->deposite_id != 0) {
                                    echo '                                                  
                                        <tr class="items">                       
                                            <td style="text-align:center">' . $i . '</td>
                                            <td> &nbsp;&nbsp;&raquo; [' . $detail->Deposite->code . '] ' . $detail->Deposite->Guest->guestName . '</td>
                                            <td style="text-align:center"> </td>
                                            <td style="text-align:right">' . landa()->rp($detail->deposite_amount) . '</td>                                                        
                                            <td style="text-align:right"> </td>                                                        
                                            <td style="text-align:right">' . landa()->rp($detail->deposite_amount) . '</td>                                                        
                                                
                                        </tr>
                                         <tr id="addRow" style="display:none">
                                        </tr>                       
                                        ';
                                    $i++;
                                } else {
                                    $departement = (isset($detail->Additional->ChargeAdditionalCategory->root)) ? $category[$detail->Additional->ChargeAdditionalCategory->root]['name'] : '';
                                    $fullCat = (isset($detail->Additional->fullInitialCategory)) ? $detail->Additional->fullInitialCategory : '';
                                    echo '                                                  
                                        <tr class="items">                       
                                            <td style="text-align:center">' . $i . '</td>
                                            <td> &nbsp;&nbsp;&raquo; ' . $fullCat . '</td>
                                            <td style="text-align:center">' . $detail->amount . '</td>
                                            <td style="text-align:right">' . landa()->rp($detail->charge) . '</td>                                                        
                                            <td style="text-align:right">' . $detail->discount . ' % </td>                                                        
                                            <td style="text-align:right">' . landa()->rp(($detail->charge * $detail->amount) - round(($detail->discount / 100) * ($detail->charge * $detail->amount))) . '</td>                                                        
                                                
                                        </tr>
                                         <tr id="addRow" style="display:none">
                                        </tr>                       
                                        ';
                                    $i++;
                                }
                            }
                        }
                        ?>




                        <tr id="addRow" style="display:none">          
                        </tr>
                        <tr>
                            <td colspan="5" style="text-align: right">
                                <b>Grand Total :</b>                    
                                <input type="hidden" id ="BillCharge_total" name="BillCharge[total]" value="<?php echo $model->total ?>" />
                            </td>
                            <td style="text-align:right"><span id="total"><?php echo (!empty($model->total)) ? landa()->rp($model->total) : '' ?></span></td>
                        </tr>                                
                        <tr class="cash">
                            <td colspan="5" style="text-align: right">
                                <b>Cash :</b>                                    
                            </td>

                            <td style="text-align:right">
                                <div class="input-prepend"><span class="add-on">Rp</span>
                                    <?php echo Chtml::textField('BillCharge[cash]', $model->cash, array('disabled' => true, 'class' => 'span2')); ?>
                                </div>
                            </td>
                        </tr>
                        <tr class="cc debit">
                            <td colspan="3" style="text-align: right">
                                <b>Credit Card Number :</b>                                    
                            </td>
                            <td style="text-align: right">
                               <?php echo Chtml::textField('BillCharge[cc_number]', $model->cc_number, array('disabled' => true, 'class' => 'span2')); ?>
                            </td>
                            <td  style="text-align: right">
                                <b>Credit Card :</b>                                    
                            </td>
                            <td style="text-align:right">
                               <div class="input-prepend"><span class="add-on">Rp</span>
                                    <?php echo Chtml::textField('BillCharge[cc_charge]', $model->cc_charge, array('disabled' => true, 'class' => 'span2')); ?>
                                </div>
                            </td>
                        </tr>
                        <tr class="cc debit">
                            <td colspan="3" style="text-align: right">
                                <b>Guest Ledger Name :</b>                                    
                            </td>
                            <td style="text-align: right">
                               <?php
                                $roomnumber = (isset($model->RoomBill->room_number)) ? $model->RoomBill->room_number :'';
                                echo'<input disabled="disabled" class="span2" type="text" value="'.$roomnumber.'" name="BillCharge[cash]" id="BillCharge_cash">';
                                ?>                      
                            </td>
                            <td  style="text-align: right">
                                <b>Guest Ledger :</b>                                    
                            </td>
                            <td style="text-align:right">
                               <div class="input-prepend"><span class="add-on">Rp</span>
                                    <?php echo Chtml::textField('BillCharge[gl_charge]', $model->gl_charge, array('disabled' => true, 'class' => 'span2')); ?>
                                </div> 
                            </td>
                        </tr>
                        <tr class="cc debit">
                            <td colspan="3" style="text-align: right">
                                <b>City Ledger Name :</b>                                    
                            </td>
                            <td style="text-align: right">
                                <?php
                                 $cl2 = (isset($model->CityLedger->name)) ? $model->CityLedger->name :'';
                                echo'<input disabled="disabled" class="span2" type="text" value="'.$cl2.'" name="BillCharge[cash]" id="BillCharge_cash">';
                                ?>                
                            </td>
                            <td  style="text-align: right">
                                <b>City Ledger :</b>                                    
                            </td>
                            <td style="text-align:right">
                               <div class="input-prepend"><span class="add-on">Rp</span>
                                    <?php echo Chtml::textField('BillCharge[ca_charge]', $model->ca_charge, array('disabled' => true, 'class' => 'span2')); ?>
                                </div>
                            </td>
                        </tr>



                        <tr>
                            <td colspan="5" style="text-align: right">
                                <b>Refund :</b>                                    
                            </td>

                            <td style="text-align:right">
                               <div class="input-prepend"><span class="add-on">Rp</span>
                                    <?php echo Chtml::textField('BillCharge[refund]', $model->refund, array('class' => 'span2', 'readOnly' => true)); ?>
                                </div>
                            </td>
                        </tr>   

                    </tbody>
                </table>  
            </div>
        </div>            
    </fieldset>

    <?php $this->endWidget(); ?>

</div>


<div class='printableArea'> 
    <?php
    $siteConfig = SiteConfig::model()->listSiteConfig();
    $content = $siteConfig->report_bill_charge;
    $content = str_replace('{departement}', strtoupper($departement), $content);
    $content = str_replace('{invoice}', $model->code, $content);
    $content = str_replace('{date}', date('d F Y H:i:s', strtotime($model->created)), $content);
    $content = str_replace('{desc}', $model->description, $content);
    $content = str_replace('{cashier}', ucwords($model->Cashier->name), $content);

    $det = '<table style="width:100%">';
    foreach ($details as $detail) {
        if ($detail->deposite_id != 0) {
            $price = $detail->charge * $detail->amount - (($detail->discount / 100) * $detail->charge * $detail->amount);
            $det.= '            
             <tr>                       
                <td style=";padding:3px 3px;vertical-align:top;width:40%">' . landa()->rp($detail->deposite_amount) . '</td>                                                                     
                <td style="text-align:right;padding:3px 3px;vertical-align:top;width:20%">' . $detail->discount . '% </td>                                                        
                <td style="text-align:right;padding:3px 3px;vertical-align:top;width:40%">' . landa()->rp($price) . '</td>                                                        
             </tr>
             
            <tr>                       
                <td style=";padding:3px 3px;vertical-align:top;" colspan="3">[' . $detail->Deposite->code . '] ' . $detail->Deposite->Guest->guestName . '</td>                                                                     
             </tr>
             

            ';
        } else {
            $price = $detail->charge * $detail->amount - round(($detail->discount / 100) * $detail->charge * $detail->amount);
            $addName = (isset($detail->Additional->name)) ? $detail->Additional->name : '';
            $det.= '
            
             <tr>                       
                <td style=";padding:3px 3px;vertical-align:top;width:40%">' . $detail->amount . ' x ' . landa()->rp($detail->charge) . '</td>                                                                     
                <td style="text-align:right;padding:3px 3px;vertical-align:top;width:20%">' . $detail->discount . '% </td>                                                        
                <td style="text-align:right;padding:3px 3px;vertical-align:top;width:40%">' . landa()->rp($price) . '</td>                                                        
             </tr>
             
            <tr>                       
                <td style=";padding:3px 3px;vertical-align:top;" colspan="3">' . $addName . '</td>                                                                     
             </tr>
             

            ';
        }
    }
    $ca_name = ($model->ca_user_id == 0 || empty($model->ca_user_id)) ? '' : $model->CityLedger->name;
    $gl_name = ($model->gl_room_bill_id == 0 || empty($model->gl_room_bill_id)) ? '' : $model->RoomBill->room_number;
    $det.= '<tr><td colspan="2" style="text-align:right;padding:3px 3px;" >Total :</td><td style="text-align:right;padding:3px 3px;">' . landa()->rp($model->total) . '</td></tr>';
    if ($model->cash != 0) {
        $det.= '<tr><td colspan="2" style="text-align:right;padding:3px 3px;" >Cash :</td><td style="text-align:right;padding:3px 3px;">' . landa()->rp($model->cash) . '</td></tr>';
    }
    if ($model->cc_charge != 0) {
        $det.= '<tr><td colspan="2" style="text-align:right;padding:3px 3px;" >Credit :</td><td style="text-align:right;padding:3px 3px;">' . landa()->rp($model->cc_charge) . '</td></tr>';
        $det.= '<tr><td colspan="2" style="text-align:right;padding:3px 3px;" >CC Numb :</td><td style="text-align:right;padding:3px 3px;">' . $model->cc_number . '</td></tr>';
    }
    if ($model->gl_charge != 0) {
        $det.= '<tr><td colspan="2" style="text-align:right;padding:3px 3px;" >GL :</td><td style="text-align:right;padding:3px 3px;">' . landa()->rp($model->gl_charge) . '</td></tr>';
        $det.= '<tr><td colspan="2" style="text-align:right;padding:3px 3px;" >GL Room :</td><td style="text-align:right;padding:3px 3px;">' . $gl_name . '</td></tr>';
    }
    if ($model->ca_charge != 0) {
        $det.= '<tr><td colspan="2" style="text-align:right;padding:3px 3px;" >CL :</td><td style="text-align:right;padding:3px 3px;">' . landa()->rp($model->ca_charge) . '</td></tr>';
        $det.= '<tr><td colspan="2" style="text-align:right;padding:3px 3px;" >CL Name :</td><td style="text-align:right;padding:3px 3px;">' . $ca_name . '</td></tr>';
    }

    $det.= '<tr><td colspan="2" style="text-align:right;padding:3px 3px;" >Refund :</td><td style="text-align:right;padding:3px 3px;">' . landa()->rp($model->refund) . '</td></tr>';

    $det .= '</table>';
    $content = str_replace('{detail}', $det, $content);
    echo $content;
    ?>
</div>


<style type="text/css">
    .printableArea{visibility: hidden;top:0px;left:0px;position: absolute;z-index: -1000}    
    @media print
    {
        body {visibility:hidden;}
        .printableArea{visibility: visible;} 
        .printableArea{width: 100%;top: 0px;left: 0px;position: absolute;} 

    }

</style>
<script type="text/javascript">
<?php
$print = (isset($_GET['print'])) ? $_GET['print'] : '1';
if ($print == "0" && $model->is_temp == 0) {
    echo 'printDiv();';
}
?>
    function printDiv()
    {
        window.print();
    }
</script>
