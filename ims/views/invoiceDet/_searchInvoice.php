<div class="well">
    Supplier / Customer's Name : 
    <?php
    $data = array(0 => t('choose', 'global')) + CHtml::listData($array, 'id', 'name');
    $this->widget('bootstrap.widgets.TbSelect2', array(
        'asDropDownList' => TRUE,
        'data' => $data,
        'name' => 'accountName',
        'options' => array(
            "placeholder" => t('choose', 'global'),
            "allowClear" => true,
        ),
        'htmlOptions' => array(
            'id' => 'accountName',
            'style' => 'width:100%;',
            ''
        ),
    ));
    ?>
</div>
<div class="well">
    Supplier / Customer's Invoices Description: 
    <table class="table table-bordered">
        <thead>
            <tr>
                <th style="text-align: center;width:10%;">Code</th>
                <th style="text-align: center">Keterangan</th>
                <th style="text-align: center;width:20%">Nilai</th>
                <th style="text-align: center;width:20%">Balance</th>
                <th style="text-align: center;width:10%">#</th>
            </tr>
        </thead>
        <tbody id="detail">

        </tbody>
    </table>
</div>
<input type="hidden" value="" id="selectedClass"/>
<script type="text/javascript">
    $(document).ready(function () {
        $("#accountName").select2();
    });
</script>