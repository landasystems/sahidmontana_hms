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
$this->setPageTitle('Salary Unpaid Report');
$this->breadcrumbs = array(
    'Salary Unpaid Report',
);

//$exam_category_id = (isset($model->Exam->exam_category_id)) ? $model->Exam->exam_category_id : 0;
//$school_year_id = (isset($model->Classroom->school_year_id)) ? $model->Classroom->school_year_id : 0;
?>
<script>
    function hide() {
        $(".well").hide();
        $(".form-horizontal").hide();
    }

</script>
<div class="well">

    <div class="row-fluid">
        <div class="span11">


            <?php
            echo $form->dateRangeRow(
            $mProcess, 'time_end', array(
            'label' => 'Time',
            'hint' => 'Click inside! An even a date range field!.',
            'prepend' => '<i class="icon-calendar"></i>',
            'options' => array('callback' => 'js:function(start, end){console.log(start.toString("MMMM d, yyyy") + " - " + end.toString("MMMM d, yyyy"));}')
            )
            );
            ?>    
        </div>
        <div>
            <?php if (isset($mProcess->time_end)) { ?>
                <a onclick="hide()" class="btn btn-small view" title="Remove Form" rel="tooltip"><i class=" icon-remove-circle"></i></a>
            <?php } ?>
        </div>

    </div>
    <div class="form-actions">
        <?php
        $this->widget('bootstrap.widgets.TbButton', array(
            'buttonType' => 'submit',
            'type' => 'primary',
            'icon' => 'ok white',
            'label' => 'View Report',
        ));
        ?>
    </div>

    <?php $this->endWidget(); ?>
</div>
<?php
if(isset($mProcess->time_end)){
    $a = explode('-',$mProcess->time_end);
    $start = date('Y/m/d', strtotime($a[0]));
    $end = date('Y/m/d', strtotime($a[1]))."23:59:59";
    
    $process = WorkorderProcess::model()->findAll(array('condition'=>'(time_end >="'.$start.'" AND time_end <="'.$end.'") AND is_payment=0','order'=>'start_user_id'));
    $this->renderPartial('_salaryunpaidResult', array('process'=>$process,'created'=>$mProcess->time_end));
    
}
?>