<style>
    .form{
        margin-bottom: 0px;
    }
    .tengah{
        margin: 0px;
        margin-top: 0px;
        margin-bottom: 0px;
    }
    .tab-pane{
        padding-top: 10px;
        height: 450px;
        overflow:auto;
        padding-right: 35px;
    }
    .tab-content{
        border-bottom: none;
        background: url(../images/patterns/2.png) repeat;
    }
</style>
<?php
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id' => 'site-config-form',
    'enableAjaxValidation' => false,
    'method' => 'post',
    'type' => 'horizontal',
    'htmlOptions' => array(
        'enctype' => 'multipart/form-data',
        'style' => 'margin-bottom:0px',
    )
        ));
?>
<div class="tengah">
    <?php
    $this->widget(
            'bootstrap.widgets.TbWizard', array(
        'type' => 'tabs', // 'tabs' or 'pills'
        'pagerContent' => '<div style="float:right">
					<input type="button" class="btn button-next" name="next" value="Next" />
                                        <input type="button" class="btn finish" name="finish" value="finish" id="finish" style="display:none;"/>
				</div>
				<div style="float:left">
					<input type="button" class="btn button-previous" name="previous" value="Previous" />
                                </div>',
        'options' => array(
            'nextSelector' => '.button-next',
            'previousSelector' => '.button-previous',
            'firstSelector' => '.button-first',
            'lastSelector' => '.button-last',
            'onTabShow' => 'js:function(tab, navigation, index) {
						var $total = navigation.find("li").length;
						var $current = index+1;
						var $percent = ($current/$total) * 100;
						$("#wizard-bar > .bar").css({width:$percent+"%"});
                                                
                                                if($current >= $total){
                                                    $(".button-next").hide();
                                                    $(".finish").show();
                                                }else{
                                                    $(".button-next").show();
                                                    $(".finish").hide();
                                                }
			}',
            'onTabClick' => 'js:function(tab, navigation, index) {return false;}',
            'onNext' => 'js:function(tab, navigation, index){
                            if(index == 1){
                                if(!$("#SiteConfig_client_name").val() || !$("#SiteConfig_email").val() || !$("#province_id").val() || !$("#SiteConfig_city_id").val() || !$("#SiteConfig_address").val()){
                                     $("#alertContent").html("<strong>Error! </strong> Client name, email,  province, city and address cannot be blank");
                                     $("#alert").modal("show");
                                    return false;
                                }
                            }
                            
                            if(index == 4){
                                if(!$("#SiteConfig_format_reservation").val() || !$("#SiteConfig_format_registration").val() || !$("#SiteConfig_format_bill").val() || !$("#SiteConfig_format_bill_charge").val() || !$("#SiteConfig_format_deposite").val()){
                                     $("#alertContent").html("<strong>Error! </strong> format reservation, format registration,  format bill, format bill for departement and format deposite cannot be blank");
                                     $("#alert").modal("show");
                                    return false;
                                }
                            }
                            
                        }',
        ),
        'tabs' => array(
            array(
                'label' => 'Profile',
                'content' => $this->renderPartial('_profile', array('model' => $model, 'form' => $form), TRUE),
                'active' => true
            ),
            array(
                'label' => 'Department',
                'content' => $this->renderPartial('_chargeAdditionalCategori', array('model' => $model, 'form' => $form), TRUE),
            ),
            array(
                'label' => 'Charge Additional',
                'content' => $this->renderPartial('_chargeAdditional', array('form' => $form, 'model' => $model), TRUE),
            ),
            array(
                'label' => 'Code Formatting',
                'content' => $this->renderPartial('_codeFormatting', array('form' => $form, 'model' => $model), TRUE),
            ),
            array(
                'label' => 'Global Configuration',
                'content' => $this->renderPartial('_global', array('form' => $form, 'model' => $model), TRUE),
            ),
//            array(
//                'label' => 'Account Accounting',
//                'content' => $this->renderPartial('_accountAccounting', array('form' => $form, 'model' => $model), TRUE),
//            ),
            array(
                'label' => 'Accounting',
                'content' => $this->renderPartial('_accounting', array('form' => $form, 'model' => $model), TRUE),
            ),
        ),
            )
    );
    ?>
</div>
<div id="wizard-bar" class="progress progress-striped active">
    <div class="bar"></div>
</div>
<div id="alert" class="modal large hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="myModalLabel"><i>Alert !</i></h3>
    </div>
    <div class="modal-body">
        <div class="alert alert-error">
            <div id="alertContent"></div>
        </div>
    </div>
</div>
<script>
    $('#finish').on('click', function () {
        document.getElementById("site-config-form").submit();
    });
</script>
<?php $this->endWidget(); ?>