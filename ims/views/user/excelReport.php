<table>
    <tr>
        <th colspan="6" rowspan="2" style="vertical-align: center;text-align: center;"><h2>DAFTAR USER  <?php
            Yii::app()->name;
            echo param('clientName');
            ?></h2></th>
    </tr>
    <tr></tr>
    <tr></tr>
</table>

<?php if ($model !== null): ?>
    <table border="1">
        <thead>
            <tr>
                <th>Nama</th>
                <th>email</th>
                <th>Departement</th>
                <th>Alamat</th>
                <th>Jabatan</th>
                <th>No. Telp</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($model as $row) {
                $roles = (!empty($row->Roles->name)) ? $row->Roles->name : '';
                echo '<tr>'
                . '<td>' . $row->name . '</td>
                    <td>' . $row->email . '</td>
                    <td>' . $row->Departement->name . '</td>
                    <td>' . $row->address . '</td>
                    <td>' . $roles . '</td>
                    <td>' . landa()->hp($row->phone) . '</td>
                    </tr>';
            }
            ?>
        </tbody>
    </table>
<?php endif; ?>
