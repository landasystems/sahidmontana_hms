

<?php
$this->widget('bootstrap.widgets.TbGridView', array(
    'id' => 'download-grid',
    'dataProvider' => new CActiveDataProvider(UserLog::model(), array('criteria' => array('order'=>'id desc'))),
    'type' => 'striped bordered condensed',
    'template' => '{summary}{pager}{items}{pager}',
    'columns' => array(
        'id',
        array(
            'name'=>'user_id',
            'header'=>'Roles',
            'value'=>'"$data->roles"'
        ),
        
        array(
            'name'=>'user_id',
            'header'=>'Name User',
            'value'=>'"$data->name"'
        ),
        array(
            'name'=>'created',
            'header'=>'Roles',
            'value'=>'"$data->time"',
            'type'=>'raw',
        ),
        
    ),
));
?>