<style>
    .panel {
        padding:10px 80px;
        left:220px;
        position:relative;
    }
</style>
<div class="container-fluid">

    <div id="header">

        <div class="row-fluid">

            <div class="navbar">
                <div class="navbar-inner">
                    <div class="container">
                        <a class="brand" href="<?php echo url('site/login') ?>"><?php echo param('clientName') ?> <span class="slogan"><?php echo param('appVersion') ?></span></a>
                    </div>
                </div><!-- /navbar-inner -->
            </div><!-- /navbar -->


        </div><!-- End .row-fluid -->

    </div><!-- End #header -->

</div><!-- End .container-fluid -->  
<div class="container-fluid">
<div class="loginContainer">
    <center><div class="" style="padding:">
            
            <br>
            <?php
            foreach (Yii::app()->user->getFlashes() as $key => $message) {
                echo '<div class="alert alert-' . $key . '">' . $message . '</div>';
            }
            ?>
            <span class="field">
                <p>Masukan password baru anda.</p>
            </span>
            

            <?php
//        $model = new User;
            $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
                'id' => 'User-form',
                'action' => url('site/savePassword'),
                'enableAjaxValidation' => false,
                'method' => 'post',
//                'name'=>'myForm',
//                'onsubmit'=>
                'type' => 'horizontal',
                'htmlOptions' => array(
                    'enctype' => 'multipart/form-data',
                    'style' => 'margin-top: 25px;'
                )
            ));
            ?>
            <div class="form-group">
                <div class="row-fluid">
                    <div class="span12">
                        <input class="span12" placeholder="" name="password" id="LoginForm_password" type="password">
                        <input type="hidden" value="<?php echo md5($id); ?>" name="id">
                    </div>
                </div>
            </div>
            <hr>
           

        </div></center>
    <div class="form-actions">
                        <div class="span3 controls">
                           
                          <center> <button class="btn btn-info" name="commit" type="submit"><i class="fa fa-sign-in"></i>&nbsp; Save Password</button></center>
                         <?php $this->endWidget(); ?>
                        </div>
                    </div>
</div>
</div>

