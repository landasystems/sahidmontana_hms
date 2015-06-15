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
class LandaEvent extends CWidget {

    public $model;
    public $type = 'upcoming'; //upcoming or past
    public $htmlOptions = array();

    //put your code here

    public function run() {
        $this->registerScript();

        $isAny = true;
        $li = '';

        if ($this->type == 'upcoming') {
            foreach ($this->model as $oEvent) {
                if ($oEvent->date_event >= date("Y-m-d")) {
                    $li .= '<li class="group">						
                        <p class="tour-date"><span class="day">' . date('d', strtotime($oEvent->date_event)) . '</span><span class="month">' . date('M', strtotime($oEvent->date_event)) . '</span></p>
                        <div class="tour-place">
                            <span class="sub-head"><a href="' . url('event/' . $oEvent->alias) . '">' . $oEvent->title . '</a></span>
                            <a href="' . url('event/' . $oEvent->alias) . '" class="btn action-btn">Detail</a>
                        </div>
                  </li>';
                    $isAny = false; //any upcoming event
                }
            }

            if ($isAny) {
                echo 'Be ready for upcoming events, from us ...<br/><br/>';
            } else {
                echo '<ul class="tour-dates current-dates" '.CHtml::renderAttributes($this->htmlOptions).'>' . $li . '</ul>';
            }
        } else {
            foreach ($this->model as $oEvent) {
                if ($oEvent->date_event < date("Y-m-d")) {
                    $li .= '<li class="group">						
                        <p class="tour-date"><span class="day">' . date('d', strtotime($oEvent->date_event)) . '</span><span class="month">' . date('M', strtotime($oEvent->date_event)) . '</span></p>
                        <div class="tour-place">
                            <span class="sub-head"><a href="' . url('event/' . $oEvent->alias) . '">' . $oEvent->title . '</a></span>
                            <a href="' . url('event/' . $oEvent->alias) . '" class="btn action-btn">Detail</a>
                        </div>
                  </li>';
                    $isAny = false; //any upcoming event
                }
            }

            if ($isAny) {
                echo 'No past event ...<br/><br/>';
            } else {
                echo '<ul class="tour-dates past-dates" '.CHtml::renderAttributes($this->htmlOptions).'>' . $li . '</ul>';
            }
        }
    }

    public function registerScript() {
        app()->landa->registerAssetCss('landaEvent.css');
    }

}

?>
