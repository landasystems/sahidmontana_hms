<legend>
    <p class="note">Fields with <span class="required">*</span> is Required.</p>
</legend>
<div class="control-group">
    <lable class="control-label">Cash <span class="required">*</span></lable>
    <div class="controls">
        <?php
        $coa = array(0 => 'Please Choose') + CHtml::listData(AccCoa::model()->findAll(array('order' => 'root, lft')), 'id', 'nestedname');
        $this->widget('bootstrap.widgets.TbSelect2', array(
            'asDropDownList' => TRUE,
            'data' => $coa,
            'value' => $model->acc_cash_id,
            'name' => 'SiteConfig[acc_cash_id]',
            'options' => array(
                "placeholder" => 'Please Choose',
                "allowClear" => true,
                'width' => '40%',
            ),
            'htmlOptions' => array(
                'id' => 'SiteConfig_acc_cash_id',
            ),
        ));
        ?>
    </div>
</div>
<div class="control-group">
    <lable class="control-label">City Ledger <span class="required">*</span></lable>
    <div class="controls">
        <?php
        $coa = array(0 => 'Please Choose') + CHtml::listData(AccCoa::model()->findAll(array('order' => 'root, lft')), 'id', 'nestedname');
        $this->widget('bootstrap.widgets.TbSelect2', array(
            'asDropDownList' => TRUE,
            'data' => $coa,
            'value' => $model->acc_city_ledger_id,
            'name' => 'SiteConfig[acc_city_ledger_id]',
            'options' => array(
                "placeholder" => 'Please Choose',
                "allowClear" => true,
                'width' => '40%',
            ),
            'htmlOptions' => array(
                'id' => 'SiteConfig_acc_city_ledger_id',
            ),
        ));
        ?>
    </div>
</div>
<div class="control-group">
    <lable class="control-label">Service Charge <span class="required">*</span></lable>
    <div class="controls">
        <?php
        $coa = array(0 => 'Please Choose') + CHtml::listData(AccCoa::model()->findAll(array('order' => 'root, lft')), 'id', 'nestedname');
        $this->widget('bootstrap.widgets.TbSelect2', array(
            'asDropDownList' => TRUE,
            'data' => $coa,
            'value' => $model->acc_service_charge_id,
            'name' => 'SiteConfig[acc_service_charge_id]',
            'options' => array(
                "placeholder" => 'Please Choose',
                "allowClear" => true,
                'width' => '40%',
            ),
            'htmlOptions' => array(
                'id' => 'SiteConfig_acc_service_charge_id',
            ),
        ));
        ?>
    </div>
</div>
<div class="control-group">
    <lable class="control-label">Tax <span class="required">*</span></lable>
    <div class="controls">
        <?php
        $coa = array(0 => 'Please Choose') + CHtml::listData(AccCoa::model()->findAll(array('order' => 'root, lft')), 'id', 'nestedname');
        $this->widget('bootstrap.widgets.TbSelect2', array(
            'asDropDownList' => TRUE,
            'data' => $coa,
            'value' => $model->acc_tax_id,
            'name' => 'SiteConfig[acc_tax_id]',
            'options' => array(
                "placeholder" => 'Please Choose',
                "allowClear" => true,
                'width' => '40%',
            ),
            'htmlOptions' => array(
                'id' => 'SiteConfig_acc_tax_id',
            ),
        ));
        ?>
    </div>
</div>
<div class="control-group">
    <lable class="control-label">Clearance <span class="required">*</span></lable>
    <div class="controls">
        <?php
        $coa = array(0 => 'Please Choose') + CHtml::listData(AccCoa::model()->findAll(array('order' => 'root, lft')), 'id', 'nestedname');
        $this->widget('bootstrap.widgets.TbSelect2', array(
            'asDropDownList' => TRUE,
            'data' => $coa,
            'value' => $model->acc_clearance_id,
            'name' => 'SiteConfig[acc_clearance_id]',
            'options' => array(
                "placeholder" => 'Please Choose',
                "allowClear" => true,
                'width' => '40%',
            ),
            'htmlOptions' => array(
                'id' => 'SiteConfig_acc_clearance_id',
            ),
        ));
        ?>
    </div>
</div>