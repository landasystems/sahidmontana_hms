<?php

$this->setPageTitle('Jurnal | Kode : ' . $model->code);
$this->beginWidget('zii.widgets.CPortlet', array(
    'htmlOptions' => array(
        'class' => ''
    )
));
$this->widget('bootstrap.widgets.TbMenu', array(
    'type' => 'pills',
    'items' => array(
        array('visible' => landa()->checkAccess('AccJurnal', 'c'), 'label' => 'Tambah', 'icon' => 'icon-plus', 'url' => Yii::app()->controller->createUrl('create'), 'linkOptions' => array()),
        array('visible' => landa()->checkAccess('AccJurnal', 'r'), 'label' => 'Daftar', 'icon' => 'icon-th-list', 'url' => Yii::app()->controller->createUrl('index'), 'linkOptions' => array()),
        array('visible' => landa()->checkAccess('AccJurnal', 'u'), 'label' => 'Edit', 'icon' => 'icon-edit', 'url' => Yii::app()->controller->createUrl('update', array('id' => $model->id)), 'active' => true, 'linkOptions' => array()),
    ),
));
$this->endWidget();
?>

<?php echo $this->renderPartial('_form', array('model' => $model, 'detailJurnal' => $detailJurnal)); ?>
<h4>History</h4>
<table class="responsive table table-bordered">
    <thead>
        <tr>
            <th>No</th>
            <th>Tanggal</th>
            <th>Status</th>
            <th>Keterangan</th>
            <th>User</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $no = 1;
        foreach ($approveDetail as $val) {
            if ($val->status == "open") {
                $stt = '<span class="label">Open</span>';
            } elseif ($val->status == "pending") {
                $stt = '<span class="label label-info">Pending</span>';
            } elseif ($val->status == "reject") {
                $stt = '<span class="label label-important">Reject</span>';
            } elseif ($val->status == "confirm") {
                $stt = '<span class="label label-success">Confirm</span>';
            }
            echo '  <tr>
                                        <td>' . $no . '</td>
                                        <td>' . date('d M Y H:i:s', strtotime($val->created)) . '</td>
                                        <td>' . $stt . '</td>
                                        <td>' . $val->description . '</td>
                                        <td>' . $val->User->name . '</td>
                                    </tr>';
            $no++;
        }
        ?>
    </tbody>
</table>