
<?php

class LandaProductPhoto extends CWidget {

    public $model;
    public $modelPhoto;

    public function run() {
        $this->registerScript();
        $name = (isset($this->model->name)) ? $this->model->name : '';
        $sImage = (isset($this->model->ProductPhoto->foto['big'])) ?  $this->model->ProductPhoto->foto['big'] : '';
        echo'
                                           <div class="clearfix" id="content"      >
    <div class="clearfix">
        <a href="' . $sImage . '" class="jqzoom" rel="gal1"  title="    ' . $name . '" >
            <img src="' . $sImage . '  "  title="' . $name . '"  style="border: 4px solid #666; width:100%;">
        </a>
    </div>
	<br/>';
        echo' <div class="clearfix" >
	<ul id="thumblist" class="clearfix" >';
        foreach ($this->modelPhoto as $oPhoto) {
            $sActive = ($this->model->product_photo_id == $oPhoto->id) ? 'zoomThumbActive' : '';
            echo'<li><a class="' . $sActive . '" href="javascript:void(0);" rel="{gallery: \'gal1\', smallimage: \'' . $oPhoto->foto['big'] . '\',largeimage: \'' . $oPhoto->foto['big'] . '\'}"><img src=\'' . $oPhoto->foto['big'] . '\' width="68" height="60"></a></li>';
        }

        echo'</ul>
	</div>
</div> ';
    }

    public function registerScript() {
        //cs()->registerScriptFile(bt('js/jquery-1.6.js'));
//        cs()->registerScriptFile(bt('js/jquery.jqzoom-core.js'));
        app()->landa->registerAssetCss('LandaProductPhoto.css');
        app()->landa->registerAssetScript('landaProductPhoto.js');
//        $assetUrl = app()->assetManager->publish(Yii::getPathOfAlias('ext.landa.assets'));
//        cs()->registerCssFile($assetUrl . '/css/landaIcoSocialMedia.css');
        cs()->registerScript('', '
                $(".jqzoom").jqzoom({
                    zoomType: "standard",
                    lens:true,
                    preloadImages: false,
                    alwaysOn:false
                });
        ', CClientScript::POS_END);
    }

}
?>


