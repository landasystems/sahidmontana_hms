<?php
$this->setPageTitle('View Deposites | '. $model->id);

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
        array('label' => 'List Data', 'icon' => 'icon-th-list', 'url' => Yii::app()->controller->createUrl('index'), 'linkOptions' => array()),
        array('label' => 'Print', 'icon' => 'icon-print', 'url' => 'javascript:void(0);return false', 'linkOptions' => array('onclick' => 'printDiv();return false;')),
)));
$this->endWidget();
?>

    <div class='printableArea'> 
        <?php
        $siteConfig = SiteConfig::model()->listSiteConfig();
        $content = $siteConfig->report_deposite;
        $content = str_replace('{invoice}', $model->code, $content);
        $content = str_replace('{date}', date('d F Y', strtotime($model->created)), $content);
        $content = str_replace('{guest}', ucwords($model->Guest->name), $content);
        $content = str_replace('{by}', ucwords($model->dp_by), $content);
        $content = str_replace('{amount}', landa()->rp($model->amount), $content);
        $content = str_replace('{cc_number}', $model->cc_number, $content);
        $content = str_replace('{desc}', $model->description, $content);
        $content = str_replace('{cashier}', ucwords($model->Cashier->name), $content);
        echo $content;
        ?>
    </div>


</div>
<style type="text/css">
    .printableArea{visibility: hidden;top:0px;left:0px;position: fixed;z-index: -1000} 
    @media print
    {
        body {visibility:hidden;}
        body {-webkit-print-color-adjust: exact;}
        .printableArea{visibility: visible;} 
        .printableArea{top: 0px;left: 0px;position: fixed;} 
        #sidebar{display: none;}  
    }
</style>
<script type="text/javascript">
    function printDiv()
    {

        window.print();
    }
</script>
