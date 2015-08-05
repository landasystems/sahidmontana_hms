<div class="form">
    <?php
    $settings = json_decode($model->settings, true);
    $extrabed = (!empty($settings['extrabed_charge'])) ? $settings['extrabed_charge'] : '';
    $fnb = (!empty($settings['fb_charge'])) ? $settings['fb_charge'] : '';
    $fnbAccount = (!empty($settings['fb_account'])) ? $settings['fb_account'] : '';
    $roomAccount = (!empty($settings['room_account'])) ? $settings['room_account'] : '';
    $rateDolar = (!empty($settings['rate'])) ? $settings['rate'] : 0;
    $tax = (!empty($settings['tax'])) ? $settings['tax'] : 0;


    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id' => 'site-config-form',
        'enableAjaxValidation' => false,
        'method' => 'post',
        'type' => 'horizontal',
        'htmlOptions' => array(
            'enctype' => 'multipart/form-data'
        )
    ));
    ?>
    <fieldset>
        <legend>
            <p class="note">Fields with <span class="required">*</span> is Required.</p>
        </legend>

        <?php echo $form->errorSummary($model, 'Opps!!!', null, array('class' => 'alert alert-error span12')); ?>


        <ul class="nav nav-tabs" id="myTab">
            <li class="active"><a href="#site">Site</a></li>
            <li><a href="#format">Code Formatting</a></li>
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">Format Invoice <b class="caret"></b></a>
                <ul class="dropdown-menu">
                    <li><a style="padding: 3px" href="#report_deposite">Deposite</a></li>
                    <li><a style="padding: 3px" href="#report_checkedout">Checked Out</a></li>
                    <li><a style="padding: 3px" href="#report_transaction">Transaction</a></li>
                    <li><a style="padding: 3px" href="#report_registration">Registration</a></li>
                </ul>
            </li> 
            <li><a href="#setting">Global</a></li>
            <li><a href="#accounting">Accounting</a></li>
        </ul>

        <div class="tab-content">
            <div class="tab-pane active" id="site">

                <table>
                    <tr>
                        <td width="300">
                            <?php
                            $siteConfig = SiteConfig::model()->listSiteConfig();
                            $img = Yii::app()->landa->urlImg('site/', $siteConfig->client_logo, 1);
                            echo '<img src="' . $img['big'] . '" class="img-polaroid"/>';
                            ?>
                            <div style="margin-left: -100px;"> <?php echo $form->fileFieldRow($model, 'client_logo', array('class' => 'span3')); ?></div>

                        </td>
                        <td style="vertical-align: top;">                                

                            <?php echo $form->textFieldRow($model, 'client_name', array('class' => 'span4', 'maxlength' => 255)); ?>

                            <?php // echo $form->dropDownListRow($model, 'language_default', array('id' => 'Indonesia', 'en' => 'English'));  ?>

                            <?php
                            echo $form->textFieldRow(
                                    $model, 'phone', array('prepend' => '+62')
                            );
                            ?>

                            <?php echo $form->textFieldRow($model, 'email', array('class' => 'span4', 'maxlength' => 45)); ?>
                            <?php
                            echo $form->textFieldRow(
                                    $model, 'npwp', array()
                            );
                            ?>
                            <?php
