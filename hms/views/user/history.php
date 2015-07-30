<?php
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id' => 'results',
    'action' => Yii::app()->createUrl('user/history?v=1'),
    'enableAjaxValidation' => false,
    'method' => 'get',
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
                'asDropDownList' => false,
                'name' => 'user',
                'value' => (!empty($_POST['user'])) ? $_POST['user'] : '',
                'options' => array(
                    'allowClear' => true,
                    "placeholder" => 'Please Choose',
                    'width' => '85%',
                    'minimumInputLength' => '3',
                    'ajax' => array(
                        'url' => Yii::app()->createUrl('user/getListUser'),
                        'dataType' => 'json',
                        'data' => 'js:function(term, page) { 
                                                        return {
                                                            q: term 
                                                        }; 
                                                    }',
                        'results' => 'js:function(data) { 
                                                        return {
                                                            results: data
                                                        };
                                                    }',
                    ),
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
$cari = isset($_GET['v']) ? "1" : "0";
if ($cari == "1") {
    $criteria = new CDbCriteria;
    $user = User::model()->findByPk($_GET['user']);
    $model = new Registration;
    $model->guest_user_id = $_GET['user'];
    $this->renderPartial('_history', array('model' => $model, 'user' => $user));
}
?>

<?php $this->endWidget(); ?>
