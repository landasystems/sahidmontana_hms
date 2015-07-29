<table width="100%">
    <tr>
        <td align="center" colspan="5"><h2>JURNAL UMUM</h2>
            <h4><?php echo date('d F Y', strtotime($start)) . " - " . date('d F Y', strtotime($end)); ?></h4>
            <hr></td>
    </tr>   
</table>

<table class="table table-bordered table" border="1">
    <thead>
        <tr> 
            <th colspan="2" width="10%">Tanggal</th>
            <th width="40%">Nama Akun</th>
            <th width="25%">Debet</th>
            <th width="25%">Credit</th>
        </tr>

        <tr class="table-bordered">
            <?php
            $total = "";
            $debet = "";
            $credit = "";
            $monthYear = "";
            $sDay = '';
            $reffID = '';

            foreach ($accCoaDet as $b) {

                $name = (isset($b->AccCoa->name)) ? $b->AccCoa->name : '';
                if ($reffID != $b->reff_id) {
                    $reffID = $b->reff_id;
                    $sDay = date('d', strtotime($b->date_coa));
                } else {
                    $sDay = '';
                }

                $sDate = ($monthYear == date('M Y', strtotime($b->date_coa))) ? "" : date('M Y', strtotime($b->date_coa));
                $monthYear = date('M Y', strtotime($b->date_coa));

                if ($b->debet > 0) {
                    echo'<tr>
                            <td>' . $sDate . '</td>
                            <td>' . $sDay . '</td>
                            <td>' . $name . '</td>
                            <td style="text-align: right">' . $b->debet . '</td>
                            <td style="text-align: right">' . $b->credit . '</td>
                        </tr>';
                    $debet += $b->debet;
                } else {
                    echo'<tr>
                            <td></td>
                            <td></td>
                            <td>&nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp;' . $name . '</td>
                            <td style="text-align: right">' . $b->debet . '</td>'
                    . '<td style="text-align: right">' . $b->credit . ' </td>
                         </tr>';
                    $credit += $b->credit;
                    $sDay = '';
                }
            }
            ?>
        </tr>
    </thead>

    <tfoot>
        <tr>
            <th colspan="3">Total</th>
            <th colspan="1" style="text-align: right"><?php echo $debet; ?></th>
            <th colspan="1" style="text-align: right"><?php echo $credit; ?></th>
        </tr>
    </tfoot>
</table>
