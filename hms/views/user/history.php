<?php
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id' => 'results',
    'enableAjaxValidation' => false,
    'method' => 'post',
    'type' => 'horizontal',
    'htmlOptions' => array(
        'enctype' => 'multipart/form-data'
        )));


$this->setPageTitle('Guest History ');
$this->breadcrumbs = array(
    'Guest' => array('guest'),
);
?>
<div class="well">
    <div class="control-group ">
        <label class="control-label" for="guest">Guest Name</label>
        <div class="controls">
            <?php
            $siteConfig = SiteConfig::model()->listSiteConfig();
            $sCriteria = json_decode($siteConfig->roles_guest, true);
            $list = '';
            foreach ($sCriteria as $o) {
                $list .= '"' . $o . '",';
            }
            $list = substr($list, 0, strlen($list) - 1);
            $sResult = User::model()->findAll(array('condition' => 'roles_id in(' . $list . ')'));
            $listUser = Chtml::listdata($sResult, 'id', 'fullName');
            $this->widget(
                    'bootstrap.widgets.TbSelect2', array(
                'asDropDownList' => true,
                'name' => 'user',
                'value' => (!empty($_POST['user'])) ? $_POST['user'] : '',
                'data' => array(0 => t('choose', 'global')) + $listUser,
                'options' => array(
                    "placeholder" => t('choose', 'global'),
                    "allowClear" => false,
                    'width' => '30%',
                ),
                    )
            );
            ?>
        </div>
    </div>

    <div class="form-actions">
        <?php
        $this->widget('bootstrap.widgets.TbButton', array(
            'buttonType' => 'submit',
            'type' => 'primary',
            'icon' => 'ok white',
            'label' => 'View History',
        ));
        ?>
    </div>
</div>

<?php
if (isset($_POST['user'])) {
    $criteria = new CDbCriteria();    
    $model = new Registration('search');    
    $model->guest_user_id = $_POST['user'];
    $this->renderPartial('_history', array('model' => $model));
}
?>

<?php $this->endWidget(); ?>
