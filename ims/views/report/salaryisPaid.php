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
$this->setPageTitle('Laporan Penggajian');
$this->breadcrumbs = array(
    'Laporan Penggajian',
);
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
                    $mSalary, 'created', array(
                'label' => 'Time',
                'hint' => 'Click inside! An even a date range field!.',
                'prepend' => '<i class="icon-calendar"></i>',
                'options' => array('callback' => 'js:function(start, end){console.log(start.toString("MMMM d, yyyy") + " - " + end.toString("MMMM d, yyyy"));}')
                    )
            );
            ?>    
        </div>
        <div>
            <?php if (isset($mSalary->created)) { ?>
                <a onclick="hide()" class="btn btn-small view" title="Remove Form" rel="tooltip"><i class=" icon-remove-circle"></i></a>
            <?php } ?>
        </div>
        <div class="control-group ">
            <label class="control-label" for="Salary_created">Type</label>
            <div class="controls">
                
                <?php echo CHtml::dropDownList('type', array(
                        'value'=>(isset($_POST['type'])) ? $_POST['type'] : ''
                    ), 
              array('paid' => 'Paid', 'unpaid' => 'Unpaid'));
                ?>
            </div></div>


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
if (isset($mSalary->created) && isset($_POST['type'])) {
    $a = explode('-', $mSalary->created);
    $start = date('Y/m/d', strtotime($a[0]));
    $end = (isset($a[1])) ? date('Y/m/d', strtotime($a[1])) . " 23:59:59" :'';

    if ($_POST['type'] == 'paid') {
        $salary = SalaryOut::model()->findAll(array('condition' => 'created >= "' . $start . '" AND created <="' . $end . '"', 'order' => 'id'));
        $this->renderPartial('_salaryResult', array('salary' => $salary, 'created' => $mSalary->created));
    }else{
        $process = WorkorderProcess::model()->findAll(array('condition' => '(time_end >="' . $start . '" AND time_end <="' . $end . '") AND is_payment=0', 'order' => 'start_user_id'));
        $this->renderPartial('_salaryunpaidResult', array('process' => $process,'created' => $mSalary->created));
    }
}
?>