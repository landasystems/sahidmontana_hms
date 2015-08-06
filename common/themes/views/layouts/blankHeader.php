<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <title><?php echo CHtml::encode($this->pageTitle); ?></title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="author" content="Landa Systems - Custom Web & Mobile Apps" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <link rel="shortcut icon" href="<?php echo bu('img/favicon.ico')?>" />
        <link rel="stylesheet" href="<?php echo bu('css/main.min.css')?>" type="text/css" />
        <?php
        cs()->registerCoreScript('jquery');
        cs()->registerScriptFile(bu('js/begin.js'));
        cs()->registerScriptFile(bu('js/main.js'), CClientScript::POS_END);
        ?>     
    </head>
    <body class="loginPage">
        <?php echo $content; ?>
    </body>
</html>
