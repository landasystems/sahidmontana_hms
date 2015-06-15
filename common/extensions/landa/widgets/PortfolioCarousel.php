<?php

class PortfolioCarousel extends CWidget {

    public $limit;
    public $order;

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
        Yii::app()->landa->registerAssetCss('PortfolioCarousel.css');

        $Criteria = new CDbCriteria();
//        $Criteria->condition = "price > 30";
        $Criteria->limit = $this->limit;
        $Criteria->order = $this->order;

        echo '<div class="jcarousel-container">
             <div class="jcarousel-clip">
             <ul id="latest-projects" class="jcarousel-list">';

        $oPortfolios = Portfolio::model()->findAll($Criteria);
        $image = array('image_name'=>'');
        foreach ($oPortfolios as $oPortfolio) {
            $image = json_decode($oPortfolio->image, true);
            $img = Yii::app()->landa->urlImg('portfolio/', $image['image_name'] , $image['id']);
            
            echo '<li class = "portfolio-item four columns jcarousel-item">
                <a href = "#"> <div class="portofolio-cimg"><img src = "' . $img['small'] . '" alt = ""></div>
                <h5> ' . $oPortfolio->title . ' </h5>
                </a>
                <em>' . $oPortfolio->PortfolioCategory->name . '</em> 
                </li>';
        }
        echo '</ul>
              </div>
              </div>';
    }

}