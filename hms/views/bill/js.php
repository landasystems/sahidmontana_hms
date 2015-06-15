<script type="text/javascript">
    jQuery(function($) {
        function rp(angka) {
            var rupiah = "";
            var angkarev = angka.toString().split("").reverse().join("");
            for (var i = 0; i < angkarev.length; i++)
                if (i % 3 == 0)
                    rupiah += angkarev.substr(i, 3) + ".";
            return rupiah.split("", rupiah.length - 1).reverse().join("");
        }
        ;


        function pay() {
//                            var grandTotal = parseFloat($("#grandTotal").val());
            var grandTotal = parseFloat($("#grandTotal").val());
            var totalDeposite = parseFloat($("#totalDeposite").val());

            var cash = parseFloat($("#cash").val());
            var credit = parseFloat($("#credit").val());
            var cl = parseFloat($("#cl").val());
            var discount = parseFloat($("#discount").val());


            grandTotal = grandTotal || 0;
            cash = cash || 0;
            credit = credit || 0;
            cl = cl || 0;
            totalDeposite = totalDeposite || 0;
            discount = discount || 0;

//                            var refund = parseInt(((grandTotal - discount) * -1) + cash + credit + cl + totalDeposite);                            
            var refund = parseInt((cash + credit + cl + ((grandTotal / 100) * discount)) - grandTotal);
            $("#refund").val(refund);

        }
        ;

        function clearList() {
            $(".items").remove();
            $("#name").html("");
            $("#date_to").val("");
            /*$("#addRow").replaceWith(row);*/
            $("#pax").val("");
            $("#total").html(0);
            $("#grandTotal").val(0);
            $("#totalDeposite").val(0);
            $("#totalNoDeposite").val(0);
            $("#cash").val(0);
            $("#credit").val(0);
            $("#cc_number").val("");
            $("#cl").val(0);
            $("#billedBy").select2("val", 0);
            $("#refund").val(0);
            $("#discount").val(0);
            $("#s2id_registration_id").select2("data", null)
            $("#s2id_roomId").select2("data", null)
        }

        $("body").on("input","#cash, #credit, #cl, #discount", function() {
            pay();
        });

        function clearing() {
            $("#name").html("");
            $("#totalDeposite").val(0);
            $("#totalNoDeposite").val(0);
            $("#cash").val(0);
            $("#credit").val(0);
            $("#cc_number").val("");
            $("#cl").val(0);
            $("#billedBy").select2("val", 0);
            $("#refund").val(0);
            $("#discount").val(0);

            if ($("#gl_room_bill_id").val() == 0) {
                $("#total").prop('readonly', false);
                $("#totalDeposite").prop('readonly', false);
                $("#totalNoDeposite").prop('readonly', false);
                $("#cash").prop('readonly', false);
                $("#credit").prop('readonly', false);
                $("#cc_number").prop('readonly', false);
                $("#cl").prop('readonly', false);
                $("#billedBy").prop('readonly', false);
                $("#refund").prop('readonly', false);
                $("#discount").prop('readonly', false);
            } else {
                $("#total").prop('readonly', true);
                $("#totalDeposite").prop('readonly', true);
                $("#totalNoDeposite").prop('readonly', true);
                $("#cash").prop('readonly', true);
                $("#credit").prop('readonly', true);
                $("#cc_number").prop('readonly', true);
                $("#cl").prop('readonly', true);
                $("#billedBy").prop('readonly', true);
                $("#refund").prop('readonly', true);
                $("#discount").prop('readonly', true);
                $(".trDp").remove();
            }
        }
        function selectBy() {
            $("#roomId").select2("val", 0);
            $("#registration_id").select2("val", 0);
            if ($('#checkoutBy_0').is(":checked")) {
                $(".byroom").show();
                $("#submit").attr("name", "room");
                $(".byregistration").hide();
            } else {
                $(".byroom").hide();
                $("#submit").attr("name", "registration");
                $(".byregistration").show();
            }
        }
        $("#roomId").on("change", function() {
            if ($(this).val() == null) {
                var row = "<tr id=\"addRow\" style=\"display:none\"></tr>";
                $(".items").remove();
                $("#name").html("");
                $("#date_to").val("");
                $("#addRow").replaceWith(row);
                $("#pax").val("");
                $("#total").html(0);
                $("#grandTotal").val(0);
                $("#totalDeposite").val(0);
                $("#totalNoDeposite").val(0);
                $("#cash").val(0);
                $("#credit").val(0);
                $("#cc_number").val("");
                $("#cl").val(0);
                $("#billedBy").select2("val", 0);
                $("#refund").val(0);
                $("#discount").val(0);
                $("#gl_room_bill_id").select2("val", 0);
            }
            $.ajax({
                url: "<?php echo url('bill/getBilling'); ?>",
                type: "POST",
                data: $("form").serialize(),
                success: function(data) {
                    obj = JSON.parse(data);
                    $(".items").remove();
                    $("#name").html(obj.name);
                    $("#date_to").val(obj.date_to);
                    $("#addRow").replaceWith(obj.detail);
                    $("#pax").val(obj.pax);
                    $("#guest_phone").val(obj.guest_phone);
                    $("#guest_address").val(obj.guest_address);
                    $("#guest_company").val(obj.guest_company);
                    $("#total").html(obj.total);
                    $("#grandTotal").val(obj.grandTotal);
                    $("#totalDeposite").val(obj.totalDeposite);
                    $("#totalNoDeposite").val(obj.totalNoDeposite);
                    $("#cash").val(0);
                    $("#credit").val(0);
                    $("#cc_number").val("");
                    $("#cl").val(0);
                    $("#billedBy").select2("val", obj.ca);
                    $("#refund").val(obj.grandTotal * -1);
                    $("#discount").val(0);
                }
            });
        });

        $("#gl_room_bill_id").on('change', function() {
            clearing();
        });

        $("#roomId").trigger("change");

        $("body").on("input", ".deposite_amount",function() {
            total();
        });

        function total() {
            dp = 0;
            $(".deposite_amount").each(function() {
                var max = parseInt($(this).parent().parent().find("#max").val());
                max = max || 0;
                ini = parseInt($(this).val());
                ini = ini || 0;
                if (ini > max)
                    $(this).val(max);

                dp += parseInt($(this).val());
                var value = $(this).val();
                value = parseInt(value);
                value = value * -1;
                value = value || 0;
                $(this).parent().parent().parent().find("#subtotal").html("Rp " + rp(value));

            });
            dp = dp || 0;
            var grandTot = $("#totalNoDeposite").val() - dp;
            $("#grandTotal").val(grandTot);
            $("#total").html("Rp " + rp(grandTot));
            $("#refund").val(grandTot * -1);
        }
        
        $('#checkoutBy').on('change',function(){
            selectBy();
            clearList();
        })

    })

</script>



