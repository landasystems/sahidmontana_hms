<?php

class RoomTypeController extends Controller {

    

    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = 'main';

    public function filters() {
        return array(
            'accessControl', // perform access control for CRUD operations
        );
    }

    public function accessRules() {
        return array(
            array('allow', // c
                'actions' => array( 'create'),
                'expression' => 'app()->controller->isValidAccess("RoomType","c")'
            ),
            array('allow', // r
                'actions' => array('index', 'view'),
                'expression' => 'app()->controller->isValidAccess("RoomType","r")'
            ),
            array('allow', // u
                'actions' => array( 'update'),
                'expression' => 'app()->controller->isValidAccess("RoomType","u")'
            ),
            array('allow', // d
                'actions' => array('delete'),
                'expression' => 'app()->controller->isValidAccess("RoomType","d")'
            )
        );
    }

    public function cssJs() {
        cs()->registerScript('', '    
                        
                        function rp(angka){
                            var rupiah = "";
                            var angkarev = angka.toString().split("").reverse().join("");
                            for(var i = 0; i < angkarev.length; i++) if(i%3 == 0) rupiah += angkarev.substr(i,3)+".";
                            return rupiah.split("",rupiah.length-1).reverse().join("");
                        };
                        function calculate(){
                            var mysub = $("#charge").val() * $("#amount").val();                            
                            $("#addSubtotal").html("Rp. " + rp(mysub));                                                       
                                            
                        };
                        
                        function pay(){                         
                            var refund = 0;                            
                            var cash = parseInt($("#BillCharge_cash").val()) 
                            var credit = parseInt($("#BillCharge_charge").val()) 
                            var total = parseInt($("#BillCharge_total").val()) 
                            cash = cash || 0;
                            credit = credit || 0;
                            total = total || 0;
                            
                             refund =  cash + credit - total;
                                                       
                            $("#BillCharge_refund").val(refund);
                                            
                        };
                        
                        function subtotal(total){
                            subTotal = 0;
                            $(".detTotal").each(function() {
                                 subTotal += parseInt($(this).val());                                 
                            });
                            $("#total").html("Rp. " + rp(subTotal));
                            $("#BillCharge_total").val(subTotal);                           
                            
                        }
                        
                        function totalRate(){                            
                            $(".roomRate").each(function() {
                                var totRate = 0;
                                var totPackage = parseInt($("#BillCharge_total").val());
                                totPackage = totPackage || 0;
                                var stadard = parseInt($(this).val());
                                stadard = stadard || 0;
                                var pax = parseInt($(".pax").val());
                                pax = pax || 0;
                                var fnb = parseInt($(".fnb").val());
                                fnb = fnb || 0;
                                totFnB = fnb*pax;
                                $(this).parent().parent().parent().find(".defaultRate").val(stadard+totPackage+totFnB);                         
                            });                                               
                            
                        }
                        
                        function clearField(){
                            $("#stock").html("");
                            $("#amount").val("");
                            $("#price_buy").val("");
                            $("#s2id_product_id").select2("data", null)
                            $(".measure").html("");
                            $("#addDate").html("");
                            $("#addPrice").html("");
                            $("#addSubtotal").html("");
                            $("#addNumber").html("");
                        }
                                                                     
                        $("body").undelegate("#yt0","click");                                               
                        
                        $("#amount").on("input", function() {
                            calculate();
                        });
                        
                        $("#charge").on("input", function() {
                            calculate();
                        });
                        
                        $(".pax").on("input", function() {
                            totalRate();
                        });
                        $(".fnb").on("input", function() {
                            totalRate();
                        });
                        
                        $(".fnb").on("input", function() {
                            totalRate();
                        });
                        
                        $(".roomRate").on("input", function() {
                            var totPackage = parseInt($("#BillCharge_total").val());
                            totPackage = totPackage || 0;
                            var stadard = parseInt($(this).val());
                            stadard = stadard || 0;
                            var pax = parseInt($(".pax").val());
                            pax = pax || 0;
                            var fnb = parseInt($(".fnb").val());
                            fnb = fnb || 0;
                            totFnB = fnb*pax;
                            $(this).parent().parent().parent().find(".defaultRate").val(stadard+totPackage+totFnB);  
                        });
                        
                        $(".delRow").on("click", function() {
                            $(this).parent().parent().remove();
                            subtotal(0);
                            totalRate();
                        });
                        
                        
                    ');
    }

    public function actionAddRow() {
        $model = ChargeAdditional::model()->findByPk((int) $_POST['additional_id']);
        if (count($model) > 0) {
            if (!empty($_POST['amount'])) {
                echo '                                                  
                    <tr class="items">
                        <input type="hidden" name="detail[id][]" id="' . $model->id . '" value="' . $model->id . '"/>                        
                        <input type="hidden" name="detail[amount][]" id="detQty" value="' . $_POST['amount'] . '"/>                        
                        <input type="hidden" name="detail[charge][]" id="detCharge"  value="' . $_POST['charge'] . '"/>                                                    
                        <input type="hidden" name="detail[total][]" id="detTotal" class="detTotal" value="' . $_POST['charge'] * $_POST['amount'] . '"/>                                                    
                            
                        <td style="text-align:center"><i class="delRow icon-remove-circle" style="cursor:all-scroll;"></i></td>
                        <td> &nbsp;&nbsp;&raquo; ' . $model->name . '</td>
                        <td style="text-align:center">' . $_POST['amount'] . '</td>' .
                '<td style="text-align:right">' . landa()->rp($_POST['charge']) . '</td>                                                        
                        <td style="text-align:right">' . landa()->rp($_POST['charge'] * $_POST['amount']) . '</td>                                                        
                    </tr>
                     <tr id="addRow" style="display:none">
                    </tr>                       
                    ';
            } else {
                echo '<tr id="addRow" style="display:none">
                    </tr>  ';
            }
        }else{
            echo '<tr id="addRow" style="display:none">
                    </tr>  ';
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
        $model = new RoomType;
        $this->cssJs();

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['RoomType'])) {
            $model->attributes=$_POST['RoomType'];
            
            $decode = Roles::model()->guest();
            foreach ($decode as $json => $val) {
                if (isset($_POST[$json]))
                    $array[$json] = $_POST[$json];
            }

            $package = array();
            if (!empty($_POST['detail'])) {
                for ($i = 0; $i < count($_POST['detail']['id']); $i++) {
                    $package[$i]['id'] = $_POST['detail']['id'][$i];
                    $package[$i]['amount'] = $_POST['detail']['amount'][$i];
                    $package[$i]['charge'] = $_POST['detail']['charge'][$i];
                    $package[$i]['total'] = $_POST['detail']['total'][$i];
                }
            }
            $model->charge_additional_ids = json_encode($package);
            $model->rate = json_encode($array);
            $model->attributes = $_POST['RoomType'];
            if ($model->save()){
                user()->setFlash('success',"Saved successfully");
                $this->redirect(array('view', 'id' => $model->id));
            }
        }

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
        $model = $this->loadModel($id);
        $this->cssJs();

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['RoomType'])) {
            $model->attributes=$_POST['RoomType'];            
            
            $decode = Roles::model()->guest();
            foreach ($decode as $json => $val) {
                if (isset($_POST[$json]))
                    $array[$json] = $_POST[$json];
            }
            $package = array();
            if (!empty($_POST['detail'])) {
                for ($i = 0; $i < count($_POST['detail']['id']); $i++) {
                    $package[$i]['id'] = $_POST['detail']['id'][$i];
                    $package[$i]['amount'] = $_POST['detail']['amount'][$i];
                    $package[$i]['charge'] = $_POST['detail']['charge'][$i];
                    $package[$i]['total'] = $_POST['detail']['total'][$i];
                }
            }

            $model->charge_additional_ids = json_encode($package);
            $model->rate = json_encode($array);

            $model->attributes = $_POST['RoomType'];
            if ($model->save())
                $this->redirect(array('view', 'id' => $model->id));
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
        cs()->registerScript('', '$("#myTab a").click(function(e) {
                    e.preventDefault();
                    $(this).tab("show");
                });  '
        );

        $model = new RoomType('search');
        $model->unsetAttributes();  // clear any default values
        $model->is_package=1;
        $model2 = new RoomType('search');
        $model2->unsetAttributes();  // clear any default values
        $model2->is_package=0;

        if (isset($_GET['RoomType'])) {
            $model->attributes = $_GET['RoomType'];
        }

        $this->render('index', array(
            'model' => $model,
            'model2' => $model2,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new RoomType('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['RoomType']))
            $model->attributes = $_GET['RoomType'];

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
        $model = RoomType::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'room-type-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    

}
