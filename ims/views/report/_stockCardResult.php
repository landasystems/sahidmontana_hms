<div class="row-fluid">
    <div class="span6">Month : <b><?php echo $month ?></b></div>
    <div class="span6">
        Method : <b><?php
            $siteConfig = SiteConfig::model()->listSiteConfig();
            echo $siteConfig['method'];
            ?></b>
    </div>
</div>

<table class="table table-bordered">
    <thead>
        <tr>
            <th rowspan="2">Date</th>
            <th rowspan="2">Description</th>
            <th colspan="3">In</th>
            <th colspan="3">Out</th>
            <th colspan="3">Balance</th>
        </tr>
        <tr>
            <th>Qty</th>
            <th>Price</th>
            <th>Total</th>
            <th>Qty</th>
            <th>Price</th>
            <th>Total</th>
            <th>Qty</th>
            <th>Price</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $sBalanceQty = 0;
        $sBalanceTotal = 0;
        foreach ($stockCard as $o) {
            $in = json_decode($o->in);
            $out = json_decode($o->out);
            $balance = json_decode($o->balance);

            $sOut = array('qty' => '', 'price' => '', 'total' => '');
            foreach ($out as $oOut) {
                $sOut['qty'] .= $oOut->qty . '<br/>';
                $sOut['price'] .= landa()->rp($oOut->price) . '<br/>';
                $sOut['total'] .= landa()->rp($oOut->qty * $oOut->price) . '<br/>';
            }

            $sBalance = array('qty' => '', 'price' => '', 'total' => '');
            $sBalanceQty = 0;
            $sBalanceTotal = 0;
            foreach ($balance as $oBalance) {
                $sBalance['qty'] .= $oBalance->qty . '<br/>';
                $sBalance['price'] .= landa()->rp($oBalance->price) . '<br/>';
                $sBalance['total'] .= landa()->rp($oBalance->qty * $oBalance->price) . '<br/>';
                $sBalanceQty += $oBalance->qty;
                $sBalanceTotal += $oBalance->qty * $oBalance->price;
            }

            if (count($in) == 0) {
                $sTdIn = '<td></td>
                         <td></td>
                         <td></td>';
            } else {
                $sTdIn = '<td>' . $in->qty . '</td>
                    <td>' . landa()->rp($in->price) . '</td>
                    <td>' . landa()->rp($in->qty * $in->price) . '</td>';
            }

            if (count($out) == 0) {
                $sTdOut = '<td></td>
                         <td></td>
                         <td></td>';
            } else {
                $sTdOut = '<td>' . $sOut['qty'] . '</td>
                    <td>' . $sOut['price'] . '</td>
                    <td>' . $sOut['total'] . '</td>';
            }

            echo '<tr>
                    <td>' . date('d', strtotime($o->created)) . '</td>
                    <td>' . $o->description . '</td>
                    ' . $sTdIn . '
                    ' . $sTdOut . '
                    <td>' . $sBalance['qty'] . '</td>
                    <td>' . $sBalance['price'] . '</td>
                    <td>' . $sBalance['total'] . '</td>
                </tr>';
        }
        ?>
    </tbody>
    <tfoot>
        <tr>
            <th colspan="8">Saldo Akhir</th>
            <th><?php echo $sBalanceQty ?></th>
            <th></th>
            <th><?php echo landa()->rp($sBalanceTotal) ?></th>
        </tr>
    </tfoot>
</table>