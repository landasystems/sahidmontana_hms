<?php if ($model !== null): ?>
    <br>
    <h2>Additional Charge <?php echo date("D, M Y") ?></h2>
    <table border="1">
        <tr>
            <th width="80px">No</th>
            <th width="200px">Name</th>
            <th width="100px">Departement</th>
            <th width="120px">Account</th>
            <th width="180px">Type Transaction</th>
            <th width="100px">Charge</th>
            <th width="100px">Discount</th>
            <th width="100px">Total Charge</th>
            <th width="80px">Publish</th>
        </tr>
        <?php
        $no = 1;
        foreach ($model as $data) {
            ?>
            <tr>
                <td>
                    <?php echo $no; ?>
                </td>
                <td>
                    <?php echo strtoupper($data->name); ?>
                </td>
                <td>
                    <?php echo $data->category; ?>
                </td>
                <td>
                    <?php echo (isset($data->Account->name)) ? $data->Account->name : ""; ?>
                </td>
                <td>
                    <?php echo $data->fullTransaction; ?>
                </td>
                <td>
                    <?php echo $data->charge; ?>
                </td>
                <td>
                    <?php echo $data->discount; ?>
                </td>
                <td>
                    <?php echo $data->charge - (($data->discount / 100) * $data->charge); ?>
                </td>
                <td>
                    <?php echo ($data->is_publish == 1) ? "Yes" : "No"; ?>
                </td>
            </tr>
            <?php
            $no++;
        }
        ?>
    </table>
<?php endif; ?>
