<?php

/**
 * Description of ArticleAccordion
 *
 * @author landa
 */
class LandaSearch extends CWidget {

    public $url;
    public $class;

//    public $type;
    //put your code here
    public function run() {
        $this->registerScript();
        echo '<div id="searchform" class="row-fluid">
            <div class="input-append">
		<input type="text" placeholder="Enter Search keywords..." id="inputString" onkeyup="lookup(this.value);" 
                    class="' . $this->class . ' span12" />
                <button class="btn search" type="button"><i class="icon-search"></i></button>
            </div>
                <div class="clearfix"></div>
		<div id="suggestions"></div>
            </div>';
    }

    public function registerScript() {
        //landa()->registerAssetCss('landaSearch.css');
        cs()->registerScript('landa', '
            function lookup(inputString) {
                if (inputString.length == 0) {
                    $("#suggestions").fadeOut(); // Hide the suggestions box
                } else if (inputString.length > 3) {
                    $.post("' . $this->url . '", {queryString: "" + inputString + ""}, function(data) { // Do an AJAX call
                        var arr = JSON.parse(data);
                        var result = "<p id=\"searchresults\">";
                        for (var i = 0; i < arr.length; i++) {
                            result += "<a href=\""+arr[i]["url"]+"\">";
                            result += "<img src=\"" +arr[i]["img"]+ "\" class=\"img-polaroid\"/>";
                            result += "<span class=\"searchheading\">" + arr[i]["title"] + "</span>";
                            result += "<span>" + arr[i]["description"] + "</span>";
                            result += "</a>";
                        };
                        result += "</p>";

                        $("#suggestions").html(result); // Fill the suggestions box
                        $("#suggestions").fadeIn(); // Show the suggestions box
                    });
                }
            }
            ', CClientScript::POS_BEGIN);
        landa()->registerAssetScript('landaSearch.js');
        landa()->registerAssetCss('landaSearch.css');
//        $assetUrl = app()->assetManager->publish(Yii::getPathOfAlias('ext.landa.assets'));
//        cs()->registerCssFile($assetUrl . '/css/landaSearch.css');
    }

}
?>

