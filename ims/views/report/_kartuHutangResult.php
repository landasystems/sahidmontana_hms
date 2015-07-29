<div class='printableArea'>

    <table width="100%">
        <tr>
            <td></td>
            <td></td>
            <td  style="text-align: center" colspan="2"><h2>KARTU HUTANG REPORT</h2>
                <?php echo date('d F Y', strtotime($start)) . " - " . date('d F Y', strtotime($end)); ?>
                <hr></td>
        </tr>    

    </table>

    <table class="table table-bordered" style="border-collapse: separate" border="1">
        <thead>
            <tr>
                <th colspan="2" width="5%"><p align="center">Date</p></th>
        <th width="25%"><p align="center">Description</p></th>
        <th width="5%"><p align="center">Reff</p></th>
        <th width="5%"><p align="center">Invoice</p></th>
        <th width="20%"><p align="center">Debet</p></th>
        <th  width="20%"><p align="center">Credit</p></th>
        <th width="20%"><p align="center">Saldo</p></th>
        </tr>


        </thead>
        <tr>
            <?php
            $balance = AccCoaDet::model()->saldoKartu(date('Y-m-d', strtotime($start)),$id);
            ?>
            <th></th>
            <th></th>
            <th>Saldo Awal</th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th style="text-align:right;"><?php echo landa()->rp($balance); ?></th>
        </tr>
        <?php
        $total = 0;
        $monthYear = "";
        $i = 0;
        $saldo = $balance;
        foreach ($accCoaDet as $a) {
            $sDate = ($monthYear == date('M Y', strtotime($a->date_coa))) ? "" : date('M Y', strtotime($a->date_coa));
            $monthYear = date('M Y', strtotime($a->date_coa));

            $saldo =  $saldo + $a->debet - $a->credit;
            echo '<tr>
			<td>' . $sDate . '</td>
                        <td>' . date('d', strtotime($a->date_coa)) . '</td>
			<td>' . $a->description . '</td>
			<td>' . $a->code . '</td>
			<td>' . $a->InvoiceDet->code . '</td>
			<td name="deb" style="text-align:right">' . landa()->rp($a->debet, false) . ',- </td>
			<td name="cred" style="text-align:right">' . landa()->rp($a->credit, false) . ',- </td>
			<td name="tdeb" style="text-align:right">' . landa()->rp($saldo) . ',- </td>
			</tr>';
        }
        ?>
        <tfoot>
            <tr>
                <th colspan="7">Saldo Akhir</th>
                <th style="text-align: right;"><?php echo landa()->rp($saldo, false) . ',-' ?></th>
            </tr>
        </tfoot>
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
