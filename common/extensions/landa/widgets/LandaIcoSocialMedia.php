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
class LandaIcoSocialMedia extends CWidget {
    public $socials;
    public $type;
    //put your code here
    public function run() {
        $siteConfig = SiteConfig::model()->listSiteConfig();
        
        $this->registerScript();
        if ($this->type == 'circle'){
            echo '<a href="#" class="icn facebook">Facebook</a>';
            echo '<a href="#" class="icn twitter">Twitter</a>';
        }elseif ($this->type == 'horizontal') {
            echo '<div class="contact-social">
                                        <div><strong>Follow us:</strong> <br> <a href="http://www.twitter.com/'. $siteConfig->url_twitter. '" target="_" class="btn_twitter">Twitter</a></div>
                                        <div><strong>Join us:</strong> <br> <a href="http://www.facebook.com/'. $siteConfig->url_facebook. '" target="_" class="btn_fb">Facebook</a></div>
                                        <div><strong>Chat with us</strong> <br> <a href="ymsgr:sendIM?'.$siteConfig->url_ym.'">
<img border=0 src="http://opi.yahoo.com/online?u=indomobilecell&amp;m=g&amp;t=2" /></a></div>
                                        <div class="clear"></div>
                                    </div>';
        }elseif ($this->type == 'vertical'){
            echo '                          <ul class="vProductItemsTiny">
                                        <li><strong>Follow us:</strong> <br> <a href="http://www.twitter.com/'. $siteConfig->url_twitter. '" target="_" class="btn_twitter">Twitter</a></li>
                                        <li><strong>Join us:</strong> <br> <a href="http://www.facebook.com/'. $siteConfig->url_facebook. '" target="_" class="btn_fb">Facebook</a></li>
                                        <li><strong>Chat with us</strong> <br> <a href="ymsgr:sendIM?'.$siteConfig->url_ym.'">
<img border=0 src="http://opi.yahoo.com/online?u=indomobilecell&amp;m=g&amp;t=2" /></a></li>
</ul>
                                        <div class="clear"></div>
                                    </div>';
        }
    }

    public function registerScript() {
        app()->landa->registerAssetCss('landaIcoSocialMedia.css');
//        $assetUrl = app()->assetManager->publish(Yii::getPathOfAlias('ext.landa.assets'));
//        cs()->registerCssFile($assetUrl . '/css/landaIcoSocialMedia.css');
    }

}

?>