//                            echo $form->dropDownListRow(
//                                    $model, 'roles_guest', CHtml::listData(User::model()->roles(), 'id', 'name'), array('multiple' => true, 'class' => 'span4',)
//                            );
                            ?>
                            <div class="control-group ">
                                <?php
                                echo CHtml::activeLabel($model, 'province_id', array('class' => 'control-label'));
                                ?>
                                <div class="controls">
                                    <?php
                                    echo CHtml::dropDownList('province_id', $model->City->province_id, CHtml::listData(Province::model()->findAll(), 'id', 'name'), array(
                                        'empty' => 'Please Choose',
                                        'ajax' => array(
                                            'type' => 'POST',
                                            'url' => CController::createUrl('landa/city/dynacities'),
                                            'update' => '#SiteConfig_city_id',
                                        ),
                                    ));
                                    ?>  
                                </div>
                            </div>
                            <?php echo $form->dropDownListRow($model, 'city_id', CHtml::listData(City::model()->findAll('province_id=:province_id', array(':province_id' => (int) $model->City->province_id)), 'id', 'name'), array('class' => 'span3')); ?>
                            <?php echo $form->textAreaRow($model, 'address', array('class' => 'span4', 'rows' => 4, 'maxlength' => 255)); ?>

                        </td>

                    </tr>
                </table>


            </div>
            <div class="tab-pane" id="format">
                <?php echo $form->textFieldRow($model, 'format_reservation', array('class' => 'span5', 'maxlength' => 255)); ?>
                <?php echo $form->textFieldRow($model, 'format_registration', array('class' => 'span5', 'maxlength' => 255)); ?>
                <?php echo $form->textFieldRow($model, 'format_bill', array('class' => 'span5', 'maxlength' => 255)); ?>
                <?php echo $form->textFieldRow($model, 'format_bill_charge', array('class' => 'span5', 'maxlength' => 255)); ?>
                <?php echo $form->textFieldRow($model, 'format_deposite', array('class' => 'span5', 'maxlength' => 255)); ?>
                <?php
