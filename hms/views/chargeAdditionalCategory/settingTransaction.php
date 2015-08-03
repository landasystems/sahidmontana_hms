<?php
$this->setPageTitle('Setting Transaction');

?>

<?php
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id' => 'user-classroom-form',
    'enableAjaxValidation' => false,
    'method' => 'post',
    'type' => 'horizontal',
    'htmlOptions' => array(
        'enctype' => 'multipart/form-data'
    )
        ));
?>

<?php
foreach (Yii::app()->user->getFlashes() as $key => $message) {
    echo '<div class="alert alert-' . $key . '">' . $message . '</div>';
}
?>

<div class="box">
    <div class="title" style="padding: 10px">
        
        <strong>
            Filter Transaction      : 
        </strong>

        <?php
        $data = array('Iwak' => 'Iwak', 'Jambu' => 'Jambu');
        $this->widget(
                'bootstrap.widgets.TbSelect2', array(
            'data' => $data,
            'asDropDownList' => true,
            'name' => 'clevertech',
            'options' => array(
                "placeholder" => 'Please Choose',
                "allowClear" => true,
                'width' => '30%',
            ),
                    'events' => array('change' => 'js: function() {
                                                     $.ajax({
                                                        url : "' . url('user/getDetail') . '",
                                                        type : "POST",
                                                        data :  { id:  $(this).val()},
                                                        success : function(data){                                                            
                                                            obj = JSON.parse(data);
                                                            $("#group").val(obj.group); 
                                                            $("#roles").val(obj.group);
                                                            $("#name").val(obj.name);
                                                            $("#province_guest").val(obj.province);
                                                            $("#city_guest").val(obj.city);
                                                            $("#address").val(obj.address);
                                                            $("#phone").val(obj.phone);
                                                            $("#idCard").val(obj.number);
                                                            addPrice();
                                                            
                                                        }
                             });
                     }'),
                )
        );
        ?>

        </h4>
       
    </div>
</div>


<?php
//    $this->widget('common.extensions.dualselect.DualSelect', array('title' => array('box1View' => 'haven`t get Classroom', 'box2View' => 'in Class'),
//        'value' => array('box1View' => Classroom::model()->haventClass, 'box2View' => array())));
?>

<?php
$this->widget('common.extensions.dualselect.DualSelect', array('title' => array('box1View' => 'Category Transaction', 'box2View' => 'in Class'),
    'value' => array('box1View' => array(), 'box2View' => array())));
?>

<?php
$this->widget('bootstrap.widgets.TbButton', array(
    'buttonType' => 'submit',
    'type' => 'primary',
    'icon' => 'ok white',
    'label' => 'Save',
));
?>
</div>

<?php $this->endWidget(); ?>
