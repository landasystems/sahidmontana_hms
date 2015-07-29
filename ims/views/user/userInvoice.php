<?php
$abc = "";
$type = $_GET['type'];
if ($type == 'supplier') {
    $title = 'Supplier Payment';
    $sTot = 'Hutang';
} else {
    $title = 'Customer Invoice';
    $sTot = 'Piutang';
}

$this->setPageTitle($title);
?>
<?php
foreach (Yii::app()->user->getFlashes() as $key => $message) {
    echo '<div class="alert alert-' . $key . '">' . $message . '</div>';
}
?>
<?php
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id' => 'userInvoice',
    'enableAjaxValidation' => false,
    'method' => 'post',
    'type' => 'horizontal',
    'htmlOptions' => array(
        'enctype' => 'multipart/form-data'
    )
        ));
?>
<div class="form">

    <div class="col-xs-12 col-md-6">

        <legend>
            <p class="note">
                <span class="required">*</span> Berikut adalah daftar <?php
                echo $title . ' ';
                Yii::app()->name;
                echo param('clientName');
                ?><br/>
                <!--<span class="required">-</span> Saldo awal akan di setting pada tanggal <span class="label label-info"><?php // echo $siteConfig->date_system                            ?></span>-->
            </p>
        </legend>
        
        <div class="well">
            <div class="control-group ">
                <label class="control-label">Pilih Nama <?php echo ucwords($_GET['type']); ?></label>
                <div class="controls">
                    <?php
                    $data = array(0 => 'Pilih') + CHtml::listData($header, 'id', 'name');
                    $this->widget('bootstrap.widgets.TbSelect2', array(
                        'asDropDownList' => TRUE,
                        'data' => $data,
                        'value' => (isset($_POST['supplier_list']) ? $_POST['supplier_list'] : ''),
                        'name' => 'supplier_list',
                        'options' => array(
                            "placeholder" => 'Pilih',
                            "allowClear" => true,
                            'width' => '50%',
                        ),
                        'htmlOptions' => array(
                            'id' => 'supplier_list',
                        ),
                    ));
                    ?>
                </div>
            </div>
        </div>
        <div class="detailInvoice">
            <?php
            if (!empty($_POST['supplier_list'])) {
                $userInvoice = AccCoaDet::model()->findAll(array(
                    'with' => array('InvoiceDet'),
                    'condition' => 'InvoiceDet.user_id=' . $_POST['supplier_list'] . ' AND (reff_type="invoice" OR InvoiceDet.is_new_invoice=1)'
                ));
                $balance = InvoiceDet::model()->findAllByAttributes(array('user_id' => $_POST['supplier_list']));
            }else{
                $userInvoice = '';
                $balance= '';
            }
//            $alert = false;
            $this->renderPartial('_userInvoice', array(
//                'sTot' => $sTot,
                'userInvoice' => $userInvoice,
                'ambil' => false,
                'alert' => $alert,
                'balance' => $balance
            ));
            ?>
        </div>
    </div>
</div>
<?php $this->endWidget(); ?>
<script type="text/javascript">
    $("#supplier_list").on("change", function () {
        var id = $(this).val();
//        alert(id);
        $.ajax({
            type: 'POST',
            data: {id: id},
            url: "<?php echo url('user/invoiceDetail'); ?>",
            success: function (data) {
                $(".detailInvoice").html(data);
                $('.datepicker').datepicker({ format: 'mm/dd/yyyy', startDate: '-3d' });
                hitung();
            }
        });
    });
    function hitung() {
        var total = 0;
        $('.nilai').each(function () {
            total += parseInt($(this).val());
        });
        $('#total_charge').val(total);
    }
    
    $("body").on('click', ".addRow", function () {
        var aa = $(this).parent().parent().parent().parent().find(".addRows");
        var desc = $(this).parent().parent().parent().find(".description").val();
        var code = $(this).parent().parent().parent().find(".codes").val();
        var date_coa = $(this).parent().parent().parent().find(".dateCoa").val();
        var terms = $(this).parent().parent().parent().find(".terms").val();
        var payment = $(this).parent().parent().parent().find(".payment").val();
        var sup_id = $("#supplier_list").val();
        $.ajax({
            type: 'POST',
            data: {code: code, terms: terms, payment: payment, desc: desc, sup_id: sup_id, date_coa: date_coa},
            url: "<?php echo url('user/addRow'); ?>",
            success: function (data) {
                aa.replaceWith(data);
                $(".addRows").val("");
                $(".description").val("");
                $(".codes").val("");
                $(".dateCoa").val("");
                $(".terms").val("");
                $(".payment").val("0");
                $(".sup_id").val("");
                $(".kosong").remove();
                hitung();
                $( ".dateStart" ).datepicker();
                $( ".term" ).datepicker();
            }
        });
    });
    $("body").on('click', '.delInv', function () {
        var id = $(this).parent().find(".id_invoice").val();
        var dell = $(this).parent().parent();
//        alert(id);
        if (id == "") {
            $(this).parent().parent().remove();
        } else {
            var answer = confirm("Are you sure want to delete this Invoice? If you do that, all of approved transaction related with this invoce won't deleted!");
            if (answer) {
                $.ajax({
                    type: 'POST',
                    url: "<?php echo url('invoiceDet/dellInv'); ?>",
                    data: {id: id},
                    success: function (data) {
                        alert(data);
                        dell.remove();
                        hitung();
                    }
                });
            } else {
                // do nothing
            }
        }
    });
    function rp(angka) {
        var rupiah = "";
        var angkarev = angka.toString().split("").reverse().join("");
        for (var i = 0; i < angkarev.length; i++)
            if (i % 3 == 0)
                rupiah += angkarev.substr(i, 3) + ".";
        return rupiah.split("", rupiah.length - 1).reverse().join("");
    }
    $("body").on('keyup', ".nilai", function () {
        hitung();
    });
</script>
