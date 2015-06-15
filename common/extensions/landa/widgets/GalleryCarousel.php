<?php

class GalleryCarousel extends CWidget {

    public $gallery_category_id;

//    public $data = array();
//    public $labels = array();
//
//    public function run() {
//        echo "<img src=\"http://chart.apis.google.com/chart?chtt=" . urlencode ($this->title) . "&cht=pc&chs=300x150&chd=" . $this->encodeData($this->data) . "&chl=" . implode('|', $this->labels) . "\">";
//    }
//
//    protected function encodeData($data) {
//        $maxValue = max($data);
//        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
//        $chartData = "s:";
//        for ($i = 0; $i < count($data); $i++) {
//            $currentValue = $data[$i];
//            if ($currentValue > -1)
//                $chartData.=substr($chars, 61 * ($currentValue / $maxValue), 1);
//            else
//                $chartData.='_';
//        }
//        return $chartData . "&chxt=y&chxl=0:|0|" . $maxValue;
//    }

    public function run() {
        echo '<div class="jcarousel-container">
            <div class="jcarousel-clip">
            <ul id="our-clients" class="our-clients">';

        $oGallerys = Gallery::model()->findAll('gallery_category_id=:gallery_category_id', array(':gallery_category_id' => $this->gallery_category_id));
        foreach ($oGallerys as $oGallery) {
            $img = Yii::app()->landa->urlImg($oGallery->GalleryCategory->path, $oGallery->image, $oGallery->id);
            echo '<li><img src="'.$img['small'].'" alt=""></li>';
        }
        echo '</ul>
             </div>
             </div>';
    }

}