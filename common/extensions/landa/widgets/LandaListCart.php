<?php

class LandaListCart extends CWidget {

    public $value;

    public function run() {
        $this->registerScript();
        $items = explode(',', $this->value);
        if ($this->value != '') {
            $count = count($items);
        } else {
            $count = 0;
        }
        foreach ($items as $item) {
            $contents[$item] = (isset($contents[$item])) ? $contents[$item] + 1 : 1;
        }
        $total = 0;
        $data = '<table class="table-cart"><tbody>';

        if (!empty($this->value)) {

            foreach ($contents as $id => $qty) {
                $model = Product::model()->findByPk($id);
                $data .= '<tr id="list">
                        <td class="cart-product-info">
                            <a href="' . $model->url . '"><img style="width:30%;" src="' . $model->imgUrl['small'] . '" alt="product image"></a>
                            <div class="cart-product-desc">
                                <p><a class="invarseColor" href="' . $model->url . '">' . $model->name . '</a></p>

                            </div>
                        </td>
                        <td class="cart-product-setting">
                            <p><strong>' . $qty . 'x-' . landa()->rp($model->price_sell) . '</strong></p>
                            <a href="#" onClick="AddCart(\'delete\',\'' . $model->id . '\',1)" class="btn btn-mini remove-pro" data-tip="tooltip" data-title="Delete"><i class="icon-trash"></i></a>
                        </td>
                    </tr>';
                $total = $total + ($model->price_sell * $qty);
            }
        }
        $data .='</tbody>
            <tfoot>
                <tr>
                    <td class="cart-product-info">
                        <a href="' . url('product/viewcart') . '" class="btn btn-small">Vew cart</a>
                        <a href="' . url('product/checkout') . '" class="btn btn-small btn-primary">Checkout</a>
                        <input type="hidden" name="landaTotal" id="landaTotal" value="(' . $count . ')"/>
                    </td>
                    <td>
                        <h3>' . landa()->rp($total) . '</h3>
                    </td>
                </tr>
            </tfoot>
            </table>';

        echo $data;        
    }

    public function registerScript() {
        cs()->registerScript('', '
            function AddCart(action,id,state) {
                var updateCount = 0;                
                if (action=="add"){
                     var qty = $("#QTY").val();
                     if (qty<1) {
                        alert("Quantity minimum is 1");
                        exit;
                    }
                     if (qty>1){
                        var newID =id;
                        for (var i = 1; i < qty; i++) {
                              newID=newID+","+id;
                        }
                        id=newID;
                     };
                }else if (action=="update"){
                    var result = id.split(",");
                    var resultValue="";
                    for (index = 0; index < result.length ; ++index) {
                        if (document.getElementById(result[index]).value<1){
                            alert("Quantity minimum is 1. Use delete button to remove product.");
                            exit;
                        }

                        if (resultValue==""){                        
                           resultValue = document.getElementById(result[index]).value;                      
                        }else{
                            resultValue = resultValue + "," +document.getElementById(result[index]).value;     
                        }
                        
                    }                                       
                }

                $.ajax({
                    type : "POST",
                    data : "product_id="+id+"&action="+action+"&resultValue="+resultValue,
                    url : "' . url('product/addCart') . '",
                    success : function(data){
                       $(".table-cart").replaceWith(data);      
                       if (state==1){
                        $(".dropdown-menu").fadeIn();
                       }else{
                        $(".dropdown-menu").hide();
                       }
                       $("html, body").animate({ scrollTop: 0 }, "slow");
                       $("#totalCart").html($("#landaTotal").val());
                    }
                 });
                
                $.ajax({
                    type : "POST",
                    data : "",
                    url : "' . url('product/updateCart') . '",
                    success : function(data){
                       $(".ListViewCart").replaceWith(data);                                                                   
                    }
                 });

                  
            }
            
            function showHide() {
                var e = document.getElementById("cart_div");
                if (e.style.display == "block")
                    e.style.display = "none";
                else
                    e.style.display = "block";
            }

            function showCheckout(id) {                
                if (id == 1) {
                    $(".other").hide();
                    $(".current").fadeIn();
                    $(".addressDestination1").prop("checked", true);              
                } else {
                    $(".current").hide();
                    $(".other").fadeIn();
                    $(".addressDestination0").prop("checked", true); 
                }
            }

            function total() {
                $("#totalCart").html($("#landaTotal").val());                
            }
                        
         
            ', CClientScript::POS_BEGIN);        
    }
    

}

?>
