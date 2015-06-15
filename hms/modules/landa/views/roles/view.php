<?php
$this->setPageTitle('Lihat Roles | ID : '. $model->id);
$this->breadcrumbs=array(
	'Roles'=>array($type),
	$model->name,
);
?>

<?php
$create = "";
$edit = "";
if ($type == "customer") {
    $create = landa()->checkAccess('GroupCustomer', 'c');
    $edit = landa()->checkAccess('GroupCustomer', 'u');
} elseif ($type == "supplier") {
    $create = landa()->checkAccess('GroupSupplier', 'c');
    $edit = landa()->checkAccess('GroupSupplier', 'u');
} elseif ($type == "employment") {
    $create = landa()->checkAccess('GroupEmployment', 'c');
    $edit = landa()->checkAccess('GroupEmployment', 'u');
} elseif ($type == "guest") {
    $create = landa()->checkAccess('GroupGuest', 'c');
    $edit = landa()->checkAccess('GroupGuest', 'u');
} elseif ($type == "client") {
    $create = landa()->checkAccess('GroupClient', 'c');
    $edit = landa()->checkAccess('GroupClient', 'u');
} elseif ($type == "contact") {
    $create = landa()->checkAccess('GroupContact', 'c');
    $edit = landa()->checkAccess('GroupContact', 'u');
} else {
    $create = landa()->checkAccess('GroupUser', 'c');
    $edit = landa()->checkAccess('GroupUser', 'u');
}

