<?php

class BillChargeController extends Controller {


    public $breadcrumbs;

    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = 'main';

    /**
     * @return array action filters
     */
    public function filters() {
        return array(
            'accessControl', // perform access control for CRUD operations
        );
    }

    public function accessRules() {
        return array(
            array('allow', // c
                'actions' => array('create'),
                'expression' => 'app()->controller->isValidAccess("BillCharge","c")'
            ),
            array('allow', // r
                'actions' => array('index', 'view'),
                'expression' => 'app()->controller->isValidAccess("BillCharge","r")'
            ),
            array('allow', // u
                'actions' => array('update'),
                'expression' => 'app()->controller->isValidAccess("BillCharge","u")'
            ),
            array('allow', // d
                'actions' => array('delete'),
                'expression' => 'app()->controller->isValidAccess("BillCharge","d")'
            )
        );
    }

    public function cssJs() {
        cs()->registerScript('', '
            


                        $(".btn-add-deposite").click(function(){
                            var id = $(this).attr("dp_id");
                            var postData = "id=" + id;   
                            
                            $.ajax({
                                url:"' . url("billCharge/addDeposite") . '",
                                data:postData,
                                type:"post",
                                success:function(data){                                    
                                    if($("#deposite"+id).length )                                    
                                    {}else{
                                        $("#addDeposite").replaceWith(data); 
                                        subtotal(0);                                             
                                        $(".delRow").on("click", function() {
                                            $(this).parent().parent().remove();
                                            subtotal(0);
                                        });                    
                                    }
                                    
                                                                                 
                                }
                            });
                        });


                        $("#amount,#charge,#discount").keypress(function (e) {
                                if (e.which == 13) {
                                  $("#yt0").trigger("click");
                                  e.preventDefault();
                                }

                            });
                            
                        function rp(angka){
                            var rupiah = "";
                            var angkarev = angka.toString().split("").reverse().join("");
                            for(var i = 0; i < angkarev.length; i++) if(i%3 == 0) rupiah += angkarev.substr(i,3)+".";
                            return rupiah.split("",rupiah.length-1).reverse().join("");
                        };
                        function calculate(){
                            var mysub = ($("#charge").val() * $("#amount").val()) - Math.round(($("#discount").val()/100)*$("#charge").val() * $("#amount").val()) ;
                            $("#addSubtotal").html("Rp. " + rp(mysub));                                                       
                                            
                        };
                            
                        function pay(){                         
                            var refund = 0;                            
                            var cash = parseInt($("#BillCharge_cash").val()) 
                            var credit = parseInt($("#BillCharge_cc_charge").val()) 
                            var gl = parseInt($("#BillCharge_gl_charge").val()) 
                            var ca = parseInt($("#BillCharge_ca_charge").val())                             
                            var total = parseInt($("#BillCharge_total").val()) 
                            
                            cash = cash || 0;
                            credit = credit || 0;
                            gl = gl || 0;
                            ca = ca || 0;
                            total = total || 0;
                            
                            refund =  cash + credit + ca + gl - total;
                                                       
                            $("#BillCharge_refund").val(refund);
                                            
                        };
                        
                        function subtotal(total){
                            subTotal = 0;
                            
                            $(".detTotal").each(function() {
                                 subTotal += parseInt($(this).val());                                 
                            });
                            $(".depositeAmount").each(function() {
                                 subTotal -= parseInt($(this).val());                                 
                            });
                            
                            $("#total").html("Rp. " + rp(subTotal));
                            $("#BillCharge_total").val(subTotal);                           
                            pay();
                        }
                        
                        function clearField(){
                            $("#stock").html("");
                            $("#amount").val("");
                            $("#charge").val("");
                            $("#price_buy").val("");
                            $("#discount").val("");
                            $("#s2id_product_id").select2("data", null)                            
                            $(".measure").html("");
                            $("#addDate").html("");
                            $("#addPrice").html("");
                            $("#addSubtotal").html("");
                            $("#addNumber").html("");
                        }
                        
                        
                        $("#price_buy").on("input", function() {
                            calculate();
                        });
                        
                       
                        
                        $("body").undelegate("#yt0","click");
                       
                        
                        $("#BillCharge_cc_charge").on("input", function() {
                            pay();
                        });
                        
                        $("#BillCharge_ca_charge").on("input", function() {
                            pay();
                        });
                        
                        $("#BillCharge_gl_charge").on("input", function() {
                            pay();
                        });
                        
                        $("#BillCharge_cash").on("input", function() {
                            pay();
                        });
                        
                        $("#amount").on("input", function() {
                            calculate();
                        });
                        $("#charge").on("input", function() {
                            calculate();
                        });
                        
                        $("#discount").on("input", function() {
                            calculate();
                        });
                        
                        $(".delRow").on("click", function() {
                            $(this).parent().parent().remove();
                            subtotal(0);
                        });
                        
                        $("#BillCharge_by").on("change", function() {
                            $(".cash").hide();                            
                            $(".cc").hide();
                            $(".debit").hide();
                            $(".gl").hide();
                            $(".ca").hide();
                            $("#BillCharge_cash").val();
                            $("#BillCharge_charge").val();                           
                            $("#BillCharge_refund").val(0);                           
                            
                            var data = $(this).val();                            
                            $("."+data).show();                          
                        });
                        
                        $(".depositeAmount").on("input", function() {                            
                            var amount = parseInt($(this).val());                                                                               
                            $(this).parent().parent().parent().find("td").eq(5).html("Rp. "+rp(amount));                            
                            subtotal();                            
                        });
                        
                        $("body").on("input",".detQty", function() {
                            var aCharge = parseInt($(this).parent().parent().find("#detCharge").val());
                            var aQty = parseInt($(this).parent().parent().find("#detQty").val());
                            var aDiscount = parseInt($(this).parent().parent().find("#detDiscount").val());
                            aCharge = aCharge || 0;
                            aQty = aQty || 0;
                            aDiscount = aDiscount || 0;
                            var aTotal = (aCharge * aQty) - Math.round((aDiscount / 100) * aCharge * aQty);
                            $(this).parent().parent().find("td").eq(5).html("Rp. "+rp(aTotal));
                            $(this).parent().parent().find("#detTotal").val(aTotal);
                            subtotal();
                        });

                        $("body").on("input",".detDiscount", function() {
                            var aCharge = parseInt($(this).parent().parent().parent().find("#detCharge").val());
                            var aQty = parseInt($(this).parent().parent().parent().find("#detQty").val());
                            var aDiscount = parseInt($(this).val());
                            aCharge = aCharge || 0;
                            aQty = aQty || 0;
                            aDiscount = aDiscount || 0;
                            var aTotal = (aCharge * aQty) - Math.round((aDiscount / 100) * aCharge * aQty);
                            $(this).parent().parent().parent().find("td").eq(5).html("Rp. "+rp(aTotal));
                            $(this).parent().parent().parent().find("#detTotal").val(aTotal);                                
                            subtotal();
                        });
                        $("body").on("input", ".detCharge", function() {
                            var aDiscount = parseInt($(this).parent().parent().parent().find("#detDiscount").val());
                            var aQty = parseInt($(this).parent().parent().parent().find("#detQty").val());
                            var aCharge = parseInt($(this).val());
                            aCharge = aCharge || 0;
                            aQty = aQty || 0;
                            aDiscount = aDiscount || 0;
                            var aTotal = (aCharge * aQty) - Math.round((aDiscount / 100) * aCharge * aQty);
                            $(this).parent().parent().parent().find("td").eq(5).html("Rp. "+rp(aTotal));
                            $(this).parent().parent().parent().find("#detTotal").val(aTotal);                                
                            subtotal();
                        });
                        
                    ');
    }

    public function actionSelectDepartement() {
        $id = (!empty($_POST['departement_id'])) ? $_POST['departement_id'] : '';
        Yii::app()->clientScript->reset();
        Yii::app()->clientScript->corePackages = array();
        $this->cssJs();
        $model = new BillCharge;
        echo $this->renderPartial('_form', array('id' => $id, 'model' => $model), false, true);
    }

    public function actionAddDeposite() {
        $id = $_POST['id'];
        $model = Deposite::model()->findByPk($id);
        echo '                                                  
                    <tr class="items">
                        <input type="hidden" name="deposite[id][]" id="deposite' . $model->id . '" value="' . $model->id . '"/>                        
                        <td style="text-align:center"><button class="delRow "><i class="icon-remove-circle" style="cursor:all-scroll;"></i></button></td>
                        <td>[' . $model->code . '] ' . $model->Guest->guestName . '</td>
                        <td style="text-align:center"><input type="text" readOnly maxlength="6" class="span1" name="" id="" value=""/></td>
                        <td style="text-align:right">
                            <div class="input-prepend">
                                <span class="add-on">Rp</span>                                
                                <input class="depositeAmount changeDiscount" id="changeDiscount depositeAmount" readOnly name="deposite[amount][]" type="text"  value="' . $model->balance_amount . '" >                                
                            </div>
                        </td>                                                        
                        <td style="text-align:center">
                            <div class="input-append">
                                <input style="width: 30px" class="" readOnly type="text" maxlength="3" value="" name="" id="" >
                                <span class="add-on">%</span>
                            </div>
                        </td>                                                        
                        <td style="text-align:right" class="subtot">' . landa()->rp($model->balance_amount) . '</td>                                                        
                    </tr>
                     <tr id="addDeposite" style="display:none">
                    </tr>  
                    
                    <script>                  
                        $(".depositeAmount").on("input", function() {                            
                            var amount = parseInt($(this).val());                                                                               
                            $(this).parent().parent().parent().find("td").eq(5).html("Rp. "+rp(amount));                            
                            subtotal();                            
                        });
                    </script>

                    ';
    }

    public function actionAddRow() {
        $model = ChargeAdditional::model()->findByPk((int) $_POST['additional_id']);
        if (count($model) > 0) {
            if (!empty($_POST['amount'])) {
                $charge = (!empty($_POST['charge'])) ? $_POST['charge'] : 0;
                
                $subtotal = ($charge * $_POST['amount']) - round(($charge * $_POST['amount']) * ($_POST['discount'] / 100));
                echo '                                                  
                    <tr class="items">
                        <input type="hidden" name="detail[id][]" id="' . $model->id . '" value="' . $model->id . '"/>                                                
                        <input type="hidden" name="detail[total][]" id="detTotal" class="detTotal" value="' . $subtotal . '"/>                                                                                                                              
                        <td style="text-align:center"><button class="delRow "><i class="icon-remove-circle" style="cursor:all-scroll;"></i></button></td>
                        <td>' . $model->fullInitialCategory . '</td>
                        <td style="text-align:center"><input type="text" maxlength="6" class="span1 detQty" name="detail[amount][]" id="detQty" value="' . $_POST['amount'] . '"/></td>
                        <td style="text-align:right">
                            <div class="input-prepend">
                                <span class="add-on">Rp</span>
                                <input class="changeDiscount detCharge angka" readOnly id="detCharge" name="detail[charge][]" type="text"  value="' . $charge . '" >                                
                            </div>
                        </td>                                                        
                        <td style="text-align:center">
                            <div class="input-append">
                                <input style="width: 30px" class="detDiscount changeDiscount" readOnly type="text" maxlength="3" value="' . $_POST['discount'] . '" name="detail[discount][]" id="detDiscount" >
                                <span class="add-on">%</span>
                            </div>
                        </td>                                                        
                        <td style="text-align:right" class="subtot">' . landa()->rp($subtotal) . '</td>                                                        
                    </tr>
                     <tr id="addRow" style="display:none">
                    </tr>';
            } else {
                echo '<tr id="addRow" style="display:none">
                    </tr>  ';
            }
        }
    }

    public function actionView($id) {
        $this->render('view', array(
            'model' => $this->loadModel($id),
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {
        $model = new BillCharge;
        $this->cssJs();
        if (isset($_POST['BillCharge'])) {
            $model->attributes = $_POST['BillCharge'];
            $model->code = SiteConfig::model()->formatting('billCharge', false);
            $model->is_temp = (isset($_POST['saveTemp'])) ? '1' : '0';
            if (!empty($_POST['detail'])) {
                if ($model->is_temp == 0) {
                    $bayar = $model->cash + $model->cc_charge + $model->ca_charge + $model->gl_charge;
                    $total = $model->total;
                    if ($bayar < $total) {
                        user()->setFlash('error', '<strong>Wrong Payment! </strong> Please check payment bellow..');
                        $this->redirect(array('create'));
                    } elseif ($model->gl_charge > 0 && $model->gl_room_bill_id == 0) {
                        user()->setFlash('error', '<strong>Wrong Payment! </strong> Please choose guest ledger name.');
                        $this->redirect(array('create'));
                    } elseif ($model->ca_charge > 0 && $model->ca_user_id == 0) {
                        user()->setFlash('error', '<strong>Wrong Payment! </strong> Please choose city ledger name.');
                        $this->redirect(array('create'));
                    }
                }
                if ($model->save()) {
                    //add citi ledger if by=ca
//                    if ($model->is_temp == 0 && !empty($model->ca_charge) && $model->ca_charge != 0) {
//                        $ca = new BillCa;
//                        $ca->bill_charge_id = $model->id;
//                        $ca->guest_user_id = $model->ca_user_id;
//                        $ca->charge = $model->ca_charge;
//                        $ca->charge_less = $model->ca_charge * -1;
//                        $ca->save();
//                    }

                    if (isset($_POST['deposite']['id'])) {
                        for ($i = 0; $i < count($_POST['deposite']['id']); $i++) {
                            $detail = new BillChargeDet;
                            $detail->bill_charge_id = $model->id;
                            $detail->deposite_id = $_POST['deposite']['id'][$i];
                            $detail->deposite_amount = $_POST['deposite']['amount'][$i];
                            $detail->save();

                            //update deposite when temp = 0
                            if ($model->is_temp == 0) {
                                $deposite = Deposite::model()->findByPk($_POST['deposite']['id'][$i]);
                                $deposite->balance_amount -= $_POST['deposite']['amount'][$i];
                                $deposite->used_amount += $_POST['deposite']['amount'][$i];
                                if ($deposite->balance_amount == $deposite->amount) {
                                    $deposite->is_applied = 1;
                                    $deposit->is_used = 1;
                                    $deposit->used_today = 1;
                                }
                                $deposite->save();
                            }
                        }
                    }

                    for ($i = 0; $i < count($_POST['detail']['id']); $i++) {
                        $detail = new BillChargeDet;
                        $detail->bill_charge_id = $model->id;
                        $detail->charge_additional_id = $_POST['detail']['id'][$i];
                        $detail->amount = $_POST['detail']['amount'][$i];
                        $detail->charge = $_POST['detail']['charge'][$i];
                        $detail->discount = $_POST['detail']['discount'][$i];
                        $detail->save();
                    }
                    $this->redirect(array('view', 'id' => $model->id, 'print' => $model->is_temp));
                } else {
                    throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
                }
            }
        }
        $model->code = SiteConfig::model()->formatting('billCharge');
        $this->render('create', array(
            'model' => $model,
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id) {
        $this->cssJs();
        $model = $this->loadModel($id);

        if ($model->is_temp == 0 && user()->roles_id != -1 && $model->is_na == 0) {
            user()->setFlash('error', '<strong>Sorry! </strong>This transaction has been paid, so it can not be edited. Call admin to edit this transaction. Thanks!.');
            $this->redirect(array('view', 'id' => $model->id));
        }
        if ($model->is_na == 1) {
            user()->setFlash('error', '<strong>Sorry! </strong>This transaction has Night Audited, so it can not be edited.');
            $this->redirect(array('view', 'id' => $model->id));
        }

        if (isset($_POST['BillCharge'])) {
            $model->attributes = $_POST['BillCharge'];
            $model->is_temp = (isset($_POST['saveTemp'])) ? '1' : '0';
            if (!empty($_POST['detail'])) {
                if ($model->is_temp == 0) {
                    $bayar = $model->cash + $model->cc_charge + $model->ca_charge + $model->gl_charge;
                    $total = $model->total;
                    if ($bayar < $total) {
                        user()->setFlash('error', '<strong>Wrong Payment! </strong> Please check payment bellow..');
                        $this->redirect(array('update', 'id' => $model->id));
                    } elseif ($model->gl_charge > 0 && $model->gl_room_bill_id == 0) {
                        user()->setFlash('error', '<strong>Wrong Payment! </strong> Please choose guest ledger name.');
                        $this->redirect(array('create'));
                    } elseif ($model->ca_charge > 0 && $model->ca_user_id == 0) {
                        user()->setFlash('error', '<strong>Wrong Payment! </strong> Please choose city ledger name.');
                        $this->redirect(array('create'));
                    }
                }
                if ($model->save()) {
                    //add citi ledger if by=ca
//                    if ($model->is_temp == 0 && !empty($model->ca_charge) && $model->ca_charge != 0) {
//                        $ca = new BillCa;
//                        $ca->bill_charge_id = $model->id;
//                        $ca->guest_user_id = $model->ca_user_id;
//                        $ca->charge = $model->ca_charge;
//                        $ca->charge_less = $model->ca_charge * -1;
//                        $ca->save();
//                    }

                    BillChargeDet::model()->deleteAll('bill_charge_id=' . $model->id);
                    if (isset($_POST['deposite']['id'])) {
                        for ($i = 0; $i < count($_POST['deposite']['id']); $i++) {
                            $detail = new BillChargeDet;
                            $detail->bill_charge_id = $model->id;
                            $detail->deposite_id = $_POST['deposite']['id'][$i];
                            $detail->deposite_amount = $_POST['deposite']['amount'][$i];
                            $detail->save();

                            //update deposite when temp = 0
                            if ($model->is_temp == 0) {
                                $deposite = Deposite::model()->findByPk($_POST['deposite']['id'][$i]);
                                $deposite->balance_amount -= $_POST['deposite']['amount'][$i];
                                $deposite->used_amount += $_POST['deposite']['amount'][$i];
                                if ($deposite->balance_amount == $deposite->amount) {
                                    $deposite->is_applied = 1;
                                    $deposit->is_used = 1;
                                    $deposit->used_today = 1;
                                }
                                $deposite->save();
                            }
                        }
                    }

                    for ($i = 0; $i < count($_POST['detail']['id']); $i++) {
                        $detail = new BillChargeDet;
                        $detail->bill_charge_id = $model->id;
                        $detail->charge_additional_id = $_POST['detail']['id'][$i];
                        $detail->amount = $_POST['detail']['amount'][$i];
                        $detail->charge = $_POST['detail']['charge'][$i];
                        $detail->discount = $_POST['detail']['discount'][$i];
                        $detail->save();
                    }
                    $this->redirect(array('view', 'id' => $model->id, 'print' => $model->is_temp));
                } else {
                    throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
                }
            }
        }

        $this->render('update', array(
            'model' => $model,
        ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id) {
        if (Yii::app()->request->isPostRequest) {
            // we only allow deletion via POST request
            $this->loadModel($id)->delete();
            BillChargeDet::model()->deleteAll('bill_charge_id=' . $id);
            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if (!isset($_GET['ajax']))
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
        } else
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    }

    /**
     * Lists all models.
     */
    public function actionIndex() {
        $criteria = new CDbCriteria();

        $model = new BillCharge('search');
        $model->unsetAttributes();  // clear any default values

        if (isset($_GET['BillCharge'])) {
            $model->attributes = $_GET['BillCharge'];



            if (!empty($model->id))
                $criteria->addCondition('id = "' . $model->id . '"');


            if (!empty($model->code))
                $criteria->addCondition('code = "' . $model->code . '"');


            if (!empty($model->guest_user_id))
                $criteria->addCondition('guest_user_id = "' . $model->guest_user_id . '"');


            if (!empty($model->description))
                $criteria->addCondition('description = "' . $model->description . '"');


            if (!empty($model->cash))
                $criteria->addCondition('cash = "' . $model->cash . '"');


            if (!empty($model->cc_number))
                $criteria->addCondition('cc_number = "' . $model->cc_number . '"');


            if (!empty($model->charge))
                $criteria->addCondition('charge = "' . $model->charge . '"');


            if (!empty($model->ca_user_id))
                $criteria->addCondition('ca_user_id = "' . $model->ca_user_id . '"');


            if (!empty($model->refund))
                $criteria->addCondition('refund = "' . $model->refund . '"');


            if (!empty($model->total))
                $criteria->addCondition('total = "' . $model->total . '"');


            if (!empty($model->created))
                $criteria->addCondition('created = "' . $model->created . '"');


            if (!empty($model->created_user_id))
                $criteria->addCondition('created_user_id = "' . $model->created_user_id . '"');


            if (!empty($model->modified))
                $criteria->addCondition('modified = "' . $model->modified . '"');
        }

        $this->render('index', array(
            'model' => $model,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new BillCharge('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['BillCharge']))
            $model->attributes = $_GET['BillCharge'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id) {
        $model = BillCharge::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'bill-charge-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    

}
