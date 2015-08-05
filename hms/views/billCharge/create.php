<?php
$this->setPageTitle('Create Bill Charges');

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
        array('label' => 'Create', 'icon' => 'icon-plus', 'url' => Yii::app()->controller->createUrl('create'), 'active' => true, 'linkOptions' => array()),
        array('label' => 'List Data', 'icon' => 'icon-th-list', 'url' => Yii::app()->controller->createUrl('index'), 'linkOptions' => array()),
    ),
));
$this->endWidget();
?>


<div class="form">
    <?php
    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id' => 'bill-charge-form',
        'enableClientValidation' => true,
        'clientOptions' => array(
            'validateOnSubmit' => true,
        ),
        'method' => 'post',
        'type' => 'horizontal',
        'htmlOptions' => array(
            'enctype' => 'multipart/form-data'
        )
    ));
    ?>
    <fieldset>
        <center class="headerInvoice" style="display: none">
            <h3 style="margin:0px">INVOICE</h3>   
            <h3 style="margin:0px">HOTEL SAHID MONTANA</h3>        
            <hr style="margin:0px">
            <hr style="margin:0px">
        </center>
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
                            $departement = array(0 => 'Please Choose') + Chtml::listdata(ChargeAdditionalCategory::model()->findAll(array('condition' => 'level=1')), 'id', 'name');
//                            echo CHtml::dropDownList('BillCharge[charge_additional_category_id]', $model->charge_additional_category_id, $departement, array('id' => 'BillCharge_charge_additional_category_id', 'class' => 'span3', 'empty' => 'Please Choose',
//                                'ajax' => array(
//                                    'type' => 'POST',
//                                    'url' => url('billCharge/selectDepartement'),
//                                    'data' => array('departement_id' => 'js:this.value'),
//                                    'success' => 'function(data) {
//                                        $("#content_table").html(data);
//                                    }',
//                                )
//                            ));
                            echo CHtml::dropDownList('BillCharge[charge_additional_category_id]', $model->charge_additional_category_id, $departement, array('id' => 'BillCharge_charge_additional_category_id', 'class' => 'span3'));
                            echo $form->error($model, 'charge_additional_category_id');
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

                <div style="text-align:right">
                    <a class="btn btn-small btn-primary" data-toggle="modal" data-target="#modalAuthority"><i class="icon-cog icon-white" style="margin:0px !important"></i> Change Price</a>
                    <a style="background: forestgreen"  class="btn btn-small  btn-primary" data-toggle="modal" data-target="#modalDeposite"><i class="icon-download-alt icon-white" style="margin:0px !important"></i> Add Deposite</a>
                </div>
                <br>
                <div id="content_table">
                    <?php echo $this->renderPartial('_form', array('model' => $model)); ?>
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

        <div class="form-actions">
            <button class="btn btn-primary"  type="button" name ="save" id="save"><i class="icon-ok icon-white"></i> Save & Print</button>
            <button class="btn btn-warning"  type="submit" name="saveTemp" id="saveTemp"><i class="icon-repeat icon-white"></i> Save To Temporary</button>
            <script>
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
                        document.getElementById("bill-charge-form").submit();
                    }
                })
            </script>
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
                <?php echo CHtml::HiddenField('BillCharge[approval_user_id]', $model->approval_user_id); ?>
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
        'htmlOptions' => array('style' => 'width: 800px ;margin-left: -400px;height:800px'))
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
                    $name = (isset($dp->Guest->guestName)) ? $dp->Guest->guestName : '';
                    echo '
                      <tr>
                        <td style="text-align: center">' . $no . '</td>
                        <td style="text-align: left">' . $dp->code . '</td>
                        <td>' . $name . '</td>
                        <td style="text-align: right">' . landa()->rp($dp->balance_amount) . '</td>
                        <td>' . $dp->created . '</td>
                        <td style="width:30px;text-align:center"><a class="btn btn-small btn-add-deposite" dp_id="' . $dp->id . '" title="Add Deposite" rel="tooltip" "><i class="cut-icon-plus-2"></i></a></td>                        
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


<style type="text/css" media="print">
    body {visibility:hidden;}
    .invoice{visibility:visible;}  
    .headerInvoice{display: block;}  
    .headerInvoice{visibility:visible;}  
    .hidePrint{display: none;}      
    #sidebar{display: none;}  
    #content{margin: 10px;padding: 10px;position: relative;top: -150px}  

</style>
<script type="text/javascript">
    function printDiv()
    {
        $(".headerInvoice").css({"display": "block"});
        window.print();
    }
</script>
