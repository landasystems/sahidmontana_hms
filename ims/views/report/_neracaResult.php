<div class='printableArea'>
    <table class="table table-bordered table" border="1">
        <thead>
            <tr>
                <td style="text-align: center;border:none;" colspan="2"><h2>NERACA REPORT</h2>
                    <h3><?php echo date('d-M-Y', strtotime($a)) ?></h3>
                    <hr>
                </td>
            </tr>  
            <tr> 
                <th>AKTIVA</th>
                <th>PASSIVA</th>
            </tr>
            <tr>
                <td width="50%">
                    <table class="table table-bordered table-striped" border="1">
                        <?php
                        $jmlakt = "";
                        $jmlpas = "";
                        //menampung dari acccoa
                        $aktiva = array();
                        $bal = array();
                        $pasiva = array();
//                    $general = array();
                        foreach ($accCoa as $b) {
                            $balance = AccCoaDet::model()->beginingBalance(date('Y-m-d', strtotime($a)), $b->id);
                            if ($b->group == 'aktiva') {
                                $aktiva[$b->id] = $b;
                                $jmlakt += $balance;
                            } else {
                                $pasiva[$b->id] = $b;
                                $jmlpas += $balance;
                            }
                            $bal[$b->id] = $balance;
                        }

                        //mencari sum dari general, perulangannya di balik, untuk mencari dari level terendah dulu
                        foreach (array_reverse($accCoa) as $b) {
                            if (isset($bal[$b->parent_id]))
                                $bal[$b->parent_id] += $bal[$b->id];
                        }

                        foreach ($aktiva as $b) {
                            if ($b->level <= $viewType) {
                                $balance = $bal[$b->id];
                                if (($balance != 0 && !empty($balance)) || ($viewType == $b->level && $balance != 0)) {
                                    $balance = ($viewType == $b->level || $b->type == 'detail') ? landa()->rp($balance, false) : '';
                                    echo'<tr><td>' . $b->spaceName . '</td><td>' . $balance . '</td></tr>';
                                }
                            }
                            $jmlakt += $balance;
                        }
                        ?>
                        <th>Total Aktiva</th>
                        <th><?php echo landa()->rp($jmlakt) ?></th>
                    </table>
                </td>
                <td width="50%">
                    <table class="table table-bordered table-striped" border="1">
                        <?php
                        $rlberjalan = "";
                        foreach ($pasiva as $b) {
                            if ($b->level <= $viewType) {
                                $balance = $bal[$b->id];
                                if (($balance != 0 && !empty($balance)) || ($viewType == $b->level && $balance != 0)) {
                                    $balance = ($viewType == $b->level || $b->type == 'detail') ? landa()->rp($balance, false) : '';
                                    echo'<tr><td>' . $b->spaceName . '</td><td>' . $balance . '</td></tr>';
                                }
                            }
                            $jmlpas += $balance;
                        }
                        $rlberjalan = $jmlakt + $jmlpas;

                        $labelRL = ($rlberjalan >= 0) ? 'Laba periode berjalan' : 'Rugi periode berjalan';
                        echo '<tr><th>' . $labelRL . '</th><th>'
                        . landa()->rp($rlberjalan, false) . '</th></tr>';
                        ?>
                        <tr>
                            <th>Total Passiva</th>
                            <th><?php echo landa()->rp($jmlpas - $rlberjalan, false) ?></th>
                        </tr>
                    </table>
                </td>
            </tr>
        </thead>
    </table>

</div>
<style type="text/css" media="print">
    body {visibility:hidden;}
    .printableArea{visibility:visible;position: absolute;top:0;left:0px;width: 100%;font-size:17px}
    table{width: 100%}
</style>
<script type="text/javascript">
    function printDiv()
    {

        window.print();

    }
</script>
