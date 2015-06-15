<?php

class LandaCarousel extends CWidget {

    public $data; //[link][img array()]
    public $linked;

    public function run() {
        $this->registerScript();

        echo '<div class="jcarousel-container">
            <div class="jcarousel-clip">
            <ul id="landaCarousel" class="landaCarousel">';
        foreach ($this->data as $link => $img) {
            $url = ($this->linked) ? $link : '#';
            echo '<li><a href="http://' . $url . '" target="_blank"><img src="' . $img['small'] . '" alt=""></a></li>';
            //echo '<li><a href="'.$oData->link.'"><img src="'.$oData->img['small'].'" alt=""></a></li>';
        }
        echo '</ul>
             </div>
             </div>';
    }

    public function registerScript() {
        cs()->registerScript('', '
            $("#landaCarousel").jcarousel();
            ', CClientScript::POS_READY);
        landa()->registerAssetScript('jcarousel.js', CClientScript::POS_END);
        landa()->registerAssetCss('landaCarousel.css');
    }

}
