<?php

class LandaListViewCart extends CWidget {

    public $value;

    public function run() {
        $this->registerScript();
        $value = Yii::app()->user->getState("cart");
        $items = explode(',', $value);
        if (!empty($value)) {
            $count = count($items);
        } else {
            $count = 0;
        }
        foreach ($items as $item) {
            $contents[$item] = (isset($contents[$item])) ? $contents[$item] + 1 : 1;
        }
        $total = 0;
        $data = '            
            <div id="ListViewCart" class="ListViewCart container " >
                    <div class="row">
                            <div class="span9">
                                    <table class="table">
                                            <thead>
                                                    <tr>
                                                            <th><span>Image</span></th>
                                                            <th class="desc"><span>Product Name</span></th>
                                                            <th><span>Quantity</span></th>
                                                            <th><span>Unit Price</span></th>
                                                            <th><span>Sub Price</span></th>
                                                            <th><span>Action</span></th>
                                                    </tr>
                                            </thead>
                                            <tbody>';

        $fieldID = '';
        if (!empty($value)) {
            foreach ($contents as $id => $qty) {
                $model = Product::model()->findByPk($id);
                $subTot = $model->price_sell * $qty;
                ($fieldID == '') ? $fieldID = $model->id : $fieldID.=',' . $model->id;
                $data.='<tr>
                        <td>
                            <a href="' . $model->url . '"><img style="width:30%;" src="' . $model->imgUrl['small'] . '" alt="product image"></a>
                        </td>
                        <td class="desc">
                                <h4><a href="' . $model->url . '" class="invarseColor">
                                        ' . $model->name . '
                                </a></h4>
                                
                                <ul class="unstyled">
                                        
                                        <li>Product Code : ' . $model->code . '</li>
                                </ul>

                        </td>
                        <td class="quantity">
                                <div class="input-prepend input-append">

                                        <input type="text" id="' . $model->id . '" class="' . $model->id . '" name="" value="' . $qty . '">

                                </div>
                        </td>
                        <td class="sub-price">
                                <h2>' . landa()->rp($model->price_sell) . '</h2>
                        </td>
                        <td class="total-price">
                                <h2>' . landa()->rp($subTot) . '</h2>									
                        </td>
                        <td>	
                                <button onClick="AddCart(\'delete\',\'' . $model->id . '\',0)" class="btn btn-small btn-danger" data-title="Remove" data-placement="top" data-tip="tooltip" data-original-title=""><i class="icon-trash"></i></button>
                        </td>
                </tr>';

                $total = $total + ($model->price_sell * $qty);
            }
        }
        $data .= '</tbody>
                    </table>
			</div><!--end span12-->				
                        <div class="span5"></div>
			<div class="span4">
					
                            <table class="table table-receipt" style="margin:20px 0 0 0 !important">
                                    <tbody>				
                                    <tr>
                                            <td class="alignRight"><h2>Total</h2></td>
                                            <td class="alignLeft" colspan="2"><h2>' . landa()->rp($total) . '</h2></td>
                                    </tr>
                                    <tr>
                                            <td class="alignRight"><button class="btn" onClick="window.location = \'' . url('index') . '\';" >Contuine Shoping</button></td>
                                            <td class="alignLeft"><button onClick="AddCart(\'update\',\'' . $fieldID . '\',0)" class="btn btn-success">Update Cart</button></td>
                                            <td class="alignLeft"><a href="' . url('product/checkout') . '" class="btn btn-small btn-primary">Checkout</a></td>
                                    </tr>
                            </tbody></table>
                            <input type="hidden" name="landaTotal" id="landaTotal" value="(' . $count . ')"/>                            
			</div><!--end span5-->
			</div><!--end row-->
		</div>';
        echo $data;
    }

    public function registerScript() {
        app()->landa->registerAssetCss('landaListProduct.css');
    }

}

?>