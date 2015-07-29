<?php
/* @var $this SiteController */
/* @var $error array */
$this->pageTitle = Yii::app()->name . ' - Error';
?>
<div class="container-fluid">

    <div class="errorContainer">
        <div class="page-header">
            <h1 class="center"> <?php echo $code; ?> <small>error</small></h1>
        </div>

        <h2 class="center"><?php echo CHtml::encode($message); ?></h2>

        <div class="center">
            <a href="javascript: history.go(-1)" class="btn" style="margin-right:10px;"><span class="icon16 typ-icon-back"></span>Go back</a>
            <a href="dashboard.html" class="btn"><span class="icon16 cut-icon-screen"></span>Dashboard</a>
        </div>

    </div>

</div><!-- End .container-fluid -->