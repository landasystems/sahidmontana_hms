<?php

class LandaInvoice extends CWidget {

    public $id;

    public function run() {
        $model = Sell::model()->findByPk($this->id);
        $infos = SellInfo::model()->findAll(array('condition' => 'sell_id=' . $model->id));
        $details = SellDet::model()->findAll(array('condition' => 'sell_id=' . $model->id));
        foreach ($infos as $info) {
            
        }
        $city = City::model()->findByPk($info->city_id);
        $infoStat = ($info->status == 'confirm') ? '<span class="label label-info">Confirm</span>':'<span class="label label-warning">Pending</span> ';
        $infoconf = ($info->status != 'confirm') ? '<a href="' . url('payment/create/' . $model->id) . '" class="btn btn-primary pull-right" style="margin-right:10px"><i class="icon-check"></i> Konfirmasi Pembayaran</a>' :''; 
        $data = '<div class="checkout-outer">
        <div class="checkout-header" style="">      <br>      
            <h2><i class="icon-shopping-cart"></i> INVOICE # ' . $model->code . ''.$infoconf.'
               
        </h2>
            <h2></h2>    
            <br>
        </div><!--end checkout-header-->
        <div class="checkout-content">';
        $data .= '            
            <div id="ListViewCart" class="" >
                    <div class="">
                            <div class="">                              
                            <table>                            
                            <tr>
                            <td class="span1">Status</td>
                            <td class="">:</td>
                            <td class="span5"> ' .$infoStat . '</td>
                            
                            <td class="span1">Province</td>
                            <td class="">:</td>
                            <td class="span5">' . $city->Province->name . '</td>
                            </tr>
                            <tr>
                            <td class="span1">Name</td>
                            <td class="">:</td>
                            <td class="span5">' . ucwords($info->name) . '</td>
                            
                            <td class="span1">City</td>
                            <td class="">:</td>
                            <td class="span5">' . $city->name . '</td>
                            </tr>    
                            <tr>
                            <td class="span1">Phone</td>
                            <td class="">:</td>
                            <td class="span5">' . landa()->hp($info->phone) . '</td>
                            
                            <td class="span1">Address</td>
                            <td class="">:</td>
                            <td class="span5">' . $info->address . '</td>
                            </tr>  
                            <tr>
                            <td class="span1">Note</td>
                            <td class="">:</td>
                            <td class="span5">' . $model->description . '</td>
                            
                            <td class="span1">Resi</td>
                            <td class="">:</td>
                            <td class="span5"><span style="text-transform:uppercase"><b>'.$model->resi.'</b></span></td>
                            </tr> 
                            </table>                             
                            <hr>
                                <table class="table">
                                        <thead style="background:#e7e7e7 !important">
                                                <tr>
                                                        <th><span>Image</span></th>
                                                        <th class="desc"><span>Product Name</span></th>
                                                        <th><span>Quantity</span></th>
                                                        <th><span>Unit Price</span></th>
                                                        <th><span>Sub Price</span></th>                                                        
                                                </tr>
                                        </thead>
                                        <tbody>';
        $total = 0;
        $ongkir = 0;

        function bulatkan($numb) {
            if ($numb == '0') {
                $num1 = '1';
                return $num1;
            } else {
                return $numb;
            }
        }

        foreach ($details as $detail) {
            $product = Product::model()->findByPk($detail->product_id);
            $subTot = $detail->price * $detail->qty;
            $url = (!empty($product->url)) ? $product->url : '';
            $imgUrl = (!empty($product->imgUrl)) ? $product->imgUrl : '';
            $name = (!empty($product->name)) ? $product->name : '';
            $code = (!empty($product->code)) ? $product->code : '';

            $data.='<tr>
                        <td>
                            <a href="' . $url . '"><img style="width:30%;" src="' . $imgUrl['small'] . '" alt="product image"></a>
                        </td>
                        <td class="desc">
                                <h4><a href="' . $url . '" class="invarseColor">
                                        ' . $name . '
                                </a></h4>
                                
                                <ul class="unstyled">
                                        
                                        <li>Product Code : ' . $code . '</li>
                                </ul>

                        </td>
                        <td class="quantity">                                
                                       <h2> ' . $detail->qty . '</h2>
                        </td>
                        <td class="sub-price">
                                <h4>' . landa()->rp($detail->price) . '</h4>
                        </td>
                        <td class="total-price">
                                <h4>' . landa()->rp($subTot) . '</h4>									
                        </td>              
                </tr>';

            $total = $total + ($detail->price * $detail->qty);
            $ceil = ceil($product->weight); //pembulatan 0
            $ongkir = $ongkir + ($detail->qty * bulatkan($ceil)); // jumlah barang dikali pembulatan berat
        }
        $ongkrm = $ongkir * $city->charge;
        $data .= '<tr><td style="text-align:right" colspan="4">
            
                           <h4>Shipping</h4>
                        </td>
                        <td class="total-price">
                                <h4>' . landa()->rp($ongkrm) . '</h4>									
                        </td>              
                </tr>
<tr>
                        <td style="text-align:right" colspan="4">
                            <h4>Total</h4>
                        </td>
                        <td class="total-price">
                                <h4>' . landa()->rp($total + $ongkrm) . '</h4>									
                        </td>              
                </tr>



                        </tbody>
                    </table>
			</div>
		
			</div><!--end row-->
		</div>';

        $account = SiteConfig::model()->findByPk(1);
        $variable = explode('<br>', ucwords($account->bank_account));
        $bank = "";
        $icond =ucwords($account->bank_account);
        //function iconz($ico){
          //  if(strpos($ico,'bni') !== false){
          //      return 'bni';
          //  }
            
         //   elseif(strpos($ico,'bri') !== false){
         //       return 'bri';
         //   }
         //    elseif(strpos($ico,'bca') !== false){
         //       return 'bca';
         //   }
         //    elseif(strpos($ico,'mandiri') !== false){
         //       return 'mandiri';
         //   }
        //}
        foreach ($variable as $key => $value) {
            $bank .= '<img src="'.param('urlImg').'file/bca.png"> ' . $value . '<br>';
        }

        $data .= '<br>
            <div class="alert">   
                <h1>
                    Cara Pembayaran
                </h1><br> <ol>          
                <li> Pesanan Anda baru Kami kirim setelah Kami menerima pembayaran dari Anda.</li>
                <li> Biaya yang harus Anda kirim adalah sebesar <span class="label label-warning">' . landa()->rp($total + $ongkrm) . '</span>.</li>
                <li> Kirim ke Rekening IndoMobile Cell:  <br>' . $bank . '</li>
                <li> Setelah melakukan Transfer mohon konfirmasikan dengan cara mengklik tombol <span class="label label-info">Konfirmasi Pembayaran </span> yang berada di atas.</li>
            
            </ol>
            <br><br>
            

            </div>
            </div>
            </div>';
        echo $data;
    }

}

?>