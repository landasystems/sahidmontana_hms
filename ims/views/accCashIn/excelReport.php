<div style="margin-left: 50px">
    <table>
        <tr>
            <th colspan="6" style="text-align: center"><h4>Laporan Uang Masuk </h4></th>
        </tr>
        <tr>
            <th colspan="6" style="text-align: center"><?php echo date('d F Y', strtotime($start)) . ' - ' . date('d F Y', strtotime($end)) ?></th>
        </tr>

    </table>
    <?php if ($model !== null): ?>
        <table border="1" style="padding: 5px;">

            <tr style="height: 30px;">
                <th>No Transaksi</th>
                <th>Kode ACC</th>
                <th>Tanggal Posting</th>
                <th>Kode Akun</th>
                <th>Nama Akun</th>
                <th>Keterangan</th>
                <th>Debet</th>
                <th>Credit</th>
            </tr>
            <?php foreach ($model as $row): ?>
                <tr>
                    <td>
                        <?php echo $row->code; ?>
                    </td>
                    <td>
                        <?php echo $row->code_acc; ?>
                    </td>
                    <td>
                        <?php echo date('d F Y',  strtotime($row->date_posting)); ?>
                    </td>
                    <td>
                        <?php echo $row->AccCoa->code; ?>
                    </td>
                    <td>
                        <?php echo $row->AccCoa->name; ?>
                    </td>
                    <td>
                        <?php echo $row->description; ?>
                    </td>
                    <td>
                        <?php echo $row->total; ?>
                    </td>
                    <td></td>
                </tr>
                <?php
                        $cashDet = AccCashInDet::model()->findAll(array(
                            'condition' => 'acc_cash_in_id='.$row->id
                        ));
                        foreach($cashDet as $det){
                            echo '<tr>';
                            echo '<td>'.$row->code.'</td>';
                            echo '<td>'.$row->code_acc.'</td>';
                            echo '<td>'.date('d F Y',  strtotime($row->date_posting)).'</td>';
                            echo '<td>'.$det->AccCoa->code.'</td>';
                            echo '<td>'.$det->AccCoa->name.'</td>';
                            echo '<td>'.$det->description.'</td>';
                            echo '<td></td>';
                            echo '<td>';
                            echo $det->amount;
                            echo '</td>';
                            echo '</tr>';
                        }
                    ?>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
</div>
