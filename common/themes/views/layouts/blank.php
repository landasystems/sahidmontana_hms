<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <title><?php echo CHtml::encode($this->pageTitle); ?></title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="author" content="Landa Systems - Custom Web & Mobile Apps" />
        <link rel="shortcut icon" href="<?php echo bt() ?>/images/favicon.ico" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <?php
        $cs = Yii::app()->getClientScript();
        $cs->registerCoreScript('jquery');
        $cs->registerCssFile(bt() . '/css/main.min.css');
        ?>     
        <script type="text/javascript">
            document.documentElement.className += 'loadstate';
        </script>
    </head>

    <body class="errorPage">
        <?php echo $content; ?>
        <script type="text/javascript">
            $(document).ready(function () {
                //------------- Some fancy stuff in error pages -------------//
                $('.errorContainer').hide();
                $('.errorContainer').fadeIn(1000).animate({
                    'top': "50%", 'margin-top': +($('.errorContainer').height() / -2 - 30)
                }, {duration: 750, queue: false}, function () {
                    // Animation complete.
                });
            });
        </script>
        <?php
        $cs->registerScriptFile(bt() . '/js/main.js', CClientScript::POS_END);
        ?>
    </body>
</html>
