<?php
/* @var $this SiteController */
$this->pageTitle = 'Dashboard - Selamat Datang di Hotel Management Systems';
$siteConfig = SiteConfig::model()->listSiteConfig();

$reservation = Reservation::model()->findAll(array('condition' => 'date_format(created,"%Y-%m-%d")="' . date('Y-m-d') . '"'));
$totReservation = count($reservation);
$registration = Registration::model()->findAll(array('condition' => 'date_format(created,"%Y-%m-%d")="' . date('Y-m-d') . '"'));
$totRegistration = count($registration);
$expArrival = Reservation::model()->findAll(array('condition' => 'date_format(date_from,"%Y-%m-%d")="' . date('Y-m-d') . '"'));
$totExpArrival = count($expArrival);
$expDeparture = Registration::model()->findAll(array('condition' => 'date_format(date_to,"%Y-%m-%d")="' . date('Y-m-d') . '"'));
$totExpDeparture = count($expDeparture);
$bill = Bill::model()->findAll(array('condition' => 'date_format(created,"%Y-%m-%d")="' . date('Y-m-d') . '"'));
$totBill = count($bill);
?>

<div class="row-fluid">
    <div class="span8">
        <div class="row-fluid">
            <div class="centerContent">
                <ul class="bigBtnIcon">
                    <li>
                        <a href="<?php echo url('roomCharting/stay') ?>">
                            <span class="icon silk-icon-office"></span>
                            <span class="txt">Room List</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo url('roomCharting') ?>">
                            <span class="icon icomoon-icon-calendar"></span>
                            <span class="txt">Room Blocking</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo url('reservation/create') ?>">
                            <span class="icon typ-icon-pencil-2"></span>
                            <span class="txt">Reservation</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo url('registration/create') ?>">
                            <span class="icon wpzoom-key"></span>
                            <span class="txt">Registration</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo url('bill/create') ?>">
                            <span class="icon silk-icon-drawer"></span>
                            <span class="txt">Guest Bill</span>
                            <span class="notification"><?php echo $totExpDeparture ?></span>
                        </a>
                    </li>
                </ul>
            </div>

            <div class="box">
                <div class="title">

                    <h4>
                        <span class="icon16 iconic-icon-bars"></span>
                        <span>Today`s Room Status Statistics</span>
                    </h4>
                    <a href="#" class="minimize" style="display: none;">Minimize</a>
                </div>
                <div class="content">                    
                    <?php
                    $room = Room::model()->find(array('select' => 'count(t.id) as count'));
                    $roomStatusHouskeeping = Room::model()->findAll(array('index' => 'status_housekeeping', 'select' => 'status_housekeeping,count(t.id) as count ', 'group' => 'status_housekeeping'));
                    $arrRoomBill = RoomBill::model()->find(array('select' => 'sum(room_price) as totalRoomCharge', 'condition' => 'date_bill="' . $siteConfig->date_system . '"'));
//                    $roomStatus = Room::model()->findAll(array('index' => 'status', 'select' => 'status,count(t.id) as count ', 'group' => 'status'));                    
                    ?>

                    <table class="table table-striped">
                        <tbody>
                            <tr>
                                <td class="span8">Vacant Inspected</td>
                                <td class="span2"><?php echo (isset($roomStatusHouskeeping['vacant inspect'])) ? $roomStatusHouskeeping['vacant inspect']['count'] : '0'; ?></td>
                                <td class="span2"><?php echo (isset($roomStatusHouskeeping['vacant inspect'])) ? round($roomStatusHouskeeping['vacant inspect']['count'] / $room->count * 100, 2) . ' %' : '0 %'; ?></td>                                
                            </tr>
                            <tr>
                                <td>Vacant</td>
                                <td><?php echo (isset($roomStatusHouskeeping['vacant'])) ? $roomStatusHouskeeping['vacant']['count'] : '0'; ?></td>
                                <td><?php echo (isset($roomStatusHouskeeping['vacant'])) ? round($roomStatusHouskeeping['vacant']['count'] / $room->count * 100, 2) . ' %' : '0 %'; ?></td>                                
                            </tr>
                            <tr>
                                <td>Dirty</td>
                                <td><?php echo (isset($roomStatusHouskeeping['dirty'])) ? $roomStatusHouskeeping['dirty']['count'] : '0'; ?></td>
                                <td><?php echo (isset($roomStatusHouskeeping['dirty'])) ? round($roomStatusHouskeeping['dirty']['count'] / $room->count * 100, 2) . ' %' : '0 %'; ?></td>                                
                            </tr>
                            <tr>
                                <td>Out of Order</td>
                                <td><?php echo (isset($roomStatusHouskeeping['out of order'])) ? $roomStatusHouskeeping['out of order']['count'] : '0'; ?></td>
                                <td><?php echo (isset($roomStatusHouskeeping['out of order'])) ? round($roomStatusHouskeeping['out of order']['count'] / $room->count * 100, 2) . ' %' : '0 %'; ?></td>                                
                            </tr>

                            <tr>
                                <td>Occupied</td>
                                <td><?php echo (isset($roomStatusHouskeeping['occupied'])) ? $roomStatusHouskeeping['occupied']['count'] : '0'; ?></td>
                                <td><?php echo (isset($roomStatusHouskeeping['occupied'])) ? round($roomStatusHouskeeping['occupied']['count'] / $room->count * 100, 2) . ' %' : '0 %'; ?></td>                                
                            </tr>
                            <tr>
                                <td>Occupied No Luggage</td>
                                <td><?php echo (isset($roomStatusHouskeeping['occupied no luggage'])) ? $roomStatusHouskeeping['occupied no luggage']['count'] : '0'; ?></td>
                                <td><?php echo (isset($roomStatusHouskeeping['occupied no luggage'])) ? round($roomStatusHouskeeping['occupied no luggage']['count'] / $room->count * 100, 2) . ' %' : '0 %'; ?></td>
                            </tr>
                            <tr>
                                <td>Do Not Disturb</td>
                                <td><?php echo (isset($roomStatusHouskeeping['do not disturb'])) ? $roomStatusHouskeeping['do not disturb']['count'] : '0'; ?></td>
                                <td><?php echo (isset($roomStatusHouskeeping['do not disturb'])) ? round($roomStatusHouskeeping['do not disturb']['count'] / $room->count * 100, 2) . ' %' : '0 %'; ?></td>                                
                            </tr>
                            <tr>
                                <td>Sleep Out</td>
                                <td><?php echo (isset($roomStatusHouskeeping['sleep out'])) ? $roomStatusHouskeeping['sleep out']['count'] : '0'; ?></td>
                                <td><?php echo (isset($roomStatusHouskeeping['sleep out'])) ? round($roomStatusHouskeeping['sleep out']['count'] / $room->count * 100, 2) . ' %' : '0 %'; ?></td>                                
                            </tr>
                            <tr>
                                <td>House Use</td>
                                <td><?php echo (isset($roomStatusHouskeeping['house use'])) ? $roomStatusHouskeeping['house use']['count'] : '0'; ?></td>
                                <td><?php echo (isset($roomStatusHouskeeping['house use'])) ? round($roomStatusHouskeeping['house use']['count'] / $room->count * 100, 2) . ' %' : '0 %'; ?></td>                                
                            </tr>
                            <tr>
                                <td>Compliment</td>
                                <td><?php echo (isset($roomStatusHouskeeping['compliment'])) ? $roomStatusHouskeeping['compliment']['count'] : '0'; ?></td>
                                <td><?php echo (isset($roomStatusHouskeeping['compliment'])) ? round($roomStatusHouskeeping['compliment']['count'] / $room->count * 100, 2) . ' %' : '0 %'; ?></td>                                
                            </tr>
                            <tr>
                                <th>Total Rooms</th>
                                <th><?php echo $room->count; ?></th>                            
                                <th>100 %</th>                            
                            </tr>
                            <tr>
                                <th>Average Room Rate</th>
                                <?php
                                $arr = (isset($roomStatusHouskeeping['occupied'])) ? ($arrRoomBill->totalRoomCharge / 1.21) / $roomStatusHouskeeping['occupied']['count'] : 0;
                                ?>
                                <th></th>                            
                                <th><?php echo landa()->rp($arr, true, 2); ?></th>                            
                            </tr>
                        </tbody>
                    </table>

                </div>
            </div><!-- End .box -->  
        </div>
    </div>
    <div class="span4">
        <div class="row-fluid">
            <div class="box">
                <div class="title">
                    <h4>
                        <span class="icon16 silk-icon-office"></span>
                        <span><?php echo Yii::app()->session['site']['client_name'] ?></span>
                    </h4>
                </div>
                <div class="content">
                    <?php
                    $img = Yii::app()->landa->urlImg('site/', $siteConfig->client_logo, param('id'));
                    echo '<img style="width:97%" src="' . $img['big'] . '" class="img-polaroid"/>';
                    ?>
                    <div class="clearfix"></div>
                    <dl>
                        <dt>Address</dt>
                        <dd><?php echo ucwords($siteConfig->fullAddress) ?></dd>
                        <dt>Telephone</dt>
                        <dd><?php echo $siteConfig->phone ?></dd>
                        <dt>Email</dt>
                        <dd><?php echo $siteConfig->email ?></dd>
                    </dl>
                </div>

            </div>
            <div class="box">
                <div class="title">
                    <h4>
                        <span class="icon16 brocco-icon-grid"></span>
                        <span>Manual Book</span>
                    </h4>
                </div>
                <div class="content">
                    <a href="http://www.landa.co.id/manualbook-hms-fo.pdf" target="_blank"><span class="icon32 cut-icon-download blue" style="float: left;padding: 12px 0px"></span></a> Click to download tutorial to operate Front Office Hotel Management Systems 
                </div>
            </div>

            <div class="reminder">
                <h4>Today's Activity Count
                    <a href="#" class="icon tip" oldtitle="Configure" title=""><span class="icon16 iconic-icon-cog"></span></a>

                </h4>
                <ul>
                    <li class="clearfix">
                        <div class="icon">
                            <span class="icon32 eco-bookmark green"></span>
                        </div>
                        <span class="txt">Reservation</span>
                        <span class="number"><?php echo $totReservation; ?></span> 
                    </li>
                    <li class="clearfix">
                        <div class="icon">
                            <span class="icon32 icomoon-icon-briefcase-2 blue"></span>
                        </div>
                        <span class="txt">Check In</span>
                        <span class="number"><?php echo $totRegistration; ?></span> 
                    </li>
                    <li class="clearfix">
                        <div class="icon">
                            <span class="icon32 silk-icon-exit red"></span>
                        </div>
                        <span class="txt">Check Out</span>
                        <span class="number"><?php echo $totBill; ?></span> 
                    </li>
                </ul>
            </div><!-- End .reminder -->

            <div class="todo">
                <h4>Latest Logged-in Users
                    <a href="#" class="icon tip" oldtitle="Configure" title=""><span class="icon16 iconic-icon-cog"></span></a>

                </h4>
                <ul>

                    <?php
                    $oUserLogs = UserLog::model()->findAll(array('with'=>array('User','User.Roles'),'order' => 'created DESC', 'limit' => '5'));
                    foreach ($oUserLogs as $oUserLog) {
                        if (isset($oUserLog->User->Roles->name)) {
                            echo '<li class="clearfix">' .
                            $oUserLog->User->name . ' | ' . $oUserLog->User->Roles->name . '
                        <span class="label pull-right" style="margin-top: 6px;">' . landa()->ago($oUserLog->created) . '</span>
                        </li> ';
                        };
                    }
                    ?>
                </ul>
            </div>
        </div>
    </div>
</div>
