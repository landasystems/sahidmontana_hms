<?php
if ($type = 'export') {
    
} else {
    
}
?>
<div id='printableArea'>
    <div class="img-polaroid" style="border:none;">
        <table class="table table-bordered" id="tabless" border="1">
            <thead>
                <tr>
                    <td style="text-align: center;border:none" align="center" colspan="5"><h2>JURNAL UMUM</h2>
                        <h4><?php echo date('d F Y', strtotime($start)) . " - " . date('d F Y', strtotime($end)); ?></h4>
                        <hr></td>
                </tr>
                <tr> 
                    <th colspan="2" width="10%">Tanggal</th>
                    <th width="50%">Nama Akun</th>
                    <th width="20%">Debet</th>
                    <th width="20%">Credit</th>
                </tr>
            </thead>
            <tbody>
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
                            <td style="text-align: right">' . landa()->rp($b->debet, false) . '</td>
                            <td style="text-align: right">' . landa()->rp($b->credit, false) . '</td>
                        </tr>';
                            $debet += $b->debet;
                        } else {
                            echo'<tr>
                            <td></td>
                            <td></td>
                            <td>&nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp' . $name . '</td>
                            <td style="text-align: right">' . landa()->rp($b->debet, false) . '</td>'
                            . '<td style="text-align: right">' . landa()->rp($b->credit, false) . ' </td>
                         </tr>';
                            $credit += $b->credit;
                            $sDay = '';
                        }
                    }
                    ?>
                </tr>

                <tr>
                    <th colspan="3">Total</th>
                    <th colspan="1" style="text-align: right"><?php echo landa()->rp($debet, false); ?></th>
                    <th colspan="1" style="text-align: right"><?php echo landa()->rp($credit, false); ?></th>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<style type="text/css" media="print">
    body {visibility:hidden;}
    .printableArea{visibility:visible;position: absolute;top:0;left:0px;width: 100%;font-size:19px}
    table{width: 100%}
</style>
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
