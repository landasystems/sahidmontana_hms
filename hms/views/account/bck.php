<?php
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id' => 'account-form',
    'enableAjaxValidation' => false,
    'method' => 'post',
    'type' => 'horizontal',
    'htmlOptions' => array(
        'enctype' => 'multipart/form-data'
    )
        ));
?>
<table>
    <?php
    foreach ($na as $key => $val) {
        echo '<tr>
                <td>' . $val['date_na'] . '<input name="id[]" type="hidden" value="'.$val['id'].'"></td>
                <td><input class="angka" name="p[]" maxlength="2" type="text" placeholder="%" value="100"></td>
              </tr>';
    }
    ?>
</table>
<?php
$this->widget('bootstrap.widgets.TbButton', array(
    'buttonType' => 'submit',
    'type' => 'primary',
    'icon' => 'ok white',
    'label' => 'Backup',
));
?>
<?php $this->endWidget(); ?>