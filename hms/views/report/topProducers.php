
<style>
    .contentwrapper {
        min-height: 200px;
    }
</style>
<?php
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id' => 'results',
    'enableAjaxValidation' => false,
    'method' => 'post',
    'type' => 'horizontal',
    'htmlOptions' => array(
        'enctype' => 'multipart/form-data'
    )
        ));
?>
<?php
$this->setPageTitle('Top Producers');
$this->breadcrumbs = array(
    'Top Producers',
);
?>

<div class="well">
    <div class="row-fluid">
        <div class="span12">
            Tahun : <?php echo CHtml::dropDownList('year', (!empty($_POST['year'])) ? $_POST['year'] : date('Y'), landa()->yearly(date('Y') - 1), array('empty' => 'Please Choose')); ?> &nbsp;&nbsp;
            <?php
            echo CHtml::ajaxSubmitButton('View Report', Yii::app()->createUrl('Report/viewTopProducers'), array(
                'type' => 'POST',
                'success' => 'function(data){
                        $("#viewData").html(data);
                        }'
                    ), array('class' => 'btn btn-primary')
            );
            ?>
        </div>     
    </div>
</div>
<?php $this->endWidget(); ?>
<DIV ID="viewData"></DIV>
