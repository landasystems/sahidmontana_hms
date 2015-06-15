<?php
$this->setPageTitle('View Deposites | ID : ' . $model->id);
$this->breadcrumbs = array(
    'Deposites' => array('index'),
    $model->id,
);
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
        //array('label'=>'Pencarian', 'icon'=>'icon-search', 'url'=>'#', 'linkOptions'=>array('class'=>'search-button')),
        array('label' => 'Print', 'icon' => 'icon-print', 'url' => 'javascript:void(0);return false', 'linkOptions' => array('onclick' => 'printDiv();return false;')),
//        array('label' => 'Print', 'icon' => 'icon-print', 'url' => '#myModal',
//            'linkOptions' => array('data-toggle' => 'modal')),
)));
$this->endWidget();
?>

<div class="form">
    <?php
    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id' => 'deposite-form',
        'enableAjaxValidation' => false,
        'method' => 'post',
        'type' => 'horizontal',
        'htmlOptions' => array(
            'enctype' => 'multipart/form-data'
        )
    ));
    ?>
    <fieldset> 
        <div class="box gradient invoice">
            <div class="title clearfix">
                <h4 class="left">
                    <span class="blue cut-icon-bookmark"></span>
                    <span>Deposite Transaction</span>                        
                </h4>
                <div class="invoice-info">                        
                    <span class="number"> <strong class="red">
                            <?php
                            echo $model->code;
                            ?>                            
                        </strong></span><br>
                    <span class="data gray"><?php echo date('d M Y') ?></span>
                </div> 
            </div>

            <div class="content">                   
                <?php
                $data = array(0 => t('choose', 'global')) + CHtml::listData(User::model()->listUsers('guest'), 'id', 'fullName');
                echo $form->select2Row($model, 'guest_user_id', array(
                    'asDropDownList' => true,
                    'data' => $data,
                    'disabled' => true,
                    'options' => array(
                        "placeholder" => t('choose', 'global'),
                        "allowClear" => true,
                        'width' => '50%',
                )));
               
                $dp_by = array('cash' => 'Cash', 'cc' => 'Credit Card', 'debit' => 'Debit Card', 'ca' => 'City Ledger');
                echo $form->dropDownListRow($model, 'dp_by', $dp_by, array('class' => 'span2', 'maxlength' => 5, 'empty' => t('choose', 'global'), 'disabled' => true,));
                echo $form->textFieldRow($model, 'amount', array('class' => 'span3', 'prepend' => 'Rp', 'disabled' => true,));
                echo $form->textFieldRow($model, 'cc_number', array('class' => 'span3', 'disabled' => true));
                echo $form->textAreaRow($model, 'description', array('rows' => 6, 'cols' => 50, 'class' => 'span8', 'disabled' => true,));
                ?>                                                        

                <table style="width:100%">
                    <tr>
                        <td style="width:50%;text-align: center;vertical-align: top">
                            <br>
                            Guest Sign
                            <br>
                            <br>
                            <br>
                    <u>......................................</u>
                    </td>
                    <td style="width:50%;text-align: center;vertical-align: top">
                        <br>
                        Cashier
                        <br>
                        <br>
                        <br>
                    <u><?php echo Yii::app()->user->name; ?></u>
                    </td>
                    </tr>
                </table>
            </div>
        </div> 
    </fieldset>

    <?php $this->endWidget(); ?>
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
