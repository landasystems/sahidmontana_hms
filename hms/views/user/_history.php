<table>
    <tr>
        <td width="100">Guest Name</td>
        <td width="5">:</td>
        <td width="200"><?php echo!empty($user->name) ? $user->name : '-'; ?></td>
        <td>Company</td>
        <td width="5">:</td>
        <td><?php echo!empty($user->company) ? $user->company : '-'; ?></td>
    </tr>
    <tr>
        <td>Address</td>
        <td width="5">:</td>
        <td><?php echo!empty($user->address) ? $user->address : '-'; ?></td>
        <td>No. KTP/Passport</td>
        <td width="5">:</td>
        <td><?php echo!empty($user->code) ? $user->code : '-'; ?></td>
    </tr>
    <tr>
        <td>Date Of Birth</td>
        <td width="5">:</td>
        <td><?php echo ($user->birth == "0000-00-00") ? '-' : date("d M Y", strtotime($user->birth)); ?></td>
        <td>Telephone/Hp No</td>
        <td width="5">:</td>
        <td><?php echo!empty($user->phone) ? $user->phone : '-'; ?></td>
    </tr>
    <tr>
        <td>Nationality</td>
        <td width="5">:</td>
        <td><?php echo!empty($user->nationality) ? $user->nationality : '-'; ?></td>
    </tr>
</table>
<?php
$this->widget('bootstrap.widgets.TbGridView', array(
    'id' => 'registration-grid',
    'dataProvider' => $model->search2(),
    'type' => 'striped bordered condensed',
    'template' => '{summary}{pager}{items}{pager}',
    'columns' => array(
        array(
            'header' => 'Room Number',
            'name' => 'id',
            'type' => 'raw',
            'htmlOptions' => array('class' => 'span3'),
            'value' => '$data->roomNumber',
        ),
        array(
            'header' => 'Code Registration',
            'name' => 'code',
            'type' => 'raw',
            'htmlOptions' => array('class' => 'span3'),
            'value' => '$data->code',
        ),
        array(
            'header' => 'Arrival',
            'name' => 'date_from',
            'type' => 'raw',
            'value' => 'date("l m-d-Y", strtotime($data->date_from))',
        ),
        array(
            'header' => 'Departure',
            'name' => 'date_to',
            'type' => 'raw',
            'value' => 'date("l m-d-Y", strtotime($data->date_to))',
        ),
        'remark',
        array(
            'header' => '',
            'name' => 'approval_user_id',
            'type' => 'raw',
            'value' => '"<a class=\'btn btn-small view\' target=\'_blank\' title=\'View\' rel=\'tooltip\' href=\'".Yii::app()->createUrl(\'registration\'."/".$data->id)."\'><i class=\'icon-eye-open\'></i></a>"',
            'htmlOptions' => array('style' => 'width:45px;text-align:center'),
        ),
    ),
));
?>