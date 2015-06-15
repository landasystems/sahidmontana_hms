<?php

class LandaCalender extends CWidget{
    
    public function run(){
        $this->registerScript();
        
        echo'<div id="calendar" class="well"></div>';
    }
    
    public function registerScript() {
        app()->landa->registerAssetCss('fullcalendar.css');
//        app()->landa->registerAssetCss('landacalendar.css');
        app()->landa->registerAssetScript('fullcalendar.min.js', CClientScript::POS_BEGIN);
      cs()->registerScript('','');
        cs()->registerCss('','
           #calendar {
		width: 90%;
		margin: 0 auto;
                color:black;
		}
           #calendar h2{
                color:black;
		}
                .fc-event-time{display:none}
');
    }
}
?>
