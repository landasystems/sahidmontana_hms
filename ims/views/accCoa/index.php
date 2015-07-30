<?php
$this->setPageTitle('Daftar Perkiraan');

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
    $('.search-form').slideToggle('fast');
    return false;
});
$('.search-form form').submit(function(){
    $.fn.yiiGridView.update('acc-coa-grid', {
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
        array('visible' => landa()->checkAccess('AccCoa', 'c'), 'label' => 'Tambah', 'icon' => 'icon-plus', 'url' => Yii::app()->controller->createUrl('create'), 'linkOptions' => array()),
        array('visible' => landa()->checkAccess('AccCoa', 'r'), 'label' => 'Daftar', 'icon' => 'icon-th-list', 'url' => Yii::app()->controller->createUrl('index'), 'active' => true, 'linkOptions' => array()),
        array('label' => 'Pencarian', 'icon' => 'icon-search', 'url' => '#', 'linkOptions' => array('class' => 'search-button')),
        array('label' => 'Export ke PDF', 'icon' => 'icon-download', 'url' => Yii::app()->controller->createUrl('GeneratePdf'), 'linkOptions' => array('target' => '_blank'), 'visible' => true),
        array('label' => 'Export ke Excel', 'icon' => 'icon-download', 'url' => Yii::app()->controller->createUrl('GenerateExcel'), 'linkOptions' => array('target' => '_blank'), 'visible' => true),
        array('label' => 'Import dari Excel', 'icon' => 'iconic-icon-upload', 'url' => Yii::app()->controller->createUrl('importExcel'), 'linkOptions' => array(), 'visible' => true),
        array('label'=>'Print', 'icon'=>'icon-print', 'url'=>'javascript:void(0);return false', 'linkOptions'=>array('onclick'=>'printDiv();return false;')),
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
</div><!-- search-form -->


<?php
$buton = "";
if (landa()->checkAccess('AccCoa', 'r')) {
    $buton .= '{view}';
}
if (landa()->checkAccess('AccCoa', 'd')) {
    $buton .= '{update}';
}
if (landa()->checkAccess('AccCoa', 'u')) {
    $buton .= '{delete}';
}
$this->widget('bootstrap.widgets.TbGridView', array(
    'id' => 'acc-coa-grid',
    'dataProvider' => $model->search(),
    'type' => 'striped bordered condensed',
    'template' => '{summary}{pager}{items}{pager}',
    'columns' => array(
        array(
            'name' => 'name',
            'type' => 'raw',
            'value' => '$data->nestedname',
        ),
        'type',
        array(
            'name' => 'type_sub_ledger',
            'header' => 'Type Sub Ledger',
            'value' => '($data->type=="detail") ? AccCoa::model()->getSubLedger($data->type_sub_ledger) : ""',
        ), array(
            'name' => 'saldo',
            'header' => 'Saldo Saat Ini',
            'value' => '($data->type=="detail") ? landa()->rp(AccCoaDet::model()->beginingBalance(date("Y-m-d"),$data->id),false,2) : ""',
        ),
        array(
            'class' => 'bootstrap.widgets.TbButtonColumn',
            'template' => $buton,
            'buttons' => array(
                'view' => array(
                    'label' => 'Lihat',
                    'options' => array(
                        'class' => 'btn btn-small view'
                    )
                ),
                'update' => array(
                    'label' => 'Edit',
//                    'visible' => '$data->code!="1" && $data->code!="2" && $data->code!="3" && $data->code!="4" && $data->code!="5"',
                    'options' => array(
                        'class' => 'btn btn-small update'
                    )
                ),
                'delete' => array(
                    'label' => 'Hapus',
//                    'visible' => '$data->code!="1" && $data->code!="2" && $data->code!="3" && $data->code!="4" && $data->code!="5"',
                    'options' => array(
                        'class' => 'btn btn-small delete'
                    )
                )
            ),
            'htmlOptions' => array('style' => 'width: 125px; text-align:center'),
        )
    ),
));
?>
<div class='printableArea'>
    <table width="100%">
        <tr>
            <td  style="text-align: center" colspan="4"><h2>Daftar Akun</h2></td>
        </tr>
        <tr>
            <td style="text-align: center" colspan="4">
                Dicetak pada Tanggal : <?php echo date('d F Y'); ?>
            </td>
        </tr>
        <tr>
        </tr>
        <tr>
            <td>
            </td>
        </tr>
    </table>
    <?php if ($model1 !== null): ?>
        <table border="1" width="100%">

            <tr>
                <th width="80px">
                    id		</th>
                <th width="80px">
                    Nama		</th>
                <th width="80px">
                    Deskripsi		</th>
                <th width="80px">
                    Saldo Saat Ini		</th>
        <!-- 		<th width="80px">
                    created_user_id		</th>
              <th width="80px">
                    modified		</th>
              <th width="80px">
                    created		</th>
              <th width="80px">
                    level		</th>
              <th width="80px">
                    lft		</th>
              <th width="80px">
                    rgt		</th>
              <th width="80px">
                    root		</th>
              <th width="80px">
                    parent_id		</th>-->
            </tr>
            <?php foreach ($model1 as $row): ?>
                <tr>
                    <td>
                        <?php echo $row->id; ?>
                    </td>
                    <td>
                        <?php echo $row->name; ?>
                    </td>
                    <td>
                        <?php echo $row->description; ?>
                    </td>
                    <td>
                        <?php echo landa()->rp(AccCoaDet::model()->beginingBalance(date("Y-m-d"), $row->id)); ?>
                    </td>
            <!--       		<td>
                    <?php // echo $row->created_user_id; ?>
                    </td>
                    <td>
                    <?php // echo $row->modified; ?>
                    </td>
                    <td>
                    <?php // echo $row->created; ?>
                    </td>
                    <td>
                    <?php // echo $row->level; ?>
                    </td>
                    <td>
                    <?php // echo $row->lft; ?>
                    </td>
                    <td>
                    <?php // echo $row->rgt; ?>
                    </td>
                    <td>
                    <?php // echo $row->root; ?>
                    </td>
                    <td>
                    <?php // echo $row->parent_id; ?>
                    </td>-->
                </tr>
            <?php endforeach; ?>
        </table>

    <?php endif; ?>
</div>
<style>
    .printableArea{display:none}
</style>
<style type="text/css" media="print">
    body {visibility:hidden;}
    .printableArea{visibility:visible;display:block;position: absolute;top:0;left:0;width: 100%;font-size:17px}
</style>
<script type="text/javascript">
    function printDiv()
    {

        window.print();

    }
</script>