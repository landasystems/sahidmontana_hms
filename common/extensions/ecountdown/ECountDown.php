<?php

class ECountDown extends CWidget
{
    private static $unique;
    public $seconds = 10;
    public $follow = null;

    private function getUniqueId()
    {
        return md5(++self::$unique);
    }

    public function init()
    {
        if ($this->follow == null) {
            $this->follow = Yii::app()->createUrl('site/login');
        }

        Yii::app()->getClientScript()
                ->registerCoreScript('jquery')
                ->registerScript('countDown', 'function countDown() {
            $(".countdown").each(function(){
                var valore = $(this).val()-1;
                $(this).val(valore);

                secondi = valore;
                _secondi = secondi % 60;
                _minuti = ((secondi - _secondi) / 60) % 60;
                _ore = (secondi - _secondi - _minuti * 60) / 3600;
                
                _ore = _ore < 10 ? "0"+_ore : _ore;
                _minuti = _minuti < 10 ? "0"+_minuti : _minuti;
                _secondi = _secondi < 10 ? "0"+_secondi : _secondi;

                $("#"+$(this).attr("rel")).html(_ore+":"+_minuti+":"+_secondi);
                
                if(valore<=-1)
                    document.location.href = "' . $this->follow . '";
            });
            setTimeout("countDown();", 1000);
        }', CClientScript::POS_END)
                ->registerScript('callcountDown', 'countDown()', CClientScript::POS_END);
    }

    public function run()
    {
        $id = $this->getUniqueId();
        echo '
        <div id="' . $id . '_box" class="label label-info">
            <input type="hidden" rel="' . $id . '" class="countdown" value="' . ($this->seconds) . '"><span id="' . $id . '"></span>
        </div>';
    }

}