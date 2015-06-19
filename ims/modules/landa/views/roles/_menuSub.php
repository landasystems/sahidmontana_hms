
<?php

foreach ($arrMenu as $arr) {
    if (isset($arr['visible']) && $arr['visible'] == false) {
        //do nothing
    } else {
        if (isset($arr['auth_id'])) { //sudah di setting authnya
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

            if (isset($mAuth[$arr['auth_id']]->crud)) {
                $arrAuth = json_decode($mAuth[$arr['auth_id']]->crud, true);
                $r = (isset($arrAuth['r']) && $arrAuth['r'] == 1) ? CHtml::CheckBox($arr['auth_id'] . '[r]', $rValue) : '';
                $c = (isset($arrAuth['c']) && $arrAuth['c'] == 1) ? CHtml::CheckBox($arr['auth_id'] . '[c]', $cValue) : '';
                $u = (isset($arrAuth['u']) && $arrAuth['u'] == 1) ? CHtml::CheckBox($arr['auth_id'] . '[u]', $uValue) : '';
                $d = (isset($arrAuth['d']) && $arrAuth['d'] == 1) ? CHtml::CheckBox($arr['auth_id'] . '[d]', $dValue) : '';

                echo '<tr>
                                    <td><input type="hidden" name="auth_id[]" value="' . $arr['auth_id'] . '"/>' . $space. $arr['label'] . '</td>
                                    <td>' . $r . '</td>
                                    <td>' . $c . '</td>
                                    <td>' . $u . '</td>
                                    <td>' . $d . '</td>
                                </tr>';
            }else{
                echo '<tr>
                                    <td colspan="5">'. $space . $arr['label'] . '</td>
                    </tr>';
            }
        } else {
            echo '<tr>
                                    <td colspan="5">'. $space . $arr['label'] . '</td>
                 </tr>';
        }


        if (isset($arr['items'])) {
            $this->renderPartial('_menuSub', array('arrMenu' => $arr['items'], 'mRolesAuth' => $mRolesAuth, 'mAuth' => $mAuth, 'model' => $model, 'space'=>$space . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'));
        }
    }
}
?>
