<table>
    <tr>
        <th colspan="6">DAFTAR USER </th>
    </tr>
    <tr></tr>
    <tr></tr>
</table>

<?php if ($model !== null): ?>
    <table border="1">
        <thead>
            <tr>
                <th>name</th>
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
                echo '<tr>
                    <td>' . $row->name . '</td>
                    <td>' . $row->username . '</td>
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
