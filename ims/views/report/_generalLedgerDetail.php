<div id='printableArea'>
    <div class="img-polaroid" style="border: none">
        <style type="text/css" media="print">
            .table td {
                padding: 0px;
                margin: 1px;
            }
            body {font-size:7pt;}

        </style>
        <?php
        $kepada = AccCoa::model()->findByPk($pada);
        if ($acc->type == 'general') {
            ?>
            <table width="100%">
                <tr>
                    <td  style="text-align: center" colspan="2"><h3>Ledger</h3>
                        <h4><?php echo date('d-M-Y', strtotime($start)) . " - " . date('d-M-Y', strtotime($end)); ?></h4>
                        <hr></td>
                </tr>   
            </table>

            <hr/>
            <b><?php echo '<td><h3>' . $acc->name . '</h3></td>'; ?> </b>

        <?php } else { ?>

            <table width="100%">

            </table>
            <table class="table table-bordered" border="1">
                <thead>
                    <tr>
                        <td  style="text-align: center;border:none;border-right: none;" colspan="7"><h3>Ledger</h3>
                            <h4><?php echo date('d-M-Y', strtotime($start)) . " - " . date('d-M-Y', strtotime($end)); ?></h4>
                            <hr></td>
                    </tr>
                    <tr>
                        <td style="font-weight: bold;border:none;" width="10%">Nama Rekening</td>
                        <td style="font-weight: bold;border:none;" width="2%">:</td>
                        <td colspan="2" style="text-align: left; font-weight: bold;border:none;"><?php echo $acc->name; ?>  <?php echo (!empty($kepada)) ? '( Terhadap :'.$kepada->name.')' : '' ?> </td>
                        <td style="border:none;"></td>
                        <td style="border:none;">Kode Rekening : </td>
                        <td style="text-align: center; font-weight: bold;border:none;"><?php echo $acc->code; ?></td>
                    </tr> 
                    <tr>
                        <th colspan="2" width="10%" style="text-align: center">TANGGAL</th>
                        <th width="8%" style="text-align: center">REFF</th>
                        <th width="50%" style="text-align: center">URAIAN</th>
                        <th width="10%" style="text-align: center">DEBIT</th>
                        <th width="10%" style="text-align: center">CREDIT</th>
                        <th width="12%" style="text-align: center">SALDO</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $isGabung = $checked;
                    $SaldoAkhir = 0;
                    $total = $beginingBalance;
                    $monthYear = "";
                    $totald = 0;
                    $totalk = 0;

                    //---------------menampung acc coa id
                    $accIn = array();
                    $accOut = array();
                    foreach ($accCoaDet as $a) {
                        if ($a->reff_type == "cash_in")
                            $accIn[] = $a->reff_id;
                        if ($a->reff_type == "cash_out")
                            $accOut[] = $a->reff_id;
                    }

                    if (empty($accIn)) {
                        $mCashIn = array();
                    } else {
                        $mCashIn = AccCashIn::model()->findAll(array(
                            'index' => 'id',
                            'condition' => 'id IN (' . implode(',', $accIn) . ')'
                            ));
                    }
                    if (empty($accOut)) {
                        $mCashOut = array();
                    } else {
                        $mCashOut = AccCashOut::model()->findAll(array(
                            'index' => 'id',
                            'condition' => 'id IN (' . implode(',', $accOut) . ')'
                            ));
                    }

                    if ($isGabung) {
                        //tampung
                        $groupLedger = array();
                        foreach ($accCoaDet as $key => $a) {

                            if ($key == 0 && $a->reff_type != "balance") {
                                echo'<tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>Saldo Awal</td>
                                <td></td>
                                <td></td>
                                <td style="text-align: right">' . landa()->rp($beginingBalance, false, 2) . '</td>
                            </tr>';
                            }

                            if (isset($groupLedger[$a->code]))
                                $groupLedger[$a->code] = array('reff_id' => $a->reff_id, 'reff_type' => $a->reff_type, 'description' => $groupLedger[$a->code]['description'] . '<br/>' . $a->description, 'data' => $a->date_coa, 'debet' => $groupLedger[$a->code]['debet'] + $a->debet, 'credit' => $groupLedger[$a->code]['credit'] + $a->credit);
                            else
                                $groupLedger[$a->code] = array('reff_id' => $a->reff_id, 'reff_type' => $a->reff_type, 'description' => $a->description, 'data' => $a->date_coa, 'debet' => $a->debet, 'credit' => $a->credit);
                        }
                        $total = 0;
                        $no = 1;
                        $total = $beginingBalance;
                        //echo tampung
                        foreach ($groupLedger as $key => $b) {

                            if ($a->reff_type == "cash_in") {
                                $giroAn = (!empty($mCashIn[$b['reff_id']]->description_to)) ? ' - ' . $mCashIn[$b['reff_id']]->description_to : '';
                                $dibayar = (!empty($mCashIn[$b['reff_id']]->AccCoa->name)) ? '<br/>(' . $mCashIn[$b['reff_id']]->AccCoa->name . $giroAn . ')' : '';
                            } elseif ($a->reff_type == "cash_out") {
                                $giroAn = (!empty($mCashOut[$b['reff_id']]->description_to)) ? ' - ' . $mCashOut[$b['reff_id']]->description_to : '';
                                $dibayar = (!empty($mCashOut[$b['reff_id']]->AccCoa->name)) ? '<br/>(' . $mCashOut[$b['reff_id']]->AccCoa->name . $giroAn . ')' : '';
                            } else {
                                $giroAn = '';
                                $dibayar = '';
                            }
                            $description = ($b['description']);
                            $sDesc = ($b['reff_type'] == 'balance') ? 'Saldo Awal' : $b['description'] . $dibayar;
                            $acDate = ($monthYear == date('M Y', strtotime($b['data']))and $no > 1 ) ? "" : date('M Y', strtotime($b['data']));
                            $monthYear = date('M Y', strtotime($b['data']));
                            $no++;
                            $dCoa = date('d', strtotime($b['data']));

                            $total += $b['debet'] - $b['credit'];
                            echo '<tr>';
                            echo '<td style="text-align: center">' . $acDate . '</td>
                            <td style="text-align: center">' . $dCoa . '</td>'; //data tanggal
                            echo '<td>' . $key . '</td>'; //code 
                            echo '<td>' . $sDesc . '</td>'; //
                            echo '<td style="text-align: right">' . landa()->rp($b['debet'],false) . '</td>'; //debet
                            echo '<td style="text-align: right">' . landa()->rp($b['credit'],false) . '</td>'; //credit
                            echo '<td style="text-align: right">' . landa()->rp($total, false, 2) . '</td>'; //total
                            echo '</tr>';
                            $totald += $b['debet'];
                            $totalk += $b['credit'];
                        }
                    } else {
                        $no = 1;
                        //------------perulangan isinya
                        foreach ($accCoaDet as $key => $a) {

                            $sDate = ($monthYear == date('M Y', strtotime($a->date_coa)) and $no > 1) ? "" : date('M Y', strtotime($a->date_coa));
                            $monthYear = date('M Y', strtotime($a->date_coa));
                            $no++;
                            if ($a->reff_type == "cash_in") {
                                $giroAn = (!empty($mCashIn[$a->reff_id]->description_to)) ? ' - ' . $mCashIn[$a->reff_id]->description_to : '';
                                $dibayar = (!empty($mCashIn[$a->reff_id]->AccCoa->name)) ? '<br/>(' . $mCashIn[$a->reff_id]->AccCoa->name . $giroAn . ')' : '';
                            } elseif ($a->reff_type == "cash_out") {
                                $giroAn = (!empty($mCashOut[$a->reff_id]->description_to)) ? ' - ' . $mCashOut[$a->reff_id]->description_to : '';
                                $dibayar = (!empty($mCashOut[$a->reff_id]->AccCoa->name)) ? '<br/>(' . $mCashOut[$a->reff_id]->AccCoa->name . $giroAn . ')' : '';
                            } else {
                                $giroAn = '';
                                $dibayar = '';
                            }

                            $description = ($a->description);
                            $sDesc = ($a->reff_type == 'balance') ? 'Saldo Awal' : $a->description . $dibayar;
                            $total = ($a->reff_type == 'balance') ? $a->debet - $a->credit : $total + $a->debet - $a->credit;
                            //jika record yang pertama, jika bukan balance maka muncul, karena saldo awal yang di atas sudah muncul
                            if ($key == 0 && $a->reff_type != "balance") {
                                echo'<tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>Saldo Awal</td>
                                <td></td>
                                <td></td>
                                <td style="text-align: right">' . landa()->rp($beginingBalance, false, 2) . '</td>
                            </tr>';
                            }
                            //-------------------------------------------------------


                            if ($a->reff_type == 'balance') {
                                echo '<tr>
                            <td style="text-align: center">' . $sDate . '</td>
                            <td style="text-align: center">' . date('d', strtotime($a->date_coa)) . '</td>
                            <td style="text-align: center">' . $a->code . '</td>
                            <td>' . $sDesc . '</td>
                            <td name="deb" style="text-align: right"></td>
                            <td name="cred" style="text-align: right"></td>
                            <td name="tdeb" style="text-align: right">' . landa()->rp($total, false, 2) . '</td>
                            </tr>';
                            } else {
                                echo'<tr>
                            <td style="text-align: center">' . $sDate . '</td>
                            <td style="text-align: center">' . date('d', strtotime($a->date_coa)) . '</td>
                            <td style="text-align: center">' . $a->code . '</td>
                            <td>' . $sDesc . '</td>
                            <td name="deb" style="text-align: right">' . landa()->rp($a->debet, false, 2) . '</td>
                            <td name="cred" style="text-align: right">' . landa()->rp($a->credit, false, 2) . '</td>
                            <td name="tdeb" style="text-align: right">' . landa()->rp($total, false, 2) . '</td>
                            </tr>';
                            }
                            $totald += $a->debet;
                            $totalk += $a->credit;
                        }
                    }
                    ?>

                </tbody>
                <tr>
                    <th colspan="4">Saldo Akhir</th>
                    <th style="text-align: right"><?php echo landa()->rp($totald, false, 2) ?></th>
                    <th style="text-align: right"><?php echo landa()->rp($totalk, false, 2) ?></th>
                    <th style="text-align: right"><?php echo landa()->rp($total, false, 2) ?></th>
                </tr>
            </table>

        <?php } ?>
    </div>
</div>

<script type="text/javascript">
    function printDiv(divName)
    {
        var printContents = document.getElementById(divName).innerHTML;
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
    }
</script>
