<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <title><?php echo CHtml::encode($this->pageTitle); ?></title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="language" content="en" />
        <meta name="author" content="Landa - Profesional Website Development" />
        <meta name="application-name" content="Application Default" />
        <link rel="shortcut icon" href="<?php echo bt() ?>/images/favicon.ico" />

        <!-- Mobile Specific Metas -->
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />

        <?php
        landa()->loginRequired();
        $cs = Yii::app()->getClientScript();
        $cs->registerCoreScript('jquery');
        $cs->registerCssFile(bt() . '/css/icons.css');
        $cs->registerCssFile(bt() . '/css/main.css');
        ?>     

        <link rel="apple-touch-icon-precomposed" sizes="144x144" href="images/apple-touch-icon-144-precomposed.png" />
        <link rel="apple-touch-icon-precomposed" sizes="114x114" href="images/apple-touch-icon-114-precomposed.png" />
        <link rel="apple-touch-icon-precomposed" sizes="72x72" href="images/apple-touch-icon-72-precomposed.png" />
        <link rel="apple-touch-icon-precomposed" href="images/apple-touch-icon-57-precomposed.png" />

        <script type="text/javascript">
            //adding load class to body and hide page
            document.documentElement.className += 'loadstate';
        </script>
    </head>
    <body>
        <img src="<?php echo bt("images/loaders/horizontal/004.gif") ?>" id="loader" />
        <!-- loading animation -->
        <div id="qLoverlay"></div>
        <div id="qLbar"></div>

        <div id="header">
            <div class="navbar">
                <div class="navbar-inner">
                    <div class="container-fluid">
                        <a class="brand" href="<?php echo url('dashboard') ?>">
                            <?php
                            $siteConfig = SiteConfig::model()->listSiteConfig();
                            echo $siteConfig->client_name;
                            ?>
                        </a>
                        <div class="nav-no-collapse">

                            <ul class="nav">
                                <?php if (user()->isSuperUser) { ?>
                                    <li class="dropdown">
                                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                            <span class="icon16 icomoon-icon-cog"></span> Settings
                                            <b class="caret"></b>
                                        </a>
                                        <ul class="dropdown-menu">
                                            <li class="menu">
                                                <?php
                                                $this->widget('zii.widgets.CMenu', array(
                                                    'items' => array(
                                                        array('label' => '<span class="icon16 iconic-icon-new-window"></span>Site config', 'url' => array('/siteConfig/update', 'id' => param('id'))),
                                                        array('visible' => in_array('inventory', param('menu')), 'label' => '<span class="icon16 minia-icon-office"></span>Unit Kerja', 'url' => array('/departement')),
                                                        array('label' => '<span class="icon16 entypo-icon-users"></span>Access', 'url' => array('/landa/roles')),
                                                    ),
                                                    'encodeLabel' => false,
                                                ));
                                                ?>
                                            </li>
                                        </ul>
                                    </li>
                                <?php } ?>
                                <?php if (in_array('message', param('menu'))) { ?>
                                    <li class="dropdown">
                                        <?php
                                        $listUser = User::model()->listUser();
                                        $arrMessages = UserMessage::model()->unread();
                                        ?>
                                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                            <span class="icon16 icomoon-icon-mail"></span>Messages 

                                            <?php
                                            if (count($arrMessages) == 0) {
                                                echo'<span class="notification"> </span>';
                                            } else {
                                                echo'<span class="notification">' . count($arrMessages) . ' </span>';
                                            }
                                            ?>
                                        </a>
                                        <ul class="dropdown-menu">
                                            <li class="menu">
                                                <ul class="messages">    
                                                    <li class="header"><strong>Unread Messages</strong> (<?php echo count($arrMessages) ?>) Items</li>
                                                    <?php
                                                    if (landa()->checkAccess('UserMessage.Create')) {
                                                        echo '<li>
                                                            <a href="' . url('userMessageDetail/create') . '"><span class="icon16 icomoon-icon-pencil-2"></span>Create Message</a>
                                                         </li>';
                                                    }
                                                    ?>
                                                    <?php
                                                    foreach ($arrMessages as $arrMessage) {
                                                        echo '<a href="' . bu() . '/userMessage/' . $arrMessage->id . '"><li>
                                                            <span class="icon"><span class="icon16 icomoon-icon-mail"></span></span>
                                                            <span class="name">
                                                                    <strong>' . $listUser[$arrMessage->user_id_opp]['name'] . '</strong>
                                                                <span class="time">' . Yii::app()->landa->ago($arrMessage->last_date) . '</span>
                                                            </span>
                                                            <span class="msg">' . $arrMessage->last_message . '</span>
                                                          </li>
                                                          </a>
                                                        ';
                                                    }
                                                    ?>
                                                    <li class="view-all"><a href="<?php echo url('userMessage') ?>">View all messages <span class="icon16  icomoon-icon-arrow-right-7"></span></a></li>
                                                </ul>
                                            </li>
                                        </ul>
                                    </li>
                                <?php } ?>

                                <?php if (in_array('sms', param('menu')) && landa()->checkAccess('Sms', 'r')) { ?>
                                    <li class="dropdown">
                                        <?php
                                        $listUserPhone = User::model()->listUserPhone();
                                        $arrMessages = Sms::model()->unread();
                                        ?>
                                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                            <span class="icon16 wpzoom-phone-3"></span>SMS 

                                            <?php
                                            if (count($arrMessages) == 0) {
                                                echo'<span class="notification"> </span>';
                                            } else {
                                                echo'<span class="notification">' . count($arrMessages) . ' </span>';
                                            }
                                            ?>
                                        </a>
                                        <ul class="dropdown-menu">
                                            <li class="menu">
                                                <ul class="messages">    
                                                    <li class="header"><strong>Unread SMS</strong> (<?php echo count($arrMessages) ?>) Items</li>
                                                    <?php
                                                    if (user()->checkAccess('sms.Create')) {
                                                        echo '<li>
                                                            <a href="' . url('sms/create') . '"><span class="icon16 icomoon-icon-pencil-2"></span>Create SMS</a>
                                                         </li>';
                                                    }
                                                    ?>                                                
                                                    <?php
                                                    foreach ($arrMessages as $arrMessage) {
                                                        if (!array_key_exists($arrMessage->phone, $listUserPhone))
                                                            $name = landa()->hp($arrMessage->phone);
                                                        else {
                                                            $name = $listUserPhone[$arrMessage->phone]['name'];
                                                        }
                                                        echo '<a href="' . bu() . '/sms/' . $arrMessage->id . '"><li>
                                                            <span class="icon"><span class="icon16 wpzoom-mobile"></span></span>
                                                            <span class="name">
                                                                    <strong> - ' . $name . '</strong>
                                                                <span class="time">' . Yii::app()->landa->ago($arrMessage->last_date) . '</span>
                                                            </span>
                                                            <span class="msg">' . $arrMessage->last_message . '&nbsp;</span>
                                                          </li></a>
                                                        ';
                                                    }
                                                    ?>
                                                    <li class="view-all"><a href="<?php echo url('sms') ?>">View all SMS <span class="icon16  icomoon-icon-arrow-right-7"></span></a></li>
                                                </ul>
                                            </li>
                                        </ul>
                                    </li>
                                <?php } ?>


                            </ul>

                            <ul class="nav pull-right usernav">
                                <?php if (in_array('notification', param('menu'))) { ?>
                                    <li class="dropdown">
                                        <?php
                                        $arrUsers = UserNotification::model()->findAll(array('condition' => 'is_read=0', 'limit' => 10));
                                        ?>
                                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                            <span class="icon16 icomoon-icon-bell"></span>
                                            <?php
                                            if (count($arrUsers) == 0) {
                                                echo'<span class="notification"> </span>';
                                            } else {
                                                echo'<span class="notification">' . count($arrUsers) . ' </span>';
                                            }
                                            ?>
                                        </a>
                                        <ul class="dropdown-menu">
                                            <li class="menu">
                                                <ul class="notif">
                                                    <li class="header"><strong>Notifications</strong> (<?php echo count($arrUsers) ?>) items</li>
                                                    <li>
                                                        <a href="<?php echo url('userNotification/create') ?>"><span class="icon16 icomoon-icon-pencil-2"></span>Create Notification</a>
                                                    </li>

                                                    <?php
                                                    foreach ($arrUsers as $arrUser) {
                                                        echo '
                                                            <a href="' . bu() . '/userNotification/' . $arrUser->id . '">
                                                            <span class="icon"><span class="icon16 icomoon-icon-comments-4"></span></span>
                                                            <span class="event">' . $arrUser->title . '</span>
                                                            </a>
                                                        ';
                                                    }
                                                    ?>

                                                    <li class="view-all"><a href="<?php echo url('/userNotification'); ?>">View all notifications<span class="icon16  icomoon-icon-arrow-right-7"></span></a></li>
                                                </ul>
                                            </li>
                                        </ul>
                                    </li>
                                <?php } ?>
                                <li class="dropdown">
                                    <a href="#" class="dropdown-toggle avatar" data-toggle="dropdown">
                                        <?php
                                        $img = user()->avatar_img;
                                        echo '<img src="' . $img['small'] . '" alt="" class="image" /> ';
                                        ?>
                                        <span class="txt"><?php echo Yii::app()->user->getState('name'); ?></span>
                                        <b class="caret"></b>
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li class="menu">
                                            <?php
                                            $user = User::model()->findByPk(user()->id);
                                            if ($user->roles_id == -1) {
                                                $type = 'user';
                                            } else {
                                                if ($user->Roles->is_allow_login == 0) {
                                                    $type = 'guest';
                                                } else {
                                                    $type = 'user';
                                                }
                                            }
                                            $this->widget('zii.widgets.CMenu', array(
                                                'items' => array(
                                                    array('visible' => true, 'label' => '<span class="icon16 icomoon-icon-user-3"></span>Edit profile', 'url' => url('user/updateProfile') . '?type=' . $type),
                                                ),
                                                'encodeLabel' => false,
                                            ));
                                            ?>
                                        </li>
                                    </ul>
                                </li>
                                <li><a href="<?php echo bu() ?>/site/logout"><span class="icon16 icomoon-icon-exit"></span> Logout</a></li>
                            </ul>
                        </div><!-- /.nav-collapse -->
                    </div>
                </div><!-- /navbar-inner -->
            </div><!-- /navbar --> 
        </div><!-- End #header -->

        <div id="wrapper">

            <!--Responsive navigation button-->  
            <div class="resBtn">
                <a href="#"><span class="icon16 minia-icon-list-3"></span></a>
            </div>

            <!--Sidebar collapse button-->  

            <!--Sidebar background-->
            <div id="sidebarbg" style="margin-left: -299px;"></div>
            <!--Sidebar content-->
            <div id="sidebar" style="margin-left: -299px; left: 0px;">
                <div class="sidenav">

                    <div class="sidebar-widget" style="margin: -1px 0 0 0;">
                        <h5 class="title" style="margin-bottom:0">Navigation</h5>
                    </div><!-- End .sidenav-widget -->

                    <div class="mainnav">
                        <?php
                        $this->widget('zii.widgets.CMenu', array(
                            'items' => Auth::model()->modules(),
                            'encodeLabel' => false,
                        ));
                        ?>
                    </div>

                    <div class="sidebar-widget">
                        <h5 class="title">Server Information</h5>
                        <div class="content">
                            <table class="table table-condensed">
                                <tr>
                                    <td><b>Date</b></td>
                                    <td> : </td>
                                    <td><?php echo date('d F Y') ?></td>
                                </tr>
                                <tr>
                                    <td><b>Time</b></td>
                                    <td> : </td>
                                    <td><?php echo date('H:i') ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div><!-- End sidenav -->


            </div><!-- End #sidebar -->



            <!--Body content-->
            <div id="content" class="clearfix" style="margin-left: 0px;">
                <div class="contentwrapper"><!--Content wrapper-->

                    <div class="heading">

                        <h3><?php echo CHtml::encode($this->pageTitle); ?></h3>                    



                        <div class="search">
                            <?php // $this->widget('common.extensions.landa.widgets.LandaSearch', array('url' => url('user/searchJson'), 'class' => 'input-text')); ?>
                        </div><!-- End search -->

                        <?php if (isset($this->breadcrumbs)): ?>
                            <?php
                            $this->widget('zii.widgets.CBreadcrumbs', array(
                                'links' => $this->breadcrumbs,
                                'htmlOptions' => array('class' => 'breadcrumb'),
                                'separator' => '<span class="divider"><span class="icon16 icomoon-icon-arrow-right"></span></span>',
                                'homeLink' => '<a href="/site/index" class="tip" title="back to dashboard"><span class="icon16 icomoon-icon-screen"></span></a>'
                            ));
                            ?><!-- breadcrumbs -->
                        <?php endif ?>

                    </div><!-- End .heading-->
                    <div class="clearfix"></div>
                    <!-- Build page from here: -->
                    <?php echo $content; ?>
                    <!-- End Build page -->
                </div><!-- End contentwrapper -->
                <div id="footer" class="span12">
                    <?php echo app()->name . ' ' . param('appVersion') ?>  ©  2013 All Rights Reserved. Designed and Developed by : <a href="http://www.landa.co.id" target="_blank">Landa Systems</a>
                </div>
            </div>
            <!-- End #content -->
        </div><!-- End #wrapper -->
        <a href="#" class="collapseBtn tipR minim" title="Hide/Show sidebar">
            <div class="landaMin img-polaroid">
                <i class="icon-arrow-right"></i>
            </div>
        </a>
        <?php
        $cs->registerScriptFile(bt() . '/js/main.js', CClientScript::POS_END);
        ?>
    </body>
</html>
