
<table class="table">
    <tr>
        <th>No</th>
        <th>Roles User</th>
        <th>Name User</th>
        <th>Time Log</th>
    </tr>
    
        <?php
        $listUser = User::model()->listUser();
        $oUserLogs = UserLog::model()->findAll(array('order' => 'created DESC','limit'=>50));
        $no=0;
        foreach ($oUserLogs as $oUserLog) {
            $name = (isset($oUserLogs->User->name)) ? $oUserLogs->User->name :'-';
            $roles = (isset($oUserLog->User->Roles->name)) ? $oUserLog->User->Roles->name :'Super User';
            $no++;
            if (isset($listUser[$oUserLog->user_id])) {
               
                echo'<tr>
                    <td>'.$no.'</td>
                    <td>'.$roles.'</td>
                    <td>'.$oUserLog->User->name.'</td>
                    <td><span class="label" style="margin-top: 6px;">' . landa()->ago($oUserLog->created) . '</span></td>
                        </tr>';
            }
        }
        ?>
   
</table>