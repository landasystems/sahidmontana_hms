
<?php

class LandaCategoryProduct extends CWidget {

    public $model;

    public function run() {
    $siteConfig = SiteConfig::model()->listSiteConfig();
//    $stock= ProductStock::model()->departement($model->stock, $siteConfig->departement_id);
        if ($siteConfig->product_layout == 1) {
            $this->registerScript();
            echo'
                 <div class="row-fluid">
            <ul class="hProductItems thumbnails" id="cycleFeatured">';
//            $model = Product::model()->findAll($this->arg);
            foreach ($this->model as $m) {
            $stock= ProductStock::model()->departement($m->stock, $siteConfig->departement_id);
                echo'
                <li class="span3 img-polaroid">
                <div class="thumbnail">
                        <a href="' . $m->url . '"><center><img src="' . $m->imgUrl['medium'] . '" style="max-height: 160px" alt=""></center></a>
                </div>
                <div class="thumbSetting">
                        <div class="thumbTitle">
                                <h3>
                                <a href="' . $m->url . '" class="invarseColor" style="text-decoration:none">' . $m->name . '</a>
                                </h3>
                        </div>
                        
                        
                        <div class="thumbPrice">
                                <span> ' . landa()->rp($m->price_sell) . '</span>
                        </div>';

                if (in_array('ecommerce', param('menu'))) {
                    if($stock == 0){
                        echo'<div class="thumbButtons">
                                <a href="" style="text-decoration:none"><button class="btn btn-danger btn-small btn-block" >
                                        STOCK HABIS
                                </button></a>
                        </div>';
                    }else{
                        echo' <div class="thumbButtons">
                                <button class="btn btn-success btn-small btn-block" onClick="AddCart(\'add\',\'' . $m->id . '\',1)">
                                        BELI
                                </button>
                        </div>'; 
                    }
                   
                } else {
                    echo'<div class="thumbButtons">
                                
                        </div>';
                }
                echo'</div>
        </li>                
';
            }
            echo'</ul></div><div class="clearfix"></div>';
        } else {
//           $this->registerScript;
//                   if(empty($this->model)) {
//            echo'<div class="alert alert-block">
//  
//  <h4>Sorry!</h4>
//  Di kategori ini produk masih kosong.
//</div>';
//        } else {
            echo'
                 <div class="row-fluid">
            <ul class="hProductItems thumbnails" id="cycleFeatured">';
//            $model = Product::model()->findAll($this->arg);
            foreach ($this->model as $m) {
                echo'
                <li class="span3 ">
                <div class="thumbnail">
                        <a href="' . $m->url . '"><center><img src="' . $m->imgUrl['small'] . '" style="max-height: 160px;" alt=""></center></a>
                </div>
                <div class="thumbSetting">
                        <div class="nama">
                                <h3>
                                <a href="' . $m->url . '" class="invarseColor">' . $m->name . '</a>
                                </h3>
                        </div>
                        
                        
                        <div class="harga">
                                <span> ' . landa()->rp($m->price_sell) . '</span>
                        </div>';

                if (in_array('ecommerce', param('menu'))) {
                    echo' <div class="thumbButtons">
                                <button class="btn btn-primary btn-small btn-block" onClick="AddCart(\'add\',\'' . $m->id . '\',1)">
                                        BELI
                                </button>
                        </div>';
                } else {
                    echo'<div class="thumbButtons">
                                
                        </div>';
                }
                echo'</div>
        </li>                
';
            }
            echo'</ul></div><div class="clearfix"></div>';
        
        }
    }

    public function registerScript() {
        app()->landa->registerAssetCss('landaListProduct.css');
//        $assetUrl = app()->assetManager->publish(Yii::getPathOfAlias('ext.landa.assets'));
//        cs()->registerCssFile($assetUrl . '/css/landaIcoSocialMedia.css');
    }

}
?>

