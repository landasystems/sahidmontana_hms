<table width="100%">
    <tr>
        <td align="center" colspan="6"><h2>GENERAL LEDGER</h2>
            <?php echo date('d M Y', strtotime($start)) . "-" . date('d M Y', strtotime($end)); ?>
            <hr></td>
    </tr>   
</table>
<?php
if ($acc->type == 'general') {
    ?>
    <hr/>
    <b><?php echo '<td><h3>' . $acc->name . '</h3></td>'; ?> </b>
    <hr/>

<?php } else { ?>

    <table width="100%">
        <tr>
            <td style="font-weight: bold" width="10%">Nama Rekening</td>
            <td  style="font-weight: bold" width="2%">:</td>
            <td style="text-align: left; font-weight: bold"><?php echo $acc->name; ?> </td>
            <td style="text-align: right; font-weight: bold"><?php echo 'Kode Akun = ' . $acc->code; ?></td>    
        </tr> 
    </table>



    <table class="table table-bordered" style="border-collapse: separate">
        <thead>
            <tr>
                <th colspan="2" width="10%" style="text-align: center">Date</p></th>
    <th width="10%" style="text-align: center">Reff</p></th>
    <th width="32%" style="text-align: center">Nama Perkiraan</p></th>
    <th width="16%" style="text-align: center">Debet</p></th>
    <th width="16%" style="text-align: center">Credit</p></th>
    <th width="16%" style="text-align: center">Saldo</p></th>
    </tr>
   
    <?php
    $SaldoAkhir = 0;
    $total = 0;
    $monthYear = "";
    $totald = 0;
    $totalk = 0;
    foreach ($accCoaDet as $key => $a) {
        //jika record yang pertama, jika bukan balance maka muncul
        if ($key == 0) {
            if ($a->reff_type != "balance") {
                if (isset($beginingBalance)) {
                    echo'<tr>
                                <td></td>
                                <td></td>
                                <td>Saldo Awal</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td style="text-align: right">'.landa()->rp($beginingBalance).'</td>
                            </tr>';
                }
            }
        }
        //-------------------------------------------------------
        
        
        $sDate = ($monthYear == date('M Y', strtotime($a->date_coa))) ? "" : date('M Y', strtotime($a->date_coa));
        $monthYear = date('M Y', strtotime($a->date_coa));
        $total = $a->balance;
        $sDesc = ($a->reff_type=='balance') ? 'Saldo Awal' : $a->description ;
        echo'<tr>
        <td>' . $sDate . '</td>
        <td>' . date('d', strtotime($a->date_coa)) . '</td>
        <td>' .  $a->code . '</td>
        <td>' . $sDesc . '</td>
        <td name="deb" style="text-align: right">' . landa()->rp($a->debet) . ' </td>
        <td name="cred" style="text-align: right">' . landa()->rp($a->credit) . ' </td>
        <td name="tdeb" style="text-align: right">' . landa()->rp($a->balance) . ' </td>
        </tr>';
        $totald += $a->debet;
        $totalk += $a->credit;
    }
    $akhirk=0;
    $akhird=0;
    if ($beginingBalance <0){
                        $akhirk=$totalk+$beginingBalance;
                        $akhird = $totald;
                    }else{
                        $akhird=$totald+=$beginingBalance;
                        $akhirk = $totalk;
                    }
    ?>

    </thead>

    <tfoot>
        <tr>
            <th colspan="4">Saldo Akhir</th>
            <th style="text-align: right"><?php echo landa()->rp($akhird) ?></th>
            <th style="text-align: right"><?php echo landa()->rp($akhirk) ?></th>
            <th style="text-align: right"><?php echo landa()->rp($total) ?></th>
        </tr>
    </tfoot>
    </table>

<?php } ?>
