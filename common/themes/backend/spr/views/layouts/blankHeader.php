<?php /* @var $this Controller */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>

        <title><?php echo CHtml::encode($this->pageTitle); ?></title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="language" content="en" />
        <meta name="author" content="Landa - Profesional Website Development" />
        <meta name="application-name" content="Application Default" />

        <!-- Mobile Specific Metas -->
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />

        <?php
        
        $cs = Yii::app()->getClientScript();
//        $cs->registerCoreScript('jquery');
        $cs->registerCssFile(bt() . '/css/icons.css');
        $cs->registerCssFile(bt() . '/css/main.css');
        ?>     


        <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
        <!--[if lt IE 9]>
          <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->

        <!-- Le fav and touch icons -->
        <link rel="shortcut icon" href="images/favicon.ico" />
        <link rel="apple-touch-icon-precomposed" sizes="144x144" href="images/apple-touch-icon-144-precomposed.png" />
        <link rel="apple-touch-icon-precomposed" sizes="114x114" href="images/apple-touch-icon-114-precomposed.png" />
        <link rel="apple-touch-icon-precomposed" sizes="72x72" href="images/apple-touch-icon-72-precomposed.png" />
        <link rel="apple-touch-icon-precomposed" href="images/apple-touch-icon-57-precomposed.png" />


        <script type="text/javascript">
            //adding load class to body and hide page
            document.documentElement.className += 'loadstate';
        </script>
    </head>

    <body class="loginPage">
        <?php echo $content; ?>
        

        <!-- Le javascript
        ================================================== -->
        <?php 
            $cs->registerScriptFile(bt() . '/plugins/touch-punch/jquery.ui.touch-punch.min.js', CClientScript::POS_END);
            $cs->registerScriptFile(bt() . '/js/main.js', CClientScript::POS_END);
        ?>
    </body>
</html>
