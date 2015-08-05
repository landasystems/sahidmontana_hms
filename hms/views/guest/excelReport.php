<table>
    <tr>
        <th colspan="5">DAFTAR GUEST <?php
            Yii::app()->name;
            echo param('clientName');
            ?></th>
    </tr>
    <tr></tr>
    <tr></tr>
</table>

<?php if ($model !== null): ?>
    <table border="1">
        <thead>
            <tr>
                <th>Guest Name</th>
                <th>Company</th>
                <th>KTP/SIM/Passport</th>
                <th>Phone</th>
                <th>Address</th>
                <th>City</th>
                <th>Nationality</th>
                <th>Group Guesst</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($model as $row) {
                echo '<tr><td>' . $row->name . '</td>
                    <td>' . $row->company . '</td>
                    <td>' . $row->code . '</td>
                    <td>' . $row->phone . '</td>
                    <td>' . $row->address . '</td>
                    <td>' . (isset($row->City->name)? $row->City->name : "") . '</td>
                    <td>' . $row->nationality . '</td>
                    <td>' . ((isset($row->Roles->name)) ? $row->Roles->name : "") . '</td>
                    </tr>';
            }
            ?>
        </tbody>
    </table>
<?php endif; ?>
