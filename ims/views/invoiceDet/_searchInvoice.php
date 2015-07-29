<div class="well">
    Supplier / Customer's Name : 
    <?php
    $data = array(0 => 'Pilih') + CHtml::listData($array, 'id', 'name');
    $this->widget('bootstrap.widgets.TbSelect2', array(
        'asDropDownList' => TRUE,
        'data' => $data,
        'name' => 'accountName',
        'options' => array(
            "placeholder" => 'Pilih',
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
                <th style="text-align: center;width:40%">Keterangan</th>
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

    $("body").on("click", ".delInvoice", function () {
        var id = $(this).attr("det_id");
        var answer = confirm("Are you sure want to delete this Invoice? If you do that, all of approved transaction related with this invoce won't deleted!");
        if (answer) {
            $.ajax({
                type: 'POST',
                data: {id: id},
                url: "<?php echo url('invoiceDet/dellInv'); ?>",
                success: function (data) {
                    alert(data);
                    selectInvoice();
                },
                error : function(){
                    alert("Terjadi Kesalahan!");
                }
            });
        }
    });
</script>