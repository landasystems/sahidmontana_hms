
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
                        $rValue = (isset($arrRolesAuth['r']) && $arrRolesAuth['r'] == 1) ? 1 : 0;
                        $dValue = (isset($arrRolesAuth['d']) && $arrRolesAuth['d'] == 1) ? 1 : 0;
                    }
                }
            }
            //-------------end of checkbox--------------------

            if (isset($arr['crud'])) {
                $arrAuth = $arr['crud'];
                $r = CHtml::CheckBox($arr['auth_id'] . '[r]', $rValue);
                
                if($arr['auth_id'] == 'BillCharge')
                $d = CHtml::CheckBox($arr['auth_id'] . '[d]', $dValue);
                
                echo '<tr>
                                    <td><input type="hidden" name="auth_id[]" value="' . $arr['auth_id'] . '"/>' . $space . $arr['label'] . '</td>
                                    <td style="text-align:center">' . $r . '</td>
                                    <td style="text-align:center">' . $d . '</td>
                                </tr>';
            } else {
                echo '<tr>
                                    <td colspan="3">' . $space . $arr['label'] . '</td>
                    </tr>';
            }
        } else {
            echo '<tr>
                                    <td colspan="3">' . $space . $arr['label'] . '</td>
                 </tr>';
        }


        if (isset($arr['items'])) {
            $this->renderPartial('_menuSub', array('arrMenu' => $arr['items'], 'mRolesAuth' => $mRolesAuth, 'model' => $model, 'space' => $space . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'));
        }
    }
}
?>
