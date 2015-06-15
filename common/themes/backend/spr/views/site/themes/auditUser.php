 <ul>

                    <?php
                    $listUser = User::model()->listUser();
                    $oUserLogs = UserLog::model()->findAll(array('order' => 'created DESC', 'limit' => '5'));
                    foreach ($oUserLogs as $oUserLog) {
                        if (isset($listUser[$oUserLog->user_id])) {
                            echo '<li class="clearfix">' .
                            $listUser[$oUserLog->user_id]['name'] . ' | Admin
                        <span class="label pull-right" style="margin-top: 6px;">' . landa()->ago($oUserLog->created) . '</span>
                        </li> ';
                        }
                    }
                    ?>

                </ul>