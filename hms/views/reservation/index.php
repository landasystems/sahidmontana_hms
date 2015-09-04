<?php
$this->setPageTitle('Reservations');


Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
    $('.search-form').slideToggle('fast');
    return false;
});
$('.search-form form').submit(function(){
    $.fn.yiiGridView.update('reservation-grid', {
        data: $(this).serialize()
    });
    return false;
});
");
?>

<?php
$this->beginWidget('zii.widgets.CPortlet', array(
    'htmlOptions' => array(
        'class' => ''
    )
));
$this->widget('bootstrap.widgets.TbMenu', array(
    'type' => 'pills',
    'items' => array(
        array('label' => 'Create', 'icon' => 'icon-plus', 'url' => Yii::app()->controller->createUrl('create'), 'linkOptions' => array()),
        array('label' => 'List Data', 'icon' => 'icon-th-list', 'url' => Yii::app()->controller->createUrl('index'), 'active' => true, 'linkOptions' => array()),
        array('label' => 'Search', 'icon' => 'icon-search', 'url' => '#', 'linkOptions' => array('class' => 'search-button')),
    ),
));
$this->endWidget();
?>

<div class="search-form" style="display:none">
    <?php
    $this->renderPartial('_search', array(
        'model' => $model,
    ));
    ?>
</div>

<?php
$this->widget('bootstrap.widgets.TbGridView', array(
    'id' => 'reservation-grid',
    'dataProvider' => $model->search(),
    'type' => 'bordered condensed',
    'template' => '{summary}{pager}{items}{pager}',
    'columns' => array(
        'code',
        array(
            'header' => 'Type',
            'name' => 'created_user_id',
            'type' => 'raw',
            'value' => '(isset($data->Guest->Roles->name)) ? $data->Guest->Roles->name : ""',
        ),
        array(
            'header' => 'Guest',
            'name' => 'guest_user_id',
            'type' => 'raw',
            'value' => '(isset($data->Guest->guestName)) ? $data->Guest->guestName : "-"',
        ),
        array(
            'header' => 'Room',
            'type' => 'raw',
            'htmlOptions' => array('class' => 'span3'),
            'value' => '$data->roomNumber',
        ),
        array(
            'name' => 'date_from',
            'type' => 'raw',
            'value' => 'date("d M Y", strtotime($data->date_from))',
        ),
        array(
            'name' => 'date_to',
            'type' => 'raw',
            'value' => 'date("d M Y", strtotime($data->date_to))',
        ),
        array(
            'name' => 'status',
            'type' => 'raw',
            'value' => 'ucwords($data->status)',
        ),
        array(
            'name' => 'package_room_type_id',
            'type' => 'raw',
            'value' => 'ucwords($data->thisPackage)',
        ),
        array(
            'header' => '',
            'class' => 'bootstrap.widgets.TbButtonColumn',
            'template' => '{view} {update} {status} {delete}',
            'buttons' => array(
                'view' => array(
                    'label' => 'View',
                    'options' => array(
                        'class' => 'btn btn-small view'
                    )
                ),
                'update' => array(
                    'visible' => '(strtotime($data->date_from)>=strtotime(app()->session["date_system"]))',
                    'label' => 'Edit',
                    'options' => array(
                        'class' => 'btn btn-small update'
                    )
                ),
                'status' => array(
                    'visible' => '(strtotime($data->date_from)>=strtotime(app()->session["date_system"]))',
                    'label' => 'Change Status',
                    'icon' => 'icon-refresh',
                    'url' => '$data->id',
                    'options' => array(
                        'class' => 'btn btn-small update status',
                        'data-target' => '#myModal',
                        'data-toggle' => 'modal',
                    )
                ),
                'delete' => array(
                    'visible' => '(strtotime($data->date_from)>=strtotime(app()->session["date_system"]))',
                    'label' => 'Delete',
                    'options' => array(
                        'class' => 'btn btn-small delete'
                    )
                )
            ),
            'htmlOptions' => array('style' => 'width: 160px'),
        )
    ),
));
?>

<?php
$this->beginWidget(
        'bootstrap.widgets.TbModal', array('id' => 'myModal', 'autoOpen' => false)
);
?>
<form method="post" action="<?php echo url("reservation/index") ?>">
    <div class="modal-header">
        <a class="close" data-dismiss="modal">&times;</a>
        <center><h4>STATUS RESERVATION</h4></center>
    </div>

    <div class="modal-body">

    </div>

    <div class="modal-footer">
        <button class="btn btn-primary"  type="submit" name="cancel">Save changes</button>
        <?php
        $this->widget(
                'bootstrap.widgets.TbButton', array(
            'label' => 'Close',
            'url' => '#',
            'htmlOptions' => array('data-dismiss' => 'modal'),
                )
        );
        ?>
    </div>
</form>
<?php
$this->endWidget();
?>

<script>
    $(".status").on("click", function() {
        var id = $(this).attr("href");
        if (id != "") {
            $.ajax({
                type: 'POST',
                url: '<?php echo url('reservation/selectReservation'); ?>',
                data: 'id=' + id,
                success: function(data) {
                    $(".modal-body").html(data);
                },
            });
        }
    });
</script>