<?php
/* @var $this SiteController */
$this->pageTitle = 'Dashboard - Selamat Datang di Area Administrator';
$siteConfig = SiteConfig::model()->listSiteConfig();
?>
<div class="row-fluid">
    <div class="span8">
        <div class="row-fluid">
            <!--<div class="centerContent">-->

            <!--</div>-->
            <?php if (in_array('inventory', param('menu'))) { ?>
                <div class="box gradient">
                    <div class="content" style="display: block;">
                        <?php
                        $result = Sell::model()->listSumSell();
                        $arrInbox = array();
                        for ($index = 5; $index > -1; $index--) {
                            $sDate = date("F Y", strtotime("-" . $index . " months"));
                            $categories[] = $sDate;
                            $arrInbox[] = (isset($result[$sDate]) ? (int) $result[$sDate] : 0);
//                            $arrOutbox[] = (isset($resultOutbox[$sDate]) ? (int) $resultOutbox[$sDate] : 0);
                        }

                        $this->Widget('common.extensions.highcharts.HighchartsWidget', array(
                            'options' => array(
                                'title' => array('text' => ''),
                                'xAxis' => array(
                                    'categories' => $categories
                                ),
                                'yAxis' => array(
                                    'title' => array('text' => 'Amount (Rp.)')
                                ),
                                'series' => array(
                                    array('name' => 'Total Penjualan', 'data' => $arrInbox),
//                                    array('name' => 'Pesan Terkirim', 'data' => $arrOutbox),
                                ),
                                'legend' => array(
                                    'enabled' => true
                                ),
                                'credits' => array(
                                    'enabled' => false
                                ),
                            )
                        ));
                        ?>
                    </div>
                </div><!-- End .box -->

            <?php } ?>                
            <?php if (in_array('accounting', param('menu'))) { ?>
                <div class="box gradient">
                    <div class="content" style="display: block;">
                        <?php
                        $coadet = cmd('
                            SELECT DATE_FORMAT(acca_acc_coa_det.date_coa,"%d %M %Y") as dateMonth,
                            SUM(acca_acc_coa_det.debet) as debet,
                            SUM(acca_acc_coa_det.credit) as ss
                            FROM acca_acc_coa_det,acca_acc_coa
                            WHERE acca_acc_coa_det.acc_coa_id = acca_acc_coa.id
                            AND acca_acc_coa_det.date_coa <= now() 
                            AND acca_acc_coa_det.date_coa >= now() - INTERVAL 7 DAY
                            AND ((acca_acc_coa.type_sub_ledger = "ks")
                            OR (acca_acc_coa.type_sub_ledger = "bk"))
                            GROUP BY YEAR(acca_acc_coa_det.date_coa),
                            MONTH(acca_acc_coa_det.date_coa),
                            DAY(acca_acc_coa_det.date_coa) ORDER BY acca_acc_coa_det.date_coa ASC')->queryAll();

                        $result = array();
                        $credit = array();
                        foreach ($coadet as $value) {
                            $result[$value['dateMonth']] = $value['debet'];
                        }
                        foreach ($coadet as $value) {
                            $credit[$value['dateMonth']] = $value['ss'];
                        }


                        $arrdebet = array();
                        $arrcredit = array();
                        for ($index = 6; $index > -1; $index--) {
                            $sDate = date("d F Y", strtotime("-" . $index . " days"));
                            $categories[] = $sDate;
                            $arrdebet[] = (isset($result[$sDate]) ? (int) $result[$sDate] : 0);
                            $arrcredit[] = (isset($credit[$sDate]) ? (int) $credit[$sDate] : 0);
                        }

                        trace($credit);
                        $this->widget(
                                'bootstrap.widgets.TbHighCharts', array(
                            'options' => array(
                                'title' => array(
                                    'text' => 'Grafik Arus Kas/Bank',
                                    'x' => -20 //center
                                ),
                                'subtitle' => array(
                                    'text' => 'Arus Keluar/Masuk akun Kas/Bank',
                                    'x' - 20
                                ),
                                'xAxis' => array(
                                    'categories' => $categories,
                                ),
                                'yAxis' => array(
                                    'title' => array(
                                        'text' => 'Amount (Rp.)',
                                    ),
                                ),
                                'tooltip' => array(
                                    'valueSuffix' => ' Rupiah'
                                ),
                                'legend' => array(
                                    'layout' => 'vertical',
                                    'align' => 'right',
                                    'verticalAlign' => 'bottom',
                                    'borderWidth' => 0
                                ),
                                'series' => array(
                                    [
                                        'name' => 'Debet',
                                        'data' => $arrdebet
                                    ],
                                    [
                                        'name' => 'Credit',
                                        'data' => $arrcredit
                                    ],
                                )
                            ),
                            'htmlOptions' => array(
                                'style' => 'min-width: 310px; height: 400px; margin: 0 auto'
                            )
                                )
                        );
                        ?>
                    </div>
                </div><!-- End .box -->

            <?php } ?>                



            <?php if (in_array('manufacture', param('menu'))) { ?>
                <div class="box gradient">
                    <div class="content" style="display: block;">
                        <?php
                        $salary = cmd('
                        SELECT DATE_FORMAT(created,"%M %Y") as dateMonth,sum(total) as amount
                        FROM acca_salary
                        WHERE `created` <= now() 
                        AND `created` >= now() - INTERVAL 6 MONTH                            
                        GROUP BY YEAR(created), MONTH(created)')->queryAll();

                        $result = array();
                        foreach ($salary as $value) {
                            $result[$value['dateMonth']] = $value['amount'];
                        }

                        $arrSalary = array();
                        for ($index = 5; $index > -1; $index--) {
                            $sDate = date("F Y", strtotime("-" . $index . " months"));
                            $categories[] = $sDate;
                            $arrSalary[] = (isset($result[$sDate]) ? (int) $result[$sDate] : 0);
                            //                            $arrOutbox[] = (isset($resultOutbox[$sDate]) ? (int) $resultOutbox[$sDate] : 0);
                        }

                        $this->Widget('common.extensions.highcharts.HighchartsWidget', array(
                            'options' => array(
                                'title' => array('text' => 'Total Penggajian by Period'),
                                'xAxis' => array(
                                    'categories' => $categories
                                ),
                                'yAxis' => array(
                                    'title' => array('text' => 'Amount (Rp.)')
                                ),
                                'series' => array(
                                    array('name' => 'Total Penggajian', 'data' => $arrSalary),
                                ),
                                'legend' => array(
                                    'enabled' => false
                                ),
                                'credits' => array(
                                    'enabled' => false
                                ),
                            )
                        ));
                        ?>
                    </div>
                </div><!-- End .box -->

                <div class="box">
                    <div class="title">

                        <h4>
                            <span class="icon16 iconic-icon-bars"></span>
                            <span>Karyawan Terbaik   </span>
                        </h4>
                        <a href="#" class="minimize" style="display: none;"></a>
                    </div>
                    <div class="content">

                        <table class="table table-striped">
                            <tbody>
                                <?php
                                $salary = Salary::model()->findAll(array('limit' => 6, 'order' => 'total DESC'));
                                if (isset($salary)) {
                                    foreach ($salary as $o) {
                                        $employment = (isset($o->Employment->name)) ? $o->Employment->name : '-';
                                        echo'<tr>
                                                                    <td>' . $employment . '</td>
                                                                    <td>' . landa()->rp($o->total) . '</td>
                                                                </tr>';
                                    }
                                } else {
                                    echo'<tr>
                                                                    <td colspan="2">Not Result</td>
                                                                </tr>';
                                }
                                ?>

                            </tbody>
                        </table>

                    </div>
                </div>  
            <?php } ?>

            <?php
            if (false) {
//            if (in_array('accounting', param('menu'))) { 
                ?>
                <div class="box gradient">
                    <div class="content" style="display: block;">
                        <table class="table table-striped">
                            <tbody>
                                <tr>
                                    <th>Account Id</th>
                                    <th>Type</th>
                                    <th>Tanggal</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                                <?php
                                $accApproval = AccApproval::model()->findAll(array('order' => 'created',
                                    'limit' => '5',));
                                $nama = "";
                                $url = "";

                                foreach ($accApproval as $status) {
                                    if ($status->status != "confirm" && $status->status != "reject") {
                                        if ($status->acc_cash_in_id != null) {
                                            $nama = "Cash In";
                                            $url = 'accCashIn';
                                        } else if ($status->acc_cash_out_id != null) {
                                            $nama = "Cash Out";
                                            $url = 'accCashOut';
                                        } else {
                                            $nama = "Journal";
                                            $url = 'accJurnal';
                                        }

                                        if ($status->status == "open") {
                                            $stt = '<span class="label">Open</span>';
                                        } elseif ($status->status == "pending") {
                                            $stt = '<span class="label label-info">Pending</span>';
                                        } elseif ($status->status == "reject") {
                                            $stt = '<span class="label label-important">Reject</span>';
                                        } elseif ($status->status == "confirm") {
                                            $stt = '<span class="label label-success">Confirm</span>';
                                        }

                                        echo '<tr>
                                                          <td>' . $status->id . '</td>
                                                          <td>' . $nama . '</td>
                                                          <td>' . date('d F Y', strtotime($status->created)) . '</td>
                                                          <td>' . $stt . '</td>
                                                          <td><a data-original-title="Aksi" class="btn btn-small " title="" rel="tooltip" href="' . Yii::app()->createUrl($url . '/approve', array("id" => $status->id)) . '"><i class="icon-ok"></i></a></td>
                                                          </tr>';
//                                        $this->widget(
//                                                'bootstrap.widgets.TbJsonGridView', array(
//                                            'dataProvider' => AccApproval::model()->findAll(array('order' => 'created','limit' => '5',)),
//                                            'filter' => $model,
//                                            'type' => 'striped bordered condensed',
//                                            'summaryText' => false,
//                                            'cacheTTL' => 10, // cache will be stored 10 seconds (see cacheTTLType)
//                                            'cacheTTLType' => 's', // type can be of seconds, minutes or hours
//                                            'columns' => array(
//                                                'id',
//                                                'name',
//                                                array(
//                                                    'name' => null,
//                                                    'type' => null
//                                                ),
//                                                array(
//                                                    'header' => Yii::t('ses', 'Edit'),
//                                                    'class' => 'bootstrap.widgets.TbJsonButtonColumn',
//                                                    'template' => '{view} {delete}',
//                                                    'viewButtonUrl' => Yii::app()->createUrl("accCashIn/approve", array("id" => $status->id)),
////                                                    'buttons' => array(
////                                                        'delete' => array(
////                                                            'click' => 'function(){return false;}'
//                                                        )
//                                                    ),
//                                                )
//                                            
//                                        );
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div><!-- End .box -->


            <?php } ?>




            <!--            <div class="box">
                            <div class="title">
            
                                <h4>
                                    <span class="icon16 iconic-icon-bars"></span>
                                    <span>Items with Minimum Stock</span>
                                </h4>
                                <a href="#" class="minimize" style="display: none;">Minimize</a>
                            </div>
                            <div class="content">
            
                                <table class="table table-striped">
                                    <tbody>
                                        <tr>
                                            <td>Gula</td>
                                            <td>40 Kg</td>
                                        </tr>
                                        <tr>
                                            <td>Karung Goni</td>
                                            <td>100 Biji</td>
                                        </tr>
                                        <tr>
                                            <td>Plastik</td>
                                            <td>20 Kodi</td>
                                        </tr>
                                        <tr>
                                            <td>Kedelai</td>
                                            <td>100 Kg</td>
                                        </tr>
                                        <tr>
                                            <td>Kecamba</td>
                                            <td>33 Kg</td>
                                        </tr>
                                        <tr>
                                            <td>Lilin</td>
                                            <td>56 Biji</td>                                                  
                                        </tr>
                                    </tbody>
                                </table>
            
                            </div>
                        </div> End .box   -->




        </div>
    </div>
    <div class="span4">
        <div class="row-fluid">
            <div class="box">
                <div class="title">

                    <h4>
                        <span class="icon16 silk-icon-office"></span>
                        <span><?php echo $siteConfig->client_name ?></span>
                    </h4>
                </div>
                <div class="content">
                    <?php
                    $img = Yii::app()->landa->urlImg('site/', $siteConfig->client_logo, param('id'));
                    echo '<img src="' . $img['big'] . '" class="img-polaroid"/>';
                    ?>
                    <div class="clearfix"></div>
                    <dl>

                        <!--                        <dt>Telephone</dt>
                                                <dd></dd>-->
                    </dl>
                </div>

            </div>


            <!--            <div class="reminder">
                            <h4>Today's Activity Count
                                <a href="#" class="icon tip" oldtitle="Configure" title=""><span class="icon16 iconic-icon-cog"></span></a>
            
                            </h4>
                            <ul>
                                <li class="clearfix">
                                    <div class="icon">
                                        <span class="icon16 entypo-icon-forward"></span>
                                    </div>
                                    <span class="txt">Sales</span>
                                    <span class="number">Rp. 7.750.000</span> 
                                </li>
                                <li class="clearfix">
                                    <div class="icon">
                                        <span class="icon16 entypo-icon-forward"></span>
                                    </div>
                                    <span class="txt">Transfer</span>
                                    <span class="number">Rp. 1.254.000</span> 
                                </li>
                                <li class="clearfix">
                                    <div class="icon">
                                        <span class="icon16 entypo-icon-forward"></span>
                                    </div>
                                    <span class="txt">Waste</span>
                                    <span class="number">Rp. 233.500</span> 
                                </li>
                                <li class="clearfix">
                                    <div class="icon">
                                        <span class="icon16 entypo-icon-reply"></span>
                                    </div>
                                    <span class="txt">Buy</span> 
                                    <span class="number">Rp. 3.350.000</span> 
                                </li>        
                            </ul>
                        </div> End .reminder -->
        </div>
    </div>
</div>
