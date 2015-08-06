<?php
$siteConfig = SiteConfig::model()->listSiteConfig();
$img = Yii::app()->landa->urlImg('site/', $siteConfig->client_logo, 1);
$this->pageTitle = Yii::app()->name . ' - Login';
?>

<style>
    .newLogin{
        left: 50%;
        top: 50%;
        width: 300px;
        height: auto;
        position: absolute;
        margin-top: -200px;
        margin-left: -150px;
        padding: 0px 20px;
        border: 3px solid cadetblue;
        box-shadow: 1px 1px 1px 1px #ccc;
        border-radius: 2px;
        background: white;
        border-radius: 20px;
    }

    .newLogin .form-actions {
        border-radius: 0 0 20px 20px;
    }
</style>


<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'login-form',
//    'enableClientValidation' => true,
    'clientOptions' => array(
        'validateOnSubmit' => true,
        'type' => 'horizontal',
    ),
        ));
?>

<div class="container-fluid">
    <div class="newLogin">        
        <form class="form-horizontal" action="dashboard.html" />
        <center>
            <img style="height: 75px;margin: 10px 5px 0 0px;" src="<?php echo $img['small'] ?>" />
            <h3 style="margin:5px 0 0 0">
                <?php
                echo param('clientName');
                ?>
            </h3>
            <span style="color:cadetblue;font-weight: bold;"><?php echo app()->name; ?></span>
        </center>
        <hr style="margin:10px 0px 20px 0px">

        <div class="form-row row-fluid">   
            <div class="span12"> 
                <div class="form-row row-fluid">
                    <div class="span12">            
                        <div class="row-fluid">
                            <div class="span4">Username</div>
                            <div class="span8">
                                <?php echo $form->textField($model, 'username', array('class' => 'span12')); ?>
                                <span class="red"><?php echo $form->error($model, 'username'); ?></span>
                            </div>                   
                        </div>
                    </div>
                </div>


                <div class="form-row row-fluid">
                    <div class="span12">
                        <div class="row-fluid">
                            <div class="span4">Password</div>
                            <div class="span8">
                                <?php echo $form->passwordField($model, 'password', array('class' => 'span12')); ?>
                                <span class="red"><?php echo $form->error($model, 'password'); ?></span>    
                            </div>             
                        </div>                
                    </div>
                </div>
            </div>  

        </div>


        <div class="form-row row-fluid">                       
            <div class="span12">
                <div class="row-fluid">
                    <div class="form-actions" style="margin:0px -20px">
                        <div class="span12 controls" style="padding:0px 0px 0px 5px">
                            <?php echo $form->checkBox($model, 'rememberMe', array('class' => 'left', 'style' => 'width:20px')); ?> Keep me login
                            <button type="submit" class="btn right" id="loginBtn"><span class="icon16 icomoon-icon-enter"></span> Login</button>
                        </div>
                    </div>
                </div>
            </div> 
        </div> 

        </form>
    </div>

</div><!-- End .container-fluid -->

<?php $this->endWidget(); ?>
