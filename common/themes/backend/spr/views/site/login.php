<?php
/* @var $this SiteController */
/* @var $model LoginForm */
/* @var $form CActiveForm  */

$this->pageTitle = Yii::app()->name . ' - Login';
$this->breadcrumbs = array(
    'Login',
);
?>


<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'login-form',
    'enableClientValidation' => true,
    'clientOptions' => array(
        'validateOnSubmit' => true,
    ),
        ));
?>

<div class="container-fluid">

    <div id="header">

        <div class="row-fluid">

            <div class="navbar">
                <div class="navbar-inner">
                    <div class="container">
                        <a class="brand" href="#"><?php echo param('clientName') ?> <span class="slogan"><?php echo param('appVersion') ?></span></a>
                    </div>
                </div><!-- /navbar-inner -->
            </div><!-- /navbar -->


        </div><!-- End .row-fluid -->

    </div><!-- End #header -->

</div><!-- End .container-fluid -->    

<div class="container-fluid">

    <div class="loginContainer">
        <form class="form-horizontal" action="dashboard.html" id="loginForm" />
        <div class="form-row row-fluid">
            <div class="span12">
                <div class="row-fluid">
                    <label class="form-label span12" for="username">
                        Username:
                        <span class="icon16 icomoon-icon-user-2 right gray marginR10"></span>
                    </label>
                   
                    <?php echo $form->textField($model, 'username', array('class'=>'span12')); ?>
                    <?php echo $form->error($model, 'username'); ?>
<!--                    <input class="span12" id="username" type="text" name="username" value="Administrator" />-->
                </div>
            </div>
        </div>

        <div class="form-row row-fluid">
            <div class="span12">
                <div class="row-fluid">
                    <label class="form-label span12" for="password">
                        Password:
                        <span class="icon16 icomoon-icon-locked right gray marginR10"></span>
                        <span class="forgot"><a href="<?php echo url('site/forgotPassword') ?>">Forgot your password?</a></span>
                    </label>
                    <?php echo $form->passwordField($model, 'password' , array('class'=>'span12')); ?>
                    <?php echo $form->error($model, 'password'); ?>
                </div>
            </div>
        </div>
        <div class="form-row row-fluid">                       
            <div class="span12">
                <div class="row-fluid">
                    <div class="form-actions">
                        <div class="span12 controls">
                            <?php echo $form->checkBox($model, 'rememberMe', array('class'=>'left', 'style'=>'width:20px')); ?> Keep me login in
                            <button type="submit" class="btn btn-info right" id="loginBtn"><span class="icon16 icomoon-icon-enter white"></span> Login</button>
                        </div>
                    </div>
                </div>
            </div> 
        </div>

        </form>
    </div>

</div><!-- End .container-fluid -->

<?php $this->endWidget(); ?>
