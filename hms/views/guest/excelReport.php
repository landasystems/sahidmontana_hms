<table>
    <tr>
        <th colspan="5">DAFTAR USER PT. <?php
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
                <th>username</th>
                <th>email</th>
                <th>name</th>
                <th>address</th>
                <th>phone</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($model as $row) {
                echo '<tr><td>' . $row->username . '</td>
                    <td>' . $row->email . '</td>
                    <td>' . $row->name . '</td>
                    <td>' . $row->address . '</td>
                    <td>' . landa()->hp($row->phone) . '</td>
                    </tr>';
            }
            ?>
        </tbody>
    </table>
<?php endif; ?>
