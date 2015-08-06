<?php
$this->setPageTitle('View Deposites | ' . $model->id);
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
        array('label' => 'List Data', 'icon' => 'icon-th-list', 'url' => Yii::app()->controller->createUrl('index'), 'linkOptions' => array()),
        array('label' => 'Edit', 'icon' => 'icon-edit', 'url' => Yii::app()->controller->createUrl('update', array('id' => $model->id)), 'linkOptions' => array()),
        array('label' => 'Print', 'icon' => 'icon-print', 'url' => 'javascript:void(0);return false', 'linkOptions' => array('onclick' => 'printElement("printElement");return false;')),
)));
$this->endWidget();
?>

<div id='printElement'> 
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
