<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ArticleAccordion
 *
 * @author landa
 */
class LandaTestimonial extends CWidget {

//    public $socials;
    public $arg;

    //put your code here
    public function run() {
        $this->registerScript();
        
        $model = Testimonial::model()->findAll($this->arg);
        foreach ($model as $m) {
            echo '<div class="testimonial">
                            <div class="testimonial-content"><p>' . $m->testimonial . '.</p>
                                <div class="testimonial-arrow"></div>
                            </div>
                            <div class="testimonial-brand">
                                <img src=' . $m->img['small'] . ' alt="">
                                <h6><strong>' . $m->name . '</strong><br>
                                    <em>' . $m->corporate . '</em></h6>
                            </div>
                        </div>';
        }
        
    }

    public function registerScript() {
        app()->landa->registerAssetCss('landaTestimonial.css');
//        $assetUrl = app()->assetManager->publish(Yii::getPathOfAlias('ext.landa.assets'));
//        cs()->registerCssFile($assetUrl . '/css/landaIcoSocialMedia.css');
    }

}
?>

