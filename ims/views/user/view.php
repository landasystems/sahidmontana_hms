<?php
$this->setPageTitle('Lihat | ' . $model->name);
?>

<?php
$create = "";
$edit = "";
if ($type == "customer") {
    $create = landa()->checkAccess('Customer', 'c');
    $edit = landa()->checkAccess('Customer', 'u');
} elseif ($type == "supplier") {
    $create = landa()->checkAccess('Supplier', 'c');
    $edit = landa()->checkAccess('Supplier', 'u');
} elseif ($type == "employment") {
    $create = landa()->checkAccess('Employment', 'c');
    $edit = landa()->checkAccess('Employment', 'u');
} else {
    $create = landa()->checkAccess('User', 'c');
    $edit = landa()->checkAccess('User', 'u');
}
$this->beginWidget('zii.widgets.CPortlet', array(
    'htmlOptions' => array(
        'class' => ''
    )
));
$this->widget('bootstrap.widgets.TbMenu', array(
    'type' => 'pills',
    'items' => array(
        array('visible' => $create, 'label' => 'Tambah', 'icon' => 'icon-plus', 'url' => Yii::app()->controller->createUrl('create', array('type' => $type)), 'linkOptions' => array()),
        array('label' => 'Daftar', 'icon' => 'icon-th-list', 'url' => Yii::app()->controller->createUrl($type), 'linkOptions' => array()),
        array('visible' => $edit, 'label' => 'Edit', 'icon' => 'icon-edit', 'url' => Yii::app()->controller->createUrl('update', array('id' => $model->id, 'type' => $type,)), 'linkOptions' => array()),
        //array('label'=>'Pencarian', 'icon'=>'icon-search', 'url'=>'#', 'linkOptions'=>array('class'=>'search-button')),
        array('label' => 'Print', 'icon' => 'icon-print', 'url' => 'javascript:void(0);return false', 'linkOptions' => array('onclick' => 'printDiv();return false;')),
)));
$this->endWidget();
?>
<div class='printableArea'>

    <?php
//$this->widget('bootstrap.widgets.TbDetailView', array(
//    'data' => $model,
//    'attributes' => array(
//        'id',
//        'username',
//        'email',
//        'password',
//        'user_position_id',
//        'code',
//        'name',
//        'city_id',
//        'address',
//        'phone',
//        'created',
//        'created_user_id',
//        'modified',
//    ),
//));
    ?>
</div>
<style type="text/css" media="print">
    body {visibility:hidden;}
    .printableArea{visibility:visible;} 
</style>
<script type="text/javascript">
    function printDiv()
    {

        window.print();

    }
</script>
<div class="tab-pane active" id="personal">

    <table>
        <tr>
            <td width="30%" style="vertical-align: top">

                <?php
                echo $model->mediumImage;
                ?>

            </td>
            <td style="vertical-align: top;" width="70%">
                <table class="table table-striped" style="width:100%">

                    <tr>
                        <td style="text-align: left" class="span1">
                            <b>Name</b>
                        </td>
                        <td style="text-align: left;width:1px">
                            :
                        </td>
                        <td style="text-align: left" class="span4">
                            <?php echo $model->name; ?>
                        </td>
                        <td style="text-align: left" class="span1">
                            
                        </td>
                        <td style="text-align: left;width:1px" class="">
                            
                        </td>                        
                        <td style="text-align: left" class="span4">
                           
                        </td>

                    </tr>
                    <tr>
                        <td style="text-align: left" class="span1">
                            <b>Provinsi</b>
                        </td>
                        <td style="text-align: left;width:1px">
                            :
                        </td>
                        <td style="text-align: left" class="span4">
                            <?php echo $model->City->Province->name; ?>
                        </td>
                        <td style="text-align: left" class="span1">
                            <span class="inventory"><b>Telephone</b></span>
                        </td>
                        <td style="text-align: left;width:1px" class="">
                            <span class="inventory">:</span>
                        </td>                        
                        <td style="text-align: left" class="span4">
                            <span class="inventory">
                                <?php echo landa()->hp($model->phone); ?>            
                            </span>
                        </td>

                    </tr>
                    <tr>
                        <td style="text-align: left" class="span1">
                            <b>Kota</b>
                        </td>
                        <td style="text-align: left;width:1px">
                            :
                        </td>
                        <td style="text-align: left" class="span4">
                            <?php echo $model->City->name; ?>
                        </td>
                        <td style="text-align: left" class="span1">
                            <span class="inventory"><b>Email</b></span>&nbsp;
                        </td>
                        <td style="text-align: left;width:1px" class="">
                            <span class="inventory">:</span>&nbsp;
                        </td>                        
                        <td style="text-align: left" class="span4">
                            <span class="inventory">
                                <?php
                                echo $model->email;
                                ?>

                            </span>&nbsp;
                        </td>

                    </tr>                     

                    <tr class="inventory">
                        <td style="text-align: left" class="span2">
                            <b>Alamat</b>
                        </td>
                        <td style="text-align: left;width:1px">
                            :
                        </td>
                        <td style="text-align: left" class="span4">
                            <?php echo $model->address; ?>
                        </td>
                        <td style="text-align: left" class="span2 inventory">

                        </td>
                        <td style="text-align: left;width:1px" class="inventory">

                        </td>                        
                        <td style="text-align: left" class="span4 inventory">

                        </td>

                    </tr>  


                    <tr class="inventory">
                        <td style="text-align: left" class="span2" colspan="6">
                            <?php echo '<i>"' . $model->description . '"</i>'; ?>
                        </td>


                    </tr>                     
                </table>                                           
            </td>

        </tr>


    </table>

</div>
<?php
if (isset($_GET['type']) && $_GET['type']!='user') {
    $header = InvoiceDet::model()->findAll(array(
        'condition' => 'user_id=' . $_GET['id'],
    ));
    $this->renderPartial('invoiceLog', array(
        'header' => $header
    ));
}
?>
