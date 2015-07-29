<div style="margin-left: 0px">
    <?php if ($model !== null): ?>
        <h3>Report Jurnal</h3>
        <table border="1">
            <tr>
                <th >No Transaksi</th>
                <th >Tanggal</th>
                <th >Keterangan</th>
                <th >Total Debet</th>
                <th >Total Credit</th>

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
                        <?php echo $row->description; ?>
                    </td>
                    <td>
                        <?php echo $row->total_debet; ?>
                    </td>
                    <td>
                        <?php echo $row->total_credit; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
</div>