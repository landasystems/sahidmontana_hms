

<?php
$this->setPageTitle('View Guest Bill | ID : ' . $model->id);

?>

<?php

$this->widget('bootstrap.widgets.TbMenu', array(
    'type' => 'pills',
    'items' => array(
        array('label' => 'Create', 'icon' => 'icon-plus', 'url' => Yii::app()->controller->createUrl('create'), 'linkOptions' => array()),
        array('label' => 'List Data', 'icon' => 'icon-th-list', 'url' => Yii::app()->controller->createUrl('index'), 'linkOptions' => array()),
        array('label' => 'Edit', 'icon' => 'icon-edit', 'url' => Yii::app()->controller->createUrl('update', array('id' => $model->id)), 'linkOptions' => array()),
        //array('label'=>'Search', 'icon'=>'icon-search', 'url'=>'#', 'linkOptions'=>array('class'=>'search-button')),
        array('label' => 'Print', 'icon' => 'icon-print', 'url' => 'javascript:void(0);return false', 'linkOptions' => array('onclick' => 'printDiv("printableArea");return false;')),
        array('label' => 'Export Excel', 'icon' => 'entypo-icon-export', 'url' => Yii::app()->controller->createUrl('GenerateReport', array('id' => $model->id)), 'linkOptions' => array()),
)));
?>


<div id='printableArea'> 
    <style type="text/css">
        /*.printableArea{display: none}*/ 
        /*    @media print
            {*/
        #bill_print{
            margin: 3px;
            padding:3px;
            line-height: 12px;
            font-size: 10px;        
            /*font-family: monospace;*/
            page-break-after: avoid;
        }
        #print{        
            font-size: 10px;        
            /*font-family: monospace;*/        
        }
        /*        #wrapper {display: none}
                .printableArea{display: block !important;width: 100%;top: 0px;left: 0px;position: absolute;}*/

        /*}*/
    </style>
    <?php
    $this->renderPartial('_excelBill', array('model' => $model, 'details' => $details));
    ?>
</div>
<?php // echo $content; ?>

<script type="text/javascript">
    function printDiv(divName)
    {
        var printContents = document.getElementById(divName).innerHTML;
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        window.print();

        document.body.innerHTML = originalContents;
        $("#myTab a").click(function(e) {
            e.preventDefault();
            $(this).tab("show");
        });
    }
</script>