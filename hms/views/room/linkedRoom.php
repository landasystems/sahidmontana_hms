<?php
$this->setPageTitle('Rooms');


Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
    $('.search-form').slideToggle('fast');
    return false;
});
$('.search-form form').submit(function(){
    $.fn.yiiGridView.update('room-grid', {
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
//        array('label' => 'Create', 'icon' => 'icon-plus', 'url' => Yii::app()->controller->createUrl('create'), 'linkOptions' => array()),
        array('label' => 'List Data', 'icon' => 'icon-th-list', 'url' => Yii::app()->controller->createUrl('linkedRoom'), 'active' => true, 'linkOptions' => array()),
//        array('label' => 'Pencarian', 'icon' => 'icon-search', 'url' => '#', 'linkOptions' => array('class' => 'search-button')),
//        array('label' => 'Export ke PDF', 'icon' => 'icon-download', 'url' => Yii::app()->controller->createUrl('GeneratePdf'), 'linkOptions' => array('target' => '_blank'), 'visible' => true),
//        array('label' => 'Export ke Excel', 'icon' => 'icon-download', 'url' => Yii::app()->controller->createUrl('GenerateExcel'), 'linkOptions' => array('target' => '_blank'), 'visible' => true),
        array('label' => 'Print', 'icon' => 'icon-print', 'url' => 'javascript:void(0);return false', 'linkOptions' => array('onclick' => 'printDiv();return false;')),
    ),
));
$this->endWidget();
?>





<div id="room-grid" class="grid-view">
    <table class="items table table-striped  table-condensed">
        <thead>
            <tr>
                <th><a  href="#">Linked Room</a></th>
                <th class="button-column">&nbsp;</th>
            </tr>
        </thead>
        <tbody>

            <?php
            foreach ($model as $room) {
                echo
                '<tr><td>' . $room->nestedName . '</td>
                         <td style="width: 15px"><a class="btn btn-small update" title="Edit" rel="tooltip" href="'.Yii::app()->controller->createUrl('room/UpdateLinkedRoom/'.$room->id).'"><i class="icon-pencil"></i></a></td>
                         </tr>';

                $linkeds = Room::model()->findAll(array('condition' => 'status="occupied" and linked_room_id=' . $room->id));
                foreach ($linkeds as $linked) {
                    echo
                    '<tr><td>' . $linked->nestedName . '</td><td></td></tr>';
                }
            }

            if (count($model) == 0)
                echo '<tr><td colspan="2">No results found</td></tr>';
            ?>                                        
    </table>    
</div>

