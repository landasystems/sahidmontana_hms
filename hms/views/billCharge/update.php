<?php
$this->setPageTitle('Bill Charges | ' . $model->id);

$this->beginWidget('zii.widgets.CPortlet', array(
    'htmlOptions' => array(
        'class' => ''
    )
));
$this->widget('bootstrap.widgets.TbMenu', array(
    'type' => 'pills',
    'items' => array(
        array('label' => 'Create', 'icon' => 'icon-plus', 'url' => Yii::app()->controller->createUrl('create')),
        array('label' => 'List Data', 'icon' => 'icon-th-list', 'url' => Yii::app()->controller->createUrl('index')),
        array('label' => 'Print', 'icon' => 'icon-print', 'url' => 'javascript:void(0);return false', 'linkOptions' => array('onclick' => 'printElement("printElement");return false;')),
    ),
));
$this->endWidget();

if ($model->isNewRecord == FALSE) {
    $details = BillChargeDet::model()->findAll(array('condition' => 'bill_charge_id=' . $model->id));
} else {
    $details = array();
}
?>

<div class="form">
    <?php
    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id' => 'bill-charge-form',
        'enableClientValidation' => true,
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
                    <span class="data gray"><?php echo date('d M Y') ?></span>
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
                            echo CHtml::dropDownList('BillCharge[charge_additional_category_id]', $model->charge_additional_category_id, $departement, array('id' => 'BillCharge_charge_additional_category_id', 'class' => 'span3', 'disabled' => false, 'empty' => 'Please Choose'));
                            echo $form->error($model, 'charge_additional_category_id');
                            ?>
                        </td>                                
                    </tr>                                                     
                    <tr>
                        <td >Date </td>
                        <td  style="width:5px">:</td>
                        <?php
                        $date = (!empty($model->created)) ? date('d F Y, H:i', strtotime($model->created)) : date('d F Y, H:i');
                        ?>
                        <td ><?php echo $date; ?></td>                                
                    </tr>  
                    <tr>
                        <td >Cover </td>
                        <td  style="width:5px">:</td>                       
                        <td ><?php echo Chtml::textField('BillCharge[cover]', $model->cover, array('class' => 'angka span1')); ?></td>                                
                    </tr>  
                    <tr>
                        <td >Note </td>
                        <td  style="width:5px">:</td>
                        <td >
                            <?php echo Chtml::textArea('BillCharge[description]', $model->description, array('style' => 'width:100%')); ?>
                        </td>                                
                    </tr>                            
                </table>   
                <?php if (!isset($_GET['v'])) { ?>
                    <div style="text-align:right">
                        <a  class="btn btn-small btn-primary" data-toggle="modal" data-target="#modalAuthority"><i class="icon-cog icon-white" style="margin:0px !important"></i> Change Price</a>
                        <a style="background: forestgreen" class="btn btn-small  btn-primary" data-toggle="modal" data-target="#modalDeposite"><i class="icon-download-alt icon-white" style="margin:0px !important"></i> Add Deposite</a>
                    </div><br>
                <?php } ?>
                <div id="content_table">
                    <?php echo $this->renderPartial('_form', array('model' => $model, 'id' => $model->charge_additional_category_id, 'details' => $details)); ?>
                </div>

                <table style="width:100%">
                    <tr>
                        <td style="width:50%;text-align: center;vertical-align: top">
                            <br>
                            Guest Sign
                            <br>
                            <br>
                            <br>
                    <u>......................................</u>
                    </td>
                    <td style="width:50%;text-align: center;vertical-align: top">
                        <br>
                        Cashier
                        <br>
                        <br>
                        <br>
                    <u><?php echo Yii::app()->user->name; ?></u>
                    </td>
                    </tr>
                </table>
            </div>
        </div>    

        <?php
        if (!isset($_GET['v'])) {
            ?>
            <div class="form-actions">
                <input type="hidden" name="BillCharge[is_temp]" id="saveTemp" value="1"/>
                <button class="btn btn-primary"  type="button" name="save" id="save"><i class="icon-ok icon-white"></i> Save </button>
                <button class="btn btn-warning"  type="submit"><i class="icon-repeat icon-white"></i> Save Temporary</button>

                <div id="alert" class="modal large hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h3 id="myModalLabel"><i>Alert !</i></h3>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-error">
                            <div id="alertContent"></div>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
    </fieldset>
    <?php
    $this->beginWidget(
            'bootstrap.widgets.TbModal', array('id' => 'modalAuthority')
    );
    ?>

    <div class="modal-header">
        <a class="close" data-dismiss="modal">&times;</a>
        <h3>VERIFICATION AUTHORITY</h3>
    </div>

    <div class="modal-body form-horizontal">
        <div class = "control-group ">
            <label class = "control-label">Username</label>
            <div class="controls">                   
                <?php echo CHtml::textField('username', '', array('class' => 'span3')); ?>
                <?php echo CHtml::HiddenField('BillCharge[approval_user_id]', $model->approval_user_id, array('class' => 'span3')); ?>

            </div>
        </div>
        <div class = "control-group ">
            <label class = "control-label">Password</label>
            <div class="controls">
                <?php echo CHtml::passwordField('password', '', array('class' => 'span3')); ?>            
                <br>
                <br>
                <span class="text-error message"></span>
            </div>
        </div>
    </div>

    <div class="modal-footer">           
        <?php
        echo CHtml::ajaxLink(
                $text = '<button class="btn btn-primary">Submit</i></button>', $url = url('user/checkAuthority'), $ajaxOptions = array(
            'type' => 'POST',
            'success' => 'function(data){ 
                        obj = JSON.parse(data);                                                               
                        if (obj.user_id!=""){                            
                            $(".changeDiscount").attr("readonly", false);
                            $("#password").val("");  
                            $("#BillCharge_approval_user_id").val(obj.user_id);
                            $("#modalAuthority").modal("hide");   
                        }else{
                            $(".message").html(obj.message);
                            $("#password").val("");
                        }
                      }'), $htmlOptions = array()
        );
        ?> 
        <?php
        $this->widget(
                'bootstrap.widgets.TbButton', array(
            'label' => 'Close',
            'url' => '#',
            'htmlOptions' => array('data-dismiss' => 'modal'),
                )
        );
        ?>
    </div>

    <?php $this->endWidget(); ?>

    <?php
    $this->beginWidget(
            'bootstrap.widgets.TbModal', array('id' => 'modalDeposite',
        'htmlOptions' => array('style' => 'width: 800px ;margin-left: -400px'))
    );
    ?>

    <div class="modal-header">
        <a class="close" data-dismiss="modal">&times;</a>
        <h3>LIST DEPOSITE GUEST</h3>
    </div>

    <div class="modal-body form-horizontal">
        <table class="table  table-striped table-hover">
            <thead>
                <tr>
                    <th style="text-align: center">No.</th>
                    <th style="text-align: center">Code</th>
                    <th style="text-align: center">Guest</th>
                    <th style="text-align: center">Amount</th>
                    <th style="text-align: center">Date</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php
                $deposite = Deposite::model()->findAll(array('condition' => 'balance_amount>0'));
                $no = 1;
                foreach ($deposite as $dp) {
                    echo '
                      <tr>
                        <td style="text-align: center">' . $no . '</td>
                        <td style="text-align: left">' . $dp->code . '</td>
                        <td>' . $dp->Guest->guestName . '</td>
                        <td style="text-align: right">' . landa()->rp($dp->balance_amount) . '</td>
                        <td>' . $dp->created . '</td>
                        <td style="width:30px;text-align:center"><a class="btn btn-small btn-add-deposite" dp_id="' . $dp->id . '" title="Add Deposite" rel="tooltip" "><i class="icon-plus"></i></a></td>                        
                      </tr>
                    ';
                    $no++;
                }
                ?>
            </tbody>
        </table>
    </div>

    <div class="modal-footer">           

    </div>

    <?php $this->endWidget(); ?>
    <?php $this->endWidget(); ?>

</div>

<div id='printElement' style="display: none"> 
    <?php
    $departement = (isset($model->ChargeAdditionalCategory->name)) ? $model->ChargeAdditionalCategory->name : '';
    $siteConfig = SiteConfig::model()->listSiteConfig();
    $content = $siteConfig->report_bill_charge;
    $content = str_replace('{departement}', strtoupper($departement), $content);
    $content = str_replace('{invoice}', $model->code, $content);
    $content = str_replace('{date}', date('d M Y, H:i', strtotime($model->created)), $content);
    $content = str_replace('{desc}', $model->description, $content);
    $content = str_replace('{cashier}', ucwords($model->Cashier->name), $content);

    $det = '<table style="width:100%">';
    foreach ($details as $detail) {
        $discount =  (empty($detail->discount)) ? '' : $detail->discount . ' %';
        if ($detail->deposite_id != 0) {
            $price = $detail->charge * $detail->amount - (($detail->discount / 100) * $detail->charge * $detail->amount);
            $det.= '            
             <tr>                       
                <td style=";vertical-align:top;width:50%">' . landa()->rp($detail->deposite_amount) . '</td>                                                                     
                <td style="text-align:right;vertical-align:top;width:10%">' . $discount . '</td>                                                        
                <td style="text-align:right;vertical-align:top;width:40%">' . landa()->rp($price) . '</td>                                                        
             </tr>
             
            <tr>                       
                <td style=";vertical-align:top;" colspan="3">[' . $detail->Deposite->code . '] ' . $detail->Deposite->Guest->guestName . '</td>                                                                     
             </tr>
             

            ';
        } else {
            $price = $detail->charge * $detail->amount - round(($detail->discount / 100) * $detail->charge * $detail->amount);
            $addName = (isset($detail->Additional->name)) ? $detail->Additional->name : '';
            $det.= '
            
             <tr>                       
                <td style=";vertical-align:top;width:50%">' . $detail->amount . ' x ' . landa()->rp($detail->charge) . '</td>                                                                     
                <td style="text-align:right;vertical-align:top;width:10%">' . $discount . '</td>                                                        
                <td style="text-align:right;vertical-align:top;width:40%">' . landa()->rp($price) . '</td>                                                        
             </tr>
             
            <tr>                       
                <td style=";vertical-align:top;" colspan="3">' . $addName . '</td>                                                                     
             </tr>
             

            ';
        }
    }
    $ca_name = ($model->ca_user_id == 0 || empty($model->ca_user_id)) ? '' : $model->CityLedger->name;
    $gl_name = ($model->gl_room_bill_id == 0 || empty($model->gl_room_bill_id)) ? '' : $model->RoomBill->room_number;
    $det.= '<tr><td colspan="2" style="text-align:right;" >Total :</td><td style="text-align:right;">' . landa()->rp($model->total) . '</td></tr>';
    if ($model->cash != 0) {
        $det.= '<tr><td colspan="2" style="text-align:right;" >Cash :</td><td style="text-align:right;">' . landa()->rp($model->cash) . '</td></tr>';
    }
    if ($model->cc_charge != 0) {
        $det.= '<tr><td colspan="2" style="text-align:right;" >Credit :</td><td style="text-align:right;">' . landa()->rp($model->cc_charge) . '</td></tr>';
        $det.= '<tr><td colspan="2" style="text-align:right;" >CC Numb :</td><td style="text-align:right;">' . $model->cc_number . '</td></tr>';
    }
    if ($model->gl_charge != 0) {
        $det.= '<tr><td colspan="2" style="text-align:right;" >GL :</td><td style="text-align:right;">' . landa()->rp($model->gl_charge) . '</td></tr>';
        $det.= '<tr><td colspan="2" style="text-align:right;" >GL Room :</td><td style="text-align:right;">' . $gl_name . '</td></tr>';
    }
    if ($model->ca_charge != 0) {
        $det.= '<tr><td colspan="2" style="text-align:right;" >CL :</td><td style="text-align:right;">' . landa()->rp($model->ca_charge) . '</td></tr>';
        $det.= '<tr><td colspan="2" style="text-align:right;" >CL Name :</td><td style="text-align:right;">' . $ca_name . '</td></tr>';
    }

    $det.= '<tr><td colspan="2" style="text-align:right;" >Refund :</td><td style="text-align:right;">' . landa()->rp($model->refund) . '</td></tr>';

    $det .= '</table>';
    $content = str_replace('{detail}', $det, $content);
    echo $content;
    ?>
</div>

<script type="text/javascript">
    $('#save').on('click', function () {
        var refund = $('#BillCharge_refund').val();
        var gl_charge = $('#BillCharge_gl_charge').val();
        var gl_id = $('#BillCharge_gl_room_bill_id').val();
        var ca_charge = $('#BillCharge_ca_charge').val();
        var ca_id = $('#BillCharge_ca_user_id').val();
        var departement = $('#BillCharge_charge_additional_category_id').val();
        if (departement == 0) {
            $('#alertContent').html('<strong>Wrong ! </strong> Please select departement');
            $('#alert').modal('show');
        }
        else if (refund < 0) {
            $('#alertContent').html('<strong>Wrong Payment! </strong> Please check payment bellow');
            $('#alert').modal('show');
        }
        else if (gl_charge > 0 && gl_id == 0) {
            $('#alertContent').html('<strong>Wrong Payment! </strong> Please choose guest ledger name.');
            $('#alert').modal('show');
        }
        else if (ca_charge > 0 && ca_id == 0) {
            $('#alertContent').html('<strong>Wrong Payment! </strong> Please choose city ledger name.');
            $('#alert').modal('show');
        } else {
            $('#saveTemp').val(0);
            document.getElementById("bill-charge-form").submit();
        }
    })
</script>