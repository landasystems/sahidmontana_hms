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
class LandaInformation extends CWidget {

    public $socials; //[fb]
    public $info; //[created_user_name][created][hits]

    //put your code here
    public function run() {
        echo '<script type="text/javascript">var switchTo5x=true;</script>
<script type="text/javascript" src="http://w.sharethis.com/button/buttons.js"></script>
<script type="text/javascript">stLight.options({publisher: "1a5f2107-9bc0-4fe6-972b-b603bc6e1d3f", doNotHash: false, doNotCopy: false, hashAddressBar: false});</script>
<span class="st_facebook_hcount" displayText="Facebook"></span>
<span class="st_twitter_hcount" displayText="Tweet"></span>
<span class="st_googleplus_hcount" displayText="Google +"></span>
<span class="st_email_hcount" displayText="Email"></span>';
    }
//    public function run() {
//        $this->registerScript();
//
//        if ($this->socials) {
//            $sSocials = '<ul class="post_meta_links pull-right">
//                        <li><div id="fb-root"></div><fb:like href="'.$this->socials['fb'].'" send="false" layout="button_count" width="" show_faces="true" font=""></fb:like></td></li>
//                        <li><a href="http://twitter.com/share" class="twitter-share-button" data-count="horizontal">Tweet</a></li>
//                        <li><g:plusone size="medium"></g:plusone></li>
//                    </ul>';
//        } else {
//            $sSocials = '';
//        }
//
//        echo '<div id="social-button" class="row-fluid">
//                <div class="span6">
//                    <ul class="post_meta_links">
//                        <li><a href="#"><i class="icon-user"></i>'.$this->info['created_user_name'].'</a></li>
//                        <li><a href="#"><i class="icon-calendar"></i>'.$this->info['created'].'</a></li> 
//                        <li><a href="#"><i class="icon-eye-open"></i>'.$this->info['hits'].'</a></li>
//                    </ul>
//                </div>
//                <div class="span6">
//                    ' . $sSocials . '
//                </div>
//            </div>';
//    }
//
//    public function registerScript() {
//        app()->landa->registerAssetCss('landaInformation.css');
//    }

}
?>

