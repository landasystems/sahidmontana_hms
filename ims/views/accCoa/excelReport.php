<table width="100%">
    <tr>
        <td  style="text-align: center" colspan="4"><h2>DAFTAR AKUN PT. TUGASANDA</h2></td>
    </tr>
    <tr>
        <td style="text-align: center" colspan="4">
            Dicetak pada Tanggal : <?php echo date('d F Y'); ?>
        </td>
    </tr>
    <tr>
    </tr>
    <tr>
        <td>
        </td>
    </tr>
</table>
<?php if ($model !== null): ?>
    <table border="1">

        <tr>
            <th width="80px">
                id		</th>
            <th width="80px">
                Nama		</th>
            <th width="80px">
                Deskripsi		</th>
            <th width="80px">
                Saldo Saat Ini		</th>
    <!-- 		<th width="80px">
                created_user_id		</th>
          <th width="80px">
                modified		</th>
          <th width="80px">
                created		</th>
          <th width="80px">
                level		</th>
          <th width="80px">
                lft		</th>
          <th width="80px">
                rgt		</th>
          <th width="80px">
                root		</th>
          <th width="80px">
                parent_id		</th>-->
        </tr>
        <?php foreach ($model as $row): ?>
            <tr>
                <td>
                    <?php echo $row->id; ?>
                </td>
                <td>
                    <?php echo $row->name; ?>
                </td>
                <td>
                    <?php echo $row->description; ?>
                </td>
                <td>
                    <?php echo landa()->rp(AccCoaDet::model()->beginingBalance(date("Y-m-d"), $row->id)); ?>
                </td>
        <!--       		<td>
                <?php // echo $row->created_user_id; ?>
                </td>
                <td>
                <?php // echo $row->modified; ?>
                </td>
                <td>
                <?php // echo $row->created; ?>
                </td>
                <td>
                <?php // echo $row->level; ?>
                </td>
                <td>
                <?php // echo $row->lft; ?>
                </td>
                <td>
                <?php // echo $row->rgt; ?>
                </td>
                <td>
                <?php // echo $row->root; ?>
                </td>
                <td>
                <?php // echo $row->parent_id; ?>
                </td>-->
            </tr>
        <?php endforeach; ?>
    </table>

<?php endif; ?>