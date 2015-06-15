<?php
$this->widget('bootstrap.widgets.TbGridView', array(
    'id' => 'registration-grid',
    'dataProvider' => $model->search(),
    'type' => 'striped bordered condensed',
    'template' => '{summary}{pager}{items}{pager}',
    'columns' => array(              
        array(
            'header' => 'Guest',
            'name' => 'guest_user_id',
            'type' => 'raw',
            'value' => '$data->Guest->name',
        ),
        'code',
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
        array(
            'name' => 'package_room_type_id',
            'type' => 'raw',
            'value' => 'ucwords($data->thisPackage)',
        ),               
        array(
            'header' => '',
            'name' => 'approval_user_id',
            'type' => 'raw',           
            'value' => '"<a class=\'btn btn-small view\' target=\'_blank\' title=\'View\' rel=\'tooltip\' href=\'".Yii::app()->createUrl(\'registration\'."/".$data->id)."\'><i class=\'icon-eye-open\'></i></a>"',
            'htmlOptions' => array('style'=>'width:45px;text-align:center'),
        ),        
     
    ),
));
?>