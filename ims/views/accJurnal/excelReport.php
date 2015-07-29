<div style="margin-left: 50px">
    <?php if ($model !== null): ?>
        <table>
            <tr>
                <th colspan="6" style="text-align: center"><h4>Laporan Jurnal </h4></th>
            </tr>
            <tr>
                <th colspan="6" style="text-align: center"><?php echo date('d F Y', strtotime($start)) . ' - ' . date('d F Y', strtotime($end)) ?></th>
            </tr>

        </table>
        <table border="1">
            <tr>
                <th >No Transaksi</th>
                <th >Code ACC</th>
                <th >Tanggal</th>
                <th >Kode Akun</th>
                <th >Nama Akun</th>
                <th >Keterangan</th>
                <th >Total Debet</th>
                <th >Total Credit</th>

            </tr>
            <?php foreach ($model as $row): ?>
                <tr>
                    <td>
                        <?php echo $row->AccJurnal->code; ?>
                    </td>
                    <td>
                        <?php echo $row->AccJurnal->code_acc; ?>
                    </td>
                    <td style="text-align: center">
                        <?php echo date('d F Y', strtotime($row->AccJurnal->date_posting)); ?>
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
                        <?php echo $row->debet; ?>
                    </td>
                    <td>
                        <?php echo $row->credit; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
</div>
