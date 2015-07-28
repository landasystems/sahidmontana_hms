<div style="margin-left: 0px">
    <h3>Report Cash Keluar</h3>
    <?php if ($model !== null): ?>
        <table border="1" style="padding: 5px;">

            <tr style="height: 30px;">
                <th>No Transaksi</th>
                 <th>Tanggal</th>
                <th>Acc Coa</th>
                <th>Keterangan</th>
                <th>Total</th>
            </tr>
            <?php foreach ($model as $row): ?>
                <tr>
                    <td>
                        <?php echo $row->code; ?>
                    </td>
                    <td>
                        <?php echo $row->date_trans; ?>
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
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
</div>