$stype = ($type == 'user') ? 'index' : $type;
$this->beginWidget('zii.widgets.CPortlet', array(
	'htmlOptions'=>array(
		'class'=>''
	)
));
$this->widget('bootstrap.widgets.TbMenu', array(
	'type'=>'pills',
	'items'=>array(
//		array('label'=>'Tambah', 'icon'=>'icon-plus', 'url'=>Yii::app()->controller->createUrl('create', array('type' => $type)), 'linkOptions'=>array()),
                array('label'=>'List Data', 'icon'=>'icon-th-list', 'url'=>Yii::app()->controller->createUrl($stype), 'linkOptions'=>array()),
                array('visible'=>$edit,'label'=>'Edit', 'icon'=>'icon-edit', 'url'=>Yii::app()->controller->createUrl('update',array('id'=>$model->id,'type' => $type)), 'linkOptions'=>array()),
		//array('label'=>'Pencarian', 'icon'=>'icon-search', 'url'=>'#', 'linkOptions'=>array('class'=>'search-button')),
		array('label'=>'Print', 'icon'=>'icon-print', 'url'=>'javascript:void(0);return false', 'linkOptions'=>array('onclick'=>'printDiv();return false;')),

)));
$this->endWidget();
?>
<div class='printableArea'>


 <div class="box">
                    <div class="title">

                        <h4>
                            <span ></span>
                            <span></span>
                        </h4>
                        <a href="#" class="minimize" style="display: none;">Minimize</a>
                        
                    </div>
                    
                    <div class="content">
                        <div class="you">
                                    <ul class="list-unstyled" style="list-style:none;">
                                        <li><h3>Name &nbsp;:&nbsp;<?php echo $model->name; ?></h3></li>
                                        <?php 
                                        $status = ($model->is_allow_login == 0) ? "<span class=\"label label-important\">No</span>" :
                                            "<span class=\"label label-info\">Yes</span>";
                                        echo'<li><h3>Is Allow Login &nbsp;:&nbsp;'.$status.'</h3></li>';
                                        ?>
                                        
                                    </ul>
                            
                                </div>
                        <hr>
                        <?php
                        if($model->is_allow_login == 1){
                        ?>
                <table class="table">
                    <thead> 
                        <tr>
                            <th>Module/ Fitur</th>
                            <th>Read</th>
                            <th>Create</th>
                            <th>Update</th>
                            <th>Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $arrMenu = Auth::model()->modules();
                        $mAuth = Auth::model()->findAll(array('index' => 'id', 'select' => 'id,crud'));

                        if ($model->isNewRecord == false) {
                            $mRolesAuth = RolesAuth::model()->findAll(array('condition' => 'roles_id=' . $model->id, 'select' => 'id,auth_id,crud', 'index' => 'auth_id'));
                            
                        }

                        foreach ($arrMenu as $arr) {
                            if (isset($arr['visible']) && $arr['visible'] == false) {
                                //do nothing
                            } else {
                                if (isset($arr['auth_id'])) {
                                    $r = '';
                                    $c = '';
                                    $u = '';
                                    $d = '';

                                    $cValue = 0;
                                    $rValue = 0;
                                    $uValue = 0;
                                    $dValue = 0;

                                    //check value of checkbox
                                    if (isset($mRolesAuth[$arr['auth_id']])) {
                                        //check value
                                        if ($model->isNewRecord == false) {
                                            if (isset($mRolesAuth[$arr['auth_id']])) {
                                                $arrRolesAuth = json_decode($mRolesAuth[$arr['auth_id']]->crud, true);
                                                $cValue = (isset($arrRolesAuth['c']) && $arrRolesAuth['c'] == 1) ? 1 : 0;
                                                $rValue = (isset($arrRolesAuth['r']) && $arrRolesAuth['r'] == 1) ? 1 : 0;
                                                $uValue = (isset($arrRolesAuth['u']) && $arrRolesAuth['u'] == 1) ? 1 : 0;
                                                $dValue = (isset($arrRolesAuth['d']) && $arrRolesAuth['d'] == 1) ? 1 : 0;
                                            }
                                        }
                                    }
                                    //-------------end of checkbox--------------------

                                    $arrAuth = json_decode($mAuth[$arr['auth_id']]->crud, true);
                                    $r = (isset($arrAuth['r']) && $arrAuth['r'] == 1) ? CHtml::CheckBox($arr['auth_id'] . '[r]', $rValue,array('disabled'=>true)) : '';
                                    $c = (isset($arrAuth['c']) && $arrAuth['c'] == 1) ? CHtml::CheckBox($arr['auth_id'] . '[c]', $cValue,array('disabled'=>true)) : '';
                                    $u = (isset($arrAuth['u']) && $arrAuth['u'] == 1) ? CHtml::CheckBox($arr['auth_id'] . '[u]', $uValue,array('disabled'=>true)) : '';
                                    $d = (isset($arrAuth['d']) && $arrAuth['d'] == 1) ? CHtml::CheckBox($arr['auth_id'] . '[d]', $dValue,array('disabled'=>true)) : '';

                                    echo '<tr>
                                    <td><input type="hidden" name="auth_id[]" value="' . $arr['auth_id'] . '"/>' . $arr['label'] . '</td>
                                    <td>' . $r . '</td>
                                    <td>' . $c . '</td>
                                    <td>' . $u . '</td>
                                    <td>' . $d . '</td>
                                </tr>';
                                } else {
                                    echo '<tr>
                                    <td colspan="5">' . $arr['label'] . '</td>
                                </tr>';
                                }


                                if (isset($arr['items'])) {
                                    foreach ($arr['items'] as $arrItems) {
                                        if (isset($arrItems['visible']) && $arrItems['visible'] == false) {
                                            //do nothing
                                        } else {
                                            $cValue = 0;
                                            $rValue = 0;
                                            $uValue = 0;
                                            $dValue = 0;
                                            $r = '';
                                            $c = '';
                                            $u = '';
                                            $d = '';

                                            //check the module have access or not
                                            if (isset($arrItems['auth_id'])) {
                                                //-----------check value of checkbox
                                                if ($model->isNewRecord == false) {
                                                    if (isset($mRolesAuth[$arrItems['auth_id']])) {
                                                        $arrRolesAuth = json_decode($mRolesAuth[$arrItems['auth_id']]->crud, true);
                                                        $cValue = (isset($arrRolesAuth['c']) && $arrRolesAuth['c'] == 1) ? 1 : 0;
                                                        $rValue = (isset($arrRolesAuth['r']) && $arrRolesAuth['r'] == 1) ? 1 : 0;
                                                        $uValue = (isset($arrRolesAuth['u']) && $arrRolesAuth['u'] == 1) ? 1 : 0;
                                                        $dValue = (isset($arrRolesAuth['d']) && $arrRolesAuth['d'] == 1) ? 1 : 0;
                                                    }
                                                }
                                                //----------end of check value of checkbox----------------
                                                
                                                if (isset($mAuth[$arrItems['auth_id']])) {
                                                    $arrAuth = json_decode($mAuth[$arrItems['auth_id']]->crud, true);
                                                    $r = (isset($arrAuth['r']) && $arrAuth['r'] == 1) ? CHtml::CheckBox($arrItems['auth_id'] . '[r]', $rValue,array('disabled'=>true)) : '';
                                                    $c = (isset($arrAuth['c']) && $arrAuth['c'] == 1) ? CHtml::CheckBox($arrItems['auth_id'] . '[c]', $cValue,array('disabled'=>true)) : '';
                                                    $u = (isset($arrAuth['u']) && $arrAuth['u'] == 1) ? CHtml::CheckBox($arrItems['auth_id'] . '[u]', $uValue,array('disabled'=>true)) : '';
                                                    $d = (isset($arrAuth['d']) && $arrAuth['d'] == 1) ? CHtml::CheckBox($arrItems['auth_id'] . '[d]', $dValue,array('disabled'=>true)) : '';
                                                }

                                                echo '<tr>
                                                    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="hidden" name="auth_id[]" value="' . $arrItems['auth_id'] . '"/>' . $arrItems['label'] . '</td>
                                                    <td>' . $r . '</td>
                                                    <td>' . $c . '</td>
                                                    <td>' . $u . '</td>
                                                    <td>' . $d . '</td>
                                                </tr>';
                                            } else {
                                                echo '<tr>
                                                    <td colspan="5">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $arrItems['label'] . '</td>
                                                </tr>';
                                            }
                                        }
                                    }
                                }
                            }
                        }
                        ?>
                    </tbody>
                </table>
                        <?php }else{'';} ?>
                    </div>
                </div>


<script type="text/javascript">
function printDiv()
{

window.print();

}
</script>