//                echo $form->datepickerRow(
//                        $model, 'date_system', array(
//                    'options' => array('language' => 'es'),
//                    'prepend' => '<i class="icon-calendar"></i>'
//                        )
//                );
                ?>

                <div class="well">
                    <ul>
                        <li>Isikan formating code, agar sistem dapat melakukan generate kode untuk module - module yang sudah tersedia</li>
                        <li><b>{ai|<em>3</em>}</b> / <b>{ai|<em>4</em>}</b>  / <b>{ai|<em>5</em>}</b> / <b>{ai|<em>6</em>}</b> : berikan format berikut untuk generate Auto Increase Numbering, contoh {ai|5} untuk 5 digit angka, {ai|3} untuk 3 digit angka</li>
                        <li><b>{dd}</b>/<b>{mm}</b>/<b>{yy}</b> : berikan format berikut untuk melakukan generate tanggal, bulan, dan tahun </li>
                        <li>Contoh Formating : <b>BILL/{dd}/{mm}{yy}/{ai|5}</b>, Hasil Generate : <b>BILL/14/0713/00001</b></li>
                    </ul>
                </div> 

            </div>
            <div class="tab-pane" id="report_deposite">
                <center><h4>DESIGN FORMAT INVOICE DEPOSITE</h4></center>
                <hr>
                <?php
                echo $form->ckEditorRow(
                        $model, 'report_deposite', array(
                    'options' => array(
                        'fullpage' => 'js:true',
                        'filebrowserBrowseUrl' => $this->createUrl("fileManager/indexBlank"),
                        'resize_maxWidth' => '1007',
                        'resize_minWidth' => '320'
                    ), 'label' => false,
                        )
                );
                ?>                 
                <div class="well">
                    Gunakan format berikut untuk men-generate sebuah field.
                    <hr>
                    <ul>                      
                        <li><b>{invoice}</b>  : Mengembalikan nomer invoice</li>
                        <li><b>{guest}</b> : Mengembalikan nama guest</li>
                        <li><b>{by}</b> : Mengembalikan jenis/type pembayaran</li>
                        <li><b>{amount}</b> : Mengembalikan provinsi customer</li>
                        <li><b>{cc_number}</b> : Mengembalikan No. Credit card untuk pembayaran menggunakan Credit Card</li>
                        <li><b>{desc}</b> : Mengembalikan keterangan / deskripsi</li>                        
                        <li><b>{cashier}</b> : Mengembalikan nama kasir yang bertugas</li>
                        <li><b>{date}</b> : Mengembalikan tanggal transaksi</li>
                    </ul>
                </div>
            </div>
            <div class="tab-pane" id="report_checkedout">
                <center><h4>DESIGN FORMAT INVOICE GUEST CHECKEDOUT</h4></center>
                <hr>
                <?php
                echo $form->ckEditorRow(
                        $model, 'report_bill', array(
                    'options' => array(
                        'fullpage' => 'js:true',
                        'filebrowserBrowseUrl' => $this->createUrl("fileManager/indexBlank"),
                        'resize_maxWidth' => '1007',
                        'resize_minWidth' => '320'
                    ), 'label' => false,
                        )
                );
                ?> 
                <div class="well">
                    Gunakan format berikut untuk men-generate sebuah field.
                    <hr>
                    <ul>                      
                        <li><b>{invoice}</b>  : Mengembalikan nomer invoice</li>
                        <li><b>{arrival}</b>  : Mengembalikan tanggal arrival</li>
                        <li><b>{departure}</b>  : Mengembalikan tanggal departure</li>
                        <li><b>{date}</b> : Mengembalikan tanggal transaksi</li>
                        <li><b>{guest}</b> : Mengembalikan nama guest</li>                        
                        <li><b>{desc}</b> : Mengembalikan keterangan / deskripsi</li>                        
                        <li><b>{cashier}</b> : Mengembalikan nama kasir yang bertugas</li>
                        <li><b>{detail}</b> : Mengembalikan data detail tagihan</li>
                    </ul>
                </div>
            </div>
            <div class="tab-pane" id="report_transaction">
                <div class="tab-pane" id="report_checkedout">
                    <center><h4>DESIGN FORMAT INVOICE TRANSACTION</h4></center>
                    <hr>
                    <?php
                    echo $form->ckEditorRow(
                            $model, 'report_bill_charge', array(
                        'options' => array(
                            'fullpage' => 'js:true',
                            'filebrowserBrowseUrl' => $this->createUrl("fileManager/indexBlank"),
                            'resize_maxWidth' => '1007',
                            'resize_minWidth' => '320'
                        ), 'label' => false,
                            )
                    );
                    ?> 
                    <div class="well">
                        Gunakan format berikut untuk men-generate sebuah field.
                        <hr>
                        <ul>                      
                            <li><b>{invoice}</b>  : Mengembalikan nomer invoice</li>
                            <li><b>{date}</b> : Mengembalikan tanggal transaksi</li>
                            <li><b>{departement}</b> : Mengembalikan nama outlet / departement</li>                        
                            <li><b>{cover}</b> : Mengembalikan jumlah cover</li>                        
                            <li><b>{desc}</b> : Mengembalikan keterangan / deskripsi</li>                        
                            <li><b>{cashier}</b> : Mengembalikan nama kasir yang bertugas</li>
                            <li><b>{detail}</b> : Mengembalikan data detail transaksi</li>

                        </ul>

                    </div>
                </div>
            </div>
            <div class="tab-pane" id="report_registration">
                <div class="tab-pane" id="report_checkedout">
                    <center><h4>DESIGN FORMAT REGISTRATION</h4></center>
                    <hr>
                    <?php
                    echo $form->ckEditorRow(
                            $model, 'report_registration', array(
                        'options' => array(
                            'fullpage' => 'js:true',
                            'filebrowserBrowseUrl' => $this->createUrl("fileManager/indexBlank"),
                            'resize_maxWidth' => '1007',
                            'resize_minWidth' => '320'
                        ), 'label' => false,
                            )
                    );
                    ?> 
                    <div class="well">
                        Gunakan format berikut untuk men-generate sebuah field.
                        <hr>
                        <ul>                      
                            <li><b>{invoice}</b>  : Mengembalikan nomer invoice</li>
                            <li><b>{name}</b>  : Mengembalikan nama tamu</li>
                            <li><b>{address}</b>  : Mengembalikan alamat tamu</li>
                            <li><b>{nationality}</b> : Mengembalikan kewarganegaraan tamu</li>
                            <li><b>{date_of_birth}</b> : Mengembalikan tanggal lahir tamu</li>                        
                            <li><b>{passport}</b> : Mengembalikan no identitas (KTP/Passport)</li>       
                            <li><b>{company}</b> : Mengembalikan nama perusahaan</li>
                            <li><b>{credit_card}</b> : Mengembalikan nomor kartu kredit</li>
                            <li><b>{date}</b> : Mengembalikan tanggal checkin</li>
                            <li><b>{departure_date}</b> : Mengembalikan tanggal checkout</li>
                            <li><b>{phone_number}</b> : Mengembalikan no. tlp</li>
                            <li><b>{email}</b> : Mengembalikan alamat email</li>                            
                        </ul>
                    </div>
                </div>
            </div>
            <div class="tab-pane" id="setting">               
                <?php
                echo $form->datepickerRow(
                        $model, 'date_system', array(
                    'options' => array('language' => 'en', 'format' => 'yyyy-mm-dd'),
                    'prepend' => '<i class="icon-calendar"></i>'
                        )
                );
                ?>
                <div class="control-group "><label class="control-label" for="">Rate US</label>
                    <div class="controls">
                        <div class="input-prepend">
                            <span class="add-on">$</span>
                            <input class="span2" maxlength="255" name="rate" value="<?php echo $rateDolar ?>" id="rate" type="text">                            
                        </div>
                    </div>
                </div>

                <div class="control-group "><label class="control-label" for="">Charge Extrabed</label>
                    <div class="controls">
                        <div class="input-append input-prepend">
                            <span class="add-on">Rp</span>
                            <input class="span2" maxlength="255" name="extrabed" value="<?php echo $extrabed ?>" id="extrabed" type="text">
                            <span class="add-on">/ Pax</span>
                        </div>
                    </div>
                </div>


                <div class="control-group "><label class="control-label" for="">Charge Breakfast</label>
                    <div class="controls">
                        <div class="input-append input-prepend">
                            <span class="add-on">Rp</span>
                            <input class="span2" maxlength="255" name="fnb" id="fnb" type="text" value="<?php echo $fnb; ?>">
                            <span class="add-on">/ Pax</span>
                        </div>
                    </div>
                </div>  

                <div class="control-group "><label class="control-label" for="">Breakfast Account</label>
                    <div class="controls">
                        <?php
                        $account = Account::model()->findAll();
                        $dataAccount = CHtml::listData($account, 'id', 'name');
                        $this->widget(
                                'bootstrap.widgets.TbSelect2', array(
                            'asDropDownList' => true,
                            'name' => 'breakfastAccount',
                            'data' => $dataAccount,
                            'value' => $fnbAccount,
                            'options' => array(
                                "placeholder" => 'Please Choose',
                                "allowClear" => false,
                                'width' => '30%;margin:0px',
                        )));
                        ?> 
                    </div>
                </div>  

                <div class="control-group "><label class="control-label" for="">Room Charge Account</label>
                    <div class="controls">
                        <?php
                        $this->widget(
                                'bootstrap.widgets.TbSelect2', array(
                            'asDropDownList' => true,
                            'name' => 'roomAccount',
                            'data' => $dataAccount,
                            'value' => $roomAccount,
                            'options' => array(
                                "placeholder" => 'Please Choose',
                                "allowClear" => false,
                                'width' => '30%;margin:0px',
                        )));
                        ?> 
                    </div>
                </div>     

                <div class="control-group">
                    <label class="control-label" for="">Others Include</label>                    
                    <div class="controls">

                        <table class="table table-striped table-bordered" style="margin-bottom: 0px">
                            <thead>
                                <tr>
                                    <th style="width: 15px;text-align:center">#</th>
                                    <th class="span4" style="text-align:center">Name</th>                                                                 
                                    <th class="span2" style="text-align:center">Price</th>                                                                                                
                                </tr>
                            </thead>
                            <tbody>     
                                <tr class="hidePrint">
                                    <td style="text-align:center">
                                        <?php
                                        echo CHtml::ajaxLink(
                                                $text = '<button><i class="icon-plus-sign"></i></button>', $url = url('siteConfig/addRow'), $ajaxOptions = array(
                                            'type' => 'POST',
                                            'success' => 'function(data){                                       
                                                $("#addRow").replaceWith(data); 
                                                $(".delRow").on("click", function() {
                                                    $(this).parent().parent().remove();                                                  
                                                });
                                                $("#addPrice").html("");
                                            
                                        }'), $htmlOptions = array()
                                        );
                                        ?>                        
                                    </td>
                                    <td>                        
                                        <?php
                                        $data2 = RoomBill::model()->getAdditional();
                                        $this->widget(
                                                'bootstrap.widgets.TbSelect2', array(
                                            'asDropDownList' => true,
                                            'name' => 'additional_id',
                                            'data' => $data2,
                                            'options' => array(
                                                "placeholder" => 'Please Choose',
                                                "allowClear" => false,
                                                'width' => '100%;margin:0px',
                                            ),
                                            'events' => array('change' => 'js: function() {
                                                            $.ajax({
                                                               url : "' . url('roomBill/getAdditional') . '",
                                                               type : "POST",
                                                               data :  { addID:$(this).val()},
                                                               success : function(data){
                                                                obj = JSON.parse(data);                
                                                                   if (data==""){
                                                                    $("#addPrice").html("");
                                                                   }                                                                                             
                                                                   $("#addPrice").html(obj.charge);                                                                     

                                                               }
                                                            });
                                                   }'),
                                                )
                                        );
                                        ?>                            
                                    </td>                                                                                                                 
                                    <td style="text-align: right" id="addPrice"></td>                                                        

                                </tr>   
                                <?php
                                $others_include = json_decode($model->others_include);
                                if (!empty($others_include)) {
                                    foreach ($others_include as $other) {
                                        $charge = ChargeAdditional::model()->findByPk($other);
                                        if (count($charge) > 0) {
                                            echo '                                                  
                                        <tr class="items">
                                            <input type="hidden" name="others_include[]" id="' . $charge->id . '" value="' . $other . '"/>                                                                                                    
                                            <td style="text-align:center"><button class="delRow"><i class="icon-remove-circle" style="cursor:all-scroll;"></i></button></td>
                                            <td> &nbsp;&nbsp;&raquo; ' . $charge->name . '</td>                        
                                            <td style="text-align:right">' . landa()->rp($charge->charge) . '</td>                                                        
                                        </tr>                     
                                        ';
                                        }
                                    }
                                }
                                ?>


                                <tr id="addRow" style="display:none"></tr>

                            </tbody>
                        </table>                                               

                    </div>        
                </div>


            </div>
            <div class="tab-pane" id="accounting">
                <div class="control-group">
                    <lable class="control-label">Cash <span class="required">*</span></lable>
                    <div class="controls">
                        <?php
                        $coa = array(0 => 'Please Choose') + CHtml::listData(AccCoa::model()->findAll(array('order' => 'root, lft')), 'id', 'nestedname');
                        $this->widget('bootstrap.widgets.TbSelect2', array(
                            'asDropDownList' => TRUE,
                            'data' => $coa,
                            'value' => $model->acc_cash_id,
                            'name' => 'SiteConfig[acc_cash_id]',
                            'options' => array(
                                "placeholder" => 'Please Choose',
                                "allowClear" => true,
                                'width' => '40%',
                            ),
                            'htmlOptions' => array(
                                'id' => 'SiteConfig_acc_cash_id',
                            ),
                        ));
                        ?>
                    </div>
                </div>
                <div class="control-group">
                    <lable class="control-label">City Ledger <span class="required">*</span></lable>
                    <div class="controls">
                        <?php
                        $coa = array(0 => 'Please Choose') + CHtml::listData(AccCoa::model()->findAll(array('order' => 'root, lft')), 'id', 'nestedname');
                        $this->widget('bootstrap.widgets.TbSelect2', array(
                            'asDropDownList' => TRUE,
                            'data' => $coa,
                            'value' => $model->acc_city_ledger_id,
                            'name' => 'SiteConfig[acc_city_ledger_id]',
                            'options' => array(
                                "placeholder" => 'Please Choose',
                                "allowClear" => true,
                                'width' => '40%',
                            ),
                            'htmlOptions' => array(
                                'id' => 'SiteConfig_acc_city_ledger_id',
                            ),
                        ));
                        ?>
                    </div>
                </div>
                <div class="control-group">
                    <lable class="control-label">Service Charge <span class="required">*</span></lable>
                    <div class="controls">
                        <?php
                        $coa = array(0 => 'Please Choose') + CHtml::listData(AccCoa::model()->findAll(array('order' => 'root, lft')), 'id', 'nestedname');
                        $this->widget('bootstrap.widgets.TbSelect2', array(
                            'asDropDownList' => TRUE,
                            'data' => $coa,
                            'value' => $model->acc_service_charge_id,
                            'name' => 'SiteConfig[acc_service_charge_id]',
                            'options' => array(
                                "placeholder" => 'Please Choose',
                                "allowClear" => true,
                                'width' => '40%',
                            ),
                            'htmlOptions' => array(
                                'id' => 'SiteConfig_acc_service_charge_id',
                            ),
                        ));
                        ?>
                    </div>
                </div>
                <div class="control-group">
                    <lable class="control-label">Tax <span class="required">*</span></lable>
                    <div class="controls">
                        <?php
                        $coa = array(0 => 'Please Choose') + CHtml::listData(AccCoa::model()->findAll(array('order' => 'root, lft')), 'id', 'nestedname');
                        $this->widget('bootstrap.widgets.TbSelect2', array(
                            'asDropDownList' => TRUE,
                            'data' => $coa,
                            'value' => $model->acc_tax_id,
                            'name' => 'SiteConfig[acc_tax_id]',
                            'options' => array(
                                "placeholder" => 'Please Choose',
                                "allowClear" => true,
                                'width' => '40%',
                            ),
                            'htmlOptions' => array(
                                'id' => 'SiteConfig_acc_tax_id',
                            ),
                        ));
                        ?>
                    </div>
                </div>
                <div class="control-group">
                    <lable class="control-label">Clearance <span class="required">*</span></lable>
                    <div class="controls">
                        <?php
                        $coa = array(0 => 'Please Choose') + CHtml::listData(AccCoa::model()->findAll(array('order' => 'root, lft')), 'id', 'nestedname');
                        $this->widget('bootstrap.widgets.TbSelect2', array(
                            'asDropDownList' => TRUE,
                            'data' => $coa,
                            'value' => $model->acc_clearance_id,
                            'name' => 'SiteConfig[acc_clearance_id]',
                            'options' => array(
                                "placeholder" => 'Please Choose',
                                "allowClear" => true,
                                'width' => '40%',
                            ),
                            'htmlOptions' => array(
                                'id' => 'SiteConfig_acc_clearance_id',
                            ),
                        ));
                        ?>
                    </div>
                </div>
            </div>



            <div class="form-actions">
                <?php
                $this->widget('bootstrap.widgets.TbButton', array(
                    'buttonType' => 'submit',
                    'type' => 'primary',
                    'icon' => 'ok white',
                    'visible' => !isset($_GET['v']),
                    'label' => $model->isNewRecord ? 'Create' : 'Save',
                ));
                ?>
            </div>
    </fieldset>







    <?php $this->endWidget(); ?>

</div>
<script>
    $(".delRow").on("click", function () {
        $(this).parent().parent().remove();
    });
</script>
